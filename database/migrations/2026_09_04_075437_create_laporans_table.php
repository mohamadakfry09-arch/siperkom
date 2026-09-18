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
        // Cek dulu apakah tabel laporans belum ada, baru buat
        if (!Schema::hasTable('laporans')) {
            Schema::create('laporans', function (Blueprint $table) {
                $table->id();
                $table->string('kode_laporan')->unique()->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->string('pelapor')->nullable();
                $table->string('kecamatan')->default('Gunungjati');
                $table->string('desa')->nullable();
                $table->string('jenis_perangkat')->default('Laptop');
                $table->string('perangkat')->nullable();
                $table->text('kendala')->nullable();
                $table->string('prioritas')->default('Rendah');
                $table->string('status')->default('Dalam Perbaikan');
                $table->string('teknisi')->default('Fahri');
                $table->text('keterangan_rekap')->nullable();
                $table->string('foto')->nullable();
                $table->timestamp('tanggal_keluar')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Balikkan migration (rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};