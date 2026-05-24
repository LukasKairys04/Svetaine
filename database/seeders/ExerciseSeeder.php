<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Sport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $strength = Sport::where('slug', 'jegos-treniruotes')->first()?->id;
        $running = Sport::where('slug', 'begimas')->first()?->id;
        $crossfit = Sport::where('slug', 'crossfit')->first()?->id;
        $yoga = Sport::where('slug', 'joga')->first()?->id;

        // [name, sport_id, muscle_groups, difficulty, benefits]
        $exercises = [
            // --- KRŪTINĖ / CHEST ---
            ['Štangos spaudimas gulint', $strength, ['krūtinė','tricepsai','pečiai'], 'intermediate', ['Jėga','Krūtinės masė']],
            ['Štangos spaudimas nuožulniu kampu (incline)', $strength, ['viršutinė krūtinė','pečiai'], 'intermediate', ['Viršutinės krūtinės augimas']],
            ['Štangos spaudimas žemyn (decline)', $strength, ['apatinė krūtinė','tricepsai'], 'intermediate', ['Apatinės krūtinės forma']],
            ['Hantelių spaudimas gulint', $strength, ['krūtinė','pečiai'], 'beginner', ['Platesnis judesio spektras']],
            ['Hantelių spaudimas nuožulniu kampu', $strength, ['viršutinė krūtinė'], 'beginner', ['Simetrija','Geresnė izoliacija']],
            ['Hantelių skečiai (flyes)', $strength, ['krūtinė'], 'beginner', ['Krūtinės izoliacija','Ištempimas']],
            ['Kabeliniai skečiai (cable crossover)', $strength, ['krūtinė'], 'beginner', ['Pastovus tempimas','Forma']],
            ['Atsispaudimai', $strength, ['krūtinė','tricepsai','pečiai'], 'beginner', ['Kūno kontrolė','Nereikia įrangos']],
            ['Atsispaudimai ant lygiagrečių (dips)', $strength, ['apatinė krūtinė','tricepsai'], 'intermediate', ['Didelis aktyvumas','Jėga']],

            // --- NUGARA / BACK ---
            ['Prisitraukimai plačiu griebimu', $strength, ['platusis nugaros raumuo','bicepsai'], 'intermediate', ['Platesnė nugara']],
            ['Prisitraukimai atvirkštiniu griebimu (chin-up)', $strength, ['nugara','bicepsai'], 'intermediate', ['Bicepsų akcentas']],
            ['Svarmens trauka gulint', $strength, ['nugara','užpakaliniai pečiai'], 'intermediate', ['Vidurinė nugara']],
            ['Hantelio trauka viena ranka', $strength, ['nugara','bicepsai'], 'beginner', ['Simetrija','Kontrolė']],
            ['T-bar trauka', $strength, ['vidurinė nugara'], 'intermediate', ['Storumas','Stabilumas']],
            ['Lat pulldown (platus griebimas)', $strength, ['platusis nugaros raumuo'], 'beginner', ['Platumas','Alternatyva prisitraukimams']],
            ['Horizontali trauka (seated row)', $strength, ['vidurinė nugara'], 'beginner', ['Laikysena']],
            ['Mirties trauka', $strength, ['nugara','kojos','sėdmenys'], 'advanced', ['Viso kūno jėga']],
            ['Rumuniška mirties trauka', $strength, ['užpakaliniai šlaunies','sėdmenys'], 'intermediate', ['Biceps femoris','Lankstumas']],
            ['Trapecijos traukimai (shrugs)', $strength, ['trapeciniai'], 'beginner', ['Trapecijų masė']],

            // --- KOJOS / LEGS ---
            ['Pritūpimai su štanga (back squat)', $strength, ['keturgalvis','sėdmenys','liemuo'], 'intermediate', ['Bazinis kojų','Jėga']],
            ['Priekiniai pritūpimai (front squat)', $strength, ['keturgalvis','liemuo'], 'advanced', ['Kvadricepso akcentas']],
            ['Bulgariški pritūpimai (split squat)', $strength, ['keturgalvis','sėdmenys'], 'intermediate', ['Simetrija','Pusiausvyra']],
            ['Išpuoliai su hanteliais', $strength, ['keturgalvis','sėdmenys'], 'beginner', ['Funkcionalumas']],
            ['Leg press', $strength, ['keturgalvis','sėdmenys'], 'beginner', ['Saugu','Didelis svoris']],
            ['Leg extension', $strength, ['keturgalvis'], 'beginner', ['Kvadricepso izoliacija']],
            ['Leg curl gulint', $strength, ['biceps femoris'], 'beginner', ['Pakaušio izoliacija']],
            ['Blauzdų kėlimai stovint (calf raises)', $strength, ['blauzdos'], 'beginner', ['Blauzdų masė']],
            ['Hip thrust su štanga', $strength, ['sėdmenys','biceps femoris'], 'intermediate', ['Sėdmenų stiprumas']],
            ['Goblet squat', $strength, ['kojos','liemuo'], 'beginner', ['Tech. mokymasis','Pradedantiesiems']],

            // --- PEČIAI / SHOULDERS ---
            ['Karinis spaudimas stovint (OHP)', $strength, ['pečiai','tricepsai'], 'intermediate', ['Viršutinio kūno jėga']],
            ['Hantelių spaudimas sėdint', $strength, ['pečiai'], 'beginner', ['Stabilumas','Forma']],
            ['Hantelių pakėlimai į šonus (lateral raises)', $strength, ['viduriniai pečiai'], 'beginner', ['Pečių platumas']],
            ['Užpakaliniai deltos skečiai (rear delt fly)', $strength, ['užpakaliniai pečiai'], 'beginner', ['Laikysena','Simetrija']],
            ['Arnoldo spaudimas', $strength, ['pečiai'], 'intermediate', ['Visas pečių kompleksas']],
            ['Upright row', $strength, ['pečiai','trapeciniai'], 'intermediate', ['Masė']],
            ['Face pull', $strength, ['užpakaliniai pečiai','trapeciniai'], 'beginner', ['Laikysena','Sveika pečių sveikata']],

            // --- RANKOS / ARMS ---
            ['Štangos bicepsų lenkimai', $strength, ['bicepsai'], 'beginner', ['Bicepsų masė']],
            ['Hantelių bicepsų lenkimai', $strength, ['bicepsai'], 'beginner', ['Simetrija']],
            ['Plaktuko lenkimai (hammer curl)', $strength, ['bicepsai','dilbiai'], 'beginner', ['Brachialis','Dilbis']],
            ['Kabeliniai bicepsų lenkimai', $strength, ['bicepsai'], 'beginner', ['Pastovus tempimas']],
            ['Tricepsų spaudimas virš galvos', $strength, ['tricepsai'], 'beginner', ['Ilgoji galva']],
            ['Tricepsų tiesimas kabeliu (pushdown)', $strength, ['tricepsai'], 'beginner', ['Izoliacija']],
            ['Skull crushers (EZ štanga)', $strength, ['tricepsai'], 'intermediate', ['Triceps masė']],
            ['Close-grip bench press', $strength, ['tricepsai','krūtinė'], 'intermediate', ['Triceps + krūtinė']],

            // --- LIEMUO / CORE ---
            ['Planka', $strength, ['liemuo','core'], 'beginner', ['Stabilumas']],
            ['Šoninė planka', $strength, ['įstrižas','core'], 'beginner', ['Šoninis core']],
            ['Hanging leg raise', $strength, ['pilvo presas'], 'intermediate', ['Apatinis presas']],
            ['Kabelinis crunch', $strength, ['pilvo presas'], 'beginner', ['Viršutinis presas']],
            ['Rusai sukimai (russian twist)', $strength, ['įstrižas'], 'beginner', ['Sukimas']],
            ['Ab wheel rollout', $strength, ['core'], 'advanced', ['Galingas core']],

            // --- CARDIO / BĖGIMAS ---
            ['Intervalinis bėgimas (HIIT)', $running, ['kojos','širdis'], 'intermediate', ['VO2max','Degina kalorijas']],
            ['Ilgas lėtas bėgimas (LISS)', $running, ['kojos','širdis'], 'beginner', ['Aerobinė bazė']],
            ['Tempo bėgimas', $running, ['kojos'], 'intermediate', ['Laktato slenkstis']],
            ['Sprintai 100m', $running, ['kojos'], 'advanced', ['Sprogstamoji galia']],
            ['Bėgimas nuokalnę', $running, ['kojos','core'], 'intermediate', ['Galia','Koordinacija']],

            // --- CROSSFIT ---
            ['Burpees', $crossfit, ['visas kūnas'], 'intermediate', ['Kondicija','Ištvermė']],
            ['Box jumps', $crossfit, ['kojos'], 'intermediate', ['Sprogstamoji jėga']],
            ['Kettlebell swings', $crossfit, ['sėdmenys','užpakalinė juosta'], 'intermediate', ['Galia','Kondicija']],
            ['Thrusters (front squat + OHP)', $crossfit, ['visas kūnas'], 'advanced', ['Viso kūno galia']],
            ['Wall balls', $crossfit, ['kojos','pečiai'], 'intermediate', ['Kondicija']],
            ['Double unders', $crossfit, ['kojos','koordinacija'], 'advanced', ['Koordinacija','Kondicija']],

            // --- JOGA ---
            ['Šuns poza (Downward Dog)', $yoga, ['visas kūnas'], 'beginner', ['Lankstumas','Ramybė']],
            ['Karžygio poza (Warrior I)', $yoga, ['kojos','liemuo'], 'beginner', ['Jėga','Balansas']],
            ['Tiltas (Bridge)', $yoga, ['sėdmenys','nugara'], 'beginner', ['Lankstumas']],
            ['Paukščio-šuns poza (Bird-Dog)', $yoga, ['core','nugara'], 'beginner', ['Stabilumas']],
        ];

        foreach ($exercises as $e) {
            [$name, $sportId, $muscles, $difficulty, $benefits] = $e;
            Exercise::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'sport_id' => $sportId,
                    'name' => $name,
                    'short_description' => $name,
                    'description' => "{$name} — efektyvus pratimas tavo tikslams pasiekti.",
                    'how_to' => "1. Tinkamai pasiruošk.\n2. Išlaikyk gerą techniką.\n3. Atlik judesį kontroliuojamai.\n4. Kvėpuok ramiai.",
                    'benefits' => $benefits,
                    'muscle_groups' => $muscles,
                    'difficulty' => $difficulty,
                    'image' => 'https://loremflickr.com/600/400/gym,fitness,exercise?lock=' . abs(crc32($name) % 9999),
                ]
            );
        }
    }
}
