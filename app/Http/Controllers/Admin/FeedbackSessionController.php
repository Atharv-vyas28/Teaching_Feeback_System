<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackSession;
use Illuminate\Http\Request;

class FeedbackSessionController extends Controller
{
    public function index()
    {
        $sessions = FeedbackSession::with(['classSession.section.course', 'classSession.faculty'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.feedback-sessions.index', compact('sessions'));
    }

    public function responses(FeedbackSession $session)
    {
        $session->load([
            'classSession.section.course', 
            'classSession.faculty',
            'responses.answers.question'
        ]);

        return view('admin.feedback-sessions.responses', compact('session'));
    }

    public function release(Request $request, FeedbackSession $session)
    {
        $session->update(['is_released' => true]);
        return back()->with('success', 'Feedback has been released to the faculty successfully.');
    }
}
