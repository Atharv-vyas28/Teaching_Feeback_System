<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\FeedbackSession;
use Illuminate\Http\Request;
use App\Services\StaffAssignmentAccessService;

class FeedbackSessionController extends Controller
{
    public function __construct(private StaffAssignmentAccessService $access) {}

    public function index()
    {
        $feedbackSessions = $this->access->feedbackSessionsFor(auth()->user())->with([
            'classSession.section.course.department',
            'classSession.section.semester',
        ])
            ->where('assigned_staff_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('staff.feedback.index', compact('feedbackSessions'));
    }

    private function authorizeAssignment(FeedbackSession $feedbackSession): void
    {
        abort_unless(
            $this->access->canManageFeedback(auth()->user(), $feedbackSession),
            403,
            'You are not assigned to this feedback session.'
        );
    }

    public function release(Request $request, FeedbackSession $feedbackSession)
    {
        $this->authorizeAssignment($feedbackSession);

        abort_unless($feedbackSession->release_at && $feedbackSession->deadline_at, 422, 'The administrator must set the feedback release time and deadline first.');
        abort_if(now()->lessThan($feedbackSession->release_at), 422, 'Feedback cannot be started before the administrator-set release time.');
        abort_if(now()->greaterThan($feedbackSession->deadline_at), 422, 'Feedback deadline has already expired.');
        $feedbackSession->update([
            'status' => 'active',
            'opened_at' => now(),
            'closed_at' => null,
        ]);

        return back()->with('success', 'Feedback started. Enrolled students can submit until the administrator-set deadline. Feedback-day attendance may be recorded before or after submission and is verified by Admin during rating processing.');
    }

    public function close(FeedbackSession $feedbackSession)
    {
        $this->authorizeAssignment($feedbackSession);

        $feedbackSession->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return back()->with('success', 'Feedback session closed.');
    }
}
