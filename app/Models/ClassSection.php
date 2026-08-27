<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSection extends Model
{
    protected $fillable = ['course_id', 'semester_id', 'name', 'max_students', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function course()      { return $this->belongsTo(Course::class); }
    public function semester()    { return $this->belongsTo(Semester::class); }
    public function sessions()    { return $this->hasMany(ClassSession::class); }
    public function enrollments() { return $this->hasMany(CourseEnrollment::class); }

    public function faculty()
    {
        return $this->belongsToMany(User::class, 'faculty_courses', 'class_section_id', 'user_id')
                    ->where('role', 'faculty');
    }

    public function staff()
    {
        return $this->belongsToMany(User::class, 'staff_courses', 'class_section_id', 'user_id')
                    ->where('role', 'staff');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_enrollments', 'class_section_id', 'user_id')
                    ->where('role', 'student')
                    ->wherePivot('status', 'active');
    }
}
