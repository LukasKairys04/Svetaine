<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfNotExists('products', ['category_id', 'is_active']);
        $this->addIndexIfNotExists('products', ['is_active', 'price']);
        $this->addIndexIfNotExists('products', 'brand');
        $this->addIndexIfNotExists('products', 'rating');
        $this->addIndexIfNotExists('products', 'stock');

        $this->addIndexIfNotExists('orders', ['user_id', 'status']);
        $this->addIndexIfNotExists('orders', 'status');
        $this->addIndexIfNotExists('orders', 'created_at');
        $this->addIndexIfNotExists('orders', 'order_number');

        $this->addIndexIfNotExists('reviews', ['product_id', 'user_id']);
        $this->addIndexIfNotExists('reviews', 'product_id');
        $this->addIndexIfNotExists('reviews', 'user_id');

        $this->addIndexIfNotExists('categories', ['type', 'is_active']);
        $this->addIndexIfNotExists('categories', 'slug');

        $this->addIndexIfNotExists('cart_items', ['user_id', 'product_id']);
        $this->addIndexIfNotExists('cart_items', 'user_id');
    }

    private function addIndexIfNotExists($table, $columns)
    {
        $indexName = is_array($columns)
            ? $table . '_' . implode('_', $columns) . '_index'
            : $table . '_' . $columns . '_index';

        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);

        if (empty($indexes)) {
            Schema::table($table, function (Blueprint $table) use ($columns) {
                $table->index($columns);
            });
        }
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
