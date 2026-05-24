<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('how_to')->nullable();
            $table->json('benefits')->nullable();
            $table->json('muscle_groups')->nullable();
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->timestamps();
        });

        Schema::create('sport_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->enum('goal', ['strength', 'hypertrophy', 'endurance', 'weight_loss', 'general'])->default('general');
            $table->integer('duration_weeks')->default(4);
            $table->integer('days_per_week')->default(3);
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sport_plan_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('sport_plans')->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained()->cascadeOnDelete();
            $table->integer('day')->default(1);
            $table->integer('sets')->default(3);
            $table->string('reps')->default('10');
            $table->integer('rest_seconds')->default(60);
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sport_plan_exercises');
        Schema::dropIfExists('sport_plans');
        Schema::dropIfExists('exercises');
        Schema::dropIfExists('sports');
    }
};
