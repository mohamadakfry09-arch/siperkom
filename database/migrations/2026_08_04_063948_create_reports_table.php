<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_id')->unique(); // ID unik laporan, cth: #REP-2026-001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Relasi ke tabel users (pelapor)
            $table->string('jenis'); // Jenis perangkat (PC, Laptop, Printer, dll)
            $table->string('perangkat'); // Nama/merk perangkat
            $table->text('kendala'); // Detail kendala/kerusakan
            $table->string('prioritas')->default('Sedang'); // Tingkat urgensi
            $table->string('status')->default('Dalam Perbaikan'); // Status laporan
            $table->string('teknisi')->nullable(); // Nama teknisi yang bertugas
            $table->date('tanggal'); // Tanggal laporan dibuat
            $table->string('foto')->nullable(); // Path file foto kerusakan
            $table->string('dokumen')->nullable(); // Path file dokumen PDF
            $table->string('dokumen_nama')->nullable(); // Nama asli dokumen PDF
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};  