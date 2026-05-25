<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Sport;
use App\Models\SportPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SportPlansSeeder extends Seeder
{
    public function run(): void
    {
        $strength = Sport::where('slug', 'jegos-treniruotes')->first();
        if (!$strength) return;
        $ex = Exercise::pluck('id', 'slug');
        SportPlan::where('sport_id', $strength->id)->each(function ($plan) {
            DB::table('sport_plan_exercises')->where('plan_id', $plan->id)->delete();
            $plan->delete();
        });
        $this->create($strength->id, [
            'slug' => 'full-body-pradedantiesiems',
            'name' => 'Full Body (3×/sav.)',
            'description' => 'Visas kūnas per vieną treniruotę — idealus pradedantiesiems. Treniruokis pirmadienį, trečiadienį ir penktadienį su poilsio dienomis tarp jų.',
            'level' => 'beginner',
            'goal' => 'general',
            'duration_weeks' => 8,
            'days_per_week' => 3,
            'image' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=900&q=80',
        ], $ex, [
            [1, 'pritupimai-su-stanga', 3, '8-10', 120],
            [1, 'stangos-spaudimas-gulint', 3, '8-10', 120],
            [1, 'hantelio-trauka-viena-ranka', 3, '10-12', 90],
            [1, 'karinis-spaudimas-stovint-ohp', 3, '8-10', 90],
            [1, 'planka', 3, '30-45s', 60],

            [2, 'rumuniska-mirties-trauka', 3, '8-10', 120],
            [2, 'lat-pulldown-platus-griebimas', 3, '10-12', 90],
            [2, 'hanteliu-spaudimas-sedint', 3, '10-12', 90],
            [2, 'blauzdu-kelimai-stovint-calf-raises', 3, '12-15', 60],
            [2, 'kabelinis-crunch', 3, '12-15', 60],

            [3, 'leg-press', 3, '10-12', 120],
            [3, 'hanteliu-spaudimas-gulint', 3, '10-12', 90],
            [3, 'horizontali-trauka-seated-row', 3, '10-12', 90],
            [3, 'hanteliu-pakelimai-i-sonus-lateral-raises', 3, '12-15', 60],
            [3, 'sonine-planka', 3, '30s/pusę', 60],
        ]);
        $this->create($strength->id, [
            'slug' => 'upper-lower-split',
            'name' => 'Upper / Lower (4×/sav.)',
            'description' => 'Viršutinis kūnas viename treniruotėje, apatinis kitame. Populiari sistema pažengusiems — geras balansas tarp dažnumo ir atsistatymo.',
            'level' => 'intermediate',
            'goal' => 'hypertrophy',
            'duration_weeks' => 10,
            'days_per_week' => 4,
            'image' => 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=900&q=80',
        ], $ex, [
            [1, 'stangos-spaudimas-gulint', 4, '6-8', 150],
            [1, 'svarmens-trauka-gulint', 4, '6-8', 150],
            [1, 'karinis-spaudimas-stovint-ohp', 3, '8-10', 120],
            [1, 'prisitraukimai-placiu-griebimu', 3, 'max', 120],
            [1, 'stangos-bicepsu-lenkimai', 3, '10-12', 90],
            [1, 'tricepsu-spaudimas-virs-galvos', 3, '10-12', 90],
            [2, 'pritupimai-su-stanga', 4, '6-8', 180],
            [2, 'rumuniska-mirties-trauka', 3, '8-10', 150],
            [2, 'leg-press', 3, '10-12', 120],
            [2, 'leg-curl-gulint', 3, '10-12', 90],
            [2, 'blauzdu-kelimai-stovint-calf-raises', 4, '12-15', 60],
            [2, 'hanging-leg-raise', 3, '10-12', 60],
            [3, 'hanteliu-spaudimas-nuozulniu-kampu', 4, '8-10', 120],
            [3, 'lat-pulldown-platus-griebimas', 4, '8-10', 120],
            [3, 'hanteliu-spaudimas-sedint', 3, '10-12', 90],
            [3, 'horizontali-trauka-seated-row', 3, '10-12', 90],
            [3, 'hanteliu-pakelimai-i-sonus-lateral-raises', 3, '12-15', 60],
            [3, 'face-pull', 3, '12-15', 60],
            [4, 'mirties-trauka', 3, '5-6', 180],
            [4, 'bulgariski-pritupimai-split-squat', 3, '10/koją', 120],
            [4, 'hip-thrust-su-stanga', 3, '10-12', 120],
            [4, 'leg-extension', 3, '12-15', 60],
            [4, 'goblet-squat', 3, '12-15', 60],
            [4, 'planka', 3, '45-60s', 60],
        ]);
        $this->create($strength->id, [
            'slug' => 'bro-split',
            'name' => 'Bro Split (5×/sav.)',
            'description' => 'Vienai raumenų grupei — atskira diena. Daug izoliacinio darbo ir tūrio kiekvienai grupei. Rekomenduojamas pažengusiems, kurie jau turi pagrindą.',
            'level' => 'advanced',
            'goal' => 'hypertrophy',
            'duration_weeks' => 12,
            'days_per_week' => 5,
            'image' => 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=900&q=80',
        ], $ex, [
            [1, 'stangos-spaudimas-gulint', 4, '6-8', 150],
            [1, 'hanteliu-spaudimas-nuozulniu-kampu', 4, '8-10', 120],
            [1, 'atsispaudimai-ant-lygiareciu-dips', 3, '8-12', 90],
            [1, 'hanteliu-skeciai-flyes', 3, '10-12', 90],
            [1, 'kabeliniai-skeciai-cable-crossover', 3, '12-15', 60],
            [2, 'mirties-trauka', 3, '5-6', 180],
            [2, 'prisitraukimai-placiu-griebimu', 4, 'max', 120],
            [2, 't-bar-trauka', 3, '8-10', 120],
            [2, 'hantelio-trauka-viena-ranka', 3, '10-12', 90],
            [2, 'horizontali-trauka-seated-row', 3, '10-12', 90],
            [3, 'karinis-spaudimas-stovint-ohp', 4, '6-8', 150],
            [3, 'hanteliu-spaudimas-sedint', 3, '10-12', 90],
            [3, 'hanteliu-pakelimai-i-sonus-lateral-raises', 4, '12-15', 60],
            [3, 'uzpakaliniai-deltos-skeciai-rear-delt-fly', 3, '12-15', 60],
            [3, 'face-pull', 3, '12-15', 60],
            [4, 'stangos-bicepsu-lenkimai', 4, '8-10', 90],
            [4, 'plaktuko-lenkimai-hammer-curl', 3, '10-12', 60],
            [4, 'kabeliniai-bicepsu-lenkimai', 3, '12-15', 60],
            [4, 'close-grip-bench-press', 4, '8-10', 90],
            [4, 'skull-crushers-ez-stanga', 3, '10-12', 60],
            [4, 'tricepsu-tiesimas-kabeliu-pushdown', 3, '12-15', 60],
            [5, 'pritupimai-su-stanga', 4, '6-8', 180],
            [5, 'rumuniska-mirties-trauka', 3, '8-10', 150],
            [5, 'leg-press', 3, '10-12', 120],
            [5, 'leg-extension', 3, '12-15', 60],
            [5, 'leg-curl-gulint', 3, '12-15', 60],
            [5, 'blauzdu-kelimai-stovint-calf-raises', 4, '12-15', 60],
        ]);
    }

        protected function create(int $sportId, array $meta, $exMap, array $schedule): void
    {
        $plan = SportPlan::create(array_merge($meta, [
            'sport_id' => $sportId,
            'is_active' => true,
        ]));

        foreach ($schedule as $i => [$day, $slug, $sets, $reps, $rest]) {
            if (!isset($exMap[$slug])) continue;
            DB::table('sport_plan_exercises')->insert([
                'plan_id' => $plan->id,
                'exercise_id' => $exMap[$slug],
                'day' => $day,
                'sets' => $sets,
                'reps' => $reps,
                'rest_seconds' => $rest,
                'sort_order' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
