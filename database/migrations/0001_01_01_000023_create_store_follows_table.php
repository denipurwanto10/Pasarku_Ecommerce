<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_follows', function (Blueprint $table) {
            $table->id();
            // user_id = buyer yang mengikuti, seller_id = pemilik toko yang diikuti
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'seller_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_follows');
    }
};
