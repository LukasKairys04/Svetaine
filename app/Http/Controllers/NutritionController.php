<?php

namespace App\Http\Controllers;

use App\Models\NutritionPlan;

class NutritionController extends Controller
{
    public function index()
    {
        return view('nutrition.index', [
            'plans' => NutritionPlan::where('is_active', true)->with('goal')->get(),
        ]);
    }

    public function show(string $slug)
    {
        $plan = NutritionPlan::where('slug', $slug)
            ->with('goal', 'recommendations.product')
            ->firstOrFail();

        return view('nutrition.show', compact('plan'));
    }

    public function planner()
    {
        return view('nutrition.planner', [
            'plans' => NutritionPlan::where('is_active', true)->get(['id', 'name', 'slug', 'short_description']),
            'preselected' => request('diet'),
        ]);
    }
}