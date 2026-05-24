<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NutritionPlanRecommendation extends Model
{
    protected $guarded = [];

    public function plan()
    {
        return $this->belongsTo(NutritionPlan::class, 'plan_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
