<?php

namespace Database\Seeders;

use App\Models\NutritionGoal;
use App\Models\NutritionPlan;
use App\Models\NutritionPlanRecommendation;
use Illuminate\Database\Seeder;

class NutritionSeeder extends Seeder
{
    public function run(): void
    {
        $goals = [
            ['name' => 'Daugiau baltymų', 'slug' => 'protein', 'icon' => 'bi-egg-fried', 'description' => 'Didesnis baltymų kiekis raumenų augimui ir atsistatymui.'],
            ['name' => 'Mažiau angliavandenių', 'slug' => 'low-carbs', 'icon' => 'bi-bread-slice', 'description' => 'Mažiau angliavandenių svorio metimui ir stabiliam energijos lygiui.'],
            ['name' => 'Daugiau energijos', 'slug' => 'energy', 'icon' => 'bi-lightning-charge', 'description' => 'Kompleksiniai angliavandeniai ir sveikieji riebalai energijai.'],
            ['name' => 'Svorio metimas', 'slug' => 'weight-loss', 'icon' => 'bi-speedometer2', 'description' => 'Kalorijų deficitas su pakankamu baltymų kiekiu.'],
            ['name' => 'Raumenų masė', 'slug' => 'muscle-gain', 'icon' => 'bi-trophy', 'description' => 'Kalorijų perteklius su aukšta baltymų norma.'],
        ];
        foreach ($goals as $g) {
            NutritionGoal::updateOrCreate(['slug' => $g['slug']], $g);
        }

        // Core diet types the user asked for. Each entry is a distinct eating
        // style (not a calorie goal) — users pick one and combine it with the
        // calorie planner for daily targets.
        $plans = [
            [
                'name' => 'Low Fat',
                'slug' => 'low-fat',
                'goal' => 'weight-loss',
                'short_description' => 'Mažiau riebalų, daugiau angliavandenių ir liesų baltymų.',
                'description' => 'Low Fat — mityba, kurioje riebalai sudaro apie 15–25% kalorijų. Daugiausia valgoma grūdų, daržovių, vaisių, liesos mėsos, žuvies ir nugriebto pieno produktų. Populiarus svorio metimui ir širdies sveikatai.',
                'pros' => ['Lengviau laikytis kalorijų deficito', 'Naudinga širdies ir kraujagyslių sveikatai', 'Daug skaidulų iš grūdų ir daržovių', 'Galima valgyti dideles porcijas'],
                'cons' => ['Per mažai riebalų gali sutrikdyti hormonų pusiausvyrą', 'Maistas mažiau sotus — greičiau išalkstama', 'Reikia riboti riešutus, aliejų, riebią žuvį'],
            ],
            [
                'name' => 'Vegetariška',
                'slug' => 'vegetarian',
                'goal' => 'energy',
                'short_description' => 'Be mėsos ir žuvies. Pagrindas — augalinis maistas, pieno produktai ir kiaušiniai.',
                'description' => 'Vegetariška mityba — atsisakoma mėsos ir žuvies, bet valgomi pieno produktai ir kiaušiniai (lakto-ovo). Ankštiniai, tofu, grūdai, daržovės ir riešutai sudaro pagrindą.',
                'pros' => ['Daug skaidulų, vitaminų ir antioksidantų', 'Mažesnis poveikis aplinkai', 'Mažesnė širdies ligų rizika', 'Dažnai pigesnis pirkinių krepšelis'],
                'cons' => ['Reikia stebėti B12, geležį, cinką ir omega-3', 'Pilną baltymų profilį reikia gauti derinant ankštinius su grūdais', 'Paruošti vegetariški produktai gali būti labai kaloringi'],
            ],
            [
                'name' => 'Viduržemio jūros',
                'slug' => 'mediterranean',
                'goal' => 'energy',
                'short_description' => 'Žuvis, alyvuogių aliejus, daržovės, pilno grūdo produktai, riešutai.',
                'description' => 'Viduržemio jūros mityba paremta tradicine Graikijos, Italijos ir Ispanijos virtuve. Daug daržovių, pilnų grūdų, alyvuogių aliejaus, žuvies, saikingai vyno. Moksliškai viena labiausiai tyrinėtų ir geriausiai įvertintų dietų.',
                'pros' => ['Mokslininkų laikoma viena sveikiausių mitybų', 'Mažina širdies ligų ir diabeto riziką', 'Tvari — galima laikytis visą gyvenimą', 'Skani ir įvairi — nereikia viską sverti'],
                'cons' => ['Kokybiškas alyvuogių aliejus ir žuvis kainuoja brangiau', 'Nėra griežtų taisyklių — reikia pačiam planuoti porcijas', 'Sportininkams gali tekti papildomai rinktis baltymų šaltinius'],
            ],
            [
                'name' => 'Karnivorų (Carnivore)',
                'slug' => 'carnivore',
                'goal' => 'protein',
                'short_description' => 'Tik gyvūninės kilmės maistas — mėsa, žuvis, kiaušiniai, nedidelė dalis pieno produktų.',
                'description' => 'Karnivorų dieta — ekstremali eliminacinė mityba, kurioje valgoma tik gyvūninės kilmės produktai. Populiari tarp žmonių, turinčių virškinimo ar autoimuninių problemų, taip pat siekiančių greito svorio metimo.',
                'pros' => ['Daug baltymų — ilgas sotumo jausmas', 'Paprasta — nereikia skaičiuoti angliavandenių ar riebalų', 'Gali padėti esant virškinimo ar autoimuninėms problemoms', 'Pradžioje greitai krenta vandens svoris'],
                'cons' => ['Trūksta skaidulų, vitamino C ir kalio', 'Ilgalaikis poveikis sveikatai kol kas mažai ištirtas', 'Sunku laikytis draugų rate ar keliaujant', 'Didelis sočiųjų riebalų kiekis — būtina stebėti cholesterolį'],
            ],
            [
                'name' => 'Whole Foods',
                'slug' => 'whole-foods',
                'goal' => 'energy',
                'short_description' => 'Pilnavertis, minimaliai apdorotas maistas: daržovės, vaisiai, grūdai, ankštiniai, žuvis ir liesa mėsa.',
                'description' => 'Whole Foods principas orientuotas į kuo mažiau apdorotą maistą ir aiškią sudėtį. Tinka ilgalaikiam gyvenimo būdui bei stabiliai savijautai.',
                'pros' => ['Lengviau kontroliuoti kalorijas', 'Daug skaidulų ir mikroelementų', 'Stabili energija dienos eigoje', 'Paprastas ilgalaikis laikymasis'],
                'cons' => ['Reikia dažniau gaminti namuose', 'Kokybiški produktai gali kainuoti brangiau', 'Sudėtingiau valgant ne namuose'],
            ],
            [
                'name' => 'Keto',
                'slug' => 'keto',
                'goal' => 'weight-loss',
                'short_description' => 'Labai mažai angliavandenių, daugiau riebalų ir vidutiniškai baltymų.',
                'description' => 'Keto mityba riboja angliavandenius ir didina riebalų kiekį, kad energijai būtų naudojami ketonai. Dažniausiai taikoma svorio kontrolei ir apetito valdymui.',
                'pros' => ['Dažnai sumažėja apetitas', 'Aiški struktūra ir taisyklės', 'Gali padėti greičiau mažinti svorį pradžioje'],
                'cons' => ['Pradžioje gali būti energijos kritimas', 'Reikia atidžiai rinktis produktus', 'Ne visiems patogu laikytis socialiai'],
            ],
        ];

        // Remove any nutrition plans that are no longer part of the curated
        // diet-type list. Keeps DB tidy across reseeds.
        $keepSlugs = array_column($plans, 'slug');
        NutritionPlan::whereNotIn('slug', $keepSlugs)->each(function ($p) {
            NutritionPlanRecommendation::where('plan_id', $p->id)->delete();
            $p->delete();
        });

        foreach ($plans as $p) {
            $goal = NutritionGoal::where('slug', $p['goal'])->first();
            $plan = NutritionPlan::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'name' => $p['name'],
                    'goal_id' => $goal?->id,
                    'short_description' => $p['short_description'],
                    'description' => $p['description'],
                    'pros' => $p['pros'],
                    'cons' => $p['cons'],
                    'image' => null,
                    'is_active' => true,
                ]
            );

            $recs = [
                ['healthy_food', 'Vištiena', 'Liesa baltymų šaltinis.'],
                ['healthy_food', 'Kiaušiniai', 'Pilnavertis baltymas su sveikaisiais riebalais.'],
                ['healthy_food', 'Avižos', 'Ilgai veikiantys angliavandeniai.'],
                ['supplement_alt', 'Whey baltymai', 'Greitas atstatymas po treniruotės.'],
                ['supplement_alt', 'Kreatinas', 'Didina jėgą ir raumenų apimtis.'],
            ];
            foreach ($recs as $r) {
                NutritionPlanRecommendation::updateOrCreate(
                    ['plan_id' => $plan->id, 'name' => $r[1]],
                    ['type' => $r[0], 'description' => $r[2]]
                );
            }
        }
    }
}
