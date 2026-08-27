<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['department_id', 'name', 'code', 'duration_years', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function department() { return $this->belongsTo(Department::class); }
    public function courses()    { return $this->hasMany(Course::class); }
    public function students()   { return $this->hasMany(User::class)->where('role', 'student'); }
}
