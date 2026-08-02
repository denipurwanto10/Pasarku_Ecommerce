<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('flash_sale_stock')->nullable()->after('discount_ends_at');
            $table->unsignedInteger('flash_sale_sold')->default(0)->after('flash_sale_stock');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['flash_sale_stock', 'flash_sale_sold']);
        });
    }
};
