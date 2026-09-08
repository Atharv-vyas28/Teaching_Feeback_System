<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffCourse extends Model
{
    protected $fillable = ['user_id', 'class_section_id', 'semester_id', 'is_active'];

    public function staff()    { return $this->belongsTo(User::class, 'user_id'); }
    public function section()  { return $this->belongsTo(ClassSection::class, 'class_section_id'); }
    public function semester() { return $this->belongsTo(Semester::class); }
}
