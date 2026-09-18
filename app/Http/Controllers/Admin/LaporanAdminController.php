<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LaporanAdminController extends Controller
{
    private function resolveAndFormatReports(Collection $reports): array
    {
        try {
            return $reports->map(function ($item) {
                $riwayatList = [];

                if (!empty($item->history)) {
                    $lines = explode("\n", $item->history);
                    foreach ($lines as $line) {
                        $riwayatList[] = [
                            'tanggal' => '',
                            'status'  => $item->status ?? 'Menunggu',
                            'catatan' => $line,
                            'foto'    => $item->foto ?? null,
                        ];
                    }
                } else {
                    $riwayatList[] = [
                        'tanggal' => $item->created_at
                            ? Carbon::parse($item->created_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i')
                            : Carbon::now()->setTimezone('Asia/Jakarta')->format('d M Y, H:i'),
                        'status'  => $item->status ?? 'Menunggu',
                        'catatan' => 'Laporan masuk dari sistem pelaporan desa terpadu.',
                        'foto'    => $item->foto ?? null,
                    ];
                }

                $merkPerangkat  = trim($item->perangkat ?? '');
                $jenisPerangkat = trim($item->jenis_perangkat ?? '');

                if (!empty($jenisPerangkat)) {
                    $formatPerangkat = !empty($merkPerangkat)
                        ? $jenisPerangkat . ' - ' . $merkPerangkat
                        : $jenisPerangkat;
                } else {
                    $formatPerangkat = !empty($merkPerangkat) ? $merkPerangkat : 'Perangkat Kantor Desa';
                }

                $namaPelapor = $item->nama_pelapor ?? ($item->user->name ?? 'Staf Pelapor');
                $alamatUser  = trim((string) ($item->user->alamat ?? $item->user->kecamatan ?? $item->alamat ?? $item->kecamatan ?? ''));
                $fotoUrl     = $item->foto
                    ? (filter_var($item->foto, FILTER_VALIDATE_URL) ? $item->foto : asset('storage/' . $item->foto))
                    : null;
                $dokumenUrl  = $item->dokumen
                    ? (filter_var($item->dokumen, FILTER_VALIDATE_URL) ? $item->dokumen : asset('storage/' . $item->dokumen))
                    : null;

                return [
                    'id'                => $item->kode_laporan ?? '#REP-2026-' . str_pad((string)$item->id, 3, '0', STR_PAD_LEFT),
                    'db_id'             => $item->id,
                    'tanggal_masuk'     => $item->created_at
                        ? Carbon::parse($item->created_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i')
                        : '-',
                    'tanggal_keluar'    => in_array($item->status, ['Selesai', 'Ditolak']) && $item->updated_at
                        ? Carbon::parse($item->updated_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i')
                        : '-',
                    'desa'              => optional($item->user)->nama_desa
                        ?: (optional($item->user)->desa
                        ?: ($item->desa ?? 'Kantor')),
                    'pelapor'           => $namaPelapor,
                    'pelapor_avatar'    => optional($item->user)->avatar ? (filter_var($item->user->avatar, FILTER_VALIDATE_URL) ? $item->user->avatar : asset('storage/' . $item->user->avatar)) : null,
                    'alamat'            => $alamatUser !== '' ? $alamatUser : 'Belum diatur',
                    'kecamatan'         => $alamatUser !== '' ? $alamatUser : 'Belum diatur',
                    'perangkat'         => $formatPerangkat,
                    'jenis_perangkat'   => $jenisPerangkat ?: 'Lainnya',
                    'kendala'           => $item->kendala ?? 'Tidak ada keterangan kendala kerusakan',
                    'status'            => $item->status ?? 'Dalam Perbaikan',
                    'prioritas'         => $item->prioritas ?? 'Sedang',
                    'teknisi'           => $item->teknisi ?? 'Belum ditugaskan',
                    'tanggal'           => $item->created_at
                        ? Carbon::parse($item->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d')
                        : date('Y-m-d'),
                    'foto'              => $fotoUrl,
                    'dokumen'           => $dokumenUrl,
                    'riwayat'           => $riwayatList,
                    'keterangan_rekap'  => $item->keterangan_rekap ?? '',
                ];
            })->toArray();
        } catch (Exception $e) {
            Log::error('Kesalahan kritis pada resolveAndFormatReports: ' . $e->getMessage());
            return [];
        }
    }

    private function getDaftarTeknisi(): array
    {
        try {
            $teknisi = User::all()
                ->whereIn('role', ['admin', 'teknisi'])
                ->pluck('name')
                ->values()
                ->toArray();

            if (empty($teknisi)) {
                return ['Fahri Ramadhan', 'Dedi Kurniawan', 'Agus Setiawan', 'Siti Marlina'];
            }

            return $teknisi;
        } catch (Exception $e) {
            Log::error('Gagal mengambil daftar teknisi: ' . $e->getMessage());
            return ['Fahri Ramadhan', 'Dedi Kurniawan', 'Agus Setiawan', 'Siti Marlina'];
        }
    }

    public function dashboardOverview(Request $request)
    {
        try {
            $bulanPilih = $request->input('bulan', Carbon::now('Asia/Jakarta')->format('m'));
            $tahunPilih = $request->input('tahun', Carbon::now('Asia/Jakarta')->format('Y'));

            $baseQuery = Laporan::query()->with('user')
                ->whereMonth('created_at', '=', $bulanPilih)
                ->whereYear('created_at', '=', $tahunPilih);

            $reportsQuery   = (clone $baseQuery)->orderBy('created_at', 'desc')->get();
            $defaultReports = $this->resolveAndFormatReports($reportsQuery);
            $daftarTeknisi  = $this->getDaftarTeknisi();

            $totalLaporan   = (clone $baseQuery)->count();
            $selesai        = (clone $baseQuery)->where('status', '=', 'Selesai')->count();
            $dalamPerbaikan = (clone $baseQuery)->where('status', '=', 'Dalam Perbaikan')->count();
            $menungguPart   = (clone $baseQuery)->where('status', '=', 'Menunggu Part')->count();
            $ditolak        = (clone $baseQuery)->where('status', '=', 'Ditolak')->count();

            $notifikasiLaporan = (clone $baseQuery)
                ->whereNotIn('status', ['Selesai', 'Ditolak'])
                ->orderBy('created_at', 'desc')
                ->get();

            $notifReports = $notifikasiLaporan->take(15)->map(function($item) {
                $alamatVal = trim((string) ($item->user->alamat ?? $item->user->kecamatan ?? $item->alamat ?? $item->kecamatan ?? ''));
                if (empty($alamatVal) || $alamatVal === '-' || strtolower($alamatVal) === 'belum diatur') {
                    $alamatVal = 'Cirebon';
                }

                return [
                    'id'            => $item->kode_laporan ?? '#REP-' . $item->id,
                    'db_id'         => $item->id,
                    'pelapor'       => $item->user->name ?? $item->nama_pelapor ?? 'Staf Pelapor',
                    'pelapor_avatar'=> optional($item->user)->avatar ? (filter_var($item->user->avatar, FILTER_VALIDATE_URL) ? $item->user->avatar : asset('storage/' . $item->user->avatar)) : null,
                    'alamat'        => $alamatVal,
                    'desa'          => optional($item->user)->nama_desa ?: (optional($item->user)->desa ?: ($item->desa ?? '-')),
                    'kecamatan'     => optional($item->user)->kecamatan ?? $item->kecamatan ?? 'Belum diatur',
                    'perangkat'     => ($item->jenis_perangkat ?? 'Lainnya') . ' - ' . ($item->perangkat ?? '-'),
                    'kendala'       => $item->kendala ?? '-',
                    'status'        => $item->status ?? 'Dalam Perbaikan',
                    'prioritas'     => $item->prioritas ?? 'Sedang',
                    'teknisi'       => $item->teknisi ?? 'Fahri',
                    'tanggal_masuk' => $item->created_at ? $item->created_at->format('d/m/Y') : '-',
                ];
            })->values()->toArray();

            return view('admin.dashboard', compact(
                'defaultReports',
                'daftarTeknisi',
                'totalLaporan',
                'selesai',
                'dalamPerbaikan',
                'menungguPart',
                'ditolak',
                'bulanPilih',
                'tahunPilih',
                'notifikasiLaporan',
                'notifReports'
            ));
        } catch (Exception $e) {
            Log::error('Gagal memuat Dashboard Admin: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function index(Request $request)
    {
        try {
            $bulanPilih = $request->input('bulan', Carbon::now('Asia/Jakarta')->format('m'));
            $tahunPilih = $request->input('tahun', Carbon::now('Asia/Jakarta')->format('Y'));

            $reportsQuery = Laporan::query()->with('user')
                ->whereMonth('created_at', '=', $bulanPilih)
                ->whereYear('created_at', '=', $tahunPilih)
                ->orderBy('created_at', 'desc')
                ->get();

            $defaultReports = $this->resolveAndFormatReports($reportsQuery);
            $daftarTeknisi  = $this->getDaftarTeknisi();

            return view('admin.antrean', compact('defaultReports', 'daftarTeknisi', 'bulanPilih', 'tahunPilih'));
        } catch (Exception $e) {
            Log::error('Gagal memuat Antrean: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function manajemen(Request $request)
    {
        try {
            $users = User::query()->orderBy('created_at', 'desc')->get();
            return view('admin.manajemen', compact('users'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman manajemen akun: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function resetPassword(int|string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->password = bcrypt('password123');
            $user->save();

            return response()->json(['success' => true, 'message' => 'Password pengguna berhasil direset menjadi password123.']);
        } catch (Exception $e) {
            Log::error('Gagal mereset password: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mereset password pengguna.'], 500);
        }
    }

    public function destroyUser(int|string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['success' => true, 'message' => 'Akun pelapor berhasil dihapus dari sistem.']);
        } catch (Exception $e) {
            Log::error('Gagal menghapus akun: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus akun pengguna.'], 500);
        }
    }

    public function persentase(Request $request)
    {
        try {
            $bulanPilih = $request->input('bulan', Carbon::now('Asia/Jakarta')->format('m'));
            $tahunPilih = $request->input('tahun', Carbon::now('Asia/Jakarta')->format('Y'));

            $baseQuery = Laporan::query()->with('user')
                ->whereMonth('created_at', '=', $bulanPilih)
                ->whereYear('created_at', '=', $tahunPilih);

            $allReports     = (clone $baseQuery)->orderBy('created_at', 'desc')->get();
            $defaultReports = $this->resolveAndFormatReports($allReports);
            $daftarTeknisi  = $this->getDaftarTeknisi();

            $totalLaporan   = (clone $baseQuery)->count();
            $selesai        = (clone $baseQuery)->where('status', '=', 'Selesai')->count();
            $dalamPerbaikan = (clone $baseQuery)->where('status', '=', 'Dalam Perbaikan')->count();
            $menungguPart   = (clone $baseQuery)->where('status', '=', 'Menunggu Part')->count();
            $ditolak        = (clone $baseQuery)->where('status', '=', 'Ditolak')->count();

            $persentaseSelesai         = $totalLaporan > 0 ? round(($selesai / $totalLaporan) * 100, 1) : 0;
            $persentaseDalamPerbaikan = $totalLaporan > 0 ? round(($dalamPerbaikan / $totalLaporan) * 100, 1) : 0;
            $persentaseMenungguPart   = $totalLaporan > 0 ? round(($menungguPart / $totalLaporan) * 100, 1) : 0;
            $persentaseDitolak         = $totalLaporan > 0 ? round(($ditolak / $totalLaporan) * 100, 1) : 0;

            $yearlyQuery = Laporan::query()->with('user')
                ->whereYear('created_at', '=', $tahunPilih)
                ->orderBy('created_at', 'desc')
                ->get();
            $allYearReports = $this->resolveAndFormatReports($yearlyQuery);

            // Notifikasi Laporan Masuk untuk Admin (antrean aktif dari pelapor, bukan laporan selesai/ditolak)
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
                })->values()->toArray();

            return view('admin.persentase', compact(
                'defaultReports',
                'allYearReports',
                'daftarTeknisi',
                'totalLaporan',
                'selesai',
                'dalamPerbaikan',
                'menungguPart',
                'ditolak',
                'persentaseSelesai',
                'persentaseDalamPerbaikan',
                'persentaseMenungguPart',
                'persentaseDitolak',
                'bulanPilih',
                'tahunPilih',
                'notifReports'
            ));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman persentase: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function rekap(Request $request)
    {
        try {
            $bulanPilih = $request->input('bulan', Carbon::now('Asia/Jakarta')->format('m'));
            $tahunPilih = $request->input('tahun', Carbon::now('Asia/Jakarta')->format('Y'));

            $query = Laporan::query()->with('user')
                ->whereMonth('created_at', '=', $bulanPilih)
                ->whereYear('created_at', '=', $tahunPilih)
                ->whereIn('status', ['Selesai', 'Ditolak'])
                ->orderBy('created_at', 'desc');

            $reportsCollection = (clone $query)->get();
            $defaultReports    = $this->resolveAndFormatReports($reportsCollection);
            $daftarTeknisi     = $this->getDaftarTeknisi();

            $totalLaporan   = (clone $query)->count();
            $selesai        = (clone $query)->where('status', '=', 'Selesai')->count();
            $dalamPerbaikan = (clone $query)->where('status', '=', 'Dalam Perbaikan')->count();
            $menungguPart   = (clone $query)->where('status', '=', 'Menunggu Part')->count();
            $ditolak        = (clone $query)->where('status', '=', 'Ditolak')->count();

            // Notifikasi Laporan Masuk untuk Admin (antrean aktif dari pelapor)
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
                        'pelapor_avatar'=> optional($item->user)->avatar ? (filter_var($item->user->avatar, FILTER_VALIDATE_URL) ? $item->user->avatar : asset('storage/' . $item->user->avatar)) : null,
                        'desa'          => optional($item->user)->nama_desa ?: (optional($item->user)->desa ?: ($item->desa ?? '-')),
                        'kecamatan'     => optional($item->user)->kecamatan ?? $item->kecamatan ?? 'Belum diatur',
                        'perangkat'     => ($item->jenis_perangkat ?? 'Lainnya') . ' - ' . ($item->perangkat ?? '-'),
                        'kendala'       => $item->kendala ?? '-',
                        'status'        => $item->status ?? 'Dalam Perbaikan',
                        'prioritas'     => $item->prioritas ?? 'Sedang',
                        'teknisi'       => $item->teknisi ?? 'Fahri',
                        'tanggal_masuk' => $item->created_at ? $item->created_at->format('d/m/Y') : '-',
                    ];
                })->values()->toArray();

            return view('admin.rekap', compact(
                'defaultReports',
                'daftarTeknisi',
                'totalLaporan',
                'selesai',
                'dalamPerbaikan',
                'menungguPart',
                'ditolak',
                'bulanPilih',
                'tahunPilih',
                'notifReports'
            ));
        } catch (Exception $e) {
            Log::error('Gagal memuat rekap bulanan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function updateKeteranganRekap(Request $request, int|string $id)
    {
        DB::beginTransaction();
        try {
            $laporan = Laporan::query()
                ->where('id', '=', $id)
                ->orWhere('kode_laporan', '=', $id)
                ->first();

            if (!$laporan) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan.'], 404);
            }

            $request->validate([
                'keterangan_rekap' => 'nullable|string|max:500',
            ]);

            $laporan->update([
                'keterangan_rekap' => $request->keterangan_rekap,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Keterangan rekap berhasil diperbarui.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui keterangan rekap: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat memperbarui keterangan.'], 500);
        }
    }

    public function show(int|string $id)
    {
        try {
            $laporan = Laporan::query()->with('user')
                ->where('id', '=', $id)
                ->orWhere('kode_laporan', '=', $id)
                ->first();

            if (!$laporan) {
                return response()->json(['success' => false, 'message' => 'Data laporan tidak ditemukan.'], 404);
            }

            return response()->json(['success' => true, 'data' => $laporan]);
        } catch (Exception $e) {
            Log::error('Gagal mengambil detail laporan: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan pada server.'], 500);
        }
    }

    public function updateStatus(Request $request, int|string $id)
    {
        DB::beginTransaction();
        try {
            $laporan = Laporan::query()
                ->where('kode_laporan', '=', $id)
                ->orWhere('id', '=', $id)
                ->first();

            if (!$laporan) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Laporan tidak ditemukan.'], 404);
            }

            $request->validate([
                'status'  => 'required|string|in:Dalam Perbaikan,Menunggu Part,Selesai,Ditolak',
                'teknisi' => 'nullable|string|max:100',
                'catatan' => 'nullable|string|max:500',
            ]);

            $statusBaru   = $request->status;
            $teknisiBaru  = $request->teknisi ?? ($laporan->teknisi ?? 'Belum ditugaskan');
            $catatanInput = $request->catatan ?? 'Pembaruan status menjadi: ' . $statusBaru;

            $waktu     = Carbon::now('Asia/Jakarta')->format('d-m-Y H:i');
            $pesanBaru = "[{$waktu}] Status: {$statusBaru} | Teknisi: {$teknisiBaru} | Ket: {$catatanInput}";
            $history   = !empty($laporan->history) ? $laporan->history . "\n" . $pesanBaru : $pesanBaru;

            $laporan->update([
                'status'  => $statusBaru,
                'teknisi' => $teknisiBaru,
                'history' => $history,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Status laporan berhasil diperbarui.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui status: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem saat memperbarui status.'], 500);
        }
    }

    public function destroy(int|string $id)
    {
        DB::beginTransaction();
        try {
            $laporan = Laporan::query()
                ->where('id', '=', $id)
                ->get()
                ->first() 
                ?? Laporan::query()->where('kode_laporan', '=', $id)->first();

            if (!$laporan) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Data laporan tidak ditemukan.'], 404);
            }

            DB::table('laporans')->where('id', '=', $laporan->id)->delete();
            
            DB::commit();

            // Hapus dan sinkronkan seeder codingan DatabaseSeeder.php secara realtime
            \App\Services\SeederSyncService::syncAllReportsFromDatabase();

            return response()->json(['success' => true, 'message' => 'Data laporan berhasil dihapus.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus laporan: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus data laporan.'], 500);
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $bulanPilih = $request->input('bulan', Carbon::now('Asia/Jakarta')->format('m'));
            $tahunPilih = $request->input('tahun', Carbon::now('Asia/Jakarta')->format('Y'));

            $startDate = Carbon::createFromFormat('Y-m', $tahunPilih . '-' . $bulanPilih)->startOfMonth()->toDateTimeString();
            $endDate = Carbon::createFromFormat('Y-m', $tahunPilih . '-' . $bulanPilih)->endOfMonth()->toDateTimeString();

            $laporans = Laporan::with('user')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', ['Selesai', 'Ditolak'])
                ->orderBy('created_at', 'desc')
                ->get();

            $months = [
                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                '04' => 'April',   '05' => 'Mei',      '06' => 'Juni',
                '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September',
                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            $namaBulan = $months[$bulanPilih] ?? $bulanPilih;
            $logoUrl = asset('images/cirebon.png');
            $petugas = optional(auth()->user())->name ?? 'Administrator SIPERKOM';
            $tglCetak = Carbon::now('Asia/Jakarta')->translatedFormat('d F Y');
            $tglJamCetak = Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB';
            $totalCount = $laporans->count();

            $html = '<!DOCTYPE html>
            <html lang="id">
            <head>
                <meta charset="UTF-8">
                <title>Rekap Laporan SIPERKOM Kabupaten Cirebon - Periode ' . htmlspecialchars($namaBulan . ' ' . $tahunPilih) . '</title>
                <style>
                    @page { 
                        size: A4 landscape; 
                        margin: 10mm 12mm 12mm 12mm; 
                    }
                    * { box-sizing: border-box; }
                    body { 
                        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; 
                        font-size: 7.5pt; 
                        color: #1e293b; 
                        line-height: 1.4; 
                        margin: 0; 
                        padding: 0; 
                        background-color: #ffffff;
                    }

                    /* Tampilan Toolbar Khusus Layar (Tidak Tercetak) */
                    @media print {
                        .no-print { display: none !important; }
                        body { background: transparent !important; padding: 0 !important; }
                        .page-wrapper { padding: 0 !important; margin: 0 !important; box-shadow: none !important; max-width: 100% !important; }
                    }
                    @media screen {
                        body { background-color: #f1f5f9; padding-top: 56px; }
                        .page-wrapper {
                            background: #ffffff;
                            max-width: 1120px;
                            margin: 18px auto;
                            padding: 28px 36px;
                            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
                            border-radius: 8px;
                        }
                        .print-toolbar {
                            position: fixed;
                            top: 0;
                            left: 0;
                            right: 0;
                            background-color: #072544;
                            color: #ffffff;
                            padding: 10px 24px;
                            z-index: 9999;
                            box-shadow: 0 2px 10px rgba(0,0,0,0.18);
                        }
                        .toolbar-container {
                            max-width: 1120px;
                            margin: 0 auto;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .toolbar-title { font-size: 9.5pt; font-weight: 700; color: #f8fafc; }
                        .btn-print {
                            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
                            color: #ffffff;
                            border: none;
                            padding: 6px 16px;
                            border-radius: 6px;
                            font-size: 8.5pt;
                            font-weight: 700;
                            cursor: pointer;
                            margin-right: 8px;
                            transition: all 0.2s;
                        }
                        .btn-print:hover { opacity: 0.92; transform: translateY(-1px); }
                        .btn-close {
                            background-color: #475569;
                            color: #ffffff;
                            border: none;
                            padding: 6px 12px;
                            border-radius: 6px;
                            font-size: 8.5pt;
                            font-weight: 600;
                            cursor: pointer;
                        }
                        .btn-close:hover { background-color: #334155; }
                    }

                    /* Kop Surat Resmi */
                    .kop-surat { margin-bottom: 12px; width: 100%; }
                    .kop-table { width: 100%; border-collapse: collapse; border: none; }
                    .kop-table td { border: none; padding: 0; vertical-align: middle; }
                    .kop-logo { width: 80px; text-align: center; }
                    .kop-logo img { width: 56px; height: auto; object-fit: contain; }
                    .kop-spacer { width: 80px; } /* Penyeimbang simetris logo di kiri */
                    .kop-text { text-align: center; }
                    .kop-instansi-1 { font-size: 11pt; font-weight: 700; color: #072544; margin: 0; letter-spacing: 0.8px; text-transform: uppercase; }
                    .kop-instansi-2 { font-size: 13.5pt; font-weight: 800; color: #0B3D6E; margin: 1.5px 0 0 0; letter-spacing: 0.5px; text-transform: uppercase; }
                    .kop-instansi-3 { font-size: 9.5pt; font-weight: 700; color: #997514; margin: 2px 0 0 0; letter-spacing: 0.3px; }
                    .kop-alamat { font-size: 7.5pt; color: #4b5563; margin: 3px 0 0 0; }
                    .kop-kontak { font-size: 7pt; color: #64748b; margin: 1px 0 0 0; }
                    .kop-line-thick { height: 2.5px; background-color: #072544; margin-top: 8px; }
                    .kop-line-thin { height: 1px; background-color: #C9A227; margin-top: 2px; margin-bottom: 16px; }

                    /* Judul & Periode Laporan */
                    .doc-header { text-align: center; margin-bottom: 16px; }
                    .doc-title { font-size: 11.5pt; font-weight: 800; color: #072544; margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.5px; }
                    .doc-period { font-size: 8.5pt; font-weight: 700; color: #475569; margin: 0; }

                    /* Kotak Informasi Meta */
                    .doc-meta-box { 
                        background-color: #f8fafc; 
                        border: 1px solid #cbd5e1; 
                        border-radius: 6px; 
                        padding: 8px 14px; 
                        margin-bottom: 16px; 
                    }
                    .meta-table { width: 100%; border-collapse: collapse; font-size: 7.5pt; }
                    .meta-table td { border: none; padding: 3px 6px; }
                    .meta-label { color: #64748b; width: 140px; font-weight: 600; }
                    .meta-val { color: #0f172a; font-weight: 700; }

                    /* Tabel Data Laporan */
                    table.report-table { 
                        border-collapse: collapse; 
                        width: 100%; 
                        margin-bottom: 20px; 
                        font-size: 7.5pt; 
                        border: 1px solid #cbd5e1;
                    }
                    table.report-table thead { display: table-header-group; }
                    table.report-table tr { page-break-inside: avoid; break-inside: avoid; }
                    table.report-table th { 
                        background-color: #072544; 
                        color: #ffffff; 
                        font-weight: 700; 
                        text-align: center; 
                        padding: 9px 7px; 
                        border: 1px solid #072544; 
                        font-size: 7pt; 
                        text-transform: uppercase; 
                        letter-spacing: 0.4px; 
                        vertical-align: middle;
                        -webkit-print-color-adjust: exact; 
                        print-color-adjust: exact; 
                    }
                    table.report-table td { 
                        padding: 8px 8px; 
                        border: 1px solid #cbd5e1; 
                        vertical-align: middle; 
                        color: #1e293b; 
                        line-height: 1.35;
                    }
                    table.report-table tbody tr:nth-child(even) { 
                        background-color: #f8fafc; 
                        -webkit-print-color-adjust: exact; 
                        print-color-adjust: exact; 
                    }

                    .center { text-align: center; }
                    .font-bold { font-weight: 700; }
                    .font-mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; }
                    
                    /* Badge Status */
                    .status-pill { 
                        display: inline-block; 
                        padding: 3px 8px; 
                        border-radius: 9999px; 
                        font-size: 6.8pt; 
                        font-weight: 800; 
                        text-transform: uppercase; 
                        letter-spacing: 0.3px;
                        text-align: center;
                        white-space: nowrap;
                        -webkit-print-color-adjust: exact; 
                        print-color-adjust: exact; 
                    }
                    .status-selesai { background-color: #dcfce7; color: #15803d; border: 1px solid #86efac; }
                    .status-ditolak { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }
                    .status-menunggu-part { background-color: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
                    .status-dalam-perbaikan { background-color: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }

                    /* Area Tanda Tangan */
                    .signature-section { 
                        margin-top: 20px; 
                        width: 100%; 
                        page-break-inside: avoid; 
                        break-inside: avoid; 
                    }
                    .sig-table { width: 100%; border-collapse: collapse; }
                    .sig-table td { border: none; padding: 0; }
                    .sig-box { text-align: center; width: 220px; }
                    .sig-date { margin: 0; font-size: 8pt; color: #334155; }
                    .sig-title { margin: 2px 0 0 0; font-size: 8pt; font-weight: 700; color: #072544; }
                    .sig-space { height: 48px; }
                    .sig-name { margin: 0; font-size: 8.5pt; font-weight: 800; color: #072544; text-decoration: underline; }
                    .sig-instansi { margin: 2px 0 0 0; font-size: 7.2pt; color: #64748b; }
                </style>
            </head>
            <body>
                <!-- Toolbar Khusus Layar Browser -->
                <div class="no-print print-toolbar">
                    <div class="toolbar-container">
                        <span class="toolbar-title">📄 Pratinjau Rekapitulasi Berkas Laporan IT SIPERKOM</span>
                        <div>
                            <button onclick="window.print()" class="btn-print">🖨️ Cetak / Simpan PDF</button>
                            <button onclick="window.close()" class="btn-close">✕ Tutup Tab</button>
                        </div>
                    </div>
                </div>

                <div class="page-wrapper">
                    <!-- KOP Surat Resmi -->
                    <div class="kop-surat">
                        <table class="kop-table">
                            <tr>
                                <td class="kop-logo">
                                    <img src="' . htmlspecialchars($logoUrl) . '" alt="Logo Kabupaten Cirebon">
                                </td>
                                <td class="kop-text">
                                    <h3 class="kop-instansi-1">PEMERINTAH KABUPATEN CIREBON</h3>
                                    <h2 class="kop-instansi-2">DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL</h2>
                                    <h1 class="kop-instansi-3">SISTEM INFORMASI PELAPORAN KERUSAKAN (SIPERKOM)</h1>
                                    <p class="kop-alamat">Jl. Sunan Muria No. 4, Sumber, Kec. Sumber, Kabupaten Cirebon, Jawa Barat 45611</p>
                                    <p class="kop-kontak">Website: cirebonkab.go.id &nbsp;|&nbsp; Email: disdukcapil@cirebonkab.go.id</p>
                                </td>
                                <td class="kop-spacer"></td>
                            </tr>
                        </table>
                        <div class="kop-line-thick"></div>
                        <div class="kop-line-thin"></div>
                    </div>

                    <!-- Judul Dokumen -->
                    <div class="doc-header">
                        <h2 class="doc-title">REKAPITULASI LAPORAN LAYANAN PERBAIKAN PERANGKAT IT</h2>
                        <p class="doc-period">Periode: ' . htmlspecialchars($namaBulan . ' ' . $tahunPilih) . '</p>
                    </div>

                    <!-- Kotak Metadata -->
                    <div class="doc-meta-box">
                        <table class="meta-table">
                            <tr>
                                <td class="meta-label">Tanggal / Jam Cetak:</td>
                                <td class="meta-val">' . htmlspecialchars($tglJamCetak) . '</td>
                                <td class="meta-label">Total Tiket Laporan:</td>
                                <td class="meta-val">' . $totalCount . ' Berkas</td>
                            </tr>
                            <tr>
                                <td class="meta-label">Petugas Administrasi:</td>
                                <td class="meta-val">' . htmlspecialchars($petugas) . '</td>
                                <td class="meta-label">Kategori Status:</td>
                                <td class="meta-val">Arsip Rekapitulasi (Selesai / Ditolak)</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Tabel Rekapitulasi -->
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th style="width: 28px;">NO</th>
                                <th style="width: 110px; white-space: nowrap;">ID LAPORAN</th>
                                <th style="width: 82px;">TGL MASUK</th>
                                <th style="width: 82px;">TGL REKAP</th>
                                <th style="width: 155px;">PELAPOR & LOKASI</th>
                                <th style="width: 125px;">PERANGKAT IT</th>
                                <th>URAIAN KENDALA / KERUSAKAN</th>
                                <th style="width: 72px;">STATUS</th>
                                <th style="width: 125px;">KETERANGAN / SOLUSI</th>
                            </tr>
                        </thead>
                        <tbody>';

            $nomor = 1;
            foreach ($laporans as $item) {
                $tglMasuk = $item->created_at 
                    ? Carbon::parse($item->created_at)->format('d M Y') . '<br><span style="font-size: 6.8pt; color: #64748b;">' . Carbon::parse($item->created_at)->format('H:i') . ' WIB</span>' 
                    : '-';
                $tglKeluar = $item->updated_at && in_array($item->status, ['Selesai', 'Ditolak']) 
                    ? Carbon::parse($item->updated_at)->format('d M Y') . '<br><span style="font-size: 6.8pt; color: #64748b;">' . Carbon::parse($item->updated_at)->format('H:i') . ' WIB</span>' 
                    : '-';
                
                $createdDate = $item->created_at ? Carbon::parse($item->created_at) : now();
                $yearMonth = $createdDate->format('Y-m');
                $idNum = (int)$item->id;
                $paddedId = $idNum < 10 ? '00' . $idNum : ($idNum < 100 ? '0' . $idNum : (string)$idNum);
                $idLaporan = $item->kode_laporan ?? ('#CRB-' . $yearMonth . '-' . $paddedId);

                $statusText = trim($item->status ?? 'Selesai');
                $pillClass = 'status-selesai';
                if ($statusText === 'Ditolak') {
                    $pillClass = 'status-ditolak';
                } elseif ($statusText === 'Menunggu Part') {
                    $pillClass = 'status-menunggu-part';
                } elseif ($statusText === 'Dalam Perbaikan') {
                    $pillClass = 'status-dalam-perbaikan';
                }

                $keteranganRekapText = $item->keterangan_rekap ?? $item->catatan ?? '-';
                $pelaporName = $item->nama_pelapor ?? ($item->user->name ?? 'Staf Pelapor');
                
                // Format Wilayah Bersih
                $desaText = optional($item->user)->nama_desa ?: (optional($item->user)->desa ?: ($item->desa ?? ''));
                $kecText = optional($item->user)->kecamatan ?: ($item->kecamatan ?? '');
                $alamatFull = optional($item->user)->alamat ?: ($item->alamat ?? '');

                $lokasiParts = [];
                if ($desaText) { $lokasiParts[] = $desaText; }
                if ($kecText && $kecText !== $desaText) { $lokasiParts[] = 'Kec. ' . $kecText; }
                $lokasiClean = !empty($lokasiParts) ? implode(', ', $lokasiParts) : ($alamatFull ?: 'Kabupaten Cirebon');

                // Pembersihan Redundansi Nama Perangkat
                $rawJenis = trim($item->jenis_perangkat ?? '');
                $rawPerangkat = trim($item->perangkat ?? '');
                if ($rawJenis && $rawPerangkat) {
                    if (stripos($rawPerangkat, $rawJenis) === 0) {
                        $perangkatText = $rawPerangkat;
                    } else {
                        $firstPart = trim(explode('/', $rawJenis)[0]);
                        $firstPart = trim(explode('-', $firstPart)[0]);
                        if ($firstPart && stripos($rawPerangkat, $firstPart) === 0) {
                            $perangkatText = $rawPerangkat;
                        } else {
                            $perangkatText = $rawJenis . ' - ' . $rawPerangkat;
                        }
                    }
                } elseif ($rawJenis) {
                    $perangkatText = $rawJenis;
                } else {
                    $perangkatText = $rawPerangkat ?: '-';
                }

                $html .= '<tr>
                    <td class="center font-bold" style="color: #64748b;">' . $nomor++ . '</td>
                    <td class="center font-bold font-mono" style="white-space: nowrap; font-size: 7.2pt; color: #072544;">' . htmlspecialchars($idLaporan) . '</td>
                    <td class="center" style="line-height: 1.25;">' . $tglMasuk . '</td>
                    <td class="center" style="line-height: 1.25;">' . $tglKeluar . '</td>
                    <td>
                        <div style="font-weight: 700; color: #072544; font-size: 7.5pt; margin-bottom: 2.5px;">' . htmlspecialchars($pelaporName) . '</div>
                        <div style="font-size: 6.8pt; color: #64748b; line-height: 1.25;">' . htmlspecialchars($lokasiClean) . '</div>
                    </td>
                    <td style="font-weight: 500;">' . htmlspecialchars($perangkatText) . '</td>
                    <td style="line-height: 1.3;">' . htmlspecialchars($item->kendala ?? '-') . '</td>
                    <td class="center"><span class="status-pill ' . $pillClass . '">' . htmlspecialchars($statusText) . '</span></td>
                    <td style="color: #334155; font-size: 7.2pt; line-height: 1.3;">' . htmlspecialchars($keteranganRekapText) . '</td>
                </tr>';
            }

            if ($laporans->isEmpty()) {
                $html .= '<tr><td colspan="9" class="center py-6 text-muted font-bold" style="padding: 24px; color: #94a3b8;">Tidak ada data rekapitulasi pada periode ' . htmlspecialchars($namaBulan . ' ' . $tahunPilih) . '.</td></tr>';
            }

            $html .= '</tbody>
                    </table>

                    <!-- Bagian Pengesahan Tanda Tangan -->
                    <div class="signature-section">
                        <table class="sig-table">
                            <tr>
                                <td style="width: 72%;"></td>
                                <td class="sig-box">
                                    <p class="sig-date">Sumber, ' . htmlspecialchars($tglCetak) . '</p>
                                    <p class="sig-title">Petugas Administrasi,</p>
                                    <div class="sig-space"></div>
                                    <p class="sig-name">' . htmlspecialchars($petugas) . '</p>
                                    <p class="sig-instansi">SIPERKOM Kabupaten Cirebon</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <script>
                    window.onload = function() { 
                        setTimeout(function() { window.print(); }, 250); 
                    }
                </script>
            </body>
            </html>';

            return response($html);

        } catch (Exception $e) {
            Log::error('Gagal mencetak PDF: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $bulanPilih = $request->input('bulan', Carbon::now('Asia/Jakarta')->format('m'));
            $tahunPilih = $request->input('tahun', Carbon::now('Asia/Jakarta')->format('Y'));

            $startDate = Carbon::createFromFormat('Y-m', $tahunPilih . '-' . $bulanPilih)->startOfMonth()->toDateTimeString();
            $endDate = Carbon::createFromFormat('Y-m', $tahunPilih . '-' . $bulanPilih)->endOfMonth()->toDateTimeString();

            $laporans = Laporan::with('user')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereIn('status', ['Selesai', 'Ditolak'])
                ->orderBy('created_at', 'desc')
                ->get();

            $fileName = 'rekap-laporan-siperkom-' . $tahunPilih . '-' . $bulanPilih . '.xls';

            $html = '<!DOCTYPE html>
            <html xmlns:x="urn:schemas-microsoft-com:office:excel">
            <head>
                <meta charset="UTF-8">
                <style>
                    table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 10pt; width: 100%; }
                    th { background-color: #0B3D6E; color: #ffffff; font-weight: bold; text-align: center; padding: 10px 8px; border: 1px solid #000000; }
                    td { padding: 8px; border: 1px solid #d3d3d3; vertical-align: middle; }
                    .center { text-align: center; }
                    .title { font-size: 13pt; font-weight: bold; height: 35px; text-align: center; background-color: #FBF6EA; color: #072544; vertical-align: middle; border: 1px solid #000000; }
                    
                    .row-selesai td { background-color: #D1E7DD; color: #0F5132; }
                    .row-ditolak td { background-color: #F8D7DA; color: #842029; }
                    .row-menunggu td { background-color: #CFF4FC; color: #055160; }
                    .row-perbaikan td { background-color: #FFF3CD; color: #664D03; }
                </style>
            </head>
            <body>
                <table>
                    <thead>
                        <tr>
                            <th colspan="10" class="title">REKAPITULASI LAPORAN KERUSAKAN PERANGKAT - SIPERKOM CIREBON (Periode: ' . $bulanPilih . '-' . $tahunPilih . ')</th>
                        </tr>
                        <tr>
                            <th style="width: 45px;">No</th>
                            <th style="width: 140px;">ID Laporan</th>
                            <th style="width: 130px;">Tanggal Masuk</th>
                            <th style="width: 130px;">Tanggal Keluar</th>
                            <th style="width: 120px;">Nama Pelapor</th>
                            <th style="width: 120px;">Alamat</th>
                            <th style="width: 140px;">Perangkat</th>
                            <th style="width: 160px;">Kendala</th>
                            <th style="width: 90px;">Status</th>
                            <th style="width: 160px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>';

            $nomor = 1;
            foreach ($laporans as $item) {
                $tglMasuk  = $item->created_at ? Carbon::parse($item->created_at)->format('Y-m-d H:i') : '-';
                $tglKeluar = $item->updated_at && in_array($item->status, ['Selesai', 'Ditolak']) ? Carbon::parse($item->updated_at)->format('Y-m-d H:i') : '-';
                
                $createdDate = $item->created_at ? Carbon::parse($item->created_at) : now();
                $yearMonth = $createdDate->format('Y-m');
                $idNum = (int)$item->id;
                $paddedId = $idNum < 10 ? '00' . $idNum : ($idNum < 100 ? '0' . $idNum : (string)$idNum);
                $idLaporan = $item->kode_laporan ?? ('#CRB-' . $yearMonth . '-' . $paddedId);

                $statusText = trim($item->status ?? 'Dalam Perbaikan');
                $rowClass = 'row-perbaikan';
                if ($statusText === 'Selesai') {
                    $rowClass = 'row-selesai';
                } elseif ($statusText === 'Ditolak') {
                    $rowClass = 'row-ditolak';
                } elseif ($statusText === 'Menunggu Part') {
                    $rowClass = 'row-menunggu';
                }

                $keteranganRekapText = $item->keterangan_rekap ?? $item->catatan ?? '-';
                $desaText = optional($item->user)->nama_desa
                    ?: (optional($item->user)->desa ?: ($item->desa ?? '-'));
                $alamatText = optional($item->user)->alamat ?: (optional($item->user)->kecamatan ?: ($item->alamat ?? ($item->kecamatan ?? 'Cirebon')));

                $html .= '<tr class="' . $rowClass . '">
                    <td class="center">' . $nomor++ . '</td>
                    <td class="center">' . htmlspecialchars($idLaporan) . '</td>
                    <td class="center">' . $tglMasuk . '</td>
                    <td class="center">' . $tglKeluar . '</td>
                    <td>' . htmlspecialchars($item->nama_pelapor ?? ($item->user->name ?? 'Staf Pelapor')) . '</td>
                    <td>' . htmlspecialchars($alamatText) . '</td>
                    <td>' . htmlspecialchars(($item->jenis_perangkat ?? 'Lainnya') . ' - ' . ($item->perangkat ?? '-')) . '</td>
                    <td>' . htmlspecialchars($item->kendala ?? '-') . '</td>
                    <td class="center"><b>' . htmlspecialchars($statusText) . '</b></td>
                    <td>' . htmlspecialchars($keteranganRekapText) . '</td>
                </tr>';
            }

            $html .= '</tbody>
                </table>
            </body>
            </html>';

            return response($html)
                ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename=' . $fileName);

        } catch (Exception $e) {
            Log::error('Gagal mengekspor data ke Excel: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}