<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['category_id', 'is_active']);
            $table->index(['is_active', 'price']);
            $table->index('brand');
            $table->index('rating');
            $table->index('stock');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('created_at');
            $table->index('order_number');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['product_id', 'user_id']);
            $table->index('product_id');
            $table->index('user_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index(['type', 'is_active']);
            $table->index('slug');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->index(['user_id', 'product_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id', 'is_active']);
            $table->dropIndex(['is_active', 'price']);
            $table->dropIndex('brand');
            $table->dropIndex('rating');
            $table->dropIndex('stock');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex('status');
            $table->dropIndex('created_at');
            $table->dropIndex('order_number');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'user_id']);
            $table->dropIndex('product_id');
            $table->dropIndex('user_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['type', 'is_active']);
            $table->dropIndex('slug');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'product_id']);
            $table->dropIndex('user_id');
        });
    }
};
