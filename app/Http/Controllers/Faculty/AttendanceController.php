<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\ClassSection;
use App\Models\ClassSession;
use App\Models\FacultyCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    protected function authorizeSection(ClassSection $section): void
    {
        $assigned = FacultyCourse::where('user_id', auth()->id())
            ->where('class_section_id', $section->id)
            ->where('is_active', true)
            ->exists();

        abort_unless(
            $assigned,
            403,
            'You are not assigned to this section.'
        );
    }

    protected function authorizeClassSession(ClassSession $classSession): void
    {
        $classSession->loadMissing('section');

        $this->authorizeSection($classSession->section);

        abort_unless(
            (int) $classSession->conducted_by === (int) auth()->id(),
            403,
            'You can only access sessions conducted by you.'
        );
    }

    public function sessions(Request $request)
    {
        $user = auth()->user();

        $sectionIds = FacultyCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('class_section_id');

        $selectedSectionId = $request->filled('section_id')
            ? (int) $request->section_id
            : null;

        if ($selectedSectionId && ! $sectionIds->contains($selectedSectionId)) {
            abort(403, 'You are not assigned to this course section.');
        }

        $sessionCounts = DB::table('class_sessions')
            ->whereIn('class_section_id', $sectionIds)
            ->where('conducted_by', $user->id)
            ->selectRaw('class_section_id, COUNT(*) as total_sessions')
            ->groupBy('class_section_id');

        $attendanceStats = DB::table('class_sessions')
            ->leftJoin('attendance', function ($join) {
                $join->on(
                    'class_sessions.id',
                    '=',
                    'attendance.class_session_id'
                )->where('attendance.source', 'regular');
            })
            ->whereIn('class_sessions.class_section_id', $sectionIds)
            ->where('class_sessions.conducted_by', $user->id)
            ->selectRaw("
                class_sessions.class_section_id,
                COUNT(attendance.id) as total_marked,
                SUM(
                    CASE
                        WHEN attendance.status IN ('present', 'late')
                        THEN 1
                        ELSE 0
                    END
                ) as present_count
            ")
            ->groupBy('class_sessions.class_section_id');

        $courseSummaries = ClassSection::query()
            ->with('course')
            ->join(
                'courses',
                'class_sections.course_id',
                '=',
                'courses.id'
            )
            ->leftJoinSub(
                $sessionCounts,
                'session_counts',
                'session_counts.class_section_id',
                '=',
                'class_sections.id'
            )
            ->leftJoinSub(
                $attendanceStats,
                'attendance_stats',
                'attendance_stats.class_section_id',
                '=',
                'class_sections.id'
            )
            ->whereIn('class_sections.id', $sectionIds)
            ->select(
                'class_sections.*',
                DB::raw(
                    'COALESCE(session_counts.total_sessions, 0)
                    as total_sessions'
                ),
                DB::raw(
                    'COALESCE(attendance_stats.total_marked, 0)
                    as total_marked'
                ),
                DB::raw(
                    'COALESCE(attendance_stats.present_count, 0)
                    as present_count'
                )
            )
            ->orderBy('courses.name')
            ->orderBy('class_sections.section_name')
            ->get();

        $trendSessions = ClassSession::with('section.course')
            ->withCount([
                'attendanceRecords as total_attendance' => function ($query) {
                    $query->where('source', 'regular');
                },

                'attendanceRecords as present_attendance' => function ($query) {
                    $query->where('source', 'regular')
                        ->whereIn('status', ['present', 'late']);
                },
            ])
            ->whereIn('class_section_id', $sectionIds)
            ->where('conducted_by', $user->id)
            ->when(
                $selectedSectionId,
                fn ($query) => $query->where(
                    'class_section_id',
                    $selectedSectionId
                )
            )
            ->orderBy('session_date')
            ->orderBy('start_time')
            ->get();

        $attendanceTrend = $trendSessions
            ->values()
            ->map(function ($session, $index) use ($selectedSectionId) {
                $percentage = $session->total_attendance > 0
                    ? round(
                        ($session->present_attendance
                            / $session->total_attendance) * 100,
                        1
                    )
                    : 0;

                $date = $session->session_date?->format('d M') ?? '';

                return [
                    'label' => $selectedSectionId
                        ? 'Lecture ' . ($index + 1) . ' · ' . $date
                        : ($session->section?->course?->code ?? 'Course')
                            . ' · ' . $date,

                    'percentage' => $percentage,
                    'has_attendance' => $session->total_attendance > 0,
                ];
            })
            ->values();

        $sessions = ClassSession::with('section.course')
            ->withCount([
                'attendanceRecords as total_attendance' => function ($query) {
                    $query->where('source', 'regular');
                },

                'attendanceRecords as present_attendance' => function ($query) {
                    $query->where('source', 'regular')
                        ->whereIn('status', ['present', 'late']);
                },
            ])
            ->whereIn('class_section_id', $sectionIds)
            ->where('conducted_by', $user->id)
            ->when(
                $selectedSectionId,
                fn ($query) => $query->where(
                    'class_section_id',
                    $selectedSectionId
                )
            )
            ->orderByDesc('session_date')
            ->orderByDesc('start_time')
            ->paginate(20)
            ->withQueryString();

        return view('faculty.attendance.sessions', compact(
            'sessions',
            'courseSummaries',
            'selectedSectionId',
            'attendanceTrend'
        ));
    }

    public function create()
    {
        $user = auth()->user();

        $sections = $user->facultyCourses()
            ->with([
                'section.course',
                'section.semester',
            ])
            ->where('is_active', true)
            ->get()
            ->pluck('section');

        return view(
            'faculty.attendance.create-session',
            compact('sections')
        );
    }

    public function storeSession(Request $request)
    {
        $validated = $request->validate([
            'class_section_id' => ['required', 'exists:class_sections,id'],
            'session_date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required', 'after:start_time'],
            'topic' => ['nullable', 'string', 'max:255'],
        ]);

        $section = ClassSection::findOrFail(
            $validated['class_section_id']
        );

        $this->authorizeSection($section);

        $session = ClassSession::create([
            'class_section_id' => $section->id,
            'conducted_by' => auth()->id(),
            'session_date' => $validated['session_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'topic' => $validated['topic'] ?? null,
            'status' => 'ongoing',
        ]);

        return redirect()
            ->route('faculty.attendance.take', $session)
            ->with(
                'success',
                'Session created. You can now mark attendance.'
            );
    }

    public function take(ClassSession $classSession)
    {
        $classSession->load('section.course');

        $this->authorizeClassSession($classSession);

        $students = User::query()
            ->join(
                'course_enrollments',
                'users.id',
                '=',
                'course_enrollments.user_id'
            )
            ->where(
                'course_enrollments.class_section_id',
                $classSession->class_section_id
            )
            ->where('course_enrollments.status', 'active')
            ->where('users.role', 'student')
            ->select('users.*')
            ->orderBy('users.roll_number')
            ->get();

        $existingAttendance = Attendance::where(
            'class_session_id',
            $classSession->id
        )
            ->where('source', 'regular')
            ->get()
            ->keyBy('student_id');

        $lectureNumber = ClassSession::where(
            'class_section_id',
            $classSession->class_section_id
        )
            ->where('conducted_by', auth()->id())
            ->where(function ($query) use ($classSession) {
                $query->where(
                    'session_date',
                    '<',
                    $classSession->session_date
                )->orWhere(function ($query) use ($classSession) {
                    $query->where(
                        'session_date',
                        $classSession->session_date
                    )
                        ->where('id', '<=', $classSession->id);
                });
            })
            ->count();

        return view(
            'faculty.attendance.take',
            compact(
                'classSession',
                'students',
                'existingAttendance',
                'lectureNumber'
            )
        );
    }

    public function save(
        Request $request,
        ClassSession $classSession
    ) {
        $classSession->load('section');

        $this->authorizeClassSession($classSession);

        $validated = $request->validate([
            'attendance' => ['required', 'array'],
            'attendance.*.status' => [
                'required',
                'in:present,absent',
            ],
            'attendance.*.remarks' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        DB::transaction(function () use ($validated, $classSession) {
            $studentIds = collect(
                array_keys($validated['attendance'])
            );

            $enrolledStudentIds = DB::table('course_enrollments')
                ->where(
                    'class_section_id',
                    $classSession->class_section_id
                )
                ->where('status', 'active')
                ->whereIn('user_id', $studentIds)
                ->pluck('user_id')
                ->flip();

            foreach ($validated['attendance'] as $studentId => $data) {
                if (! isset($enrolledStudentIds[$studentId])) {
                    continue;
                }

                Attendance::updateOrCreate(
                    [
                        'class_session_id' => $classSession->id,
                        'student_id' => $studentId,
                        'source' => 'regular',
                    ],
                    [
                        'marked_by' => auth()->id(),
                        'status' => $data['status'],
                        'feedback_enabled' => false,
                        'marked_at' => now(),
                        'remarks' => $data['remarks'] ?? null,
                    ]
                );
            }

            $classSession->update([
                'status' => 'completed',
            ]);
        });

        return redirect()
            ->route('faculty.attendance.sessions')
            ->with(
                'success',
                'Attendance saved successfully.'
            );
    }

    public function analytics(ClassSession $classSession)
    {
        $classSession->load('section.course');

        $this->authorizeClassSession($classSession);

        $records = Attendance::with('student')
            ->where('class_session_id', $classSession->id)
            ->where('source', 'regular')
            ->get()
            ->sortBy(
                fn ($attendance) => $attendance->student?->name
            )
            ->values();

        $presentStudents = $records
            ->filter(
                fn ($attendance) => in_array(
                    $attendance->status,
                    ['present', 'late']
                )
            )
            ->values();

        $absentStudents = $records
            ->filter(
                fn ($attendance) => $attendance->status === 'absent'
            )
            ->values();

        $stats = [
            'total' => $records->count(),
            'present' => $presentStudents->count(),
            'absent' => $absentStudents->count(),
        ];

        $stats['attendance_percentage'] = $stats['total'] > 0
            ? round(
                ($stats['present'] / $stats['total']) * 100,
                2
            )
            : 0;

        $lineChartData = $records
            ->map(function ($attendance) {
                return [
                    'student' => $attendance->student?->roll_number
                        ?? $attendance->student?->name
                        ?? 'Student',

                    'value' => in_array(
                        $attendance->status,
                        ['present', 'late']
                    ) ? 1 : 0,
                ];
            })
            ->values();

        return view(
            'faculty.attendance.analytics',
            compact(
                'classSession',
                'records',
                'presentStudents',
                'absentStudents',
                'stats',
                'lineChartData'
            )
        );
    }

    public function summary(Request $request)
    {
        $user = auth()->user();

        $sectionIds = FacultyCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('class_section_id');

        $filteredSectionId = $request->filled('section_id')
            ? (int) $request->section_id
            : null;

        if ($filteredSectionId && ! $sectionIds->contains($filteredSectionId)) {
            abort(403, 'You are not assigned to that course section.');
        }

        $activeSectionIds = $filteredSectionId
            ? collect([$filteredSectionId])
            : $sectionIds;

        $sessionQuery = ClassSession::whereIn(
            'class_section_id',
            $activeSectionIds
        )->where('conducted_by', $user->id);

        if ($request->filled('date_from')) {
            $sessionQuery->whereDate(
                'session_date',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $sessionQuery->whereDate(
                'session_date',
                '<=',
                $request->date_to
            );
        }

        $sessionIds = $sessionQuery->pluck('id');

        $summaryQuery = DB::table('attendance')
            ->join('users', 'attendance.student_id', '=', 'users.id')
            ->join(
                'class_sessions',
                'attendance.class_session_id',
                '=',
                'class_sessions.id'
            )
            ->join(
                'class_sections',
                'class_sessions.class_section_id',
                '=',
                'class_sections.id'
            )
            ->join(
                'courses',
                'class_sections.course_id',
                '=',
                'courses.id'
            )
            ->whereIn('attendance.class_session_id', $sessionIds)
            ->where('attendance.source', 'regular')
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->search;

                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where(
                            'users.name',
                            'like',
                            "%{$search}%"
                        )->orWhere(
                            'users.roll_number',
                            'like',
                            "%{$search}%"
                        );
                    });
                }
            )
            ->selectRaw("
                attendance.student_id,
                users.name as student_name,
                users.roll_number,
                courses.name as course_name,
                courses.code as course_code,
                class_sections.id as section_id,
                COUNT(attendance.id) as total_classes,
                SUM(
                    CASE
                        WHEN attendance.status IN ('present', 'late')
                        THEN 1
                        ELSE 0
                    END
                ) as present_count,
                SUM(
                    CASE
                        WHEN attendance.status = 'absent'
                        THEN 1
                        ELSE 0
                    END
                ) as absent_count
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

        $summary = $summaryQuery
            ->paginate(30)
            ->withQueryString();

        $sections = FacultyCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->with('section.course')
            ->get()
            ->pluck('section');

        return view(
            'faculty.attendance.summary',
            compact(
                'summary',
                'sections',
                'filteredSectionId'
            )
        );
    }

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

        $sessionQuery = ClassSession::whereIn(
            'class_section_id',
            $activeSectionIds
        )->where('conducted_by', $user->id);

        if ($request->filled('date_from')) {
            $sessionQuery->whereDate(
                'session_date',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $sessionQuery->whereDate(
                'session_date',
                '<=',
                $request->date_to
            );
        }

        $sessionIds = $sessionQuery->pluck('id');

        $filters = $request->all();
        $filters['source'] = 'regular';

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AttendanceExport(
                $sessionIds,
                $filters
            ),
            'attendance_' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}