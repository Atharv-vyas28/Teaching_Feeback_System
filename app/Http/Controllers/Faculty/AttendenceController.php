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

class AttendanceController extends Controller
{
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

                Attendance::updateOrCreate(
                    [
                        'class_session_id' => $classSession->id,
                        'student_id'       => $studentId,
                    ],
                    [
                        'marked_by'        => auth()->id(),
                        'status'           => $status,
                        'feedback_enabled' => $isPresent ? ($data['feedback_enabled'] ?? false) : false,
                        'marked_at'        => now(),
                        'remarks'          => $data['remarks'] ?? null,
                    ]
                );
            }

            $classSession->update(['status' => 'completed']);
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
