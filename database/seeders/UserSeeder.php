<?php



namespace Database\Seeders;



use App\Models\User;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;



class UserSeeder extends Seeder

{

    public function run(): void

    {

        User::updateOrCreate(

            ['email' => 'admin@fitshop.lt'],

            [

                'name' => 'Admin',

                'password' => Hash::make('admin123'),

                'is_admin' => true,

                'email_verified_at' => now(),

            ]

        );



        User::updateOrCreate(

            ['email' => 'user@fitshop.lt'],

            [

                'name' => 'Demo User',

                'password' => Hash::make('user1234'),

                'is_admin' => false,

                'email_verified_at' => now(),

                'phone' => '+37060000000',

                'address' => 'Gedimino pr. 1',

                'city' => 'Vilnius',

                'zip' => 'LT-01103',

                'country' => 'Lithuania',

                'gender' => 'male',

                'height_cm' => 180,

                'weight_kg' => 80,

            ]

        );



        // Papildomi demo naudotojai — naudojami atsiliepimų seederio.

        $demo = [

            ['Tomas K.', 'tomas@example.com'],

            ['Rūta M.', 'ruta@example.com'],

            ['Andrius B.', 'andrius@example.com'],

            ['Laura P.', 'laura@example.com'],

            ['Martynas S.', 'martynas@example.com'],

            ['Greta L.', 'greta@example.com'],

            ['Karolis V.', 'karolis@example.com'],

            ['Ieva D.', 'ieva@example.com'],

        ];

        foreach ($demo as [$name, $email]) {

            User::updateOrCreate(

                ['email' => $email],

                ['name' => $name, 'password' => Hash::make('password'), 'is_admin' => false, 'email_verified_at' => now()]

            );

        }

    }

}

