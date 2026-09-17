<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSection;
use App\Models\FeedbackSession;
use App\Models\StaffCourse;
use App\Models\User;
use App\Notifications\NewStaffFeedbackAssignmentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $assignments = StaffCourse::with(['staff.department', 'section.course.department', 'section.semester', 'feedbackSession', 'assignedBy'])
            ->when($request->filled('staff_id'), fn ($q) => $q->where('user_id', $request->integer('staff_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('course_id'), fn ($q) => $q->whereHas('section', fn ($s) => $s->where('course_id', $request->integer('course_id'))))
            ->latest('assigned_at')->paginate(20)->withQueryString();

        $staffMembers = User::where('role', 'staff')->where('is_active', true)->orderBy('name')->get();
        $sections = ClassSection::with(['course.department', 'semester'])->where('is_active', true)->orderBy('id')->get();
        $feedbackSessions = FeedbackSession::with('classSession.section.course')->orderByDesc('created_at')->get();

        return view('admin.staff-assignments.index', compact('assignments', 'staffMembers', 'sections', 'feedbackSessions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'staff_id' => ['required', 'exists:users,id'],
            'class_section_id' => ['required', 'exists:class_sections,id'],
            'feedback_session_id' => ['nullable', 'exists:feedback_sessions,id'],
            'release_at' => ['required_with:feedback_session_id', 'nullable', 'date'],
            'deadline_at' => ['required_with:feedback_session_id', 'nullable', 'date', 'after:release_at'],
        ]);

        $staff = User::findOrFail($data['staff_id']);
        abort_unless($staff->isStaff() && $staff->is_active, 422, 'Select an active Staff account.');
        $section = ClassSection::findOrFail($data['class_section_id']);

        if (! empty($data['feedback_session_id'])) {
            $feedback = FeedbackSession::findOrFail($data['feedback_session_id']);
            abort_unless($feedback->classSession->class_section_id === $section->id, 422, 'Feedback session does not belong to the selected section.');
        }

        DB::transaction(function () use ($data, $staff, $section) {
            // An active feedback-session assignment can belong to one Staff member only.
            if (! empty($data['feedback_session_id'])) {
                StaffCourse::active()->where('feedback_session_id', $data['feedback_session_id'])
                    ->update(['is_active' => false, 'status' => 'inactive', 'deactivated_at' => now()]);
            }

            $assignment = StaffCourse::create([
                'user_id' => $staff->id,
                'class_section_id' => $section->id,
                'semester_id' => $section->semester_id,
                'feedback_session_id' => $data['feedback_session_id'] ?? null,
                'assigned_by' => auth()->id(),
                'is_active' => true,
                'status' => 'active',
                'assigned_at' => now(),
            ]);

            if ($assignment->feedback_session_id) {
                $feedbackSession = $assignment->feedbackSession;
                $releaseAt = $data['release_at'] ?? null;
                $deadlineAt = $data['deadline_at'] ?? null;

                $feedbackSession->update([
                    'assigned_staff_id' => $staff->id,
                    'release_at' => $releaseAt,
                    'deadline_at' => $deadlineAt,
                    'status' => $releaseAt && now()->greaterThanOrEqualTo($releaseAt) ? 'active' : 'draft',
                    'opened_at' => $releaseAt && now()->greaterThanOrEqualTo($releaseAt) ? now() : null,
                    'closed_at' => null,
                ]);
                $staff->notify(new NewStaffFeedbackAssignmentNotification($assignment));
            }
        });

        return back()->with('success', 'Staff assignment saved successfully.');
    }

    public function deactivate(StaffCourse $assignment)
    {
        $assignment->update(['is_active' => false, 'status' => 'inactive', 'deactivated_at' => now()]);
        return back()->with('success', 'Staff assignment deactivated. Access is removed immediately.');
    }
}
