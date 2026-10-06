<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\FeedbackSession;
use Illuminate\Http\Request;
use App\Services\StaffAssignmentAccessService;

class FeedbackSessionController extends Controller
{
    public function __construct(private StaffAssignmentAccessService $access)
    {
    }

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

        $data = $request->validate([
            'release_at' => ['required', 'date', 'after_or_equal:now'],
            'deadline_at' => ['required', 'date', 'after:release_at'],
        ]);

        $feedbackSession->update([
            'status' => 'active',
            'release_at' => $data['release_at'],
            'deadline_at' => $data['deadline_at'],
            'opened_at' => now(),
            'closed_at' => null,
        ]);

        return back()->with('success', 'Feedback released for the selected time window.');
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
