<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('address_id')->nullable()->after('buyer_id')->constrained('addresses')->nullOnDelete();
            $table->unsignedBigInteger('shipping_amount')->default(0)->after('discount_amount');
            $table->json('shipping_breakdown')->nullable()->after('shipping_amount');
            $table->string('shipping_city')->nullable()->after('shipping_phone');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('address_id');
            $table->dropColumn(['shipping_amount', 'shipping_breakdown', 'shipping_city']);
        });
    }
};
