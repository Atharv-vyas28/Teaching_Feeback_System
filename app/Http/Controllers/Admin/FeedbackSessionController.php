<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedbackSession;
use App\Services\FeedbackRatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeedbackSessionController extends Controller
{
    public function __construct(
        private FeedbackRatingService $ratingService
    ) {
    }

    public function index()
    {
        $sessions = FeedbackSession::with([
            'classSession.section.course',
            'classSession.section.faculty',
        ])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.feedback-sessions.index', compact('sessions'));
    }

   public function responses(FeedbackSession $session)
{
    $session->load([
        'classSession.section.course',
        'classSession.section.faculty',
    ]);

    $responses = $session->responses()
        ->with('answers.question')
        ->orderBy('submitted_at')
        ->get();

    /*
     * Timeline: responses grouped by hour.
     * No student identity is included.
     */
    $responseTimeline = $responses
        ->filter(fn ($response) => $response->submitted_at !== null)
        ->groupBy(fn ($response) =>
            $response->submitted_at->format('Y-m-d H:00')
        )
        ->map(function ($items, $period) {
            return [
                'period' => $period,
                'label' => \Carbon\Carbon::parse($period)
                    ->format('M d, H:00'),
                'count' => $items->count(),
            ];
        })
        ->values();

    /*
     * Question-wise rating analysis.
     */
    $questionAnalysis = [];

    foreach ($responses as $response) {
        foreach ($response->answers as $answer) {
            if (
                $answer->question?->type !== 'rating' ||
                $answer->rating_value === null
            ) {
                continue;
            }

            $questionId = $answer->feedback_question_id;

            if (! isset($questionAnalysis[$questionId])) {
                $questionAnalysis[$questionId] = [
                    'question' => $answer->question->question_text,
                    'ratings' => [],
                ];
            }

            $questionAnalysis[$questionId]['ratings'][] =
                (float) $answer->rating_value;
        }
    }

    $questionAnalysis = collect($questionAnalysis)
        ->map(function ($item) {
            $average = count($item['ratings']) > 0
                ? round(
                    array_sum($item['ratings']) / count($item['ratings']),
                    2
                )
                : 0;

            return [
                'question' => $item['question'],
                'average' => $average,
                'response_count' => count($item['ratings']),
            ];
        })
        ->values();

    /*
     * Distribution of ratings from 1 to 5.
     */
    $ratingDistribution = [
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0,
        5 => 0,
    ];

    foreach ($responses as $response) {
        foreach ($response->answers as $answer) {
            if (
                $answer->question?->type === 'rating' &&
                $answer->rating_value !== null
            ) {
                $rating = (int) round($answer->rating_value);

                if (isset($ratingDistribution[$rating])) {
                    $ratingDistribution[$rating]++;
                }
            }
        }
    }

    return view(
        'admin.feedback-sessions.responses',
        compact(
            'session',
            'responses',
            'responseTimeline',
            'questionAnalysis',
            'ratingDistribution'
        )
    );
}

    public function release(Request $request, FeedbackSession $session)
{
    if ($session->isReleased()) {
        return back()->with(
            'success',
            'This feedback was already released to faculty.'
        );
    }

    $responseCount = $session->responses()->count();

    if ($responseCount === 0) {
        return back()->with(
            'error',
            'No student feedback has been submitted for this session yet.'
        );
    }

   DB::transaction(function () use ($session) {
    $session->update([
        'status' => 'closed',
        'closed_at' => now(),
    ]);

    // If this fails, the transaction rolls back and feedback remains unreleased.
    $this->ratingService->calculateForSession($session->fresh());

    // Mark as released only after ratings are successfully saved.
    $session->update([
        'is_released' => true,
    ]);
});

    return back()->with(
        'success',
        "{$responseCount} anonymous response(s) released to the assigned faculty."
    );
}
}