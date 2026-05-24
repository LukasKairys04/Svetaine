<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Sport;
use App\Models\SportPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        $sports = [
            ['name' => 'Jėgos treniruotės', 'description' => 'Jėgos ir raumenų masės auginimas su svarmenimis.'],
            ['name' => 'Bėgimas', 'description' => 'Ištvermės treniruotės lauke ar ant bėgimo takelio.'],
            ['name' => 'Dviratis', 'description' => 'Ištvermė ir kojų raumenys, mažas poveikis sąnariams.'],
            ['name' => 'Joga', 'description' => 'Lankstumas, pusiausvyra ir proto ramybė.'],
            ['name' => 'CrossFit', 'description' => 'Funkcinės treniruotės, didelis intensyvumas.'],
            ['name' => 'Plaukimas', 'description' => 'Viso kūno treniruotė vandenyje — švelniai sąnariams.'],
        ];
        foreach ($sports as $s) {
            Sport::updateOrCreate(
                ['slug' => Str::slug($s['name'])],
                array_merge($s, [
                    'short_description' => $s['description'],
                    'image' => 'https://picsum.photos/seed/' . Str::slug($s['name']) . '/800/500',
                    'is_active' => true,
                ])
            );
        }

        $strength = Sport::where('slug', 'jegos-treniruotes')->first();
        $running = Sport::where('slug', 'begimas')->first();

        // Jėgos treniruotės pratimų biblioteka — slug'ai atitinka SportPlansSeeder.
        // Formatas: [sport, pavadinimas, trumpas apr., how-to, raumenys, benefits, difficulty]
        $exercises = [
            // --- Bazinės kompleksinės ---
            [$strength, 'Pritūpimai su štanga', 'Bazinis kojų pratimas su štanga.', "Štangą padėk ant viršutinės nugaros. Nusileisk iki 90°, atsikelk kontroliuojamai.", ['kojos', 'sėdmenys', 'liemuo'], ['Stipriausias kojų pratimas', 'Auga jėga'], 'intermediate'],
            [$strength, 'Mirties trauka', 'Bazinis viso kūno pratimas.', "Stovint priešais štangą, pakelk tiesia nugara iki pilno išsitiesimo.", ['nugara', 'kojos', 'sėdmenys'], ['Visapusiška jėga', 'Funkcinis'], 'advanced'],
            [$strength, 'Rumuniška mirties trauka', 'Kabliukinių raumenų ir sėdmenų pratimas.', "Su štanga šiek tiek sulenktais keliais nulenk liemenį į priekį, išlaikydamas tiesia nugarą.", ['kabliukiniai', 'sėdmenys', 'nugara'], ['Sėdmenų formavimas', 'Atleidžia kabliukinius'], 'intermediate'],
            [$strength, 'Leg press', 'Kojų spaudimas treniruokliu.', "Sėdint treniruoklyje, stumk platformą kojomis iki beveik pilno išsitiesimo.", ['kojos', 'sėdmenys'], ['Saugu stuburui', 'Didelis svoris'], 'beginner'],
            [$strength, 'Leg curl gulint', 'Kabliukinių raumenų izoliacija.', "Gulint ant pilvo lenk kojas ties keliais priešinantis svoriui.", ['kabliukiniai'], ['Tikslinė izoliacija'], 'beginner'],
            [$strength, 'Leg extension', 'Keturgalvių izoliacija.', "Sėdint treniruoklyje ištiesk kojas ties keliais.", ['keturgalviai'], ['Forma keturgalviams'], 'beginner'],
            [$strength, 'Goblet squat', 'Pritūpimai laikant hantelį prieš krūtinę.', "Laikyk hantelį prie krūtinės, pritūpk iki gylio.", ['kojos', 'liemuo'], ['Lengva mokytis techniką'], 'beginner'],
            [$strength, 'Bulgariški pritūpimai (split squat)', 'Vienos kojos pritūpimai su atgaline koja ant suolo.', "Užkelk užpakalinę koją ant suolo, pritūpk priekine koja.", ['kojos', 'sėdmenys'], ['Asimetrijos taisymas', 'Sėdmenys'], 'intermediate'],
            [$strength, 'Hip thrust su štanga', 'Sėdmenų akcentas su štanga.', "Atsisėsk pečiais į suolą, štangą padėk ant klubų, stumk klubus į viršų.", ['sėdmenys', 'kabliukiniai'], ['Didžiausias sėdmenų aktyvavimas'], 'intermediate'],
            [$strength, 'Blauzdų kėlimai stovint (calf raises)', 'Blauzdų izoliacija stovint.', "Stovėdamas pakelk kulnus į viršų iki maksimumo.", ['blauzdos'], ['Blauzdų dydis'], 'beginner'],

            // --- Krūtinė ---
            [$strength, 'Štangos spaudimas gulint', 'Bazinis krūtinės pratimas.', "Gulint ant suolo nuleisk štangą iki krūtinės ir spausk į viršų.", ['krūtinė', 'tricepsai', 'pečiai'], ['Pagrindinis krūtinės pratimas'], 'intermediate'],
            [$strength, 'Hantelių spaudimas gulint', 'Krūtinės pratimas su hanteliais.', "Gulint ant suolo spausk hantelius iš krūtinės aukštyn.", ['krūtinė', 'tricepsai'], ['Didesnis amplitudė'], 'beginner'],
            [$strength, 'Hantelių spaudimas nuožulniu kampu', 'Viršutinės krūtinės pratimas.', "Nuožulniame suole (30°) spausk hantelius aukštyn.", ['viršutinė krūtinė'], ['Viršutinės krūtinės formos'], 'intermediate'],
            [$strength, 'Hantelių skėčiai (flyes)', 'Krūtinės izoliacija.', "Gulint ant suolo pamažu skėsk hantelius į šonus plačiais lankais.", ['krūtinė'], ['Krūtinės ištempimas'], 'beginner'],
            [$strength, 'Kabeliniai skėčiai (cable crossover)', 'Krūtinės kabelių pratimas.', "Tarp dviejų blokų traukdamas kabelius sujunk rankas priešais krūtinę.", ['krūtinė'], ['Pilna kontrolė'], 'beginner'],
            [$strength, 'Atsispaudimai ant lygiagrečių (dips)', 'Apatinės krūtinės ir tricepso pratimas.', "Laikydamasis lygiagrečių, pasvirk į priekį ir nusileisk.", ['krūtinė', 'tricepsai'], ['Apatinė krūtinė'], 'intermediate'],

            // --- Nugara ---
            [$strength, 'Svarmens trauka gulint', 'Nugaros pratimas pasvirus su štanga.', "Pasilenk į priekį tiesia nugara, trauk štangą į pilvą.", ['nugara', 'bicepsai'], ['Storosios nugaros jėga'], 'intermediate'],
            [$strength, 'Prisitraukimai', 'Nugaros pratimas su savo svoriu.', "Pakibęs ant skersinio traukis iki smakro virš skersinio.", ['nugara', 'bicepsai'], ['Klasika'], 'intermediate'],
            [$strength, 'Prisitraukimai plačiu griebimu', 'Prisitraukimai akcentuojant latisimą.', "Tas pats kaip prisitraukimai, bet plačiu griebimu.", ['nugara'], ['Platus V'], 'advanced'],
            [$strength, 'Lat pulldown platus griebimas', 'Viršutinės nugaros pratimas treniruokliu.', "Sėdint traukdamas virvę platu griebimu iki krūtinės.", ['nugara'], ['Saugi alternatyva prisitraukimams'], 'beginner'],
            [$strength, 'Horizontali trauka (seated row)', 'Vidutinės nugaros pratimas.', "Sėdėdamas trauk rankeną link juosmens.", ['nugara'], ['Storosios nugaros forma'], 'beginner'],
            [$strength, 'Hantelio trauka viena ranka', 'Vienašalis nugaros pratimas.', "Keliu ant suolo, trauk hantelį viena ranka link juosmens.", ['nugara'], ['Asimetrijos taisymas'], 'beginner'],
            [$strength, 'T-bar trauka', 'Storosios nugaros trauka.', "Su štanga įvesta į kampą, trauk rankeną link krūtinės.", ['nugara'], ['Didelis svoris'], 'intermediate'],
            [$strength, 'Face pull', 'Užpakalinių deltų ir nugaros pratimas.', "Iš viršutinio bloko trauk virvę link veido išsklaidydamas alkūnes.", ['užpakalinės deltos', 'trapecijos'], ['Pečių sveikata'], 'beginner'],

            // --- Pečiai ---
            [$strength, 'Karinis spaudimas stovint (OHP)', 'Bazinis pečių spaudimas virš galvos.', "Stovėdamas spausk štangą nuo pečių į viršų.", ['pečiai', 'tricepsai'], ['Pagrindinis pečių pratimas'], 'intermediate'],
            [$strength, 'Hantelių spaudimas sėdint', 'Pečių spaudimas su hanteliais.', "Sėdint ant suolo spausk hantelius virš galvos.", ['pečiai'], ['Saugu stuburui'], 'beginner'],
            [$strength, 'Hantelių pakėlimai į šonus (lateral raises)', 'Šoninių deltų izoliacija.', "Stovint pakelk hantelius iki pečių lygio plačiais lankais.", ['šoninės deltos'], ['Pečių plotis'], 'beginner'],
            [$strength, 'Užpakaliniai deltos skėčiai (rear delt fly)', 'Užpakalinių deltų izoliacija.', "Pasilenk į priekį ir skėsk hantelius į šonus.", ['užpakalinės deltos'], ['Pečių sveikata'], 'beginner'],

            // --- Rankos ---
            [$strength, 'Štangos bicepsų lenkimai', 'Bazinis bicepsų pratimas.', "Stovint lenk štangą nuo klubų iki krūtinės.", ['bicepsai'], ['Bicepsų jėga'], 'beginner'],
            [$strength, 'Plaktuko lenkimai (hammer curl)', 'Bicepsų ir brachialio izoliacija.', "Lenk hantelius neutraliu griebimu (delnai vienas į kitą).", ['bicepsai', 'brachialis'], ['Storesni bicepsai'], 'beginner'],
            [$strength, 'Kabeliniai bicepsų lenkimai', 'Bicepsų pratimas su kabeliu.', "Stovint priešais bloką lenk rankeną iki krūtinės.", ['bicepsai'], ['Pastovus įtempimas'], 'beginner'],
            [$strength, 'Close-grip bench press', 'Tricepsų akcentuotas spaudimas.', "Kaip štangos spaudimas gulint, bet siauresnis griebimas (per pečius).", ['tricepsai', 'krūtinė'], ['Tricepsų jėga'], 'intermediate'],
            [$strength, 'Skull crushers (EZ štanga)', 'Tricepsų izoliacija gulint.', "Gulint nuleisk EZ štangą iki kaktos, ištiesk rankas.", ['tricepsai'], ['Tricepsų masė'], 'intermediate'],
            [$strength, 'Tricepsų spaudimas virš galvos', 'Tricepsų izoliacija virš galvos.', "Sėdint laikyk hantelį abiem rankom virš galvos, lenk ir tiesk.", ['tricepsai'], ['Ilgos galvos akcentas'], 'beginner'],
            [$strength, 'Tricepsų tiesimas kabeliu (pushdown)', 'Tricepsų izoliacija kabeliu.', "Iš viršaus spausk rankeną žemyn neatplesdamas alkūnių.", ['tricepsai'], ['Puiki izoliacija'], 'beginner'],

            // --- Liemuo ---
            [$strength, 'Planka', 'Izometrinis liemens pratimas.', "Laikyk kūną tiesiai ant dilbių ir pirštų.", ['liemuo'], ['Stabilumas'], 'beginner'],
            [$strength, 'Šoninė planka', 'Izometrinis šoninis liemens pratimas.', "Laikyk kūną tiesiai ant vieno dilbio, šonu į grindis.", ['šoninis liemuo'], ['Šoninis stabilumas'], 'beginner'],
            [$strength, 'Kabelinis crunch', 'Pilvo raumenų kabelinis pratimas.', "Klūpėdamas priešais viršutinį bloką lenk liemenį traukdamas kabelį.", ['pilvas'], ['Stiprus pilvas'], 'beginner'],
            [$strength, 'Hanging leg raise', 'Pilvo pratimas kabant.', "Pakibęs ant skersinio pakelk kojas iki 90° ar aukščiau.", ['pilvas'], ['Pilnas pilvo darbas'], 'intermediate'],

            // --- Bėgimas ---
            [$running, 'Intervalinis bėgimas', 'HIIT bėgimas ištvermei.', "Apšilk 10 min. 30s greitai / 60s lėtai × 8. Atvėsk 5 min.", ['kojos', 'širdis'], ['Didesnis VO2max', 'Degina kalorijas'], 'intermediate'],
            [$running, 'Ilgas lėtas bėgimas', 'Aerobinės bazės lavinimas.', "Bėgk pastoviu tempu 45–90 min.", ['kojos', 'širdis'], ['Stipri ištvermė'], 'beginner'],
        ];

        foreach ($exercises as $e) {
            [$sport, $name, $short, $howTo, $muscles, $benefits, $difficulty] = $e;
            Exercise::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'sport_id' => $sport?->id,
                    'name' => $name,
                    'short_description' => $short,
                    'description' => $short,
                    'how_to' => $howTo,
                    'benefits' => $benefits,
                    'muscle_groups' => $muscles,
                    'difficulty' => $difficulty,
                    'image' => 'https://picsum.photos/seed/' . Str::slug($name) . '/600/400',
                ]
            );
        }

        $plan = SportPlan::updateOrCreate(
            ['slug' => 'pradedanciojo-jegos-planas'],
            [
                'sport_id' => $strength?->id,
                'name' => 'Pradedančiojo jėgos planas',
                'description' => '3 dienų pilno kūno planas pradedantiesiems (4 savaitės).',
                'level' => 'beginner',
                'goal' => 'strength',
                'duration_weeks' => 4,
                'days_per_week' => 3,
                'image' => 'https://picsum.photos/seed/beginner-strength/800/500',
                'is_active' => true,
            ]
        );

        $exMap = Exercise::pluck('id', 'slug');
        $assign = [
            // day, slug, sets, reps
            [1, 'pritupimai-su-stanga', 3, '8-10'],
            [1, 'stangos-spaudimas-gulint', 3, '8-10'],
            [1, 'svarmens-trauka', 3, '8-10'],
            [2, 'mirties-trauka', 3, '5-6'],
            [2, 'prisitraukimai', 3, 'max'],
            [3, 'pritupimai-su-stanga', 3, '8-10'],
            [3, 'stangos-spaudimas-gulint', 3, '8-10'],
        ];
        foreach ($assign as $i => $a) {
            [$day, $slug, $sets, $reps] = $a;
            if (!isset($exMap[$slug])) continue;
            DB::table('sport_plan_exercises')->updateOrInsert(
                ['plan_id' => $plan->id, 'exercise_id' => $exMap[$slug], 'day' => $day],
                ['sets' => $sets, 'reps' => $reps, 'rest_seconds' => 90, 'sort_order' => $i, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
