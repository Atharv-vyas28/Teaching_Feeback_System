<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackEligibility extends Model
{
    protected $table = 'feedback_eligibility';

    protected $fillable = [
        'feedback_session_id', 'student_id', 'has_submitted', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'has_submitted' => 'boolean',
            'submitted_at'  => 'datetime',
        ];
    }

    // NOTE: No relation to feedback_responses — anonymity preserved
    public function feedbackSession() { return $this->belongsTo(FeedbackSession::class); }
    public function student()         { return $this->belongsTo(User::class, 'student_id'); }
}
