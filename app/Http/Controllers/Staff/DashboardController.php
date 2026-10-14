<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StaffCourse;
use App\Models\ClassSession;
use App\Models\Attendance;
use App\Models\FeedbackSession;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;
use App\Services\StaffAssignmentAccessService;

class DashboardController extends Controller
{
    public function __construct(private StaffAssignmentAccessService $access) {}

    public function index()
    {
        $user = auth()->user();
        $currentSemester = Semester::where('is_current', true)->first();

        $sectionIds = StaffCourse::active()->where('user_id', $user->id)
            ->pluck('class_section_id');

        $feedbackQuery = $this->access->feedbackSessionsFor($user);
        $assignedFeedback = (clone $feedbackQuery)->count();
        $activeFeedback   = (clone $feedbackQuery)->where('status', 'active')->count();
        $closedFeedback   = (clone $feedbackQuery)->where('status', 'closed')->count();

        // Expired = active but past deadline
        $expiredFeedback  = (clone $feedbackQuery)
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

        $activeFeedbackSessions = $this->access->feedbackSessionsFor($user)->with([
                'classSession.section.course',
            ])
            ->where('status', 'active')
            ->orderByDesc('release_at')
            ->take(5)
            ->get();

        return view('staff.dashboard', compact(
            'stats', 'recentSessions', 'currentSemester', 'activeFeedbackSessions'
        ));
    }
}
