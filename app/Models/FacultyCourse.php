<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultyCourse extends Model
{
    protected $fillable = ['user_id', 'class_section_id', 'semester_id', 'is_active'];

    public function faculty()  { return $this->belongsTo(User::class, 'user_id'); }
    public function section()  { return $this->belongsTo(ClassSection::class, 'class_section_id'); }
    public function semester() { return $this->belongsTo(Semester::class); }
}
