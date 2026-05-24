<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function index()
    {
        return view('calculators.index');
    }

    public function bmi(Request $request)
    {
        $result = null;
        if ($request->filled(['height', 'weight'])) {
            $h = (float) $request->input('height') / 100;
            $w = (float) $request->input('weight');
            if ($h > 0) {
                $bmi = round($w / ($h * $h), 2);
                $cat = match (true) {
                    $bmi < 18.5 => ['Per mažas svoris', 'warning'],
                    $bmi < 25 => ['Normalus svoris', 'success'],
                    $bmi < 30 => ['Antsvoris', 'warning'],
                    default => ['Nutukimas', 'danger'],
                };
                $result = ['bmi' => $bmi, 'label' => $cat[0], 'color' => $cat[1]];
            }
        }
        return view('calculators.bmi', compact('result'));
    }

    public function nutrition(Request $request)
    {
        $result = null;
        if ($request->filled(['age', 'gender', 'height', 'weight', 'activity', 'goal'])) {
            $age = (int) $request->input('age');
            $gender = $request->input('gender');
            $h = (float) $request->input('height');
            $w = (float) $request->input('weight');
            $activity = (float) $request->input('activity'); // 1.2, 1.375, 1.55, 1.725, 1.9
            $goal = $request->input('goal'); // lose, maintain, gain

            // Mifflin-St Jeor
            $bmr = $gender === 'female'
                ? 10 * $w + 6.25 * $h - 5 * $age - 161
                : 10 * $w + 6.25 * $h - 5 * $age + 5;
            $tdee = $bmr * $activity;
            $calories = match ($goal) {
                'lose' => $tdee - 500,
                'gain' => $tdee + 400,
                default => $tdee,
            };
            $calories = (int) round($calories);
            $protein = (int) round($w * ($goal === 'gain' ? 2 : 1.8)); // g
            $fat = (int) round(($calories * 0.25) / 9);
            $carbs = (int) round(($calories - $protein * 4 - $fat * 9) / 4);

            $result = compact('bmr', 'tdee', 'calories', 'protein', 'fat', 'carbs');
        }
        return view('calculators.nutrition', compact('result'));
    }

    public function sportPlan(Request $request)
    {
        $result = null;
        if ($request->filled(['level', 'goal', 'days', 'time'])) {
            $level = $request->input('level');
            $goal = $request->input('goal');
            $days = (int) $request->input('days');
            $time = (int) $request->input('time'); // minutes per session

            $splits = [
                3 => ['Full body A', 'Full body B', 'Full body C'],
                4 => ['Upper', 'Lower', 'Upper', 'Lower'],
                5 => ['Push', 'Pull', 'Legs', 'Upper', 'Lower'],
                6 => ['Push', 'Pull', 'Legs', 'Push', 'Pull', 'Legs'],
            ];
            $split = $splits[$days] ?? $splits[3];
            $cardio = match ($goal) {
                'weight_loss' => '3–4x per savaitę 20–30 min HIIT arba 45 min LISS',
                'endurance' => '4–5x per savaitę bėgimas / dviratis',
                default => '1–2x per savaitę 15–20 min cardio',
            };
            $repsRange = match ($goal) {
                'strength' => '4–6',
                'hypertrophy' => '8–12',
                'endurance' => '15–20',
                default => '8–12',
            };
            $result = compact('level', 'goal', 'days', 'time', 'split', 'cardio', 'repsRange');
        }
        return view('calculators.sport-plan', compact('result'));
    }
}
