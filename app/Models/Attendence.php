<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'class_session_id', 'student_id', 'marked_by',
        'status', 'feedback_enabled', 'marked_at', 'remarks',
    ];

    protected function casts(): array
    {
        return [
            'feedback_enabled' => 'boolean',
            'marked_at'        => 'datetime',
        ];
    }

    public function session()  { return $this->belongsTo(ClassSession::class, 'class_session_id'); }
    public function student()  { return $this->belongsTo(User::class, 'student_id'); }
    public function markedBy() { return $this->belongsTo(User::class, 'marked_by'); }

    public function isPresent(): bool
    {
        return in_array($this->status, ['present', 'late']);
    }

    public function canSubmitFeedback(): bool
    {
        return $this->isPresent() && $this->feedback_enabled;
    }
}
