<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportPlan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'sport_plan_exercises', 'plan_id', 'exercise_id')
            ->withPivot(['day', 'sets', 'reps', 'rest_seconds', 'notes', 'sort_order'])
            ->withTimestamps()
            ->orderBy('sport_plan_exercises.day')
            ->orderBy('sport_plan_exercises.sort_order');
    }
}
