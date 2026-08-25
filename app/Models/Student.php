<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';

    protected $primaryKey = 'roll_no';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'roll_no',
        'name',
        'branch',
        'year',
    ];

    /*
    |--------------------------------------------------------------------------
    | Student enrolled in many courses
    |--------------------------------------------------------------------------
    */

    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'enroll',
            'roll_no',
            'course_id',
            'roll_no',
            'course_id',
            'email_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student has many attendance records
    |--------------------------------------------------------------------------
    */

    public function attendances()
    {
        return $this->hasMany(
            Attendance::class,
            'roll_no',
            'roll_no'
        );
    }
}