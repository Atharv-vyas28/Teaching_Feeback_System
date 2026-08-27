<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\ClassSection;
use App\Models\Semester;
use App\Models\User;
use App\Models\RatingResult;
use App\Services\FeedbackRatingService;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function __construct(private FeedbackRatingService $ratingService) {}

    public function attendanceReport(Request $request)
    {
        $semesters = Semester::orderByDesc('start_date')->get();
        $query = Attendance::with(['student', 'session.section.course', 'session'])
            ->join('class_sessions', 'attendance.class_session_id', '=', 'class_sessions.id')
            ->join('class_sections', 'class_sessions.class_section_id', '=', 'class_sections.id');

        if ($request->filled('semester_id')) {
            $query->where('class_sections.semester_id', $request->semester_id);
        }
        if ($request->filled('student_id')) {
            $query->where('attendance.student_id', $request->student_id);
        }

        $attendance = $query->select('attendance.*')->latest('attendance.created_at')->paginate(30)->withQueryString();
        $students   = User::where('role', 'student')->orderBy('name')->get();

        return view('admin.reports.attendance', compact('attendance', 'semesters', 'students'));
    }

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
