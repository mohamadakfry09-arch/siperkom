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
            if (!Schema::hasColumn('laporans', 'keterangan_rekap')) {
                $table->text('keterangan_rekap')->nullable()->after('teknisi');
            }
        });
    }

    /**
     * Balikkan migration (rollback).
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn('keterangan_rekap');
        });
    }
};