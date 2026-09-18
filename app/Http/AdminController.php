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
            $totalLaporan = Laporan::all()->count();
            $laporanDalamPerbaikan = Laporan::all()->where('status', 'Dalam Perbaikan')->count();
            $laporanMenungguPart = Laporan::all()->where('status', 'Menunggu Part')->count();
            $laporanSelesai = Laporan::all()->where('status', 'Selesai')->count();
            $laporanDitolak = Laporan::all()->where('status', 'Ditolak')->count();

            $latestReports = Laporan::with('user')->latest()->take(5)->get();

            // Notifikasi khusus Admin: Hanya laporan baru & antrean masuk dari pelapor
            $notifReports = Laporan::with('user')
                ->whereNotIn('status', ['Selesai', 'Ditolak'])
                ->latest()
                ->take(15)
                ->get()
                ->map(function($item) {
                    return [
                        'id'            => $item->kode_laporan ?? '#REP-' . $item->id,
                        'db_id'         => $item->id,
                        'pelapor'       => $item->user->name ?? $item->nama_pelapor ?? 'Staf Pelapor',
                        'desa'          => optional($item->user)->nama_desa ?: (optional($item->user)->desa ?: ($item->desa ?? '-')),
                        'kecamatan'     => optional($item->user)->kecamatan ?? $item->kecamatan ?? 'Belum diatur',
                        'perangkat'     => ($item->jenis_perangkat ?? 'Lainnya') . ' - ' . ($item->perangkat ?? '-'),
                        'kendala'       => $item->kendala ?? '-',
                        'status'        => $item->status ?? 'Dalam Perbaikan',
                        'prioritas'     => $item->prioritas ?? 'Sedang',
                        'teknisi'       => $item->teknisi ?? 'Fahri',
                        'tanggal_masuk' => $item->created_at ? $item->created_at->format('d/m/Y') : '-',
                    ];
                });

            return view('admin.dashboard', compact(
                'totalLaporan',
                'laporanDalamPerbaikan',
                'laporanMenungguPart',
                'laporanSelesai',
                'laporanDitolak',
                'latestReports',
                'notifReports'
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

            // Ambil data laporan beserta relasi user
            $laporans = Laporan::with('user')->latest()->get();

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
                'fatan'        => 'Gunungjati', // Auto-resolve otomatis khusus pelapor fatan
            ];

            $defaultReports = $laporans->map(function ($item) use ($petaKecamatan) {
                // 1. Cek isi kolom langsung dari tabel laporan
                $kec = !empty($item->kecamatan) && trim($item->kecamatan) !== '-' ? trim($item->kecamatan) : null;

                // 2. Cek dari relasi user
                if (!$kec && $item->user && !empty($item->user->kecamatan) && trim($item->user->kecamatan) !== '-') {
                    $kec = trim($item->user->kecamatan);
                }

                // 3. Deteksi otomatis dari nama desa atau nama pelapor jika belum ada
                if (!$kec) {
                    $desa = strtolower(trim($item->desa ?? optional($item->user)->desa ?? ''));
                    $pelapor = strtolower(trim($item->pelapor ?? optional($item->user)->name ?? ''));

                    foreach ($petaKecamatan as $key => $namaKec) {
                        if (($desa && str_contains($desa, $key)) || ($pelapor && str_contains($pelapor, $key))) {
                            $kec = $namaKec;
                            break;
                        }
                    }
                }

                // 4. Jika masih belum teridentifikasi, gunakan default Gunungjati
                $kecamatanFinal = $kec ?? 'Gunungjati';

                return [
                    'db_id'            => $item->id,
                    'id'               => $item->kode_laporan ?? 'REP-' . $item->id,
                    'tanggal'          => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                    'tanggal_keluar'   => $item->updated_at ? $item->updated_at->format('d M Y, H:i') : '-',
                    'pelapor'          => $item->user->name ?? $item->pelapor ?? 'Masyarakat',
                    'kecamatan'        => $kecamatanFinal,
                    'desa'             => $item->desa ?? optional($item->user)->desa ?? '-',
                    'jenis_perangkat'  => $item->jenis_perangkat ?? '-',
                    'perangkat'        => $item->perangkat ?? '-',
                    'kendala'          => $item->kendala ?? '-',
                    'foto'             => $item->foto ?? null,
                    'teknisi'          => $item->teknisi ?? 'Fahri',
                    'status'           => $item->status ?? 'Dalam Perbaikan',
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
                $query->whereYear('created_at', $tahun)
                    ->whereMonth('created_at', $bulan);
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