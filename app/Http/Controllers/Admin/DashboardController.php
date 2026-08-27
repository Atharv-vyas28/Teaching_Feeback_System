<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use App\Models\Course;
use App\Models\ClassSession;
use App\Models\FeedbackSession;
use App\Models\Attendance;
use App\Models\Semester;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_students'  => User::where('role', 'student')->where('is_active', true)->count(),
            'total_faculty'   => User::where('role', 'faculty')->where('is_active', true)->count(),
            'total_staff'     => User::where('role', 'staff')->where('is_active', true)->count(),
            'total_courses'   => Course::where('is_active', true)->count(),
            'total_depts'     => Department::where('is_active', true)->count(),
            'active_sessions' => FeedbackSession::where('status', 'active')->count(),
        ];

        $recentSessions = ClassSession::with(['section.course', 'conductor'])
            ->latest('session_date')
            ->take(8)
            ->get();

        $currentSemester = Semester::where('is_current', true)->first();

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentSessions', 'currentSemester', 'recentUsers'));
    }
}
