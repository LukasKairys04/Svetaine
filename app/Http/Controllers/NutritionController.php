<?php

namespace App\Http\Controllers;

use App\Models\NutritionPlan;

class NutritionController extends Controller
{
    public function index()
    {
        // rodomi visi aktyvūs mitybos planai su jų tikslais
        return view('nutrition.index', [
            'plans' => NutritionPlan::where('is_active', true)->with('goal')->get(),
        ]);
    }

    public function show(string $slug)
    {
        // randamas konkretus mitybos planas pagal slug
        $plan = NutritionPlan::where('slug', $slug)
            ->with('goal', 'recommendations.product')
            ->firstOrFail();

        return view('nutrition.show', compact('plan'));
    }

    public function planner()
    {
        // perduodami planai mitybos planuotojui
        return view('nutrition.planner', [
            'plans' => NutritionPlan::where('is_active', true)->get(['id', 'name', 'slug', 'short_description']),
            'preselected' => request('diet'),
        ]);
    }
}