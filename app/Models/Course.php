<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'department_id', 'program_id', 'name', 'code',
        'credits', 'semester_number', 'description', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function department()    { return $this->belongsTo(Department::class); }
    public function program()       { return $this->belongsTo(Program::class); }
    public function classSections() { return $this->hasMany(ClassSection::class); }
}
