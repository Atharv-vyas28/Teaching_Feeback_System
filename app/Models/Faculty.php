<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculties';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'position',
        'experience',
        'email_id',
        'id'
    ];

    /*
    |--------------------------------------------------------------------------
    | Faculty teaches many courses
    |--------------------------------------------------------------------------
    */

    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'teach',
            'faculty_id',
            'course_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Faculty conducts many lectures
    |--------------------------------------------------------------------------
    */

    public function lectures()
    {
        return $this->hasMany(
            Lecture::class,
            'faculty_id',
            'id'
        );
    }
}