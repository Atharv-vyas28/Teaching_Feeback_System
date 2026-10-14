<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackSession extends Model
{
    protected $fillable = [
        'class_section_id',
        'faculty_id',
        'class_session_id',
        'created_by',
        'assigned_staff_id',
        'status',
        'release_at',
        'deadline_at',
        'duration_minutes',
        'opened_at',
        'closed_at',
        'is_released',
    ];

    protected function casts(): array
    {
        return [
            'release_at' => 'datetime',
            'deadline_at' => 'datetime',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'is_released' => 'boolean',
            'duration_minutes' => 'integer'
        ];
    }

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    public function classSession()
    {
        return $this->belongsTo(ClassSession::class);
    }

    public function getEndsAtAttribute()
    {
        if (!$this->opened_at || !$this->duration_minutes) {
            return null;
        }

        return $this->opened_at->copy()->addMinutes($this->duration_minutes);
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

    public function staffAssignments()
    {
        return $this->hasMany(StaffCourse::class, 'feedback_session_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isReleased(): bool
    {
        return (bool) $this->is_released;
    }

    public function classSection()
    {
        return $this->belongsTo(ClassSection::class);
    }

    /*
     * Server-side feedback time validation.
     * Students cannot bypass this by changing browser time or JavaScript.
     */
    public function isAcceptingResponses(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();

        if ($this->release_at && $now->lt($this->release_at)) {
            return false;
        }

        if ($this->opened_at && $this->duration_minutes) {
            $endsAt = $this->opened_at
                ->copy()
                ->addMinutes($this->duration_minutes);

            return $now->lt($endsAt);
        }

        if ($this->deadline_at) {
            return $now->lte($this->deadline_at);
        }

        return true;
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'active')
            ->where(function ($query) {
                $query
                    ->where(function ($q) {
                        $q->whereNotNull('opened_at')
                            ->whereNotNull('duration_minutes')
                            ->whereRaw(
                                'DATE_ADD(opened_at, INTERVAL duration_minutes MINUTE) < ?',
                                [now()]
                            );
                    })
                    ->orWhere(function ($q) {
                        $q->whereNull('duration_minutes')
                            ->whereNotNull('deadline_at')
                            ->where('deadline_at', '<', now());
                    });
            });
    }

    public function scopeReadyToOpen($query)
    {
        return $query->where('status', 'draft')
            ->whereNotNull('release_at')
            ->where('release_at', '<=', now())
            ->where(function ($deadline) {
                $deadline->whereNull('deadline_at')->orWhere('deadline_at', '>=', now());
            });
    }
}
