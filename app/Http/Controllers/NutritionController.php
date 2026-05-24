<?php

namespace App\Http\Controllers;

use App\Models\NutritionPlan;

class NutritionController extends Controller
{
    /**
     * Pagrindinis mitybos puslapis: trumpas įvadas, mitybos tipai
     * ir integruota BMI skaičiuoklė.
     */
    public function index()
    {
        return view('nutrition.index', [
            'plans' => NutritionPlan::where('is_active', true)->with('goal')->get(),
        ]);
    }

    /**
     * Individualaus mitybos tipo puslapis (Low Fat, Vegetarian ir kt.)
     * su detaliu aprašymu, privalumais/trūkumais ir susijusiomis rekomendacijomis.
     */
    public function show(string $slug)
    {
        $plan = NutritionPlan::where('slug', $slug)->with('goal', 'recommendations.product')->firstOrFail();
        return view('nutrition.show', compact('plan'));
    }

    /**
     * Asmeninis kalorijų planuoklis. Leidžia pasirinkti mitybos tipą,
     * įvesti kūno duomenis ir gauti Mifflin-St Jeor pagrįstą makro paskirstymą.
     * Rezultatai saugomi naršyklėje per localStorage (tik kliento pusėje, be DB).
     */
    public function planner()
    {
        return view('nutrition.planner', [
            'plans' => NutritionPlan::where('is_active', true)->get(['id', 'name', 'slug', 'short_description']),
            'preselected' => request('diet'),
        ]);
    }
}
