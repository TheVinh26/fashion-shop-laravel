<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {

            $table->dropForeign(['cart_id']);
            $table->dropForeign(['product_id']);

            $table->dropUnique('cart_items_cart_id_product_id_unique');

            $table->unique(['cart_id', 'product_id', 'size'], 'cart_items_unique_with_size');

            $table->foreign('cart_id')
                  ->references('id')->on('carts')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {

            $table->dropForeign(['cart_id']);
            $table->dropForeign(['product_id']);

            $table->dropUnique('cart_items_unique_with_size');

            $table->unique(['cart_id', 'product_id']);

            $table->foreign('cart_id')
                  ->references('id')->on('carts')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');
        });
    }
};

