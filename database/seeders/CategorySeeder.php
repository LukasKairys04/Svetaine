<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $productCats = [
            ['name' => 'Sporto papildai', 'description' => 'Baltymai, kreatinas, aminorūgštys, BCAA ir kt.'],
            ['name' => 'Mityba', 'description' => 'Sveika mityba, batonėliai, užkandžiai.'],
            ['name' => 'Sportas', 'description' => 'Sporto įranga, aksesuarai, drabužiai.'],
            ['name' => 'Vitaminai', 'description' => 'Vitaminai ir mineralai.'],
            ['name' => 'Gėrimai', 'description' => 'Izotoniniai, energiniai, BCAA gėrimai.'],
        ];

        foreach ($productCats as $i => $c) {
            Category::updateOrCreate(
                ['slug' => Str::slug($c['name'])],
                array_merge($c, ['type' => 'product', 'sort_order' => $i, 'is_active' => true])
            );
        }

        $subcategories = [
            'sporto-papildai' => [
                ['name' => 'High Protein', 'description' => 'Didelio baltymo kiekio papildai raumenų augimui.'],
                ['name' => 'Kreatinas', 'description' => 'Kreatino produktai jėgai ir ištvermei.'],
                ['name' => 'Aminorūgštys', 'description' => 'BCAA, glutaminas ir kitos aminorūgštys.'],
                ['name' => 'Pre-Workout', 'description' => 'Prieš treniruotę skirti energijos papildai.'],
            ],
            'mityba' => [
                ['name' => 'Low Fat', 'description' => 'Mažo riebalų kiekio produktai.'],
                ['name' => 'High Protein', 'description' => 'Baltymais praturtinti maisto produktai.'],
                ['name' => 'Užkandžiai', 'description' => 'Sveiki batonėliai ir užkandžiai.'],
            ],
            'sportas' => [
                ['name' => 'Laisvi svoriai', 'description' => 'Hanteliai, svarmenys, svoriai.'],
                ['name' => 'Aksesuarai', 'description' => 'Gumos, kilimėliai, šokdynės ir kt.'],
                ['name' => 'Krepšiai ir ekipuotė', 'description' => 'Sporto krepšiai ir aprangos priedai.'],
            ],
            'vitaminai' => [
                ['name' => 'Vitaminai D ir C', 'description' => 'Pagrindiniai vitaminai imunitetui.'],
                ['name' => 'Omega ir riebalų rūgštys', 'description' => 'Omega-3, žuvų taukai.'],
                ['name' => 'Kompleksai', 'description' => 'Multivitaminų kompleksai sportininkams.'],
            ],
            'gerimai' => [
                ['name' => 'Izotoniniai', 'description' => 'Elektrolitų ir druskų gėrimai.'],
                ['name' => 'Baltyminiai gėrimai', 'description' => 'Paruošti gerti baltyminiai kokteiliai.'],
            ],
        ];

        foreach ($subcategories as $parentSlug => $children) {
            $parent = Category::where('slug', $parentSlug)->first();
            if (!$parent) continue;

            foreach ($children as $j => $child) {
                $childSlug = $parentSlug . '-' . Str::slug($child['name']);
                Category::updateOrCreate(
                    ['slug' => $childSlug],
                    array_merge($child, [
                        'type' => 'product',
                        'parent_id' => $parent->id,
                        'sort_order' => $j,
                        'is_active' => true,
                    ])
                );
            }
        }
    }
}
