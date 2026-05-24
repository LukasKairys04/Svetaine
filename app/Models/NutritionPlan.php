<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionPlan extends Model
{
    protected $guarded = [];

    protected $casts = [
        'pros' => 'array',
        'cons' => 'array',
        'is_active' => 'boolean',
    ];

    public function goal()
    {
        return $this->belongsTo(NutritionGoal::class, 'goal_id');
    }

    public function recommendations()
    {
        return $this->hasMany(NutritionPlanRecommendation::class, 'plan_id');
    }
}
