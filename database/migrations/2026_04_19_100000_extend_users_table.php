<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
            $table->string('phone')->nullable()->after('is_admin');
            $table->string('address')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address');
            $table->string('country')->nullable()->after('city');
            $table->string('zip')->nullable()->after('country');
            $table->date('birthdate')->nullable()->after('zip');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birthdate');
            $table->decimal('height_cm', 5, 2)->nullable()->after('gender');
            $table->decimal('weight_kg', 5, 2)->nullable()->after('height_cm');
            $table->string('avatar')->nullable()->after('weight_kg');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_admin', 'phone', 'address', 'city', 'country', 'zip',
                'birthdate', 'gender', 'height_cm', 'weight_kg', 'avatar',
            ]);
        });
    }
};
