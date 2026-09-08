<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackAnswer extends Model
{
    protected $fillable = [
        'feedback_response_id', 'feedback_question_id', 'rating_value', 'text_answer',
    ];

    protected function casts(): array
    {
        return ['rating_value' => 'float'];
    }

    public function response()  { return $this->belongsTo(FeedbackResponse::class, 'feedback_response_id'); }
    public function question()  { return $this->belongsTo(FeedbackQuestion::class, 'feedback_question_id'); }
}
