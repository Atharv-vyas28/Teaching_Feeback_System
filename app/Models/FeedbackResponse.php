<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackResponse extends Model
{
    protected $fillable = [
        'feedback_session_id', 'anonymous_token', 'submitted_at',
    ];
    // No student_id — intentionally anonymous at DB level

    protected function casts(): array
    {
        return ['submitted_at' => 'datetime'];
    }

    public function feedbackSession() { return $this->belongsTo(FeedbackSession::class); }
    public function answers()         { return $this->hasMany(FeedbackAnswer::class); }
}
