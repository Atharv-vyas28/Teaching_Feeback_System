<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use App\Models\FeedbackSession;
use App\Models\FacultyCourse;
use App\Services\FeedbackEligibilityService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class FeedbackSessionController extends Controller
{
    public function __construct(
        private FeedbackEligibilityService $eligibilityService
    ) {
    }

    protected function authorizeSession(ClassSession $classSession): void
    {
        $assigned = FacultyCourse::where('user_id', auth()->id())
            ->where('class_section_id', $classSession->class_section_id)
            ->where('is_active', true)
            ->exists();

        abort_unless($assigned || auth()->user()->isAdmin(), 403);
    }

    public function index()
    {
        $user = auth()->user();

        $sectionIds = FacultyCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('class_section_id');

        $feedbackSessions = FeedbackSession::with([
            'classSession.section.course',
            'eligibility',
        ])
            ->withCount('responses')
            ->whereHas(
                'classSession',
                fn ($query) => $query->whereIn('class_section_id', $sectionIds)
            )
            ->latest()
            ->paginate(15);

        return view('faculty.feedback.index', compact('feedbackSessions'));
    }

    public function create(ClassSession $classSession)
    {
        $this->authorizeSession($classSession);

        abort_if(
            $classSession->feedbackSession,
            400,
            'Feedback session already exists.'
        );

        return view('faculty.feedback.create', compact('classSession'));
    }

    public function store(Request $request, ClassSession $classSession)
    {
        $this->authorizeSession($classSession);

        abort_if(
            $classSession->feedbackSession,
            400,
            'Feedback session already exists.'
        );

        FeedbackSession::create([
            'class_session_id' => $classSession->id,
            'created_by' => auth()->id(),
            'status' => 'draft',
            'is_released' => false,
        ]);

        return redirect()
            ->route('faculty.feedback.index')
            ->with('success', 'Feedback session created.');
    }

    public function open(FeedbackSession $feedbackSession)
    {
        $this->authorizeSession($feedbackSession->classSession);

        abort_if(
            $feedbackSession->status === 'active',
            400,
            'This feedback session is already active.'
        );

        $feedbackSession->update([
            'status' => 'active',
            'opened_at' => now(),
            'is_released' => false,
        ]);

        return back()->with(
            'success',
            'Feedback session opened. Eligible students can now submit feedback.'
        );
    }

    public function close(FeedbackSession $feedbackSession)
    {
        $this->authorizeSession($feedbackSession->classSession);

        $feedbackSession->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        // Do not calculate or expose ratings here.
        // Admin release is the only point where faculty receives feedback.

        return back()->with(
            'success',
            'Feedback session closed and sent for administrator review.'
        );
    }

    public function analytics(FeedbackSession $feedbackSession)
    {
        $this->authorizeSession($feedbackSession->classSession);

        // Critical release protection.
        abort_unless(
            $feedbackSession->isReleased(),
            403,
            'Feedback has not been released by the administrator yet.'
        );

        $responseCount = $feedbackSession->responses()->count();
        $eligibleCount = DB::table('course_enrollments')
        ->where(
            'class_section_id',
            $feedbackSession->classSession->class_section_id
        )
        ->where('status', 'active')
        ->count();

        $scoredResponses = $feedbackSession->responses()
        ->join(
            'feedback_eligibility as eligibility',
            function ($join) {
                $join->on(
                    'feedback_responses.feedback_session_id',
                    '=',
                    'eligibility.feedback_session_id'
                )->on(
                    'feedback_responses.anonymous_token',
                    '=',
                    'eligibility.anonymous_token'
                );
            }
        )
        ->where('eligibility.included_in_score', true)
        ->whereNotNull('eligibility.attendance_weight')
        ->select(
            'feedback_responses.*',
            'eligibility.attendance_weight'
        )
        ->with('answers.question')
        ->get();

    $questionStats = [];

    $weightedRatingTotal = 0;
    $totalAttendanceWeight = 0;

    foreach ($scoredResponses as $response) {
        $attendanceWeight = (float) $response->attendance_weight;

        foreach ($response->answers as $answer) {
            $questionId = $answer->feedback_question_id;

            if (! isset($questionStats[$questionId])) {
                $questionStats[$questionId] = [
                    'question' => $answer->question->question_text,
                    'type' => $answer->question->type,
                    'weight' => $answer->question->weight,
                    'values' => [],
                    'texts' => [],
                    'weighted_total' => 0,
                    'total_weight' => 0,
                ];
            }

            if ($answer->rating_value !== null && $attendanceWeight > 0) {
                $questionStats[$questionId]['values'][] = $answer->rating_value;

                $questionStats[$questionId]['weighted_total'] +=
                    $answer->rating_value * $attendanceWeight;

                $questionStats[$questionId]['total_weight'] +=
                    $attendanceWeight;

                $weightedRatingTotal +=
                    $answer->rating_value * $attendanceWeight;

                $totalAttendanceWeight += $attendanceWeight;
            }

            if (! empty($answer->text_answer)) {
                $questionStats[$questionId]['texts'][] = $answer->text_answer;
            }
        } 
    }

    foreach ($questionStats as &$stat) {
        $stat['average'] = $stat['total_weight'] > 0
            ? round(
                $stat['weighted_total'] / $stat['total_weight'],
                2
            )
            : null;
    }

        $overallWeightedRating = $totalAttendanceWeight > 0
            ? round(
                $weightedRatingTotal / $totalAttendanceWeight,
                2
            )
            : 0;

        $ratingResult = (object) [
            'overall_weighted_rating' => $overallWeightedRating,
        ];

        $facultyScores = DB::table('feedback_answers as answers')
            ->join(
                'feedback_responses as responses',
                'answers.feedback_response_id',
                '=',
                'responses.id'
            )
            ->join(
                'feedback_eligibility as eligibility',
                function ($join) {
                    $join->on(
                        'responses.feedback_session_id',
                        '=',
                        'eligibility.feedback_session_id'
                    )->on(
                        'responses.anonymous_token',
                        '=',
                        'eligibility.anonymous_token'
                    );
                }
            )
            ->join(
                'feedback_sessions as feedback_sessions',
                'responses.feedback_session_id',
                '=',
                'feedback_sessions.id'
            )
            ->join(
                'class_sessions as class_sessions',
                'feedback_sessions.class_session_id',
                '=',
                'class_sessions.id'
            )
            ->join(
                'class_sections as class_sections',
                'class_sessions.class_section_id',
                '=',
                'class_sections.id'
            )
            ->join(
                'courses as courses',
                'class_sections.course_id',
                '=',
                'courses.id'
            )
            ->whereNotNull('answers.rating_value')
            ->where('feedback_sessions.status', 'closed')
            ->where('feedback_sessions.is_released', true)
            ->where('eligibility.included_in_score', true)
            ->whereNotNull('eligibility.attendance_weight')
            ->selectRaw("
                courses.department_id,
                class_sessions.conducted_by as faculty_id,
                COUNT(DISTINCT responses.id) as response_count,
                SUM(
                    answers.rating_value * eligibility.attendance_weight
                ) / NULLIF(
                    SUM(eligibility.attendance_weight),
                    0
                ) as faculty_score
            ")
            ->groupBy(
                'courses.department_id',
                'class_sessions.conducted_by'
            );

        $departmentLeaderboard = DB::query()
            ->fromSub($facultyScores, 'faculty_scores')
            ->join(
                'departments',
                'faculty_scores.department_id',
                '=',
                'departments.id'
            )
            ->selectRaw("
                departments.id,
                departments.name as department_name,
                ROUND(AVG(faculty_scores.faculty_score), 2) as overall_rating,
                COUNT(DISTINCT faculty_scores.faculty_id) as faculty_count,
                SUM(faculty_scores.response_count) as response_count
            ")
            ->groupBy('departments.id', 'departments.name')
            ->orderByDesc('overall_rating')
            ->orderByDesc('response_count')
            ->get();

        return view('faculty.feedback.analytics', compact(
            'feedbackSession',
            'ratingResult',
            'eligibleCount',
            'responseCount',
            'questionStats',
            'departmentLeaderboard'
        ));
    }

    public function myRatings()
    {
        $user = auth()->user();

        $currentSemester = \App\Models\Semester::where(
            'is_current',
            true
        )->first();

        $ratingResults = \App\Models\RatingResult::where(
            'faculty_id',
            $user->id
        )
            ->with(['section.course', 'semester'])
            ->orderByDesc('calculated_at')
            ->get();

        return view(
            'faculty.feedback.my-ratings',
            compact('ratingResults', 'currentSemester')
        );
    }
}