<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporans';

    // Izinkan semua kolom diisi secara massal
    protected $guarded = [];

    /**
     * Relasi ke tabel users.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Booted method untuk sinkronisasi otomatis ke DatabaseSeeder.php
     */
    protected static function booted()
    {
        static::saved(function ($laporan) {
            \App\Services\SeederSyncService::syncAllReportsFromDatabase();
        });

        static::deleted(function ($laporan) {
            \App\Services\SeederSyncService::syncAllReportsFromDatabase();
        });
    }
}