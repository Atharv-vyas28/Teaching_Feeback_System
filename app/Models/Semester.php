<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $fillable = ['academic_year_id', 'name', 'number', 'start_date', 'end_date', 'is_current'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'is_current' => 'boolean',
        ];
    }

    public function academicYear()   { return $this->belongsTo(AcademicYear::class); }
    public function classSections()  { return $this->hasMany(ClassSection::class); }
    public function enrollments()    { return $this->hasMany(CourseEnrollment::class); }
}
