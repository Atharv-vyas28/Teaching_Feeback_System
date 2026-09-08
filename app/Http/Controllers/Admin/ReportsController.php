<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\ClassSection;
use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use App\Models\User;
use App\Models\RatingResult;
use App\Services\FeedbackRatingService;
use App\Exports\AttendanceExport;
use App\Exports\AttendanceSummaryExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportsController extends Controller
{
    public function __construct(private FeedbackRatingService $ratingService) {}

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Build a query of class_session IDs that match all active filters.
     */
    private function filteredSessionIds(Request $request)
    {
        $query = ClassSession::query()
            ->join('class_sections', 'class_sessions.class_section_id', '=', 'class_sections.id')
            ->join('courses', 'class_sections.course_id', '=', 'courses.id')
            ->select('class_sessions.id');

        if ($request->filled('department_id')) {
            $query->where('courses.department_id', $request->department_id);
        }
        if ($request->filled('course_id')) {
            $query->where('class_sections.course_id', $request->course_id);
        }
        if ($request->filled('semester_id')) {
            $query->where('class_sections.semester_id', $request->semester_id);
        }
        if ($request->filled('staff_id') || $request->filled('faculty_id')) {
            $userId = $request->filled('staff_id') ? $request->staff_id : $request->faculty_id;
            $query->where('class_sessions.conducted_by', $userId);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('class_sessions.session_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('class_sessions.session_date', '<=', $request->date_to);
        }

        return $query->pluck('class_sessions.id');
    }

    // ─── Attendance Report (detailed) ─────────────────────────────────────────

    public function attendanceReport(Request $request)
    {
        $sessionIds = $this->filteredSessionIds($request);

        $attendanceQuery = Attendance::with([
                'student',
                'classSession.section.course.department',
                'markedBy',
            ])
            ->whereIn('class_session_id', $sessionIds)
            ->when($request->filled('student_id'), fn($q) => $q->where('student_id', $request->student_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->whereHas('student', fn($sub) =>
                    $sub->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('roll_number', 'like', '%' . $request->search . '%')
                );
            })
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at');

        $attendance  = $attendanceQuery->paginate(30)->withQueryString();
        $semesters   = Semester::orderByDesc('start_date')->get();
        $students    = User::where('role', 'student')->orderBy('name')->get();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $courses     = Course::where('is_active', true)->orderBy('name')->get();
        $staffList   = User::where('role', 'staff')->where('is_active', true)->orderBy('name')->get();
        $facultyList = User::where('role', 'faculty')->where('is_active', true)->orderBy('name')->get();

        return view('admin.reports.attendance', compact(
            'attendance', 'semesters', 'students',
            'departments', 'courses', 'staffList', 'facultyList'
        ));
    }

    // ─── Attendance Summary (per-student % table) ─────────────────────────────

    public function attendanceSummary(Request $request)
    {
        $sessionIds = $this->filteredSessionIds($request);

        $summary = DB::table('attendance')
            ->join('users', 'attendance.student_id', '=', 'users.id')
            ->join('class_sessions', 'attendance.class_session_id', '=', 'class_sessions.id')
            ->join('class_sections', 'class_sessions.class_section_id', '=', 'class_sections.id')
            ->join('courses', 'class_sections.course_id', '=', 'courses.id')
            ->leftJoin('departments', 'courses.department_id', '=', 'departments.id')
            ->whereIn('attendance.class_session_id', $sessionIds)
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($sub) use ($request) {
                    $sub->where('users.name', 'like', '%' . $request->search . '%')
                        ->orWhere('users.roll_number', 'like', '%' . $request->search . '%');
                });
            })
            ->selectRaw("
                attendance.student_id,
                users.name as student_name,
                users.roll_number,
                departments.name as department_name,
                courses.name as course_name,
                courses.code as course_code,
                COUNT(attendance.id) as total_classes,
                SUM(CASE WHEN attendance.status IN ('present','late') THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendance.status = 'absent' THEN 1 ELSE 0 END) as absent_count
            ")
            ->groupBy(
                'attendance.student_id', 'users.name', 'users.roll_number',
                'departments.name', 'courses.name', 'courses.code'
            )
            ->orderBy('departments.name')
            ->orderBy('courses.name')
            ->orderBy('users.name')
            ->paginate(30)
            ->withQueryString();

        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $courses     = Course::where('is_active', true)->orderBy('name')->get();
        $semesters   = Semester::orderByDesc('start_date')->get();
        $staffList   = User::where('role', 'staff')->where('is_active', true)->orderBy('name')->get();

        return view('admin.reports.attendance-summary', compact(
            'summary', 'departments', 'courses', 'semesters', 'staffList'
        ));
    }

    // ─── Excel Export ─────────────────────────────────────────────────────────

    public function exportAttendanceExcel(Request $request)
    {
        $sessionIds = $this->filteredSessionIds($request);

        $type     = $request->input('export_type', 'detailed'); // 'detailed' | 'summary'
        $filename = 'attendance_export_' . now()->format('Y-m-d') . '.xlsx';

        if ($type === 'summary') {
            return Excel::download(
                new AttendanceSummaryExport($sessionIds, $request->all()),
                $filename
            );
        }

        return Excel::download(
            new AttendanceExport($sessionIds, $request->all()),
            $filename
        );
    }

    // ─── Ratings ──────────────────────────────────────────────────────────────

    public function ratingsReport(Request $request)
    {
        $semesters  = Semester::orderByDesc('start_date')->get();
        $semesterId = $request->input('semester_id', optional(Semester::where('is_current', true)->first())->id);

        $ratings = RatingResult::with(['faculty', 'section.course', 'semester'])
            ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
            ->orderByDesc('overall_weighted_rating')
            ->get();

        return view('admin.reports.ratings', compact('ratings', 'semesters', 'semesterId'));
    }
}
