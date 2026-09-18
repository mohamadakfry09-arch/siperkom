<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('kode_laporan')->unique()->nullable();
            $table->string('nama_pelapor')->nullable();
            $table->string('jenis_perangkat');
            $table->string('perangkat');
            $table->text('kendala');
            $table->enum('status', ['Dalam Perbaikan', 'Menunggu Part', 'Selesai', 'Ditolak'])->default('Dalam Perbaikan');
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi'])->default('Sedang');
            $table->string('teknisi')->nullable();
            $table->text('history')->nullable();
            $table->string('foto')->nullable();
            $table->string('dokumen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};