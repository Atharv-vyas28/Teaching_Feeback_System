<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackEligibility extends Model
{
    protected $table = 'feedback_eligibility';

    protected $fillable = [
        'feedback_session_id', 'student_id', 'anonymous_token', 'has_submitted',
        'submitted_at', 'included_in_score', 'attendance_weight',
    ];

    protected function casts(): array
    {
        return [
            'has_submitted' => 'boolean',
            'submitted_at'  => 'datetime',
            'included_in_score' => 'boolean',
            'attendance_weight' => 'float',
        ];
    }

    // NOTE: No relation to feedback_responses — anonymity preserved
    public function feedbackSession() { return $this->belongsTo(FeedbackSession::class); }
    public function student()         { return $this->belongsTo(User::class, 'student_id'); }
}
