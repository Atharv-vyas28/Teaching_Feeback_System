<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\FacultyCourse;
use App\Models\FeedbackEligibility;
use App\Models\FeedbackQuestion;
use App\Models\FeedbackSession;
use App\Models\RatingResult;
use Illuminate\Support\Facades\DB;

class FeedbackRatingService
{
    /** Process private eligibility and persist one anonymous weighted result. */
    public function calculateForSession(FeedbackSession $feedbackSession): ?RatingResult
    {
        $feedbackSession->loadMissing('classSession.section');
        $classSession = $feedbackSession->classSession;
        $section = $classSession?->section;

        if (! $classSession || ! $section) return null;

        $facultyCourse = FacultyCourse::where('class_section_id', $section->id)
            ->where('is_active', true)->first();
        if (! $facultyCourse) return null;

        $this->processEligibility($feedbackSession, $section->id);

        $questions = FeedbackQuestion::active()->where('type', 'rating')->ordered()->get();
        $eligibleResponseCount = $this->eligibleResponseCount($feedbackSession->id);
        $totalQuestionWeight = (float) $questions->sum('weight');
        $overall = 0.0;
        $questionAverages = [];

        foreach ($questions as $question) {
            $average = $this->weightedQuestionAverage($feedbackSession->id, $question->id);
            $normalisedWeight = $totalQuestionWeight > 0
                ? (float) $question->weight / $totalQuestionWeight : 0.0;
            $contribution = $average === null ? 0.0 : $average * $normalisedWeight;

            $questionAverages[$question->id] = [
                'question' => $question->question_text,
                'average' => $average === null ? null : round($average, 2),
                'weight' => (float) $question->weight,
                'normalised_weight_percent' => round($normalisedWeight * 100, 2),
                'contribution' => round($contribution, 4),
            ];
            $overall += $contribution;
        }

        return RatingResult::updateOrCreate(
            ['feedback_session_id' => $feedbackSession->id],
            [
                'faculty_id' => $facultyCourse->user_id,
                'class_section_id' => $section->id,
                'semester_id' => $section->semester_id,
                'overall_weighted_rating' => $eligibleResponseCount > 0 ? round($overall, 2) : null,
                'response_count' => $eligibleResponseCount,
                'question_averages' => $questionAverages,
                'calculated_at' => now(),
            ]
        );
    }

    /** Feedback-day attendance permits a response; regular attendance is its weight. */
    private function processEligibility(FeedbackSession $feedbackSession, int $sectionId): void
    {
        $regularSessionIds = ClassSession::where('class_section_id', $sectionId)
            ->where('status', 'completed')
            ->whereHas('attendanceRecords', fn ($query) => $query->where('source', 'regular'))
            ->pluck('id');
        $regularClassCount = $regularSessionIds->count();

        FeedbackEligibility::where('feedback_session_id', $feedbackSession->id)
            ->where('has_submitted', true)->whereNotNull('anonymous_token')
            ->orderBy('id')->each(function (FeedbackEligibility $eligibility) use ($feedbackSession, $regularSessionIds, $regularClassCount) {
                $wasPresentOnFeedbackDay = Attendance::where('class_session_id', $feedbackSession->class_session_id)
                    ->where('student_id', $eligibility->student_id)
                    ->where('source', 'feedback_day')
                    ->whereIn('status', ['present', 'late'])->exists();

                if (! $wasPresentOnFeedbackDay || $regularClassCount === 0) {
                    $eligibility->update(['included_in_score' => false, 'attendance_weight' => null]);
                    return;
                }

                $attendedClassCount = Attendance::whereIn('class_session_id', $regularSessionIds)
                    ->where('student_id', $eligibility->student_id)->where('source', 'regular')
                    ->whereIn('status', ['present', 'late'])
                    ->distinct('class_session_id')->count('class_session_id');

                $eligibility->update([
                    'included_in_score' => true,
                    'attendance_weight' => round($attendedClassCount / $regularClassCount, 6),
                ]);
            });
    }

    private function eligibleResponseCount(int $feedbackSessionId): int
    {
        return DB::table('feedback_responses as response')
            ->join('feedback_eligibility as eligibility', function ($join) {
                $join->on('eligibility.anonymous_token', '=', 'response.anonymous_token')
                    ->on('eligibility.feedback_session_id', '=', 'response.feedback_session_id');
            })
            ->where('response.feedback_session_id', $feedbackSessionId)
            ->where('eligibility.has_submitted', true)->where('eligibility.included_in_score', true)
            ->whereNotNull('eligibility.attendance_weight')->count();
    }

    private function weightedQuestionAverage(int $feedbackSessionId, int $questionId): ?float
    {
        $result = DB::table('feedback_answers as answer')
            ->join('feedback_responses as response', 'response.id', '=', 'answer.feedback_response_id')
            ->join('feedback_eligibility as eligibility', function ($join) {
                $join->on('eligibility.anonymous_token', '=', 'response.anonymous_token')
                    ->on('eligibility.feedback_session_id', '=', 'response.feedback_session_id');
            })
            ->where('response.feedback_session_id', $feedbackSessionId)
            ->where('answer.feedback_question_id', $questionId)->whereNotNull('answer.rating_value')
            ->where('eligibility.has_submitted', true)->where('eligibility.included_in_score', true)
            ->whereNotNull('eligibility.attendance_weight')
            ->selectRaw('SUM(answer.rating_value * eligibility.attendance_weight) AS weighted_total')
            ->selectRaw('SUM(eligibility.attendance_weight) AS weight_total')->first();

        return ! $result || (float) $result->weight_total <= 0
            ? null : (float) $result->weighted_total / (float) $result->weight_total;
    }

    public function calculateForFaculty(int $facultyId, int $semesterId): array
    {
        $results = RatingResult::where('faculty_id', $facultyId)->where('semester_id', $semesterId)
            ->with(['section.course'])->get();
        if ($results->isEmpty()) return ['overall' => 0, 'courses' => [], 'response_count' => 0, 'response_rate' => 0];

        return [
            'overall' => round($results->avg('overall_weighted_rating'), 2),
            'courses' => $results->toArray(),
            'response_count' => $results->sum('response_count'),
            'response_rate' => round($results->avg('response_rate'), 2),
        ];
    }
}
