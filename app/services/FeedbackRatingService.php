<?php

namespace App\Services;

use App\Models\FeedbackSession;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackAnswer;
use App\Models\FeedbackEligibility;
use App\Models\RatingResult;
use App\Models\ClassSection;
use App\Models\FacultyCourse;
use Illuminate\Support\Facades\DB;

class FeedbackRatingService
{
    /**
     * Calculate weighted faculty ratings.
     *
     * Example:
     *   Q1 avg 4.5 weight 30%  → contribution: 4.5 × 0.30 = 1.35
     *   Q2 avg 4.2 weight 20%  → contribution: 4.2 × 0.20 = 0.84
     *   Q3 avg 4.7 weight 50%  → contribution: 4.7 × 0.50 = 2.35
     *   Overall weighted = 1.35 + 0.84 + 2.35 = 4.54
     */
    public function calculateForSession(FeedbackSession $feedbackSession): ?RatingResult
    {
        $classSession = $feedbackSession->classSession;
        $section      = $classSession->section;

        // Find faculty assigned to this section
        $facultyCourse = FacultyCourse::where('class_section_id', $section->id)
            ->where('is_active', true)
            ->first();

        if (!$facultyCourse) return null;

        $ratingQuestions = FeedbackQuestion::where('is_active', true)
            ->where('type', 'rating')
            ->orderBy('display_order')
            ->get();

        $responseIds = $feedbackSession->responses()->pluck('id');
        $responseCount = $responseIds->count();

        if ($responseCount === 0) return null;

        $questionAverages = [];
        $totalWeight = 0;
        $weightedSum = 0;

        foreach ($ratingQuestions as $question) {
            $avg = FeedbackAnswer::whereIn('feedback_response_id', $responseIds)
                ->where('feedback_question_id', $question->id)
                ->whereNotNull('rating_value')
                ->avg('rating_value') ?? 0;

            $questionAverages[$question->id] = [
                'question'     => $question->question_text,
                'average'      => round($avg, 2),
                'weight'       => $question->weight,
                'contribution' => round($avg * ($question->weight / 100), 4),
            ];

            $totalWeight += $question->weight;
            $weightedSum += $avg * ($question->weight / 100);
        }

        // Normalize if weights don't sum to 100
        $overallWeighted = $totalWeight > 0 ? round($weightedSum * (100 / $totalWeight), 2) : 0;

        $eligibleCount = FeedbackEligibility::where('feedback_session_id', $feedbackSession->id)->count();
        $responseRate  = $eligibleCount > 0 ? round(($responseCount / $eligibleCount) * 100, 2) : 0;

        return RatingResult::updateOrCreate(
            [
                'faculty_id'       => $facultyCourse->user_id,
                'class_section_id' => $section->id,
                'semester_id'      => $section->semester_id,
            ],
            [
                'overall_weighted_rating' => $overallWeighted,
                'course_rating'           => $overallWeighted, // same scale for course
                'response_count'          => $responseCount,
                'eligible_count'          => $eligibleCount,
                'response_rate'           => $responseRate,
                'question_averages'       => $questionAverages,
                'calculated_at'           => now(),
            ]
        );
    }

    public function calculateForFaculty(int $facultyId, int $semesterId): array
    {
        $results = RatingResult::where('faculty_id', $facultyId)
            ->where('semester_id', $semesterId)
            ->with(['section.course'])
            ->get();

        if ($results->isEmpty()) {
            return ['overall' => 0, 'courses' => [], 'response_count' => 0];
        }

        $overallAvg = $results->avg('overall_weighted_rating');

        return [
            'overall'        => round($overallAvg, 2),
            'courses'        => $results->toArray(),
            'response_count' => $results->sum('response_count'),
            'response_rate'  => round($results->avg('response_rate'), 2),
        ];
    }
}