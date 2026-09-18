<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            if (!Schema::hasColumn('laporans', 'kecamatan')) {
                $table->string('kecamatan')->default('Gunungjati')->after('user_id');
            }
            if (!Schema::hasColumn('laporans', 'desa')) {
                $table->string('desa')->nullable()->after('kecamatan');
            }
        });
    }

    /**
     * Balikkan migration (rollback).
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn(['kecamatan', 'desa']);
        });
    }
};