<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use Exception;
use Illuminate\Support\Facades\Log;

class RekapController extends Controller
{
    /**
     * Menampilkan halaman rekap laporan di dashboard admin secara lengkap.
     */
    public function index(Request $request)
    {
        try {
            $bulan = $request->input('bulan', date('m'));
            $tahun = $request->input('tahun', date('Y'));

            // Mengambil data laporan beserta relasi user
            $query = Laporan::with('user')->latest();

            if (!empty($bulan) && !empty($tahun)) {
                $query->whereYear('created_at', $tahun)
                      ->whereMonth('created_at', $bulan);
            }

            $laporans = $query->get();

            // Mapping data agar lengkap dikirim ke frontend Alpine.js
            $defaultReports = $laporans->map(function($item) {
                $keteranganFinal = $item->keterangan_rekap ?? $item->keterangan ?? $item->kendala ?? '-';
                
                // Paksakan kecamatan menjadi Depok jika pelapornya Fatan, selain itu ikuti data atau default
                $namaPelapor = $item->user->name ?? $item->pelapor ?? '';
                if (str_contains(strtolower($namaPelapor), 'fatan')) {
                    $kecamatanVal = 'Depok';
                } else {
                    $kecamatanVal = trim($item->kecamatan ?? optional($item->user)->kecamatan ?? 'Gunungjati');
                }

                $desaVal = trim(optional($item->user)->nama_desa
                    ?: (optional($item->user)->desa
                    ?: ($item->desa ?? 'Cirebon')));
                if ($desaVal === '-') $desaVal = 'Cirebon';
                if ($kecamatanVal === '-') $kecamatanVal = 'Cirebon';

                $userAlamat = optional($item->user)->alamat;
                $alamatVal = (!empty($item->alamat) && $item->alamat !== '-')
                    ? $item->alamat
                    : (!empty($userAlamat) && $userAlamat !== '-' ? $userAlamat : null);

                if (empty($alamatVal) || $alamatVal === '-' || strtolower(trim($alamatVal)) === 'belum diatur') {
                    $alamatVal = (!empty($kecamatanVal) && $kecamatanVal !== '-') ? $kecamatanVal : 'cirebon';
                }
                if ($alamatVal === '-' || empty($alamatVal)) {
                    $alamatVal = 'cirebon';
                }

                return [
                    'id'               => $item->kode_laporan ?? '#CRB-' . date('Y-m-', strtotime($item->created_at)) . sprintf('%03d', $item->id),
                    'db_id'            => $item->id,
                    'tanggal'          => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                    'tanggal_keluar'   => $item->tanggal_keluar ?? ($item->updated_at ? $item->updated_at->format('d M Y, H:i') : '-'),
                    'pelapor'          => $namaPelapor ?: 'Masyarakat',
                    'alamat'           => $alamatVal,
                    'desa'             => $desaVal,
                    'kecamatan'        => $kecamatanVal,
                    'jenis_perangkat'  => $item->jenis_perangkat ?? 'Laptop',
                    'perangkat'        => $item->perangkat ?? '-',
                    'kendala'          => $item->kendala ?? '-',
                    'status'           => trim($item->status ?? 'Dalam Perbaikan'),
                    'teknisi'          => $item->teknisi ?? 'Fahry Rizky Prayogi',
                    'keterangan'       => $keteranganFinal,
                    'keterangan_rekap' => $keteranganFinal,
                    'foto'             => $item->foto ? (filter_var($item->foto, FILTER_VALIDATE_URL) ? $item->foto : asset('storage/' . $item->foto)) : null,
                ];
            });

            return view('admin.rekap', compact('defaultReports', 'bulan', 'tahun'));
        } catch (Exception $e) {
            Log::error('Gagal memuat Rekap: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data rekap.');
        }
    }

    /**
     * Proses unduh / ekspor laporan lengkap ke file Excel (.xls).
     */
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
            Log::error('Gagal Ekspor Excel: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengunduh file Excel.');
        }
    }
}