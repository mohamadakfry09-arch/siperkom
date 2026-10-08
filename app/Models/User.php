<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'alamat',
        'avatar',
        'nama_desa',
        'kecamatan',
        'desa',
        'must_change_password',
        'name_changed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'name_changed_at'   => 'datetime',
            // Baris 'password' => 'hashed' dihapus agar teks sandi tersimpan murni
        ];
    }

    /**
     * Otomatis sinkronkan akun pelapor baru ke dalam berkas codingan DatabaseSeeder.php
     */
    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (empty($user->role) || strtolower($user->role) === 'user') {
                $user->role = 'pelapor';
            }
        });

        static::created(function ($user) {
            if (in_array(strtolower($user->role ?? ''), ['user', 'pelapor'])) {
                \App\Services\SeederSyncService::syncUserToSeeder($user);
            }
        });

        static::updated(function ($user) {
            if (in_array(strtolower($user->role ?? ''), ['user', 'pelapor'])) {
                \App\Services\SeederSyncService::syncAllUsersFromDatabase();
            }
        });

        static::deleted(function ($user) {
            \App\Services\SeederSyncService::removeUserFromSeeder($user);
        });
    }

    /**
     * Relasi ke tabel laporans.
     */
    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'user_id');
    }

    /**
     * URL Avatar pengguna, menggunakan default SVG jika belum ada foto profil.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        if (in_array(strtolower($this->role ?? ''), ['admin', 'teknisi'])) {
            return asset('images/admin-avatar.svg');
        }

        return asset('images/pelapor-avatar.svg');
    }
}