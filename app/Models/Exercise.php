<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $guarded = [];

    protected $casts = [
        'benefits' => 'array',
        'muscle_groups' => 'array',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function plans()
    {
        return $this->belongsToMany(SportPlan::class, 'sport_plan_exercises', 'exercise_id', 'plan_id')
            ->withPivot(['day', 'sets', 'reps', 'rest_seconds', 'notes', 'sort_order'])
            ->withTimestamps();
    }
}
