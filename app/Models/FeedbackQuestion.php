<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackQuestion extends Model
{
    protected $fillable = [
        'question_text', 'type', 'max_marks', 'weight',
        'display_order', 'is_active', 'is_required',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'is_required' => 'boolean',
            'max_marks'   => 'float',
            'weight'      => 'float',
        ];
    }

    public function answers() { return $this->hasMany(FeedbackAnswer::class); }

    public function scopeActive($query) { return $query->where('is_active', true); }
    public function scopeOrdered($query) { return $query->orderBy('display_order'); }
}
