<?php



namespace Database\Seeders;



use App\Models\Testimonial;

use Illuminate\Database\Seeder;



class TestimonialSeeder extends Seeder

{

    public function run(): void

    {

        $items = [

            ['Jonas', 5.0, 'Puikūs produktai, greitas pristatymas. Rekomenduoju!'],

            ['Gintarė', 4.8, 'Labai patiko planai ir baltymų kokybė.'],

            ['Marius', 4.5, 'Geros kainos, bus grįžtama.'],

        ];

        foreach ($items as $t) {

            Testimonial::updateOrCreate(

                ['name' => $t[0], 'content' => $t[2]],

                ['rating' => $t[1], 'approved' => true]

            );

        }

    }

}

