<?php



namespace Database\Seeders;



use App\Models\Product;

use App\Models\Review;

use App\Models\User;

use Illuminate\Database\Seeder;



/**

 * ReviewSeeder — kiekvienam produktui sugeneruoja 3–6 realistiškus atsiliepimus

 * iš demo naudotojų rato. Reitingai svyruoja tarp 4 ir 5 žvaigždučių, kad

 * atitiktų vidutinį produkto rating'ą iš ProductSeeder.

 */

class ReviewSeeder extends Seeder

{

    /** Bendro pobūdžio atsiliepimų šablonai (su placeholder'u {name} jei norėsime vardu kreiptis). */

    protected array $templates = [

        ['Labai patiko — užsisakysiu dar.',                      'Veikia būtent taip, kaip aprašyta. Greitas pristatymas.'],

        ['Kaina / kokybė puiki.',                                 'Laukiau nuolaidos ir pagaliau sulaukiau. Labai patenkintas.'],

        ['Naudoju jau kelias savaites — rezultatas matosi.',      'Treniruojuosi 4× per savaitę, pagelbėjo atsistatyti.'],

        ['Skanu ir patogu.',                                      'Gerai tirpsta, nėra kruopelių. Rekomenduočiau.'],

        ['Patiko, bet tikėjausi kiek daugiau.',                   'Geras produktas, bet man per saldus. Antrą kartą rinkčiausi kitą skonį.'],

        ['Tvarkingai supakuota, greitai atvyko.',                 'Pakuotė nepažeista, viskas sandariai uždaryta.'],

        ['Kokybiškas produktas.',                                 'Gaminys atitinka aprašymą. Jokių pretenzijų.'],

        ['Rekomenduoju draugams.',                                'Pirmą kartą užsisakiau iš šios parduotuvės — neapsivyliau.'],

        ['Veikia.',                                               'Po mėnesio naudojimo jaučiu tikrą skirtumą.'],

        ['Gera kaina.',                                           'Pigiau nei kitose parduotuvėse. Ačiū!'],

    ];



    public function run(): void

    {

        Review::query()->delete();



        $userIds = User::where('is_admin', false)->pluck('id')->values()->all();

        if (count($userIds) < 2) {

            return;

        }



        foreach (Product::all() as $product) {

            $picked = collect($userIds)->shuffle()->take(2)->values();

            $ratings = [5, 4, 5, 4, 5];



            for ($i = 0; $i < 2; $i++) {

                [$title, $comment] = $this->templates[array_rand($this->templates)];

                $rating = $ratings[array_rand($ratings)];



                Review::create([

                    'product_id' => $product->id,

                    'user_id' => $picked[$i],

                    'rating' => $rating,

                    'title' => $title,

                    'comment' => $comment,

                    'approved' => true,

                    'created_at' => now()->subDays(rand(2, 120)),

                    'updated_at' => now(),

                ]);

            }



            $avgRating = (float) $product->reviews()->avg('rating');

            $product->update([

                'rating' => round($avgRating, 1),

                'rating_count' => 2,

            ]);

        }

    }

}

