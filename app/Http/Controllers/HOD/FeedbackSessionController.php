<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\FeedbackSession;
use App\Models\Semester;
use App\Models\StaffCourse;
use App\Models\User;
use App\Notifications\NewStaffFeedbackAssignmentNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FeedbackSessionController extends Controller
{
    private function hod(): User
    {
        $user = auth()->user();

        abort_unless(
            $user &&
            $user->role === 'faculty' &&
            $user->is_head,
            403
        );

        return $user;
    }

    public function index()
    {
        $hod = $this->hod();

        $semester = Semester::query()
            ->where('is_current', true)
            ->firstOrFail();

        $sections = ClassSection::query()
            ->with([
                'course',
                'feedbackSessions',
            ])
            ->where('semester_id', $semester->id)
            ->whereHas('course', function ($query) use ($hod) {
                $query->where('department_id', $hod->department_id);
            })
            ->where(function ($query) {
                $query->where(function ($activeFeedbackQuery) {
                    $activeFeedbackQuery
                        ->where('is_active', true)
                        ->where('is_feedback_active', true);
                })->orWhereHas('feedbackSessions');
            })
            ->get();

        $courses = $sections
            ->groupBy('course_id')
            ->map(function (Collection $courseSections) {
                $course = $courseSections->first()->course;

                $allSectionsScheduled = $courseSections->isNotEmpty()
                    && $courseSections->every(function ($section) {
                        return $section->feedbackSessions !== null;
                    });

                $isDone = $allSectionsScheduled
                    && $courseSections->every(function ($section) {
                        return $section->feedbackSessions->isReleased();
                    });

                $status = $isDone
                    ? 'completed'
                    : ($allSectionsScheduled ? 'scheduled' : 'open');

                return (object) [
                    'id' => $course->id,
                    'code' => $course->code,
                    'name' => $course->name,
                    'sections_count' => $courseSections->count(),
                    'status' => $status,
                    'is_done' => $isDone,
                ];
            })
            ->sortBy(fn($course) => match ($course->status) {
                'open' => 0,
                'scheduled' => 1,
                'completed' => 2,
            })
            ->values();

        return view('hod.feedback-sessions.index', compact(
            'semester',
            'courses'
        ));
    }

    public function create(Semester $semester, Course $course)
    {
        $hod = $this->hod();

        abort_unless(
            $course->department_id === $hod->department_id,
            404
        );

        $sections = ClassSection::query()
            ->where('semester_id', $semester->id)
            ->where('course_id', $course->id)
            ->where('is_active', true)
            ->where('is_feedback_active', true)
            ->get();

        abort_unless($sections->isNotEmpty(), 404);

        $alreadyScheduled = FeedbackSession::query()
            ->whereIn('class_section_id', $sections->pluck('id'))
            ->exists();

        if ($alreadyScheduled) {
            return redirect()
                ->route('hod.feedback-sessions.index')
                ->with(
                    'error',
                    'Feedback is already scheduled for this course.'
                );
        }

        $staffMembers = User::query()
            ->where('role', 'staff')
            ->where('is_active', true)
            ->where('department_id', $hod->department_id)
            ->orderBy('name')
            ->get();

        return view('hod.feedback-sessions.create', compact(
            'semester',
            'course',
            'sections',
            'staffMembers'
        ));
    }

    public function store(
        Request $request,
        Semester $semester,
        Course $course
    ) {
        $hod = $this->hod();

        abort_unless(
            $course->department_id === $hod->department_id,
            404
        );

        $data = $request->validate([
            'staff_ids' => ['required', 'array', 'min:1'],
            'staff_ids.*' => ['integer', 'distinct', 'exists:users,id'],
            'opened_at' => [
                'required',
                'date',
            ],
            'duration_minutes' => ['required', 'integer', 'in:30,60,90,120'],
        ]);

        $sections = ClassSection::query()
            ->where('semester_id', $semester->id)
            ->where('course_id', $course->id)
            ->where('is_active', true)
            ->where('is_feedback_active', true)
            ->get();

        abort_unless($sections->isNotEmpty(), 404);

        $alreadyScheduled = FeedbackSession::query()
            ->whereIn('class_section_id', $sections->pluck('id'))
            ->exists();

        if ($alreadyScheduled) {
            return back()
                ->withInput()
                ->withErrors([
                    'feedback' =>
                        'Feedback is already scheduled for one or more sections of this course.',
                ]);
        }

        $staffMembers = User::query()
            ->whereIn('id', $data['staff_ids'])
            ->where('role', 'staff')
            ->where('is_active', true)
            ->where('department_id', $hod->department_id)
            ->get();

        if ($staffMembers->count() !== count($data['staff_ids'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'staff_ids' =>
                        'One or more selected users are not active staff members in your department.',
                ]);
        }

        $openedAt = Carbon::parse($data['opened_at']);

        $endsAt = $openedAt
            ->copy()
            ->addMinutes((int) $data['duration_minutes']);

        DB::transaction(function () use ($sections, $staffMembers, $openedAt, $endsAt, $data) {
            foreach ($sections as $section) {
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

                $feedbackSession = FeedbackSession::create([
                    'class_section_id' => $section->id,
                    'class_session_id' => $classSession->id,
                    'created_by' => auth()->id(),
                    'assigned_staff_id' => $staffMembers->first()->id,
                    'status' => $openedAt->isFuture()
                        ? 'draft'
                        : 'active',
                    'release_at' => $openedAt,
                    'opened_at' => $openedAt,
                    'duration_minutes' => (int) $data['duration_minutes'],
                    'is_released' => false,
                ]);

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
                        new NewStaffFeedbackAssignmentNotification($assignment)
                    );
                }
            }
        });

        return redirect()
            ->route('hod.feedback-sessions.index')
            ->with(
                'success',
                "Feedback has been scheduled for {$course->code}."
            );
    }
}