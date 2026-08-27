<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    protected $fillable = [
        'class_section_id', 'conducted_by', 'session_date',
        'start_time', 'end_time', 'topic', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return ['session_date' => 'date'];
    }

    public function section()         { return $this->belongsTo(ClassSection::class, 'class_section_id'); }
    public function conductor()       { return $this->belongsTo(User::class, 'conducted_by'); }
    public function attendanceRecords() { return $this->hasMany(Attendance::class); }
    public function feedbackSession() { return $this->hasOne(FeedbackSession::class); }

    public function presentStudents()
    {
        return $this->hasMany(Attendance::class)->where('status', 'present');
    }
}
