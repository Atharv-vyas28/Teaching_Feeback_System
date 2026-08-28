<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedbackRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'feedback_response_id',
        'faculty_id',
        'released_at',
        'status',
    ];

    public function feedbackResponse()
    {
        return $this->belongsTo(FeedbackResponse::class);
    }

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }
}
?>
