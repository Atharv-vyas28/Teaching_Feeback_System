<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackResponse;
use App\Models\FeedbackRelease;
use App\Models\User;
use App\Notifications\FeedbackReleasedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class FeedbackResponseController extends Controller
{
    /**
     * List pending (un‑approved) feedback responses for a given session.
     */
    public function index($sessionId)
    {
        $responses = FeedbackResponse::where('feedback_session_id', $sessionId)
            ->where('approved', false)
            ->with(['answers.question'])
            ->orderByDesc('submitted_at')
            ->paginate(20);

        return view('admin.feedback-responses.index', compact('responses', 'sessionId'));
    }

    /**
     * Batch approval of selected feedback responses.
     */
    public function approve(Request $request, $sessionId)
    {
        $request->validate([
            'response_ids' => 'required|array',
            'response_ids.*' => 'integer|exists:feedback_responses,id',
        ]);

        FeedbackResponse::whereIn('id', $request->input('response_ids'))
            ->update(['approved' => true, 'approved_at' => now()]);

        return back()->with('success', count($request->input('response_ids')) . ' responses approved.');
    }

    /**
     * Release approved responses to the faculty of the session.
     */
    public function releaseApproved(Request $request, $sessionId)
    {
        $session = \App\Models\FeedbackSession::with('classSession.faculty')->findOrFail($sessionId);
        $faculty = $session->classSession->faculty ?? null;
        if (! $faculty) {
            return back()->with('error', 'No faculty assigned to this session.');
        }

        $responses = FeedbackResponse::where('feedback_session_id', $sessionId)
            ->where('approved', true)
            ->whereNull('released_at')
            ->get();

        DB::transaction(function () use ($responses, $faculty) {
            foreach ($responses as $resp) {
                FeedbackRelease::create([
                    'feedback_response_id' => $resp->id,
                    'faculty_id'           => $faculty->id,
                    'released_at'          => now(),
                    'status'               => 'released',
                ]);
                $resp->update(['released_at' => now()]);
            }
        });

        // Notifications (in‑app + email)
        $faculty->notify(new FeedbackReleasedNotification($responses->count(), $session));
        Mail::raw(
            "{$responses->count()} new feedback response(s) have been released for your session.",
            function ($msg) use ($faculty) {
                $msg->to($faculty->email)->subject('New Feedback Released');
            }
        );

        return back()->with('success', $responses->count() . ' responses released to faculty.');
    }
}
?>
