<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\FacultyCourse;
use App\Models\ClassSession;
use App\Models\FeedbackSession;
use App\Models\RatingResult;
use App\Models\Semester;
use App\Models\Attendance;
use App\Models\FeedbackEligibility;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $currentSemester = Semester::where('is_current', true)->first();

        $sectionIds = FacultyCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('class_section_id');

        $stats = [
            'sections'         => $sectionIds->count(),
            'total_sessions'   => ClassSession::whereIn('class_section_id', $sectionIds)->count(),
            'active_feedback'  => FeedbackSession::whereHas('classSession', fn($q) => $q->whereIn('class_section_id', $sectionIds))
                                    ->where('status', 'active')->count(),
            'overall_rating'   => RatingResult::where('faculty_id', $user->id)->avg('overall_weighted_rating'),
        ];

        $recentSessions = ClassSession::with(['section.course', 'feedbackSession', 'attendanceRecords'])
            ->whereIn('class_section_id', $sectionIds)
            ->orderByDesc('session_date')
            ->take(6)
            ->get();

        $ratingResults = RatingResult::where('faculty_id', $user->id)
            ->with(['section.course'])
            ->latest('calculated_at')
            ->take(4)
            ->get();

        return view('faculty.dashboard', compact('stats', 'recentSessions', 'ratingResults', 'currentSemester'));
    }
}
