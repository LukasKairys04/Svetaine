<?php

use Database\Seeders\CategorySeeder;
use Database\Seeders\ExerciseSeeder;
use Database\Seeders\NewsSeeder;
use Database\Seeders\NutritionSeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\PromoCodeSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\SportPlansSeeder;
use Database\Seeders\SportSeeder;
use Database\Seeders\SupportMessageSeeder;
use Database\Seeders\TestimonialSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            ReviewSeeder::class,
            TestimonialSeeder::class,
            SupportMessageSeeder::class,
            PromoCodeSeeder::class,
            NutritionSeeder::class,
            SportSeeder::class,
            ExerciseSeeder::class,
            SportPlansSeeder::class,
            NewsSeeder::class,
        ] as $seeder) {
            Artisan::call('db:seed', [
                '--class' => $seeder,
                '--force' => true,
            ]);
        }
    }

    public function down(): void
    {
    }
};
