<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'lecture_id',
        'roll_no',
        'status',
    ];

    public function lecture()
    {
        return $this->belongsTo(
            Lecture::class,
            'lecture_id',
            'lecture_id'
        );
    }

    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'roll_no',
            'roll_no'
        );
    }
}