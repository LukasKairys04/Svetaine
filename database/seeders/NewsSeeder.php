<?php

namespace Database\Seeders;

use App\Models\Naujiena;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['5 baltymų šaltiniai, kurių vertė nepervertinama', 'Kaip užtikrinti pakankamą baltymų kiekį mityboje kasdien.'],
            ['Kaip pasirinkti tinkamą pre-workout?', 'Paprastas vadovas, kaip išsirinkti papildą pagal tikslus.'],
            ['Bėgimo treniruočių planas pradedantiesiems', '8 savaičių planas nuo 0 iki 5km be sustojimo.'],
        ];

        foreach ($items as $i => $n) {
            News::updateOrCreate(
                ['slug' => Str::slug($n[0])],
                [
                    'title' => $n[0],
                    'excerpt' => $n[1],
                    'content' => "<p>{$n[1]}</p><p>Šiame straipsnyje aptariame pagrindines rekomendacijas, kurios padės pasiekti tavo tikslus greičiau ir efektyviau.</p>",
                    'image' => 'https://picsum.photos/seed/news' . $i . '/800/500',
                    'is_published' => true,
                    'published_at' => now()->subDays($i),
                ]
            );
        }
    }
}
