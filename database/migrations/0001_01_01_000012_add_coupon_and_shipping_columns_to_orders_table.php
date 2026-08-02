<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('coupon_code')->nullable()->after('payment_method');
            $table->unsignedBigInteger('discount_amount')->default(0)->after('total_amount');
            $table->string('courier')->nullable()->after('coupon_code');
            $table->string('tracking_number')->nullable()->after('courier');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['coupon_code', 'discount_amount', 'courier', 'tracking_number']);
        });
    }
};
