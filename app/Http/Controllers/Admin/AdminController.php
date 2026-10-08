<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        try {
            $totalLaporan = Laporan::query()->count('id');
            $laporanDalamPerbaikan = Laporan::query()->where('status', 'Dalam Perbaikan')->count();
            $laporanMenungguPart = Laporan::query()->where('status', 'Menunggu Part')->count();
            $laporanSelesai = Laporan::query()->where('status', 'Selesai')->count();
            $laporanDitolak = Laporan::query()->where('status', 'Ditolak')->count();

            $latestReports = Laporan::with('user')->latest()->take(5)->get();

            return view('admin.dashboard', compact(
                'totalLaporan',
                'laporanDalamPerbaikan',
                'laporanMenungguPart',
                'laporanSelesai',
                'laporanDitolak',
                'latestReports'
            ));
        } catch (Exception $e) {
            Log::error('Gagal memuat Dashboard Admin: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat halaman dashboard.');
        }
    }

    public function rekap(Request $request)
    {
        try {
            $bulanPilih = $request->input('bulan', date('m'));
            $tahunPilih = $request->input('tahun', date('Y'));

            // Ambil seluruh data laporan beserta relasi user
            $laporans = Laporan::with('user')->latest()->get();

            // Cache data user ke memory untuk lookup cepat jika foreign key user_id tidak cocok
            $allUsers = User::all();
            $usersById = $allUsers->keyBy('id');
            $usersByName = $allUsers->keyBy(function ($u) {
                return strtolower(trim($u->name));
            });

            // Kamus otomatis pemetaan Desa / Nama ke Kecamatan di Cirebon
            $petaKecamatan = [
                'gunungjati'   => 'Gunungjati',
                'bayan'        => 'Gunungjati',
                'klayan'       => 'Gunungjati',
                'mertasinga'   => 'Gunungjati',
                'jadimulya'    => 'Gunungjati',
                'pasindangan'  => 'Gunungjati',
                'arjawinangun' => 'Arjawinangun',
                'jungjang'     => 'Arjawinangun',
                'tegalgubug'   => 'Arjawinangun',
                'karangampel'  => 'Kedawung',
                'tuparev'      => 'Kedawung',
                'kedawung'     => 'Kedawung',
                'sumber'       => 'Sumber',
                'weru'         => 'Weru',
                'plumbon'      => 'Plumbon',
                'klangenan'    => 'Klangenan',
                'palimanan'    => 'Palimanan',
                'fatan'        => 'Gunungjati', // Auto-resolve otomatis khusus user fatan
            ];

            // Mapping data lengkap agar terbaca presisi di view Alpine.js / Blade secara real-time
            $defaultReports = $laporans->map(function ($item) use ($usersById, $usersByName, $petaKecamatan) {
                $kecamatan = null;

                // 1. Cek dari kolom kecamatan langsung di tabel laporans
                if (!empty($item->kecamatan) && trim($item->kecamatan) !== '-' && strtolower(trim($item->kecamatan)) !== 'belum diatur') {
                    $kecamatan = trim($item->kecamatan);
                }

                // 2. Cek dari relasi user aktif jika ada
                if (!$kecamatan && $item->user && !empty($item->user->kecamatan) && trim($item->user->kecamatan) !== '-' && strtolower(trim($item->user->kecamatan)) !== 'belum diatur') {
                    $kecamatan = trim($item->user->kecamatan);
                }

                // 3. Fallback pencarian user berdasarkan user_id jika relasi Eloquent tidak ter-bind
                if (!$kecamatan && !empty($item->user_id) && isset($usersById[$item->user_id])) {
                    $u = $usersById[$item->user_id];
                    if (!empty($u->kecamatan) && trim($u->kecamatan) !== '-' && strtolower(trim($u->kecamatan)) !== 'belum diatur') {
                        $kecamatan = trim($u->kecamatan);
                    }
                }

                // 4. Fallback pencarian user berdasarkan nama pelapor
                if (!$kecamatan) {
                    $rawName = strtolower(trim($item->pelapor ?? optional($item->user)->name ?? ''));
                    if ($rawName && isset($usersByName[$rawName])) {
                        $u = $usersByName[$rawName];
                        if (!empty($u->kecamatan) && trim($u->kecamatan) !== '-' && strtolower(trim($u->kecamatan)) !== 'belum diatur') {
                            $kecamatan = trim($u->kecamatan);
                        }
                    }
                }

                // 5. Cek kamus otomatis pemetaan ($petaKecamatan) berdasarkan nama pelapor atau desa
                if (!$kecamatan) {
                    $rawPelapor = strtolower(trim($item->pelapor ?? optional($item->user)->name ?? ''));
                    $rawDesa    = strtolower(trim($item->desa ?? optional($item->user)->desa ?? ''));

                    foreach ($petaKecamatan as $kunci => $namaKec) {
                        if (($rawPelapor && str_contains($rawPelapor, $kunci)) || ($rawDesa && str_contains($rawDesa, $kunci))) {
                            $kecamatan = $namaKec;
                            break;
                        }
                    }
                }

                // 6. Fallback jika ada data desa atau gunakan default Gunungjati
                $kecamatanFinal = $kecamatan 
                    ?? (!empty($item->desa) && trim($item->desa) !== '-' ? trim($item->desa) : null)
                    ?? (!empty(optional($item->user)->desa) && trim($item->user->desa) !== '-' ? trim($item->user->desa) : null)
                    ?? 'Gunungjati';

                $desaFinal = optional($item->user)->nama_desa
                    ?: (optional($item->user)->desa
                    ?: (!empty($item->desa) && trim($item->desa) !== '-' ? trim($item->desa) : '-'));

                $userTarget = $item->user ?? ($usersById[$item->user_id] ?? null);
                $alamatFinal = (!empty($item->alamat) && $item->alamat !== '-')
                    ? $item->alamat
                    : ((!empty(optional($userTarget)->alamat) && optional($userTarget)->alamat !== '-')
                    ? optional($userTarget)->alamat
                    : (!empty($kecamatanFinal) && $kecamatanFinal !== '-' ? $kecamatanFinal : 'cirebon'));

                return [
                    'db_id'            => $item->id,
                    'id'               => $item->kode_laporan ?? 'REP-' . $item->id,
                    'tanggal'          => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                    'tanggal_keluar'   => $item->updated_at ? $item->updated_at->format('d M Y, H:i') : '-',
                    'pelapor'          => $item->user->name ?? $item->pelapor ?? 'Masyarakat',
                    'alamat'           => $alamatFinal,
                    'kecamatan'        => $kecamatanFinal,
                    'desa'             => $desaFinal,
                    'jenis_perangkat'  => $item->jenis_perangkat ?? '-',
                    'perangkat'        => $item->perangkat ?? '-',
                    'kendala'          => $item->kendala ?? '-',
                    'foto'             => $item->foto ?? null,
                    'teknisi'          => $item->teknisi ?? 'Fahry Rizky Prayogi',
                    'status'           => $item->status ?? $item->Status ?? 'Dalam Perbaikan',
                    'keterangan_rekap' => $item->keterangan_rekap ?? $item->keterangan ?? '-'
                ];
            })->toArray();

            return view('admin.rekap', compact('defaultReports', 'bulanPilih', 'tahunPilih'));
        } catch (Exception $e) {
            Log::error('Gagal memuat Halaman Rekap: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data rekap laporan.');
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $bulan = $request->input('bulan', date('m'));
            $tahun = $request->input('tahun', date('Y'));

            $query = Laporan::with('user')->latest();

            if (!empty($bulan) && !empty($tahun)) {
                $query->whereYear('created_at', '=', $tahun)
                    ->whereMonth('created_at', '=', $bulan);
            }

            $laporans = $query->get();
            $namaFile = 'rekap-laporan-siperkom-' . $tahun . '-' . $bulan . '.xls';

            return response()->view('admin.rekap_excel', compact('laporans', 'bulan', 'tahun'), 200, [
                'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $namaFile . '"',
            ]);
        } catch (Exception $e) {
            Log::error('Gagal Ekspor Excel Admin: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunduh file Excel.');
        }
    }

    public function manajemenPelapor(Request $request)
    {
        try {
            return view('admin.manajemen');
        } catch (Exception $e) {
            Log::error('Gagal memuat Manajemen Pelapor: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan pada halaman manajemen pelapor.');
        }
    }
}