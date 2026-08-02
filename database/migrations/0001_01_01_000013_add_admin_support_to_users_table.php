<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Perluas kolom role agar mendukung 'admin' tanpa mengubah data yang sudah ada.
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('buyer','seller','admin') NOT NULL DEFAULT 'buyer'");
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('buyer','seller','admin'))");
        }
        // Untuk SQLite, constraint enum lama tetap ada tapi tidak menghalangi karena validasi
        // nilai 'admin' sudah dijaga penuh di level aplikasi (form request / Registrasi tidak
        // mengizinkan publik memilih role admin).

        Schema::table('users', function (Blueprint $table) {
            $table->enum('store_status', ['pending', 'approved', 'suspended'])->default('approved')->after('role');
            $table->boolean('is_suspended')->default(false)->after('store_status');
            $table->text('store_status_note')->nullable()->after('is_suspended');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['store_status', 'is_suspended', 'store_status_note']);
        });
    }
};
