<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'code', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function programs()  { return $this->hasMany(Program::class); }
    public function courses()   { return $this->hasMany(Course::class); }
    public function users()     { return $this->hasMany(User::class); }
}
