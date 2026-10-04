<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\ClassSession;
use App\Models\FeedbackSession;
use App\Models\StaffCourse;
use App\Models\User;
use App\Notifications\NewStaffFeedbackAssignmentNotification;
use App\Services\FeedbackRatingService;
use App\Services\FeedbackEligibilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackSessionController extends Controller
{
    public function __construct(
        private FeedbackRatingService $ratingService,
        private FeedbackEligibilityService $eligibilityService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Semester
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $semesters = \App\Models\Semester::orderBy('name')->get();

        return view('admin.feedback-sessions.index', [
            'level' => 'semester',
            'semesters' => $semesters,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Departments
    |--------------------------------------------------------------------------
    */

    public function departments(\App\Models\Semester $semester)
    {
        $departments = \App\Models\Department::query()
            ->whereHas('courses.classSections', function ($query) use ($semester) {
                $query->where('semester_id', $semester->id);
            })
            ->withCount([
                'courses as semester_courses_count' => function ($query) use ($semester) {
                    $query->whereHas('classSections', function ($q) use ($semester) {
                        $q->where('semester_id', $semester->id);
                    });
                },
            ])
            ->orderBy('name')
            ->get();

        return view('admin.feedback-sessions.index', [
            'level' => 'department',
            'semester' => $semester,
            'departments' => $departments,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Courses
    |--------------------------------------------------------------------------
    */

    public function courses(
        \App\Models\Semester $semester,
        \App\Models\Department $department
    ) {
        abort_unless(
            $department->courses()
                ->whereHas('classSections', function ($query) use ($semester) {
                    $query->where('semester_id', $semester->id);
                })
                ->exists(),
            404
        );

        $sections = ClassSection::query()
            ->with('course')
            ->where('semester_id', $semester->id)
            ->where('is_active', true)
            ->whereHas('course', function ($query) use ($department) {
                $query->where('department_id', $department->id);
            })
            ->get();

        $courses = $sections
            ->groupBy('course_id')
            ->map(function ($courseSections) {
                $course = $courseSections->first()->course;

                return (object) [
                    'id' => $course->id,
                    'code' => $course->code,
                    'name' => $course->name,
                    'sections' => $courseSections,
                ];
            })
            ->values();

        return view('admin.feedback-sessions.index', [
            'level' => 'course',
            'semester' => $semester,
            'department' => $department,
            'courses' => $courses,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Faculty
    |--------------------------------------------------------------------------
    */

    public function faculty(
        \App\Models\Semester $semester,
        \App\Models\Department $department,
        \App\Models\Course $course
    ) {
        abort_unless(
            $course->department_id === $department->id,
            404
        );

        $sections = ClassSection::query()
            ->with([
                'faculty',
                'feedbackSessions',
            ])
            ->where('semester_id', $semester->id)
            ->where('course_id', $course->id)
            ->where('is_active', true)
            ->get();

        $facultyList = $sections
            ->flatMap(function ($section) {
                return $section->faculty->map(function ($faculty) use ($section) {
                    if ($section->feedbackSessions !== null) {
                        $feedbackSession = $section->feedbackSessions
                            ->firstWhere('faculty_id', $faculty->id);

                    } else {
                        $feedbackSession = $section->feedbackSessions;
                    }


                    return (object) [
                        'faculty' => $faculty,
                        'section' => $section,
                        'feedbackSession' => $feedbackSession,
                        'feedback_status' => $this->getFacultyFeedbackStatus(
                            $feedbackSession
                        ),
                    ];
                });
            })
            ->unique(function ($item) {
                return $item->faculty->id;
            })
            ->values();

        $remainingCount = $facultyList
            ->where('feedback_status', 'not_taken')
            ->count();

        $ongoingCount = $facultyList
            ->where('feedback_status', 'ongoing')
            ->count();

        $doneCount = $facultyList
            ->whereIn('feedback_status', [
                'completed',
                'awaiting_release',
            ])
            ->count();

        return view('admin.feedback-sessions.index', [
            'level' => 'faculty',
            'semester' => $semester,
            'department' => $department,
            'course' => $course,
            'facultyList' => $facultyList,
            'remainingCount' => $remainingCount,
            'ongoingCount' => $ongoingCount,
            'doneCount' => $doneCount,
        ]);
    }

    private function getFacultyFeedbackStatus(
        ?FeedbackSession $session
    ): string {
        if (!$session) {
            return 'not_taken';
        }

        if ($session->isReleased()) {
            return 'completed';
        }

        if ($session->status === 'active') {
            if ($session->isAcceptingResponses()) {
                return 'ongoing';
            }

            return 'awaiting_release';
        }

        if (
            $session->status === 'draft' &&
            $session->opened_at &&
            now()->lt($session->opened_at)
        ) {
            return 'scheduled';
        }

        if ($session->status === 'closed') {
            return 'awaiting_release';
        }

        return 'not_taken';
    }

    /*
    |--------------------------------------------------------------------------
    | Open Feedback Form
    |--------------------------------------------------------------------------
    */

    public function openForm(
        ClassSection $section,
        User $faculty
    ) {
        $section->load([
            'course',
            'faculty',
        ]);

        abort_unless(
            $faculty->isFaculty() &&
            $section->faculty->contains($faculty->id),
            404
        );

        $staffMembers = User::query()
            ->where('role', 'staff')
            ->where('is_active', true)
            ->where('department_id', $section->course->department_id)
            ->orderBy('name')
            ->get();

        return view('admin.feedback-sessions.open', [
            'section' => $section,
            'faculty' => $faculty,
            'staffMembers' => $staffMembers,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Open Feedback
    |--------------------------------------------------------------------------
    |
    | Creates:
    | 1. A special ClassSession for feedback-day attendance.
    | 2. A FeedbackSession for the selected faculty.
    | 3. One StaffCourse assignment for every selected staff member.
    |
    */

    public function open(
        Request $request,
        ClassSection $section,
        User $faculty
    ) {
        $section->load([
            'course',
            'faculty',
        ]);

        /*
         * Make sure this faculty actually teaches this section.
         */
        abort_unless(
            $faculty->isFaculty() &&
            $section->faculty->contains($faculty->id),
            404
        );

        $data = $request->validate([
            'staff_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'staff_ids.*' => [
                'integer',
                'distinct',
                'exists:users,id',
            ],
            'opened_at' => [
                'required',
                'date',
            ],
            'duration_minutes' => [
                'required',
                'integer',
                'in:30,60,90,120',
            ],
        ]);

        /*
         * Get all selected staff members.
         */
        $staffMembers = User::query()
            ->whereIn('id', $data['staff_ids'])
            ->where('role', 'staff')
            ->where('is_active', true)
            ->get();

        /*
         * Every selected ID must actually belong to an active staff user.
         */
        if ($staffMembers->count() !== count($data['staff_ids'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'staff_ids' =>
                        'One or more selected users are not active staff members.',
                ]);
        }

        /*
         * A feedback session is unique for:
         *
         * class_section_id + faculty_id
         *
         * This allows different faculty teaching the same course
         * to have separate feedback sessions.
         */
        $existingSession = FeedbackSession::query()
            ->where('class_section_id', $section->id)
            ->where('faculty_id', $faculty->id)
            ->exists();

        if ($existingSession) {
            return back()
                ->withInput()
                ->withErrors([
                    'feedback' =>
                        'Feedback has already been opened for this faculty for this course section.',
                ]);
        }

        $openedAt = \Carbon\Carbon::parse(
            $data['opened_at']
        );

        $endsAt = $openedAt->copy()->addMinutes(
            (int) $data['duration_minutes']
        );

        DB::transaction(function () use ($section, $faculty, $staffMembers, $openedAt, $endsAt, $data) {
            /*
             * ----------------------------------------------------------
             * 1. Create feedback-day ClassSession
             * ----------------------------------------------------------
             *
             * This session is NOT a regular lecture.
             * It exists only so Staff can record feedback-day attendance.
             */
            $classSession = ClassSession::create([
                'class_section_id' => $section->id,
                'conducted_by' => $staffMembers->first()->id,
                'session_date' => $openedAt->toDateString(),
                'start_time' => $openedAt->format('H:i:s'),
                'end_time' => $endsAt->format('H:i:s'),
                'topic' => 'Teaching Feedback',
                'status' => $openedAt->isFuture()
                    ? 'scheduled'
                    : 'ongoing',
            ]);

            /*
             * ----------------------------------------------------------
             * 2. Create FeedbackSession
             * ----------------------------------------------------------
             */
            $feedbackSession = FeedbackSession::create([
                'class_section_id' => $section->id,
                'class_session_id' => $classSession->id,
                'faculty_id' => $faculty->id,
                'created_by' => auth()->id(),

                /*
                 * Kept for backward compatibility.
                 * Actual staff assignments are stored in staff_courses.
                 */
                'assigned_staff_id' => $staffMembers->first()->id,

                'status' => 'active',
                'release_at' => $openedAt,
                'opened_at' => $openedAt,
                'duration_minutes' => (int) $data['duration_minutes'],
                'is_released' => false,
            ]);

            /*
             * ----------------------------------------------------------
             * 3. Assign ALL selected staff members
             * ----------------------------------------------------------
             */
            foreach ($staffMembers as $staff) {
                $assignment = StaffCourse::create([
                    'user_id' => $staff->id,
                    'class_section_id' => $section->id,
                    'feedback_session_id' => $feedbackSession->id,
                    'assigned_by' => auth()->id(),
                    'is_active' => true,
                    'status' => 'active',
                    'assigned_at' => now(),
                ]);

                $staff->notify(
                    new NewStaffFeedbackAssignmentNotification(
                        $assignment
                    )
                );
            }
        });

        /*
         * Return to the Faculty level, not the course level.
         */
        return redirect()
            ->route('admin.feedback-sessions.faculty', [
                'semester' => $section->semester_id,
                'department' => $section->course->department_id,
                'course' => $section->course->id,
            ])
            ->with(
                'success',
                'Feedback opened successfully for ' . $faculty->name . '.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Assign / Add Staff
    |--------------------------------------------------------------------------
    */

    public function assignStaff(
        Request $request,
        FeedbackSession $session
    ) {
        $data = $request->validate([
            'staff_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'staff_ids.*' => [
                'integer',
                'distinct',
                'exists:users,id',
            ],
        ]);

        $staffMembers = User::query()
            ->whereIn('id', $data['staff_ids'])
            ->where('role', 'staff')
            ->where('is_active', true)
            ->get();

        if ($staffMembers->count() !== count($data['staff_ids'])) {
            return back()->withErrors([
                'staff_ids' =>
                    'One or more selected users are not active staff members.',
            ]);
        }

        DB::transaction(function () use ($session, $staffMembers) {
            $session->load('classSession.section');

            foreach ($staffMembers as $staff) {
                /*
                 * Do not create duplicate active assignments.
                 */
                $alreadyAssigned = StaffCourse::query()
                    ->where('feedback_session_id', $session->id)
                    ->where('user_id', $staff->id)
                    ->where('is_active', true)
                    ->exists();

                if ($alreadyAssigned) {
                    continue;
                }

                StaffCourse::create([
                    'user_id' => $staff->id,
                    'class_section_id' =>
                        $session->class_section_id,
                    'feedback_session_id' =>
                        $session->id,
                    'assigned_by' =>
                        auth()->id(),
                    'is_active' => true,
                    'status' => 'active',
                    'assigned_at' => now(),
                ]);

                $staff->notify(
                    new NewStaffFeedbackAssignmentNotification(
                        $session
                    )
                );
            }

            /*
             * Keep the first active staff in the old column
             * for backward compatibility.
             */
            $firstActiveStaff = StaffCourse::query()
                ->where('feedback_session_id', $session->id)
                ->where('is_active', true)
                ->orderBy('id')
                ->first();

            if ($firstActiveStaff) {
                $session->update([
                    'assigned_staff_id' => $firstActiveStaff->user_id,
                ]);
            }
        });

        return back()->with(
            'success',
            'Staff assigned to this feedback session.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Responses
    |--------------------------------------------------------------------------
    */

    public function responses(FeedbackSession $session)
    {
        $session->load([
            'classSession.section.course',
            'classSession.section.faculty',
            'faculty',
        ]);

        $responses = $session
            ->responses()
            ->with('answers.question')
            ->orderBy('submitted_at')
            ->get();

        /*
         * Response timeline.
         */
        $responseTimeline = $responses
            ->filter(fn($response) => $response->submitted_at)
            ->groupBy(
                fn($response) =>
                    $response->submitted_at->format('Y-m-d H:00')
            )
            ->map(
                fn($items, $period) => [
                    'period' => $period,
                    'label' => \Carbon\Carbon::parse($period)
                        ->format('M d, H:00'),
                    'count' => $items->count(),
                ]
            )
            ->values();

        /*
         * Question analysis.
         */
        $questionAnalysis = [];

        $ratingDistribution = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
        ];

        foreach ($responses as $response) {
            foreach ($response->answers as $answer) {
                if (
                    $answer->question?->type !== 'rating' ||
                    $answer->rating_value === null
                ) {
                    continue;
                }

                $id = $answer->feedback_question_id;

                $questionAnalysis[$id]['question']
                    ??= $answer->question->question_text;

                $questionAnalysis[$id]['ratings'][] =
                    (float) $answer->rating_value;

                $rating = (int) round(
                    $answer->rating_value
                );

                if (isset($ratingDistribution[$rating])) {
                    $ratingDistribution[$rating]++;
                }
            }
        }

        $questionAnalysis = collect($questionAnalysis)
            ->map(fn($item) => [
                'question' => $item['question'],
                'average' => round(
                    array_sum($item['ratings'])
                    / count($item['ratings']),
                    2
                ),
                'response_count' => count($item['ratings']),
            ])
            ->values();

        return view(
            'admin.feedback-sessions.responses',
            compact(
                'session',
                'responses',
                'responseTimeline',
                'questionAnalysis',
                'ratingDistribution'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Release Feedback
    |--------------------------------------------------------------------------
    */

    public function release(
        Request $request,
        FeedbackSession $session
    ) {
        if ($session->isReleased()) {
            return back()->with(
                'success',
                'This feedback was already released to faculty.'
            );
        }

        $responseCount = $session
            ->responses()
            ->count();

        if ($responseCount === 0) {
            return back()->with(
                'error',
                'No student feedback has been submitted for this session yet.'
            );
        }

        DB::transaction(function () use ($session) {
            /*
             * Close feedback first.
             */
            $session->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            /*
             * Refresh the model.
             */
            $session = $session->fresh();

            /*
             * Calculate eligible students using
             * feedback-day attendance.
             */
            $this->eligibilityService
                ->calculateScoreEligibility($session);

            /*
             * Calculate final faculty rating.
             */
            $this->ratingService
                ->calculateForSession($session);

            /*
             * Release result to faculty.
             */
            $session->update([
                'is_released' => true,
            ]);
        });

        return back()->with(
            'success',
            "{$responseCount} anonymous response(s) processed and released to faculty."
        );
    }
}

