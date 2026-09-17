<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffCourse extends Model
{
    protected $fillable = [
        'user_id', 'class_section_id', 'feedback_session_id', // 'semester_id',
        'assigned_by', 'is_active', 'status', 'assigned_at', 'deactivated_at',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean', 'assigned_at' => 'datetime', 'deactivated_at' => 'datetime'];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    public function staff()    { return $this->belongsTo(User::class, 'user_id'); }
    public function section()  { return $this->belongsTo(ClassSection::class, 'class_section_id'); }
    public function semester() { return $this->belongsTo(Semester::class); }
    public function feedbackSession() { return $this->belongsTo(FeedbackSession::class); }
    public function assignedBy() { return $this->belongsTo(User::class, 'assigned_by'); }
}
