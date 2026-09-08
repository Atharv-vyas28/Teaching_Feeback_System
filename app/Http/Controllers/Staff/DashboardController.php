<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffCourse;
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Models\FeedbackSession;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $currentSemester = Semester::where('is_current', true)->first();

        $sectionIds = StaffCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('class_section_id');

        $assignedFeedback = FeedbackSession::where('assigned_staff_id', $user->id)->count();
        $activeFeedback   = FeedbackSession::where('assigned_staff_id', $user->id)
                                ->where('status', 'active')->count();
        $closedFeedback   = FeedbackSession::where('assigned_staff_id', $user->id)
                                ->whereIn('status', ['closed'])->count();

        // Expired = active but past deadline
        $expiredFeedback  = FeedbackSession::where('assigned_staff_id', $user->id)
                                ->where('status', 'active')
                                ->whereNotNull('deadline_at')
                                ->where('deadline_at', '<', now())
                                ->count();

        $stats = [
            'sections'          => $sectionIds->count(),
            'total_sessions'    => ClassSession::whereIn('class_section_id', $sectionIds)->count(),
            'today_sessions'    => ClassSession::whereIn('class_section_id', $sectionIds)
                                        ->whereDate('session_date', today())->count(),
            'active_feedback'   => $activeFeedback,
            'assigned_feedback' => $assignedFeedback,
            'closed_feedback'   => $closedFeedback,
            'expired_feedback'  => $expiredFeedback,
        ];

        $recentSessions = ClassSession::with(['section.course', 'attendanceRecords'])
            ->whereIn('class_section_id', $sectionIds)
            ->orderByDesc('session_date')
            ->take(8)
            ->get();

        $activeFeedbackSessions = FeedbackSession::with([
                'classSession.section.course',
            ])
            ->where('assigned_staff_id', $user->id)
            ->where('status', 'active')
            ->orderByDesc('release_at')
            ->take(5)
            ->get();

        return view('staff.dashboard', compact(
            'stats', 'recentSessions', 'currentSemester', 'activeFeedbackSessions'
        ));
    }
}
