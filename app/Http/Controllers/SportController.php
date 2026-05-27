<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Sport;
use App\Models\SportPlan;
use Illuminate\Http\Request;

class SportController extends Controller
{
    public function index()
    {
        $featuredPlans = SportPlan::where('is_active', true)
            ->with('sport')
            ->latest()
            ->take(3)
            ->get();

        return view('sports.index', compact('featuredPlans'));
    }

    public function builder(Request $request)
    {
        // paimami visi pratimai ir jų sporto šakos
        $exercises = Exercise::with('sport')->orderBy('name')->get();

        // surenkamos unikalios raumenų grupės
        $muscleGroups = $exercises->pluck('muscle_groups')->filter()->flatten()->unique()->sort()->values();

        $importedPlan = null;

        // jei pasirinktas planas, jis įkeliamas į plano kūrėją
        if ($request->filled('from_plan')) {
            $sourcePlan = SportPlan::where('slug', $request->string('from_plan')->toString())
                ->with(['exercises' => fn ($q) => $q->orderBy('sport_plan_exercises.day')->orderBy('sport_plan_exercises.sort_order')])
                ->first();

            if ($sourcePlan) {
                // plano pratimai sugrupuojami pagal dienas
                $schedule = $sourcePlan->exercises
                    ->groupBy('pivot.day')
                    ->sortKeys()
                    ->map(function ($dayExercises, $day) {
                        return [
                            'name' => 'Diena ' . $day,
                            'exercises' => $dayExercises->values()->map(function ($ex) {
                                return [
                                    'name' => $ex->name,
                                    'sets' => (int) ($ex->pivot->sets ?? 3),
                                    'reps' => (string) ($ex->pivot->reps ?? '8-12'),
                                    'weight' => '',
                                    'rir' => 2,
                                    'rest' => (int) ($ex->pivot->rest_seconds ?? 90),
                                    'notes' => (string) ($ex->pivot->notes ?? ''),
                                ];
                            })->all(),
                        ];
                    })->values()->all();

                // nustatomas dienų skaičius nuo 2 iki 6
                $days = max(2, min(6, count($schedule) ?: (int) $sourcePlan->days_per_week));

                // jei trūksta dienų, pridedamos tuščios
                while (count($schedule) < $days) {
                    $schedule[] = ['name' => 'Diena ' . (count($schedule) + 1), 'exercises' => []];
                }

                $importedPlan = [
                    'days' => $days,
                    'activeDay' => 0,
                    'schedule' => $schedule,
                ];
            }
        }

        return view('sports.builder', compact('exercises', 'muscleGroups', 'importedPlan'));
    }

    public function show(string $slug)
    {
        // rodomas konkretus sportas su pratimais ir planais
        $sport = Sport::where('slug', $slug)
            ->with(['exercises', 'plans' => fn($q) => $q->where('is_active', true)])
            ->firstOrFail();

        return view('sports.show', compact('sport'));
    }

    public function plan(string $slug)
    {
        // rodomas konkretus sporto planas
        $plan = SportPlan::where('slug', $slug)
            ->with(['sport', 'exercises'])
            ->firstOrFail();

        return view('sports.plan', compact('plan'));
    }
}