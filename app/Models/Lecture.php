<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;

    protected $table = 'lectures';

    protected $primaryKey = 'lecture_id';

    protected $fillable = [
        'course_id',
        'faculty_id',
        'lecture_no',
        'topic',
        'date',
        'lecture_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Lecture belongs to a course
    |--------------------------------------------------------------------------
    */

    public function course()
    {
        return $this->belongsTo(
            Course::class,
            'course_id',
            'course_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Lecture is conducted by a faculty member
    |--------------------------------------------------------------------------
    */

    public function faculty()
    {
        return $this->belongsTo(
            Faculty::class,
            'faculty_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Lecture has many attendance records
    |--------------------------------------------------------------------------
    */

    public function attendances()
    {
        return $this->hasMany(
            Attendance::class,
            'lecture_id',
            'lecture_id'
        );
    }
}