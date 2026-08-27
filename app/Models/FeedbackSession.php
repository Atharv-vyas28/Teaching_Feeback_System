<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackSession extends Model
{
    protected $fillable = [
        'class_session_id', 'created_by', 'status', 'opened_at', 'closed_at', 'is_released',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function classSession()  { return $this->belongsTo(ClassSession::class); }
    public function creator()       { return $this->belongsTo(User::class, 'created_by'); }
    public function eligibility()   { return $this->hasMany(FeedbackEligibility::class); }
    public function responses()     { return $this->hasMany(FeedbackResponse::class); }

    public function isActive(): bool { return $this->status === 'active'; }
    public function isReleased(): bool { return (bool) $this->is_released; }
}
