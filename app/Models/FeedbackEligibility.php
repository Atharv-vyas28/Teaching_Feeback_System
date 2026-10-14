<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackEligibility extends Model
{
    protected $table = 'feedback_eligibility';

    protected $fillable = [
        'feedback_session_id',
        'student_id',
        'has_submitted',
        'submitted_at',
        'anonymous_token',
        'included_in_score',
        'attendance_weight',
    ];

    protected function casts(): array
    {
        return [
            'has_submitted' => 'boolean',
            'submitted_at' => 'datetime',
            'included_in_score' => 'boolean',
            'attendance_weight' => 'float',
        ];
    }

    public function feedbackSession()
    {
        return $this->belongsTo(FeedbackSession::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}