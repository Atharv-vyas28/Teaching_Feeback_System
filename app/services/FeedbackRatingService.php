<?php

namespace App\Services;

use App\Models\FeedbackAnswer;
use App\Models\FeedbackEligibility;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackSession;
use App\Models\FacultyCourse;
use App\Models\RatingResult;

class FeedbackRatingService
{
    public function calculateForSession(
        FeedbackSession $feedbackSession
    ): ?RatingResult {
        $feedbackSession->load([
            'classSession.section',
        ]);

        $classSession = $feedbackSession->classSession;

        if (! $classSession) {
            return null;
        }

        $section = $classSession->section;

        if (! $section) {
            return null;
        }

        $facultyCourse = FacultyCourse::where(
            'class_section_id',
            $section->id
        )
            ->where('is_active', true)
            ->first();

        if (! $facultyCourse) {
            return null;
        }

        $ratingQuestions = FeedbackQuestion::where(
            'is_active',
            true
        )
            ->where('type', 'rating')
            ->orderBy('display_order')
            ->get();

        if ($ratingQuestions->isEmpty()) {
            return null;
        }

        $eligibilityRecords = FeedbackEligibility::where(
            'feedback_session_id',
            $feedbackSession->id
        )
            ->where('has_submitted', true)
            ->where('included_in_score', true)
            ->whereNotNull('attendance_weight')
            ->whereNotNull('anonymous_token')
            ->get();

        if ($eligibilityRecords->isEmpty()) {
            return null;
        }

        $anonymousTokens = $eligibilityRecords
            ->pluck('anonymous_token');

        $questionAverages = [];

        $totalQuestionWeight = 0;
        $overallWeightedSum = 0;

        foreach ($ratingQuestions as $question) {

            $answers = FeedbackAnswer::query()
                ->join(
                    'feedback_responses',
                    'feedback_answers.feedback_response_id',
                    '=',
                    'feedback_responses.id'
                )
                ->join(
                    'feedback_eligibility',
                    'feedback_responses.anonymous_token',
                    '=',
                    'feedback_eligibility.anonymous_token'
                )
                ->where(
                    'feedback_responses.feedback_session_id',
                    $feedbackSession->id
                )
                ->where(
                    'feedback_eligibility.feedback_session_id',
                    $feedbackSession->id
                )
                ->where(
                    'feedback_answers.feedback_question_id',
                    $question->id
                )
                ->whereNotNull(
                    'feedback_answers.rating_value'
                )
                ->where(
                    'feedback_eligibility.has_submitted',
                    true
                )
                ->where(
                    'feedback_eligibility.included_in_score',
                    true
                )
                ->whereNotNull(
                    'feedback_eligibility.attendance_weight'
                )
                ->whereIn(
                    'feedback_responses.anonymous_token',
                    $anonymousTokens
                )
                ->select(
                    'feedback_answers.rating_value',
                    'feedback_eligibility.attendance_weight'
                )
                ->get();

            $totalAttendanceWeight =
                $answers->sum('attendance_weight');

            $weightedAverage = 0;

            if ($totalAttendanceWeight > 0) {

                $weightedSum = $answers->sum(
                    function ($answer) {
                        return
                            $answer->rating_value *
                            $answer->attendance_weight;
                    }
                );

                $weightedAverage =
                    $weightedSum / $totalAttendanceWeight;
            }

            $questionAverages[$question->id] = [

                'question' =>
                    $question->question_text,

                'average' =>
                    round($weightedAverage, 2),

                'weight' =>
                    $question->weight,

                'contribution' =>
                    round(
                        $weightedAverage *
                        ($question->weight / 100),
                        4
                    ),
            ];

            $totalQuestionWeight +=
                $question->weight;

            $overallWeightedSum +=
                $weightedAverage *
                ($question->weight / 100);
        }

        $overallWeighted =
            $totalQuestionWeight > 0
                ? round(
                    $overallWeightedSum *
                    (100 / $totalQuestionWeight),
                    2
                )
                : 0;

        $responseCount =
            $eligibilityRecords->count();

        return RatingResult::updateOrCreate(
            [
                'faculty_id' =>
                    $facultyCourse->user_id,

                'class_section_id' =>
                    $section->id,

                'semester_id' =>
                    $section->semester_id,
            ],

            [
                'overall_weighted_rating' =>
                    $overallWeighted,

                'response_count' =>
                    $responseCount,

                'question_averages' =>
                    $questionAverages,

                'calculated_at' =>
                    now(),
            ]
        );
    }

    public function calculateForFaculty(
        int $facultyId,
        int $semesterId
    ): array {
        $results = RatingResult::where(
            'faculty_id',
            $facultyId
        )
            ->where(
                'semester_id',
                $semesterId
            )
            ->with([
                'section.course',
            ])
            ->get();

        if ($results->isEmpty()) {
            return [
                'overall' => 0,
                'courses' => [],
                'response_count' => 0,
            ];
        }

        return [

            'overall' =>
                round(
                    $results->avg(
                        'overall_weighted_rating'
                    ),
                    2
                ),

            'courses' =>
                $results->toArray(),

            'response_count' =>
                $results->sum(
                    'response_count'
                ),
        ];
    }
}
