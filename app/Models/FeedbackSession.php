<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackSession extends Model
{
    protected $fillable = [
        'class_session_id',
        'created_by',
        'assigned_staff_id',
        'status',
        'opened_at',
        'release_at',
        'deadline_at',
        'closed_at',
        'is_released',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'release_at' => 'datetime',
            'deadline_at' => 'datetime',
            'closed_at' => 'datetime',
            'is_released' => 'boolean',
        ];
    }

    public function classSession()
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function eligibility()
    {
        return $this->hasMany(FeedbackEligibility::class);
    }

    public function responses()
    {
        return $this->hasMany(FeedbackResponse::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isReleased(): bool
    {
        return (bool) $this->is_released;
    }

    /*
     * Server-side feedback time validation.
     * Students cannot bypass this by changing browser time or JavaScript.
     */
    public function isAcceptingResponses(): bool
    {
        $now = now();

        return $this->status === 'active'
            && (! $this->release_at || $now->greaterThanOrEqualTo($this->release_at))
            && (! $this->deadline_at || $now->lessThanOrEqualTo($this->deadline_at));
    }
}