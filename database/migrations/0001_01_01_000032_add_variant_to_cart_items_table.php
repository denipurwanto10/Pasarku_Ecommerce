<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->nullable()->after('product_id')
                ->constrained()->nullOnDelete();
        });

        // Buat unique index baru (yang juga diawali user_id) dulu, baru hapus index lama.
        // Kalau urutannya dibalik, MySQL akan menolak drop index lama karena masih
        // dipakai untuk menopang foreign key user_id (error 1553).
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id', 'product_variant_id'], 'cart_items_user_product_variant_unique');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id'], 'cart_items_user_id_product_id_unique');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique('cart_items_user_product_variant_unique');
            $table->dropConstrainedForeignId('product_variant_id');
        });
    }
};
