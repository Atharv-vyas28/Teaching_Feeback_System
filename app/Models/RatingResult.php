<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingResult extends Model
{
    protected $fillable = [
        'faculty_id', 'class_section_id', 'semester_id',
        'overall_weighted_rating', 'course_rating', 'response_count',
        'eligible_count', 'response_rate', 'question_averages', 'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'question_averages' => 'array',
            'calculated_at'     => 'datetime',
        ];
    }

    public function faculty()  { return $this->belongsTo(User::class, 'faculty_id'); }
    public function section()  { return $this->belongsTo(ClassSection::class, 'class_section_id'); }
    public function semester() { return $this->belongsTo(Semester::class); }
}
