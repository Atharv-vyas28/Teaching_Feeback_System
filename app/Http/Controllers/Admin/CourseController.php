<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use App\Models\ClassSection;
use App\Models\Semester;
use App\Models\User;
use App\Models\CourseEnrollment;
use App\Models\FacultyCourse;
use App\Models\StaffCourse;
use App\Notifications\FacultyAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['department', 'program'])->orderBy('name');
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        $courses = $query->paginate(20)->withQueryString();
        $departments = Department::where('is_active', true)->get();
        return view('admin.courses.index', compact('courses', 'departments'));
    }

    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $programs    = Program::where('is_active', true)->get();
        return view('admin.courses.create', compact('departments', 'programs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => 'required|string|max:20|unique:courses,code',
            'department_id'   => 'required|exists:departments,id',
            'program_id'      => 'required|exists:programs,id',
            'credits'         => 'required|integer|min:1|max:10',
            'semester_number' => 'required|integer|min:1|max:12',
            'description'     => 'nullable|string',
        ]);
        Course::create($request->only(
            'name', 'code', 'department_id', 'program_id',
            'credits', 'semester_number', 'description'
        ) + ['is_active' => true]);
        return redirect()->route('admin.courses.index')->with('success', 'Course created.');
    }

    public function edit(Course $course)
    {
        $departments = Department::where('is_active', true)->get();
        $programs    = Program::where('is_active', true)->get();
        return view('admin.courses.edit', compact('course', 'departments', 'programs'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'code'            => ['required', 'string', 'max:20', Rule::unique('courses')->ignore($course->id)],
            'department_id'   => 'required|exists:departments,id',
            'program_id'      => 'required|exists:programs,id',
            'credits'         => 'required|integer|min:1|max:10',
            'semester_number' => 'required|integer|min:1|max:12',
            'description'     => 'nullable|string',
            'is_active'       => 'boolean',
        ]);
        $course->update($request->only(
            'name', 'code', 'department_id', 'program_id',
            'credits', 'semester_number', 'description'
        ) + ['is_active' => $request->boolean('is_active')]);
        return redirect()->route('admin.courses.index')->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted.');
    }

    // Sections
    public function sections(Course $course)
    {
        $semesters = Semester::orderBy('name')->get();
        $sections  = $course->classSections()->with(['semester', 'enrollments'])->paginate(15);
        return view('admin.courses.sections', compact('course', 'sections', 'semesters'));
    }

    public function storeSection(Request $request, Course $course)
    {
        $request->validate([
            'name'        => 'required|string|max:50',
            'semester_id' => 'required|exists:semesters,id',
            'max_students'=> 'required|integer|min:1|max:200',
        ]);
        $course->classSections()->create($request->only('name', 'semester_id', 'max_students') + ['is_active' => true]);
        return back()->with('success', 'Section created.');
    }

    // Enrollments
    public function enrollments()
    {
        $enrollments = CourseEnrollment::with(['student', 'section.course', 'semester'])
            ->latest()->paginate(20);
        $students  = User::where('role', 'student')->where('is_active', true)->orderBy('name')->get();
        $sections  = ClassSection::with('course')->where('is_active', true)->get();
        $semesters = Semester::orderBy('name')->get();
        return view('admin.enrollments.index', compact('enrollments', 'students', 'sections', 'semesters'));
    }

    public function storeEnrollment(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'semester_id'      => 'required|exists:semesters,id',
        ]);
        CourseEnrollment::firstOrCreate(
            [
                'user_id'          => $request->user_id,
                'class_section_id' => $request->class_section_id,
                'semester_id'      => $request->semester_id,
            ],
            ['status' => 'active', 'enrolled_at' => now()]
        );
        return back()->with('success', 'Student enrolled.');
    }

    // Faculty assignments
    public function assignFaculty(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'class_section_id' => 'required|exists:class_sections,id',
            'semester_id'      => 'required|exists:semesters,id',
        ]);
        $faculty = User::findOrFail($request->user_id);
        if (!$faculty->isFaculty()) {
            return back()->with('error', 'Selected user is not faculty.');
        }
        $assignment = FacultyCourse::firstOrCreate(
            [
                'user_id'          => $request->user_id,
                'class_section_id' => $request->class_section_id,
                'semester_id'      => $request->semester_id,
            ],
            ['is_active' => true]
        );

        if ($assignment->wasRecentlyCreated) {
            $faculty->notify(new FacultyAssignedNotification($assignment));
        }

        return back()->with('success', 'Faculty assigned.');
    }
}
@extends('layouts.dashboard')
@section('title', 'Take Attendance')
@php $header = 'Take Attendance'; $subheader = $classSession->section->course->name ?? 'Class Session'; @endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>
<a href="{{ route('staff.dashboard') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Attendance</div>
<a href="{{ route('staff.attendance.sessions') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    My Sessions
</a>
<a href="{{ route('staff.attendance.create') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New Session
</a>
@endsection

@section('content')
<div class="card" style="margin-bottom:20px;">
    <div class="card-body" style="display:flex;gap:24px;flex-wrap:wrap;">
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Course</div><div style="font-weight:700;color:#0F172A;">{{ $classSession->section->course->name ?? 'N/A' }}</div></div>
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Date</div><div style="font-weight:700;color:#0F172A;">{{ $classSession->session_date->format('M d, Y') }}</div></div>
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Time</div><div style="font-weight:700;color:#0F172A;">{{ $classSession->start_time }} – {{ $classSession->end_time }}</div></div>
        <div><div style="font-size:0.7rem;color:#94A3B8;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:3px;">Students</div><div style="font-weight:700;color:#0F172A;">{{ count($students) }} enrolled</div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Mark Attendance</h3>
        <div style="display:flex;gap:8px;">
            <button type="button" onclick="markAll('present')" class="btn-success btn-sm">All Present</button>
            <button type="button" onclick="markAll('absent')"  class="btn-secondary btn-sm">All Absent</button>
        </div>
    </div>
    <form method="POST" action="{{ route('staff.attendance.save', $classSession) }}">
        @csrf
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>Student</th><th>Roll Number</th><th>Status</th><th>Remarks</th></tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                    @php $existing = $existingAttendance[$student->id] ?? null; @endphp
                    <tr>
                        <td style="color:#94A3B8;font-weight:600;">{{ $i + 1 }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#DBEAFE,#BFDBFE);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;color:#1D4ED8;">
                                    {{ strtoupper(substr($student->name,0,1)) }}
                                </div>
                                {{ $student->name }}
                            </div>
                        </td>
                        <td style="color:#64748B;font-size:0.8rem;">{{ $student->roll_number ?? '—' }}</td>
                        <td>
                            <select name="attendance[{{ $student->id }}][status]" class="form-select status-select" style="width:130px;">
                                @foreach(['present','absent','late','excused'] as $status)
                                <option value="{{ $status }}" {{ ($existing && $existing->status === $status) ? 'selected' : ($status === 'absent' ? 'selected' : '') }}>
                                    {{ ucfirst($status) }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="attendance[{{ $student->id }}][remarks]" class="form-input" style="width:150px;" value="{{ $existing->remarks ?? '' }}" placeholder="Optional">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:20px;display:flex;gap:12px;">
            <button type="submit" class="btn-primary">Save Attendance</button>
            <a href="{{ route('staff.attendance.sessions') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
function markAll(status) {
    document.querySelectorAll('.status-select').forEach(sel => sel.value = status);
}
</script>
@endpush
@endsection
<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use App\Models\ClassSection;
use App\Models\Attendance;
use App\Models\User;
use App\Models\FacultyCourse;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FeedbackSession;
use App\Services\FeedbackEligibilityService;


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

    public function __construct(
        private FeedbackEligibilityService $eligibilityService
    ) {}

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
                        // Automatically enable feedback for present students based on requirements
                        'feedback_enabled' => $isPresent,
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

    public function enableFeedback(Request $request, ClassSession $classSession)
    {
        $this->authorizeSection($classSession->section);

        $request->validate([
            'student_ids'   => 'required|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        // Only enable for present students
        Attendance::where('class_session_id', $classSession->id)
            ->whereIn('student_id', $request->student_ids)
            ->whereIn('status', ['present', 'late'])
            ->update(['feedback_enabled' => true]);

        return back()->with('success', 'Feedback enabled for selected students.');
    }
}
<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use App\Models\Attendance;
use App\Models\FeedbackSession;
use App\Models\FeedbackEligibility;
use App\Models\ClassSession;
use App\Models\Semester;
use App\Services\FeedbackEligibilityService;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct(private FeedbackEligibilityService $eligibilityService) {}

    public function index()
    {
        $student = auth()->user();
        $currentSemester = Semester::where('is_current', true)->first();

        $enrolledSectionIds = CourseEnrollment::where('user_id', $student->id)
            ->where('status', 'active')
            ->pluck('class_section_id');

        // Attendance summary
        $totalClasses  = Attendance::where('student_id', $student->id)->count();
        $presentCount  = Attendance::where('student_id', $student->id)->whereIn('status', ['present', 'late'])->count();
        $attendancePct = $totalClasses > 0 ? round(($presentCount / $totalClasses) * 100, 1) : 0;

        // Available feedback sessions
        $feedbackSessions = FeedbackSession::whereHas('classSession', fn($q) => $q->whereIn('class_section_id', $enrolledSectionIds))
            ->where('status', 'active')
            ->with(['classSession.section.course'])
            ->get();

        $availableFeedback = [];
        foreach ($feedbackSessions as $session) {
            $eligibility = $this->eligibilityService->checkEligibility($student, $session);
            if ($eligibility['eligible']) {
                $availableFeedback[] = $session;
            }
        }

        $completedFeedback = FeedbackEligibility::where('student_id', $student->id)
            ->where('has_submitted', true)
            ->count();

        $enrollments = CourseEnrollment::where('user_id', $student->id)
            ->where('status', 'active')
            ->with(['section.course', 'section.semester'])
            ->get();

        $todaySessions = ClassSession::whereIn('class_section_id', $enrolledSectionIds)
            ->whereDate('session_date', today())
            ->with(['section.course'])
            ->get();

        return view('student.dashboard', compact(
            'student', 'currentSemester', 'enrollments', 'todaySessions',
            'totalClasses', 'presentCount', 'attendancePct',
            'availableFeedback', 'completedFeedback'
        ));
    }

    public function attendance()
    {
        $student = auth()->user();
        $records = Attendance::where('student_id', $student->id)
            ->with(['session.section.course'])
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('student.attendance', compact('records'));
    }
}
@extends('layouts.dashboard')
@section('title', 'Staff Dashboard')
@php $header = 'Staff Dashboard'; $subheader = 'Manage class sessions and attendance for your assigned sections.'; @endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>
<a href="{{ route('staff.dashboard') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Attendance</div>
<a href="{{ route('staff.attendance.sessions') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
    My Sessions
</a>
<a href="{{ route('staff.attendance.create') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    New Session
</a>
@endsection

@section('content')
{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;">
    @php
    $statItems = [
        ['label'=>'Assigned Sections', 'value'=>$stats['sections'],        'color'=>'background:linear-gradient(135deg,#DBEAFE,#BFDBFE);color:#1D4ED8',  'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        ['label'=>'Total Sessions',    'value'=>$stats['total_sessions'],  'color'=>'background:linear-gradient(135deg,#EDE9FE,#DDD6FE);color:#6D28D9',  'icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['label'=>"Today's Sessions",  'value'=>$stats['today_sessions'],  'color'=>'background:linear-gradient(135deg,#FEF9C3,#FDE68A);color:#92400E',  'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['label'=>'Active Feedback',   'value'=>$stats['active_feedback'], 'color'=>'background:linear-gradient(135deg,#DCFCE7,#BBF7D0);color:#15803D',  'icon'=>'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
    ];
    @endphp
    @foreach($statItems as $s)
    <div class="stat-card">
        <div class="stat-icon" style="{{ $s['color'] }}">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
        </div>
        <div class="stat-label">{{ $s['label'] }}</div>
        <div class="stat-value">{{ $s['value'] }}</div>
    </div>
    @endforeach
</div>

{{-- Recent Sessions --}}
<div class="card">
    <div class="card-header">
        <h3>Recent Sessions</h3>
        <a href="{{ route('staff.attendance.create') }}" class="btn-primary btn-sm">
            <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Session
        </a>
    </div>
    <div style="overflow-x:auto;">
        <table class="data-table">
            <thead>
                <tr><th>Course</th><th>Date</th><th>Present</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($recentSessions as $session)
                <tr>
                    <td>
                        <div style="font-weight:600;color:#0F172A;">{{ $session->section->course->name ?? 'N/A' }}</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">{{ $session->topic ?? 'No topic' }}</div>
                    </td>
                    <td>{{ $session->session_date->format('M d, Y') }}</td>
                    <td>
                        <span class="badge badge-blue">
                            {{ $session->attendanceRecords->whereIn('status',['present','late'])->count() }} / {{ $session->attendanceRecords->count() }}
                        </span>
                    </td>
                    <td><span class="badge {{ ['completed'=>'badge-green','ongoing'=>'badge-blue','scheduled'=>'badge-yellow','cancelled'=>'badge-red'][$session->status] ?? 'badge-gray' }}">{{ ucfirst($session->status) }}</span></td>
                    <td>
                        @if(in_array($session->status, ['ongoing','scheduled']))
                        <a href="{{ route('staff.attendance.take', $session) }}" class="btn-primary btn-sm">Take Attendance</a>
                        @else
                        <a href="{{ route('staff.attendance.take', $session) }}" class="btn-secondary btn-sm">View</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;padding:30px;color:#94A3B8;">No sessions yet. <a href="{{ route('staff.attendance.create') }}" style="color:#3B82F6;">Create one →</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($currentSemester)
<div style="margin-top:16px;padding:14px 18px;background:#EFF6FF;border:1px solid #BFDBFE;border-radius:12px;font-size:0.83rem;color:#1E40AF;">
    📅 <strong>Current Semester:</strong> {{ $currentSemester->name }} ({{ $currentSemester->start_date->format('M d') }} – {{ $currentSemester->end_date->format('M d, Y') }})
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const driver = window.driver.js.driver;
    const tour = driver({
        showProgress: true,
        animate: true,
        steps: [
            { element: '.sidebar-brand', popover: { title: 'Welcome to SmartPulse!', description: 'This is your staff dashboard.', side: 'right', align: 'start' } },
            { element: 'a[href="{{ route("staff.attendance.create") }}"]', popover: { title: 'New Class Session', description: 'Help faculty by starting class sessions and marking attendance.', side: 'right', align: 'start' } },
            { element: 'a[href="{{ route("staff.attendance.sessions") }}"]', popover: { title: 'Manage Sessions', description: 'View and edit previous class sessions you managed.', side: 'right', align: 'start' } },
            { element: '.card', popover: { title: 'Recent Activity', description: 'See the latest sessions conducted.', side: 'top', align: 'start' } },
        ]
    });

    const startBtn = document.getElementById('start-tour-btn');
    if (startBtn) {
        startBtn.addEventListener('click', () => tour.drive());
    }

    if (!localStorage.getItem('tourCompleted_staff')) {
        setTimeout(() => tour.drive(), 500);
        localStorage.setItem('tourCompleted_staff', 'true');
    }
});
</script>
@endpush
@endsection
@extends('layouts.dashboard')
@section('title', 'Course Sections')
@php $header = 'Sections: ' . $course->name; $subheader = 'Manage sections for ' . $course->code; @endphp

@section('sidebar-nav')
<div class="nav-section-label">Main</div>
<a href="{{ route('admin.dashboard') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
    Dashboard
</a>
<div class="nav-section-label">Academic</div>
<a href="{{ route('admin.departments.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
    Departments
</a>
<a href="{{ route('admin.courses.index') }}" class="nav-link active">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
    Courses
</a>
<a href="{{ route('admin.semesters.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    Semesters
</a>
<a href="{{ route('admin.enrollments.index') }}" class="nav-link">
    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
    Enrollments
</a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px;">
    <div class="card" style="align-self:start;">
        <div class="card-header"><h3>Add New Section</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.courses.sections.store', $course) }}">
                @csrf
                <div style="margin-bottom:16px;">
                    <label class="form-label">Section Name <span style="color:#DC2626;">*</span></label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. A, B, C" required>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label">Semester <span style="color:#DC2626;">*</span></label>
                    <select name="semester_id" class="form-select" required>
                        @foreach($semesters as $sem)
                        <option value="{{ $sem->id }}">{{ $sem->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:20px;">
                    <label class="form-label">Max Students <span style="color:#DC2626;">*</span></label>
                    <input type="number" name="max_students" class="form-input" value="60" min="1" max="200" required>
                </div>
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">Create Section</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Current Sections</h3></div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Section</th>
                        <th>Semester</th>
                        <th>Enrolled</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sections as $sec)
                    <tr>
                        <td style="font-weight:600;color:#0F172A;">Section {{ $sec->section_name ?? $sec->name }}</td>
                        <td>{{ $sec->semester->name ?? 'N/A' }}</td>
                        <td>{{ $sec->enrollments->count() }} / {{ $sec->max_students }}</td>
                        <td>
                            <span class="badge {{ $sec->is_active ? 'badge-green' : 'badge-gray' }}">
                                {{ $sec->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;padding:40px;color:#94A3B8;">No sections found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
