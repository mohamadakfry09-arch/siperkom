<?php

namespace App\Services;

use App\Models\User;
use App\Models\Laporan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class SeederSyncService
{
    /**
     * Otomatis menyinkronkan seluruh akun pelapor dari database MySQL / phpMyAdmin ke dalam DatabaseSeeder.php.
     * 
     * Setiap akun yang ada di MySQL akan tercatat di DatabaseSeeder.php dengan sandi teks biasa murni.
     * Setiap akun yang dihapus dari MySQL (baik lewat aplikasi maupun langsung lewat phpMyAdmin)
     * akan OTOMATIS TERHAPUS dari codingan DatabaseSeeder.php!
     *
     * @return bool
     */
    public static function syncAllUsersFromDatabase(): bool
    {
        try {
            if (!Schema::hasTable('users')) {
                return false;
            }

            $seederPath = database_path('seeders/PelaporUserSeeder.php');
            if (!file_exists($seederPath)) {
                $initialContent = "<?php\n\nnamespace Database\Seeders;\n\nuse Illuminate\Database\Seeder;\nuse App\Models\User;\n\nclass PelaporUserSeeder extends Seeder\n{\n    public function run(): void\n    {\n        // [START_PELAPOR_SEEDERS]\n        // [END_PELAPOR_SEEDERS]\n    }\n}\n";
                file_put_contents($seederPath, $initialContent);
            }

            $content = file_get_contents($seederPath);
            if (!$content) {
                return false;
            }

            // Ambil seluruh akun pelapor dari MySQL
            $pelaporUsers = User::whereNotIn('role', ['admin', 'teknisi'])
                ->orderBy('id', 'asc')
                ->get();

            // Susun blok kode seeder untuk seluruh pelapor yang ada di database saat ini
            $snippets = [];
            foreach ($pelaporUsers as $u) {
                $email = trim($u->email);
                if (empty($email)) {
                    continue;
                }

                $name = addslashes($u->name ?? 'Pelapor');
                $roleVal = strtolower(trim((string)$u->role));
                $userAlamatVal = trim((string)($u->alamat ?? ''));
                if (empty($userAlamatVal) || $userAlamatVal === '-' || strtolower($userAlamatVal) === 'belum diatur') {
                    $userAlamatVal = (!empty($u->kecamatan) && $u->kecamatan !== '-') ? $u->kecamatan : 'cirebon';
                }
                $alamat = addslashes($userAlamatVal);
                
                $pwd = (string)($u->password ?? 'password');
                if (str_starts_with($pwd, '$2y$')) {
                    $pwd = 'password';
                }
                $pwdEscaped = addslashes($pwd);
                $avatar = (!empty($u->avatar)) ? "'" . addslashes((string)$u->avatar) . "'" : "null";

                $snippet = "        // Akun Pelapor: {$name}\n" .
                           "        User::updateOrCreate(\n" .
                           "            ['email' => '{$email}'],\n" .
                           "            [\n" .
                           "                'name' => '{$name}',\n" .
                           "                'password' => '{$pwdEscaped}',\n" .
                           "                'role' => '{$roleVal}',\n" .
                           "                'alamat' => '{$alamat}',\n" .
                           "                'avatar' => {$avatar},\n" .
                           "                'must_change_password' => false,\n" .
                           "            ]\n" .
                           "        );";
                $snippets[] = $snippet;
            }

            $userCodeBlock = empty($snippets) ? "" : implode("\n\n", $snippets) . "\n";

            $startTag = "// [START_PELAPOR_SEEDERS]";
            $endTag   = "// [END_PELAPOR_SEEDERS]";

            if (strpos($content, $startTag) !== false && strpos($content, $endTag) !== false) {
                $startPos = strpos($content, $startTag) + strlen($startTag);
                $endPos   = strpos($content, $endTag);

                $before = substr($content, 0, $startPos);
                $after  = substr($content, $endPos);

                $newContent = $before . "\n" . $userCodeBlock . "        " . $after;
            } else {
                $newContent = "<?php\n\nnamespace Database\Seeders;\n\nuse Illuminate\Database\Seeder;\nuse App\Models\User;\n\nclass PelaporUserSeeder extends Seeder\n{\n    public function run(): void\n    {\n        " . $startTag . "\n" . $userCodeBlock . "        " . $endTag . "\n    }\n}\n";
            }

            if ($newContent !== $content) {
                file_put_contents($seederPath, $newContent);
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Gagal syncAllUsersFromDatabase: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Menyinkronkan akun baru ke berkas seeder.
     */
    public static function syncUserToSeeder(User $user, ?string $plainPassword = null): bool
    {
        return self::syncAllUsersFromDatabase();
    }

    /**
     * Menghapus akun dari berkas seeder saat akun dihapus dari database.
     */
    public static function removeUserFromSeeder(mixed $userOrEmail = null): bool
    {
        return self::syncAllUsersFromDatabase();
    }

    /**
     * Otomatis menyinkronkan seluruh laporan dari database MySQL / phpMyAdmin ke dalam DatabaseSeeder.php.
     * 
     * Termasuk file dokumen dan foto kerusakan!
     * Setiap laporan yang ada di MySQL akan tercatat di DatabaseSeeder.php secara realtime.
     * Setiap laporan yang dihapus dari MySQL (baik lewat aplikasi maupun langsung lewat phpMyAdmin)
     * akan OTOMATIS TERHAPUS dari codingan DatabaseSeeder.php!
     *
     * @return bool
     */
    public static function syncAllReportsFromDatabase(): bool
    {
        try {
            if (!Schema::hasTable('laporans')) {
                return false;
            }

            $seederPath = database_path('seeders/DatabaseSeeder.php');
            if (!file_exists($seederPath)) {
                return false;
            }

            $content = file_get_contents($seederPath);
            if (!$content) {
                return false;
            }

            // Ambil seluruh laporan dari MySQL urut berdasarkan id
            $laporans = Laporan::with('user')
                ->orderBy('id', 'asc')
                ->get();

            $snippets = [];
            foreach ($laporans as $l) {
                $kode = addslashes((string)($l->kode_laporan ?? ('#REP-' . $l->id)));
                $pelapor = addslashes((string)($l->pelapor ?? ($l->user->name ?? 'Pelapor')));
                $userAlamat = optional($l->user)->alamat;
                $alamatVal = (!empty($l->alamat) && $l->alamat !== '-')
                    ? $l->alamat
                    : (!empty($userAlamat) && $userAlamat !== '-' ? $userAlamat : 'cirebon');
                $alamatEscaped = addslashes((string)$alamatVal);

                $kecamatanVal = (!empty($l->kecamatan) && $l->kecamatan !== '-') ? $l->kecamatan : (optional($l->user)->kecamatan ?: 'Cirebon');
                $kecamatan = addslashes((string)$kecamatanVal);

                $desaVal = (!empty($l->desa) && $l->desa !== '-') ? $l->desa : (optional($l->user)->nama_desa ?: (optional($l->user)->desa ?: 'Cirebon'));
                $desa = addslashes((string)$desaVal);

                $jenisPerangkat = addslashes((string)($l->jenis_perangkat ?? 'Perangkat IT'));
                $perangkat = addslashes((string)($l->perangkat ?? '-'));
                $kendala = addslashes((string)($l->kendala ?? '-'));
                $prioritas = addslashes((string)($l->prioritas ?? 'Sedang'));
                $status = addslashes((string)($l->status ?? 'Dalam Perbaikan'));
                $teknisi = addslashes((string)($l->teknisi ?? 'Fahry Rizky Prayogi'));

                $keteranganRekap = ($l->keterangan_rekap !== null && $l->keterangan_rekap !== '') 
                    ? "'" . addslashes((string)$l->keterangan_rekap) . "'" 
                    : "null";

                $foto = (!empty($l->foto)) 
                    ? "'" . addslashes((string)$l->foto) . "'" 
                    : "null";

                $dokumen = (!empty($l->dokumen)) 
                    ? "'" . addslashes((string)$l->dokumen) . "'" 
                    : "null";

                $tanggalKeluar = (!empty($l->tanggal_keluar)) 
                    ? "'" . addslashes((string)$l->tanggal_keluar) . "'" 
                    : "null";

                $userEmail = $l->user ? trim((string)$l->user->email) : null;
                if (!empty($userEmail)) {
                    $escapedEmail = addslashes($userEmail);
                    $userIdExpr = "optional(User::where('email', '{$escapedEmail}')->first())->id ?? " . ($l->user_id ? (int)$l->user_id : "optional(User::first())->id");
                } elseif ($l->user_id) {
                    $userIdExpr = "optional(User::find(" . (int)$l->user_id . "))->id ?? optional(User::first())->id";
                } else {
                    $userIdExpr = "optional(User::first())->id";
                }

                $snippet = "        // Laporan: {$kode} - {$pelapor}\n" .
                           "        Laporan::updateOrCreate(\n" .
                           "            ['kode_laporan' => '{$kode}'],\n" .
                           "            [\n" .
                           "                'user_id' => {$userIdExpr},\n" .
                           "                'pelapor' => '{$pelapor}',\n" .
                           "                'alamat' => '{$alamatEscaped}',\n" .
                           "                'kecamatan' => '{$kecamatan}',\n" .
                           "                'desa' => '{$desa}',\n" .
                           "                'jenis_perangkat' => '{$jenisPerangkat}',\n" .
                           "                'perangkat' => '{$perangkat}',\n" .
                           "                'kendala' => '{$kendala}',\n" .
                           "                'prioritas' => '{$prioritas}',\n" .
                           "                'status' => '{$status}',\n" .
                           "                'teknisi' => '{$teknisi}',\n" .
                           "                'keterangan_rekap' => {$keteranganRekap},\n" .
                           "                'foto' => {$foto},\n" .
                           "                'dokumen' => {$dokumen},\n" .
                           "                'tanggal_keluar' => {$tanggalKeluar},\n" .
                           "            ]\n" .
                           "        );";

                $snippets[] = $snippet;
            }

            $laporanCodeBlock = empty($snippets) ? "" : implode("\n\n", $snippets) . "\n";

            $startTag = "// [START_LAPORAN_SEEDERS]";
            $endTag   = "// [END_LAPORAN_SEEDERS]";

            if (strpos($content, $startTag) !== false && strpos($content, $endTag) !== false) {
                $startPos = strpos($content, $startTag) + strlen($startTag);
                $endPos   = strpos($content, $endTag);

                $before = substr($content, 0, $startPos);
                $after  = substr($content, $endPos);

                $newContent = $before . "\n" . $laporanCodeBlock . "        " . $after;
            } else {
                return false;
            }

            if ($newContent !== $content) {
                file_put_contents($seederPath, $newContent);
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning('Gagal syncAllReportsFromDatabase: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Menyinkronkan laporan ke berkas seeder saat laporan dibuat/diupdate.
     */
    public static function syncReportToSeeder(): bool
    {
        return self::syncAllReportsFromDatabase();
    }

    /**
     * Menghapus laporan dari berkas seeder saat laporan dihapus dari database.
     */
    public static function removeReportFromSeeder(): bool
    {
        return self::syncAllReportsFromDatabase();
    }

    /**
     * Menyinkronkan perubahan akun admin ke AdminUserSeeder.php
     */
    public static function syncAdminToSeeder(User $admin): bool
    {
        try {
            $seederPath = database_path('seeders/AdminUserSeeder.php');
            if (!file_exists($seederPath)) {
                return false;
            }

            $name = addslashes($admin->name ?? 'Administrator SIPERKOM');
            $alamat = addslashes($admin->alamat ?? 'Jl. Sunan Kalijaga No. 7, Sumber');
            $pwd = (string)($admin->password ?? 'cirebon2');
            if (str_starts_with($pwd, '$2y$')) {
                $pwd = 'cirebon2';
            }
            $pwdEscaped = addslashes($pwd);
            $avatar = (!empty($admin->avatar)) ? "'" . addslashes((string)$admin->avatar) . "'" : "'avatars/admin-avatar.svg'";
            $email = addslashes($admin->email ?? 'admin@cirebonkab.go.id');

            $newContent = "<?php\n\nnamespace Database\\Seeders;\n\nuse Illuminate\\Database\\Seeder;\nuse App\\Models\\User;\n\nclass AdminUserSeeder extends Seeder\n{\n    public function run(): void\n    {\n        // 1. Akun Administrator Sistem (Admin & Teknisi Menyatu)\n        User::updateOrCreate(\n            ['email' => '{$email}'],\n            [\n                'name' => '{$name}',\n                'password' => '{$pwdEscaped}',\n                'role' => 'admin',\n                'alamat' => '{$alamat}',\n                'avatar' => {$avatar},\n                'must_change_password' => false,\n            ]\n        );\n    }\n}\n";

            file_put_contents($seederPath, $newContent);
            return true;
        } catch (\Throwable $e) {
            Log::warning('Gagal syncAdminToSeeder: ' . $e->getMessage());
            return false;
        }
    }
}

