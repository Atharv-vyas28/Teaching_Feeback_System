<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    protected $fillable = ['user_id', 'class_section_id', 'semester_id', 'status', 'enrolled_at'];

    protected function casts(): array
    {
        return ['enrolled_at' => 'date'];
    }

    public function student()  { return $this->belongsTo(User::class, 'user_id'); }
    public function section()  { return $this->belongsTo(ClassSection::class, 'class_section_id'); }
    public function semester() { return $this->belongsTo(Semester::class); }
}
