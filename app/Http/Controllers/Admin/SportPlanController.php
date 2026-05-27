<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use App\Models\SportPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SportPlanController extends Controller
{
    public function index()
    {
        // rodomas sporto planų sąrašas su sporto šaka
        $plans = SportPlan::with('sport')->latest()->paginate(20);

        return view('admin.sports.index', compact('plans'));
    }

    public function create()
    {
        // naujo sporto plano forma su pradinėmis reikšmėmis
        return view('admin.sports.form', [
            'plan' => new SportPlan(['is_active' => true, 'level' => 'beginner', 'goal' => 'general', 'duration_weeks' => 4, 'days_per_week' => 3]),
            'sports' => Sport::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        // sukuriamas naujas sporto planas
        SportPlan::create($this->validated($request));

        return redirect()->route('admin.sport-plans.index')->with('success', 'Planas sukurtas.');
    }

    public function edit(SportPlan $plan)
    {
        // sporto plano redagavimo forma
        return view('admin.sports.form', [
            'plan' => $plan,
            'sports' => Sport::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, SportPlan $plan)
    {
        // atnaujinamas pasirinktas sporto planas
        $plan->update($this->validated($request, $plan));

        return redirect()->route('admin.sport-plans.index')->with('success', 'Planas atnaujintas.');
    }

    public function destroy(SportPlan $plan)
    {
        // ištrinamas pasirinktas sporto planas
        $plan->delete();

        return back()->with('success', 'Planas pašalintas.');
    }

    protected function validated(Request $request, ?SportPlan $plan = null): array
    {
        $id = $plan?->id;

        // bendras sporto plano validavimas kūrimui ir redagavimui
        $data = $request->validate([
            'sport_id' => 'nullable|exists:sports,id',
            'name' => 'required|string|max:255',
            'slug' => "nullable|string|max:255|unique:sport_plans,slug,{$id}",
            'description' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'goal' => 'required|in:strength,hypertrophy,endurance,weight_loss,general',
            'duration_weeks' => 'required|integer|min:1|max:52',
            'days_per_week' => 'required|integer|min:1|max:7',
            'image' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        // jei slug neįvestas, jis sugeneruojamas iš plano pavadinimo
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}