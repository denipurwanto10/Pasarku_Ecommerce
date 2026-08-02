<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('city')->nullable()->after('address');
            $table->enum('shipping_type', ['flat', 'per_city'])->default('flat')->after('store_banner');
            $table->unsignedBigInteger('shipping_flat_rate')->default(0)->after('shipping_type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['city', 'shipping_type', 'shipping_flat_rate']);
        });
    }
};
