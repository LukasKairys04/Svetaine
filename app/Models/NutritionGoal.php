<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionGoal extends Model
{
    protected $guarded = [];

    public function plans()
    {
        return $this->hasMany(NutritionPlan::class, 'goal_id');
    }
}
