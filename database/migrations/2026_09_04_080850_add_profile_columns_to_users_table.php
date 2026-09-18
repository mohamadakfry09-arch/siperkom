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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('email');
            }
            if (!Schema::hasColumn('users', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'desa')) {
                $table->string('desa')->nullable()->after('kecamatan');
            }
        });
    }

    /**
     * Balikkan migration (rollback).
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'kecamatan', 'desa']);
        });
    }
};