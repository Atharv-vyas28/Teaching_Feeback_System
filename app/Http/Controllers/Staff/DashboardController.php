<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffCourse;
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Models\FeedbackSession;
use App\Models\Semester;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $currentSemester = Semester::where('is_current', true)->first();

        $sectionIds = StaffCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('class_section_id');

        $stats = [
            'sections'        => $sectionIds->count(),
            'total_sessions'  => ClassSession::whereIn('class_section_id', $sectionIds)->count(),
            'today_sessions'  => ClassSession::whereIn('class_section_id', $sectionIds)
                                    ->whereDate('session_date', today())->count(),
            'active_feedback' => FeedbackSession::whereHas('classSession', fn($q) => $q->whereIn('class_section_id', $sectionIds))
                                    ->where('status', 'active')->count(),
        ];

        $recentSessions = ClassSession::with(['section.course', 'attendanceRecords'])
            ->whereIn('class_section_id', $sectionIds)
            ->orderByDesc('session_date')
            ->take(8)
            ->get();

        return view('staff.dashboard', compact('stats', 'recentSessions', 'currentSemester'));
    }
}
