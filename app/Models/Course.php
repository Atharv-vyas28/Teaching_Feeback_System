<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $primaryKey = 'course_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'course_type',
    ];

    /*
    |--------------------------------------------------------------------------
    | Course is taught by many faculties
    |--------------------------------------------------------------------------
    */

    public function faculties()
    {
        return $this->belongsToMany(
            Faculty::class,
            'teach',
            'course_id',
            'faculty_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Course has many students
    |--------------------------------------------------------------------------
    */

    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'enroll',
            'course_id',
            'roll_no',
            'course_id',
            'roll_no'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Course has many lectures
    |--------------------------------------------------------------------------
    */

    public function lectures()
    {
        return $this->hasMany(
            Lecture::class,
            'course_id',
            'course_id'
        );
    }
}