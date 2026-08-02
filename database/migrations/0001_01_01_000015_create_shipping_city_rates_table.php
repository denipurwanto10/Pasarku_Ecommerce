<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_city_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->string('city');
            $table->unsignedBigInteger('rate');
            $table->timestamps();

            $table->unique(['seller_id', 'city']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_city_rates');
    }
};
