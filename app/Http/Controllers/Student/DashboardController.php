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
            ->paginate(25);

        // Per-course summary
        $courseSummary = DB::table('attendance')
            ->join('class_sessions', 'attendance.class_session_id', '=', 'class_sessions.id')
            ->join('class_sections', 'class_sessions.class_section_id', '=', 'class_sections.id')
            ->join('courses', 'class_sections.course_id', '=', 'courses.id')
            ->where('attendance.student_id', $student->id)
            ->selectRaw("
                courses.name as course_name,
                courses.code as course_code,
                COUNT(attendance.id) as total_classes,
                SUM(CASE WHEN attendance.status IN ('present','late') THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendance.status = 'absent' THEN 1 ELSE 0 END) as absent_count
            ")
            ->groupBy('courses.name', 'courses.code')
            ->orderBy('courses.name')
            ->get()
            ->map(function ($row) {
                $row->attendance_pct = $row->total_classes > 0
                    ? round(($row->present_count / $row->total_classes) * 100, 1)
                    : 0;
                return $row;
            });

        return view('student.attendance', compact('records', 'courseSummary'));
    }
}
