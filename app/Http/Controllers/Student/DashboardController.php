<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use App\Models\Attendance;
use App\Models\FeedbackSession;
use App\Models\FeedbackEligibility;
use App\Models\ClassSession;
use App\Models\Semester;
use App\Services\FeedbackEligibilityService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(private FeedbackEligibilityService $eligibilityService) {}

    public function index()
    {
        $student = auth()->user();
        $currentSemester = Semester::where('is_current', true)->first();

        $enrolledSectionIds = CourseEnrollment::where('user_id', $student->id)
            ->where('status', 'active')
            ->pluck('class_section_id');

        // Attendance summary
        $totalClasses  = Attendance::where('student_id', $student->id)->count();
        $presentCount  = Attendance::where('student_id', $student->id)->whereIn('status', ['present', 'late'])->count();
        $attendancePct = $totalClasses > 0 ? round(($presentCount / $totalClasses) * 100, 1) : 0;

        // Available feedback sessions
        $feedbackSessions = FeedbackSession::whereHas('classSession', fn($q) => $q->whereIn('class_section_id', $enrolledSectionIds))
            ->where('status', 'active')
            ->with(['classSession.section.course'])
            ->get();

        $availableFeedback = [];
        foreach ($feedbackSessions as $session) {
            $eligibility = $this->eligibilityService->checkEligibility($student, $session);
            if ($eligibility['eligible']) {
                $availableFeedback[] = $session;
            }
        }

        $completedFeedback = FeedbackEligibility::where('student_id', $student->id)
            ->where('has_submitted', true)
            ->count();

        $enrollments = CourseEnrollment::where('user_id', $student->id)
            ->where('status', 'active')
            ->with(['section.course', 'section.semester'])
            ->get();

        $todaySessions = ClassSession::whereIn('class_section_id', $enrolledSectionIds)
            ->whereDate('session_date', today())
            ->with(['section.course'])
            ->get();

        return view('student.dashboard', compact(
            'student', 'currentSemester', 'enrollments', 'todaySessions',
            'totalClasses', 'presentCount', 'attendancePct',
            'availableFeedback', 'completedFeedback'
        ));
    }

    public function attendance()
    {
        $student = auth()->user();
        $records = Attendance::where('student_id', $student->id)
            ->with(['session.section.course'])
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('student.attendance', compact('records'));
    }
}
