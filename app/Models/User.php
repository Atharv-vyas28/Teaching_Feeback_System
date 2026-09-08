<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'department_id', 'program_id', 'name', 'email',
        'roll_number', 'employee_id', 'role', 'current_semester',
        'phone', 'avatar', 'is_active', 'password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role helpers
    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isFaculty(): bool  { return $this->role === 'faculty'; }
    public function isStaff(): bool    { return $this->role === 'staff'; }
    public function isStudent(): bool  { return $this->role === 'student'; }
    public function isFacultyOrStaff(): bool { return in_array($this->role, ['faculty', 'staff']); }

    // Relationships
    public function department() { return $this->belongsTo(Department::class); }
    public function program()    { return $this->belongsTo(Program::class); }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'user_id');
    }

    public function facultyCourses()
    {
        return $this->hasMany(FacultyCourse::class, 'user_id');
    }

    public function staffCourses()
    {
        return $this->hasMany(StaffCourse::class, 'user_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function conductedSessions()
    {
        return $this->hasMany(ClassSession::class, 'conducted_by');
    }

    public function feedbackEligibility()
    {
        return $this->hasMany(FeedbackEligibility::class, 'student_id');
    }

    public function ratingResults()
    {
        return $this->hasMany(RatingResult::class, 'faculty_id');
    }
}
