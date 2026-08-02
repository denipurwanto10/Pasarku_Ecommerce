<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('moderation_status', ['approved', 'rejected'])->default('approved')->after('is_active');
            $table->text('moderation_note')->nullable()->after('moderation_status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['moderation_status', 'moderation_note']);
        });
    }
};
