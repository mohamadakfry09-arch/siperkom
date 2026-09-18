<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Exception;

/**
 * =========================================================================
 * KELAS DashboardController (SIPERKOM Kabupaten Cirebon - Pelapor Edition)
 * =========================================================================
 * Controller utama untuk mengelola halaman dashboard pelapor atau warga 
 * dalam memantau status laporan kerusakan perangkat.
 * 
 * @package App\Http\Controllers
 * @author Tim Pengembang SIPERKOM Kabupaten Cirebon
 * @version 2026.2.1-CleanDashboardCore
 * @copyright 2026 Pemerintah Kabupaten Cirebon
 */
class DashboardController extends Controller
{
    /**
     * Helper standardisasi format data laporan untuk konsistensi frontend Pelapor.
     */
    private function formatReportItem($item, $user = null): array
    {
        $dbId = $item->id;
        $kode = $item->kode_laporan ?? ('#CRB-' . str_pad($dbId, 3, '0', STR_PAD_LEFT));

        // Foto URL: handle URL absolut atau path storage lokal
        $fotoUrl = null;
        if ($item->foto) {
            $fotoUrl = filter_var($item->foto, FILTER_VALIDATE_URL) ? $item->foto : asset('storage/' . $item->foto);
        }

        // Dokumen: nama berkas dan URL
        $dokumenNama = null;
        $dokumenUrl = null;
        if ($item->dokumen) {
            $dokumenNama = basename($item->dokumen);
            $dokumenUrl = filter_var($item->dokumen, FILTER_VALIDATE_URL) ? $item->dokumen : asset('storage/' . $item->dokumen);
        }

        $userObj = $item->user ?? $user;
        $alamat = (!empty(optional($userObj)->alamat) && optional($userObj)->alamat !== '-')
            ? optional($userObj)->alamat
            : ((!empty($item->alamat) && $item->alamat !== '-')
            ? $item->alamat
            : ((!empty(optional($userObj)->kecamatan) && optional($userObj)->kecamatan !== '-')
            ? optional($userObj)->kecamatan
            : ((!empty($item->kecamatan) && $item->kecamatan !== '-')
            ? $item->kecamatan
            : 'cirebon')));
        $desa = optional($userObj)->nama_desa ?: (optional($userObj)->desa ?: ($item->desa ?? 'Cirebon'));
        if ($desa === '-') $desa = 'Cirebon';
        $kecamatan = $alamat;
        $pelapor = $item->pelapor ?: (optional($userObj)->name ?: ($user ? $user->name : 'Pelapor'));

        $tanggalMasuk = $item->created_at ? $item->created_at->format('Y-m-d') : date('Y-m-d');
        $tanggalKeluar = $item->tanggal_keluar ? (is_string($item->tanggal_keluar) ? substr($item->tanggal_keluar, 0, 10) : $item->tanggal_keluar->format('Y-m-d')) : ($item->updated_at ? $item->updated_at->format('Y-m-d') : '-');

        return [
            'id'               => $kode,
            'db_id'            => $dbId,
            'kode_laporan'     => $kode,
            'perangkat'        => $item->perangkat ?? '-',
            'jenis_perangkat'  => $item->jenis_perangkat ?? $item->kategori ?? null,
            'kendala'          => $item->kendala ?? '-',
            'catatan'          => $item->keterangan_rekap ?? $item->catatan ?? $item->keterangan ?? null,
            'keterangan_rekap' => $item->keterangan_rekap ?? $item->catatan ?? $item->keterangan ?? '-',
            'created_at'       => $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : null,
            'tanggal'          => $tanggalMasuk,
            'tanggal_masuk'    => $tanggalMasuk,
            'tanggal_keluar'   => $tanggalKeluar,
            'updated_at'       => $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : null,
            'teknisi'          => $item->teknisi ?? 'Fahri',
            'status'           => $item->status ?? 'Dalam Perbaikan',
            'prioritas'        => $item->prioritas ?? 'Sedang',
            'pelapor'          => $pelapor,
            'desa'             => $desa,
            'alamat'           => $alamat,
            'kecamatan'        => $alamat,
            'foto'             => $fotoUrl,
            'dokumen'          => $dokumenNama,
            'dokumen_url'      => $dokumenUrl,
        ];
    }

    /**
     * Menampilkan Dashboard utama untuk user/pelapor.
     * 
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            // Ambil seluruh laporan milik user yang sedang login agar banner & antrean akurat
            $laporans = Laporan::query()->with('user')
                ->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('pelapor', $user->name);
                })
                ->latest()
                ->get();

            // Hitung laporan aktif untuk anti-spam (Maksimal 2 laporan aktif)
            $activeReportsCount = $laporans
                ->whereNotIn('status', ['Selesai', 'Ditolak'])
                ->count();

            // Format data laporan untuk frontend dan notifikasi pelapor
            $defaultReports = $laporans->map(fn($item) => $this->formatReportItem($item, $user))->toArray();
            $reportsData = $defaultReports;

            return view('pelapor.dashboard', compact('laporans', 'defaultReports', 'reportsData', 'activeReportsCount'));

        } catch (Exception $e) {
            Log::error('Kesalahan pada DashboardController index: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->with('error', 'Gagal memuat dashboard pelapor.');
        }
    }

    /**
     * Menampilkan halaman riwayat laporan milik pelapor (yang masih aktif/proses).
     * 
     * @return View|RedirectResponse
     */
    public function riwayat(): View|RedirectResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            // Ambil data riwayat laporan yang masih aktif / proses
            $laporans = Laporan::query()->with('user')
                ->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('pelapor', $user->name);
                })
                ->whereNotIn('status', ['Selesai', 'Ditolak'])
                ->latest()
                ->paginate(15);

            // Ambil seluruh laporan untuk sinkronisasi notifikasi lonceng secara instan
            $allReports = Laporan::query()->with('user')
                ->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('pelapor', $user->name);
                })
                ->latest()
                ->get();

            $reportsData = $allReports->map(fn($item) => $this->formatReportItem($item, $user))->values()->toArray();
            $defaultReports = $reportsData;

            return view('pelapor.riwayat', compact('laporans', 'allReports', 'reportsData', 'defaultReports'));

        } catch (Exception $e) {
            Log::error('Kesalahan pada DashboardController riwayat: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->with('error', 'Gagal memuat riwayat laporan.');
        }
    }

    /**
     * Menampilkan halaman rekap laporan milik pelapor (yang sudah Selesai / Ditolak).
     * 
     * @return View|RedirectResponse
     */
    public function rekap(): View|RedirectResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            // Ambil data laporan final milik pelapor (Selesai & Ditolak)
            $laporans = Laporan::query()->with('user')
                ->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('pelapor', $user->name);
                })
                ->whereIn('status', ['Selesai', 'Ditolak'])
                ->latest()
                ->paginate(15);

            // Ambil seluruh laporan pelapor untuk sinkronisasi arsip & lonceng instan
            $allReports = Laporan::query()->with('user')
                ->where(function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('pelapor', $user->name);
                })
                ->latest()
                ->get();

            $reportsData = $allReports->map(fn($item) => $this->formatReportItem($item, $user))->values()->toArray();
            $defaultReports = $reportsData;

            return view('pelapor.rekap', compact('laporans', 'allReports', 'reportsData', 'defaultReports'));

        } catch (Exception $e) {
            Log::error('Kesalahan pada DashboardController rekap: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->with('error', 'Gagal memuat rekap laporan.');
        }
    }

    public function exportExcel(Request $request)
    {
        return $this->exportFinalReports($request, 'excel');
    }

    public function exportPdf(Request $request)
    {
        return $this->exportFinalReports($request, 'pdf');
    }

    private function exportFinalReports(Request $request, string $format)
    {
        $user = Auth::user();
        $bulan = str_pad((string) $request->input('bulan', date('m')), 2, '0', STR_PAD_LEFT);
        $tahun = (string) $request->input('tahun', date('Y'));
        $laporans = Laporan::with('user')
            ->where('user_id', $user->id)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->whereIn('status', ['Selesai', 'Ditolak'])
            ->latest()
            ->get();

        $headers = ['ID Laporan', 'Tgl Masuk', 'Tgl Selesai / Rekap', 'Pelapor', 'Alamat', 'Kategori', 'Nama / Tipe Unit', 'Detail Kendala', 'Teknisi', 'Status Rekap', 'Keterangan'];
        $html = '<html><head><meta charset="UTF-8"><style>body{font-family:Arial;font-size:9pt}h2{text-align:center;color:#072544}table{border-collapse:collapse;width:100%}th{background:#0B3D6E;color:#fff;padding:7px;border:1px solid #072544}td{padding:6px;border:1px solid #d3d3d3}.selesai{background:#D1E7DD;color:#0F5132}.ditolak{background:#F8D7DA;color:#842029}.center{text-align:center}</style></head><body><h2>REKAP LAPORAN SIPERKOM</h2><p style="text-align:center">Periode: ' . $bulan . '-' . $tahun . '</p><table><thead><tr>';
        foreach ($headers as $header) {
            $html .= '<th>' . htmlspecialchars($header) . '</th>';
        }
        $html .= '</tr></thead><tbody>';

        foreach ($laporans as $laporan) {
            $status = trim((string) $laporan->status);
            $tanggalKeluar = $laporan->updated_at ? Carbon::parse($laporan->updated_at)->format('Y-m-d') : '-';
            $values = [
                $laporan->kode_laporan ?? '#REP-' . $laporan->id,
                optional($laporan->created_at)->format('Y-m-d') ?? '-',
                $tanggalKeluar,
                $laporan->pelapor ?? optional($laporan->user)->name ?? '-',
                $laporan->alamat ?? optional($laporan->user)->alamat ?? $laporan->kecamatan ?? optional($laporan->user)->kecamatan ?? '-',
                $laporan->jenis_perangkat ?? '-',
                $laporan->perangkat ?? '-',
                $laporan->kendala ?? '-',
                $laporan->teknisi ?? '-',
                $status,
                $laporan->keterangan_rekap ?? $laporan->catatan ?? '-',
            ];
            $html .= '<tr class="' . ($status === 'Selesai' ? 'selesai' : 'ditolak') . '">';
            foreach ($values as $value) {
                $html .= '<td>' . htmlspecialchars((string) $value) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table></body></html>';
        $filename = 'rekap-pelapor-' . $tahun . '-' . $bulan . ($format === 'excel' ? '.xls' : '.html');

        return response($html, 200, [
            'Content-Type' => $format === 'excel' ? 'application/vnd.ms-excel' : 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Endpoint Sinkronisasi Real-Time (Live Sync tanpa refresh) untuk Pelapor.
     */
    public function liveSync(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }

        $now = Carbon::now('Asia/Jakarta');

        $laporans = Laporan::query()->with('user')
            ->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('pelapor', $user->name);
            })
            ->latest()
            ->get();

        $reports = $laporans->map(fn($item) => $this->formatReportItem($item, $user))->values();
        $activeReportsCount = $laporans->whereNotIn('status', ['Selesai', 'Ditolak'])->count();

        return response()->json([
            'success'            => true,
            'reports'            => $reports,
            'activeReportsCount' => $activeReportsCount,
            'total'              => $laporans->count(),
            'timestamp'          => now()->timestamp
        ]);
    }
}