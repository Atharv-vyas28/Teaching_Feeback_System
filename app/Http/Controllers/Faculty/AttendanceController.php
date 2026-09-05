<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use App\Models\ClassSection;
use App\Models\Attendance;
use App\Models\User;
use App\Models\FacultyCourse;
use App\Models\Course;
use App\Models\Department;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FeedbackSession;


class AttendanceController extends Controller
{
    public function analytics(ClassSession $classSession)
{
    $isAssigned = FacultyCourse::where('user_id', auth()->id())
        ->where('class_section_id', $classSession->class_section_id)
        ->where('is_active', true)
        ->exists();

    abort_unless($isAssigned, 403);

    $classSession->load([
        'section.course',
        'attendanceRecords.student',
    ]);

    $records = $classSession->attendanceRecords
        ->sortBy(fn ($attendance) => $attendance->student?->name)
        ->values();

    $presentStudents = $records
        ->filter(fn ($attendance) => in_array(
            $attendance->status,
            ['present', 'late']
        ))
        ->values();

    $absentStudents = $records
        ->filter(fn ($attendance) => $attendance->status === 'absent')
        ->values();

    $feedbackEnabledStudents = $presentStudents
        ->filter(fn ($attendance) => $attendance->feedback_enabled)
        ->values();

    $feedbackDisabledStudents = $presentStudents
        ->filter(fn ($attendance) => ! $attendance->feedback_enabled)
        ->values();

    $stats = [
        'total' => $records->count(),
        'present' => $presentStudents->count(),
        'absent' => $absentStudents->count(),
        'late' => $records->where('status', 'late')->count(),
        'feedback_enabled' => $feedbackEnabledStudents->count(),
        'feedback_disabled' => $feedbackDisabledStudents->count(),
    ];

    $stats['attendance_percentage'] = $stats['total'] > 0
        ? round(($stats['present'] / $stats['total']) * 100, 2)
        : 0;

    $lineChartData = $records->map(function ($attendance) {
        return [
            'student' => $attendance->student?->roll_number
                ?? $attendance->student?->name
                ?? 'Student',

            'value' => in_array($attendance->status, ['present', 'late'])
                ? 1
                : 0,
        ];
    })->values();

    return view(
        'faculty.attendance.analytics',
        compact(
            'classSession',
            'records',
            'presentStudents',
            'absentStudents',
            'feedbackEnabledStudents',
            'feedbackDisabledStudents',
            'stats',
            'lineChartData'
        )
    );
}

    public function __construct() {}

    protected function authorizeSection(ClassSection $section): void
    {
        $user = auth()->user();
        // Faculty: must be assigned; Admin: always allowed
        if ($user->isFaculty()) {
            $assigned = FacultyCourse::where('user_id', $user->id)
                ->where('class_section_id', $section->id)
                ->exists();
            abort_unless($assigned, 403, 'Not assigned to this section.');
        }
    }

    public function sessions()
    {
        $user = auth()->user();
        $sections = $user->facultyCourses()
            ->with(['section.course', 'section.semester'])
            ->where('is_active', true)
            ->get()
            ->pluck('section');

        $sessions = ClassSession::with(['section.course', 'attendanceRecords'])
            ->whereIn('class_section_id', $sections->pluck('id'))
            ->where('conducted_by', $user->id)
            ->orderByDesc('session_date')
            ->paginate(20);

        return view('faculty.attendance.sessions', compact('sessions'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $sections = $user->facultyCourses()
            ->with(['section.course', 'section.semester'])
            ->where('is_active', true)
            ->get()
            ->pluck('section');

        return view('faculty.attendance.create-session', compact('sections'));
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'class_section_id' => 'required|exists:class_sections,id',
            'session_date'     => 'required|date',
            'start_time'       => 'required',
            'end_time'         => 'required|after:start_time',
            'topic'            => 'nullable|string|max:255',
        ]);

        $section = ClassSection::findOrFail($request->class_section_id);
        $this->authorizeSection($section);

        $session = ClassSession::create([
            'class_section_id' => $request->class_section_id,
            'conducted_by'     => auth()->id(),
            'session_date'     => $request->session_date,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'topic'            => $request->topic,
            'status'           => 'ongoing',
        ]);

        return redirect()->route('faculty.attendance.take', $session)->with('success', 'Session created. Now take attendance.');
    }

    public function take(ClassSession $classSession)
    {
        $this->authorizeSection($classSession->section);

        // Get enrolled students
        $students = User::join('course_enrollments', 'users.id', '=', 'course_enrollments.user_id')
            ->where('course_enrollments.class_section_id', $classSession->class_section_id)
            ->where('course_enrollments.status', 'active')
            ->where('users.role', 'student')
            ->select('users.*')
            ->orderBy('users.roll_number')
            ->get();

        $existingAttendance = Attendance::where('class_session_id', $classSession->id)
            ->get()
            ->keyBy('student_id');

        return view('faculty.attendance.take', compact('classSession', 'students', 'existingAttendance'));
    }

    public function save(Request $request, ClassSession $classSession)
    {
        $this->authorizeSection($classSession->section);

        $request->validate([
            'attendance'                => 'required|array',
            'attendance.*.status'       => 'required|in:present,absent,late,excused',
        ]);

        DB::transaction(function () use ($request, $classSession) {
            $hasPresentStudent = false;
            foreach ($request->attendance as $studentId => $data) {
                // Verify student is actually enrolled in this section
                $isEnrolled = DB::table('course_enrollments')
                    ->where('user_id', $studentId)
                    ->where('class_section_id', $classSession->class_section_id)
                    ->where('status', 'active')
                    ->exists();

                if (!$isEnrolled) continue;

                $status = $data['status'];
                $isPresent = in_array($status, ['present', 'late']);
                if ($isPresent) $hasPresentStudent = true;

                Attendance::updateOrCreate(
                    [
                        'class_session_id' => $classSession->id,
                        'student_id'       => $studentId,
                    ],
                    [
                        'marked_by'        => auth()->id(),
                        'status'           => $status,
                        'source'           => 'regular',
                        'feedback_enabled' => false,
                        'marked_at'        => now(),
                        'remarks'          => $data['remarks'] ?? null,
                    ]
                );
            }

            $classSession->update(['status' => 'completed']);

            // Auto-transition to active feedback state if any student was present
            if ($hasPresentStudent) {
                $feedbackSession = FeedbackSession::firstOrCreate(
                    ['class_session_id' => $classSession->id],
                    [
                        'created_by' => auth()->id(),
                        'status' => 'active',
                        'opened_at' => now(),
                    ]
                );

                if ($feedbackSession->status !== 'active') {
                    $feedbackSession->update(['status' => 'active', 'opened_at' => now()]);
                }

                $this->eligibilityService->createEligibilityRecords($feedbackSession);
            }
        });

        return redirect()->route('faculty.attendance.sessions')->with('success', 'Attendance saved successfully.');
    }

    /**
     * Per-student attendance summary across all faculty courses.
     * Supports filters: course (class_section_id), date_from, date_to, student search.
     */
    public function summary(Request $request)
    {
        $user = auth()->user();

        $sectionIds = FacultyCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('class_section_id');

        // Validate the requested section belongs to this faculty
        $filteredSectionId = $request->filled('section_id')
            ? (int) $request->section_id
            : null;

        if ($filteredSectionId && ! $sectionIds->contains($filteredSectionId)) {
            abort(403, 'You are not assigned to that course section.');
        }

        $activeSectionIds = $filteredSectionId
            ? collect([$filteredSectionId])
            : $sectionIds;

        // Session IDs matching date range
        $sessionQuery = ClassSession::whereIn('class_section_id', $activeSectionIds);

        if ($request->filled('date_from')) {
            $sessionQuery->whereDate('session_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $sessionQuery->whereDate('session_date', '<=', $request->date_to);
        }

        $sessionIds = $sessionQuery->pluck('id');

        // Per-student summary
        $summaryQuery = DB::table('attendance')
            ->join('users', 'attendance.student_id', '=', 'users.id')
            ->join('class_sessions', 'attendance.class_session_id', '=', 'class_sessions.id')
            ->join('class_sections', 'class_sessions.class_section_id', '=', 'class_sections.id')
            ->join('courses', 'class_sections.course_id', '=', 'courses.id')
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
                courses.name as course_name,
                courses.code as course_code,
                class_sections.id as section_id,
                COUNT(attendance.id) as total_classes,
                SUM(CASE WHEN attendance.status IN ('present','late') THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendance.status = 'absent' THEN 1 ELSE 0 END) as absent_count
            ")
            ->groupBy(
                'attendance.student_id',
                'users.name',
                'users.roll_number',
                'courses.name',
                'courses.code',
                'class_sections.id'
            )
            ->orderBy('courses.name')
            ->orderBy('users.name');

        $summary = $summaryQuery->paginate(30)->withQueryString();

        // Sections for filter dropdown
        $sections = FacultyCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->with(['section.course'])
            ->get()
            ->pluck('section');

        return view('faculty.attendance.summary', compact(
            'summary', 'sections', 'filteredSectionId'
        ));
    }

    /**
     * Export faculty attendance as Excel (.xlsx).
     * Respects the same filters as summary().
     */
    public function exportExcel(Request $request)
    {
        $user = auth()->user();

        $sectionIds = FacultyCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('class_section_id');

        $filteredSectionId = $request->filled('section_id')
            ? (int) $request->section_id
            : null;

        if ($filteredSectionId && ! $sectionIds->contains($filteredSectionId)) {
            abort(403);
        }

        $activeSectionIds = $filteredSectionId
            ? collect([$filteredSectionId])
            : $sectionIds;

        $sessionQuery = ClassSession::whereIn('class_section_id', $activeSectionIds);
        if ($request->filled('date_from')) {
            $sessionQuery->whereDate('session_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $sessionQuery->whereDate('session_date', '<=', $request->date_to);
        }
        $sessionIds = $sessionQuery->pluck('id');

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AttendanceExport($sessionIds, $request->all()),
            'attendance_' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}
