<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use App\Models\ClassSection;
use App\Models\Attendance;
use App\Models\User;
use App\Models\StaffCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    protected function authorizeSection(ClassSection $section): void
    {
        $assigned = StaffCourse::where('user_id', auth()->id())
            ->where('class_section_id', $section->id)
            ->exists();
        abort_unless($assigned, 403);
    }

    public function sessions()
    {
        $user = auth()->user();
        $sectionIds = StaffCourse::where('user_id', $user->id)->where('is_active', true)
            ->pluck('class_section_id');

        $sessions = ClassSession::with(['section.course', 'conductor', 'attendanceRecords'])
            ->whereIn('class_section_id', $sectionIds)
            ->orderByDesc('session_date')
            ->paginate(20);

        return view('staff.attendance.sessions', compact('sessions'));
    }

    public function create()
    {
        $user = auth()->user();
        $sections = StaffCourse::where('user_id', $user->id)->where('is_active', true)
            ->with(['section.course', 'section.semester'])
            ->get()
            ->pluck('section');

        return view('staff.attendance.create-session', compact('sections'));
    }

    public function storeSession(Request $request)
    {
        $request->validate([
            'class_section_id' => 'required|exists:class_sections,id',
            'session_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'topic' => 'nullable|string|max:255',
        ]);
        $section = ClassSection::findOrFail($request->class_section_id);
        $this->authorizeSection($section);

        $session = ClassSession::create([
            'class_section_id' => $request->class_section_id,
            'conducted_by' => auth()->id(),
            'session_date' => $request->session_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'topic' => $request->topic,
            'status' => 'ongoing',
        ]);

        return redirect()->route('staff.attendance.take', $session)->with('success', 'Session created.');
    }

    public function take(ClassSession $classSession)
    {
        $this->authorizeSection($classSession->section);

        $students = User::join('course_enrollments', 'users.id', '=', 'course_enrollments.user_id')
            ->where('course_enrollments.class_section_id', $classSession->class_section_id)
            ->where('course_enrollments.status', 'active')
            ->where('users.role', 'student')
            ->select('users.*')
            ->orderBy('users.roll_number')
            ->get();

        $existingAttendance = Attendance::where('class_session_id', $classSession->id)
            ->get()->keyBy('student_id');

        return view('staff.attendance.take', compact('classSession', 'students', 'existingAttendance'));
    }

    public function save(Request $request, ClassSession $classSession)
    {
        $classSession->load('section');

        $this->authorizeSection($classSession->section);

        $validated = $request->validate([
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.remarks' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated, $classSession) {
            foreach ($validated['attendance'] as $studentId => $data) {
                $isEnrolled = DB::table('course_enrollments')
                    ->where('user_id', $studentId)
                    ->where(
                        'class_section_id',
                        $classSession->class_section_id
                    )
                    ->where('status', 'active')
                    ->exists();

                if (!$isEnrolled) {
                    continue;
                }

                Attendance::updateOrCreate(
                    [
                        'class_session_id' => $classSession->id,
                        'student_id' => $studentId,
                    ],
                    [
                        'marked_by' => auth()->id(),
                        'status' => $data['status'],
                        'source' => 'feedback_day',
                        'feedback_enabled' => true,
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
            ->route('staff.attendance.sessions')
            ->with(
                'success',
                'Feedback-day attendance saved successfully.'
            );
    }
    /**
     * Per-student attendance history/summary for staff-assigned sections.
     */
    public function history(Request $request)
    {
        $user = auth()->user();

        $allSectionIds = StaffCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->pluck('class_section_id');

        $filteredSectionId = $request->filled('section_id')
            ? (int) $request->section_id
            : null;

        if ($filteredSectionId && !$allSectionIds->contains($filteredSectionId)) {
            abort(403, 'You are not assigned to that section.');
        }

        $activeSectionIds = $filteredSectionId
            ? collect([$filteredSectionId])
            : $allSectionIds;

        $sessionQuery = ClassSession::whereIn('class_section_id', $activeSectionIds);
        if ($request->filled('date_from')) {
            $sessionQuery->whereDate('session_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $sessionQuery->whereDate('session_date', '<=', $request->date_to);
        }
        $sessionIds = $sessionQuery->pluck('id');

        $summary = DB::table('attendance')
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
            ->where('attendance.source', 'feedback_day')
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
                COUNT(attendance.id) as total_classes,
                SUM(CASE WHEN attendance.status IN ('present','late') THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendance.status = 'absent' THEN 1 ELSE 0 END) as absent_count
            ")
            ->groupBy(
                'attendance.student_id',
                'users.name',
                'users.roll_number',
                'courses.name',
                'courses.code'
            )
            ->orderBy('courses.name')
            ->orderBy('users.name')
            ->paginate(30)
            ->withQueryString();

        $sections = StaffCourse::where('user_id', $user->id)
            ->where('is_active', true)
            ->with(['section.course'])
            ->get()
            ->pluck('section');

        return view('staff.attendance.history', compact('summary', 'sections', 'filteredSectionId'));
    }
}
