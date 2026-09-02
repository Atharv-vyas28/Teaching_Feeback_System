<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\Department;
use App\Models\FeedbackEligibility;
use App\Models\FeedbackResponse;
use App\Models\FeedbackSession;
use App\Models\RatingResult;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'department_id' => $request->integer('department_id') ?: null,
            'semester_id' => $request->integer('semester_id') ?: null,
            'course_id' => $request->integer('course_id') ?: null,
            'faculty_id' => $request->integer('faculty_id') ?: null,
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
        ];

        /*
         * Applies the selected filters to class-session-based data:
         * attendance, feedback sessions, feedback eligibility, responses.
         */
        $classSessionFilter = function ($query) use ($filters) {
            $query
                ->when($filters['from_date'], fn ($q) =>
                    $q->whereDate('session_date', '>=', $filters['from_date'])
                )
                ->when($filters['to_date'], fn ($q) =>
                    $q->whereDate('session_date', '<=', $filters['to_date'])
                )
                ->whereHas('section', function ($sectionQuery) use ($filters) {
                    $sectionQuery
                        ->when($filters['semester_id'], fn ($q) =>
                            $q->where('semester_id', $filters['semester_id'])
                        )
                        ->whereHas('course', function ($courseQuery) use ($filters) {
                            $courseQuery
                                ->when($filters['department_id'], fn ($q) =>
                                    $q->where('department_id', $filters['department_id'])
                                )
                                ->when($filters['course_id'], fn ($q) =>
                                    $q->where('id', $filters['course_id'])
                                );
                        })
                        ->when($filters['faculty_id'], function ($q) use ($filters) {
                            $q->whereHas('faculty', fn ($facultyQuery) =>
                                $facultyQuery->where('users.id', $filters['faculty_id'])
                            );
                        });
                });
        };

        /*
         * Applies filters to released rating records.
         * Ratings are created only after Admin releases feedback.
         */
        $ratingFilter = function ($query) use ($filters) {
            $query
                ->when($filters['faculty_id'], fn ($q) =>
                    $q->where('faculty_id', $filters['faculty_id'])
                )
                ->whereHas('section', function ($sectionQuery) use ($filters) {
                    $sectionQuery
                        ->when($filters['semester_id'], fn ($q) =>
                            $q->where('semester_id', $filters['semester_id'])
                        )
                        ->whereHas('course', function ($courseQuery) use ($filters) {
                            $courseQuery
                                ->when($filters['department_id'], fn ($q) =>
                                    $q->where('department_id', $filters['department_id'])
                                )
                                ->when($filters['course_id'], fn ($q) =>
                                    $q->where('id', $filters['course_id'])
                                );
                        });
                });
        };

        $feedbackSessionsQuery = FeedbackSession::query()
            ->whereHas('classSession', $classSessionFilter);

        $ratingResultsQuery = RatingResult::query();
        $ratingFilter($ratingResultsQuery);

        $responseIdsQuery = (clone $feedbackSessionsQuery)->select('id');

        $totalResponses = FeedbackResponse::whereIn(
            'feedback_session_id',
            $responseIdsQuery
        )->count();

        $eligibleStudents = FeedbackEligibility::whereIn(
            'feedback_session_id',
            (clone $feedbackSessionsQuery)->select('id')
        )->count();

        $responseRate = $eligibleStudents > 0
            ? round(($totalResponses / $eligibleStudents) * 100, 2)
            : 0;

        $averageAttendance = (float) Attendance::query()
            ->whereHas('classSession', $classSessionFilter)
            ->selectRaw("
                AVG(
                    CASE
                        WHEN status = 'present' THEN 100
                        ELSE 0
                    END
                ) as average_attendance
            ")
            ->value('average_attendance');

        $averageRating = (float) (clone $ratingResultsQuery)
            ->avg('overall_weighted_rating');

        $stats = [
            'total_students' => User::where('role', 'student')
                ->where('is_active', true)
                ->when($filters['department_id'], fn ($q) =>
                    $q->where('department_id', $filters['department_id'])
                )
                ->count(),

            'total_faculty' => User::where('role', 'faculty')
                ->where('is_active', true)
                ->when($filters['department_id'], fn ($q) =>
                    $q->where('department_id', $filters['department_id'])
                )
                ->count(),

            'total_courses' => Course::where('is_active', true)
                ->when($filters['department_id'], fn ($q) =>
                    $q->where('department_id', $filters['department_id'])
                )
                ->count(),

            'total_depts' => Department::where('is_active', true)->count(),

            'active_feedback' => (clone $feedbackSessionsQuery)
                ->where('status', 'active')
                ->count(),

            'pending_review' => (clone $feedbackSessionsQuery)
                ->where('status', 'closed')
                ->where('is_released', false)
                ->count(),

            'total_responses' => $totalResponses,
            'eligible_students' => $eligibleStudents,
            'response_rate' => $responseRate,
            'average_rating' => round($averageRating, 2),
            'average_attendance' => round($averageAttendance, 2),
        ];

        $departments = Department::where('is_active', true)
            ->when($filters['department_id'], fn ($q) =>
                $q->where('id', $filters['department_id'])
            )
            ->orderBy('name')
            ->get();

        $departmentPerformance = $departments->map(function ($department) use (
            $filters,
            $ratingFilter,
            $classSessionFilter
        ) {
            $departmentRatings = RatingResult::query()
                ->whereHas('section.course', fn ($q) =>
                    $q->where('department_id', $department->id)
                );

            $ratingFilter($departmentRatings);

            $departmentSessions = FeedbackSession::query()
                ->whereHas('classSession', function ($query) use (
                    $department,
                    $classSessionFilter
                ) {
                    $classSessionFilter($query);

                    $query->whereHas('section.course', fn ($courseQuery) =>
                        $courseQuery->where('department_id', $department->id)
                    );
                });

            $responseCount = FeedbackResponse::whereIn(
                'feedback_session_id',
                (clone $departmentSessions)->select('id')
            )->count();

            $eligibleCount = FeedbackEligibility::whereIn(
                'feedback_session_id',
                (clone $departmentSessions)->select('id')
            )->count();

            $attendance = (float) Attendance::query()
                ->whereHas('classSession', function ($query) use (
                    $department,
                    $classSessionFilter
                ) {
                    $classSessionFilter($query);

                    $query->whereHas('section.course', fn ($courseQuery) =>
                        $courseQuery->where('department_id', $department->id)
                    );
                })
                ->selectRaw("
                    AVG(
                        CASE
                            WHEN status = 'present' THEN 100
                            ELSE 0
                        END
                    ) as average_attendance
                ")
                ->value('average_attendance');

            $rating = (float) $departmentRatings
                ->avg('overall_weighted_rating');

            $responseRate = $eligibleCount > 0
                ? round(($responseCount / $eligibleCount) * 100, 1)
                : 0;

            return [
                'id' => $department->id,
                'name' => $department->name,
                'code' => $department->code,
                'rating' => round($rating, 2),
                'attendance' => round($attendance, 1),
                'response_rate' => $responseRate,
                'faculty_count' => User::where('role', 'faculty')
                    ->where('department_id', $department->id)
                    ->where('is_active', true)
                    ->count(),
                'course_count' => Course::where('department_id', $department->id)
                    ->where('is_active', true)
                    ->count(),
                'student_count' => User::where('role', 'student')
                    ->where('department_id', $department->id)
                    ->where('is_active', true)
                    ->count(),
            ];
        })->sortByDesc('rating')->values();

        $faculty = User::where('role', 'faculty')
            ->where('is_active', true)
            ->when($filters['department_id'], fn ($q) =>
                $q->where('department_id', $filters['department_id'])
            )
            ->when($filters['faculty_id'], fn ($q) =>
                $q->where('id', $filters['faculty_id'])
            )
            ->with('department')
            ->get();

        $facultyPerformance = $faculty->map(function ($facultyMember) use (
            $ratingFilter,
            $filters
        ) {
            $facultyRatings = RatingResult::where('faculty_id', $facultyMember->id);
            $ratingFilter($facultyRatings);

            $rating = (float) (clone $facultyRatings)
                ->avg('overall_weighted_rating');

            $responseCount = (int) (clone $facultyRatings)
                ->sum('response_count');

            return [
                'id' => $facultyMember->id,
                'name' => $facultyMember->name,
                'department' => $facultyMember->department?->name ?? 'Unassigned',
                'rating' => round($rating, 2),
                'response_count' => $responseCount,
                'course_count' => $facultyMember->facultyCourses()
                    ->where('is_active', true)
                    ->count(),
            ];
        })->sortByDesc('rating')->take(10)->values();

        $ratingTrend = (clone $ratingResultsQuery)
            ->selectRaw('semester_id, AVG(overall_weighted_rating) as rating')
            ->with('semester:id,name')
            ->groupBy('semester_id')
            ->orderBy('semester_id')
            ->get()
            ->map(fn ($row) => [
                'label' => $row->semester?->name ?? 'Semester',
                'rating' => round((float) $row->rating, 2),
            ])
            ->values();

        $pendingFeedback = (clone $feedbackSessionsQuery)
            ->with([
                'classSession.section.course',
                'classSession.section.faculty',
            ])
            ->where('status', 'closed')
            ->where('is_released', false)
            ->withCount('responses')
            ->latest('closed_at')
            ->take(6)
            ->get();

        $attentionAlerts = collect();

        foreach ($departmentPerformance as $department) {
            if ($department['rating'] > 0 && $department['rating'] < 3.5) {
                $attentionAlerts->push([
                    'type' => 'Low rating',
                    'message' => "{$department['name']} has a low average rating of {$department['rating']} / 5.",
                ]);
            }

            if ($department['attendance'] > 0 && $department['attendance'] < 75) {
                $attentionAlerts->push([
                    'type' => 'Low attendance',
                    'message' => "{$department['name']} attendance is {$department['attendance']}%, below the 75% threshold.",
                ]);
            }

            if ($department['response_rate'] > 0 && $department['response_rate'] < 70) {
                $attentionAlerts->push([
                    'type' => 'Low response rate',
                    'message' => "{$department['name']} feedback response rate is {$department['response_rate']}%.",
                ]);
            }
        }

        $attentionAlerts = $attentionAlerts->take(6);

        $recentSessions = ClassSession::with([
            'section.course',
            'conductor',
        ])
            ->where($classSessionFilter)
            ->latest('session_date')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'filters',
            'departments',
            'departmentPerformance',
            'facultyPerformance',
            'ratingTrend',
            'pendingFeedback',
            'attentionAlerts',
            'recentSessions'
        ));
    }
}