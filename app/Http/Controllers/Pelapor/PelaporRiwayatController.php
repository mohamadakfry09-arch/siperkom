<?php

namespace App\Http\Controllers\Pelapor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Laporan;

class PelaporRiwayatController extends Controller
{
    /**
     * Menampilkan halaman rekap laporan sekaligus membersihkan otomatis 
     * riwayat laporan selesai/ditolak yang sudah berumur lebih dari 30 hari.
     */
    public function rekap(Request $request)
    {
        $userId = Auth::id();
        $limitDate = now()->subDays(30)->toDateTimeString();

        // Menggunakan DB::table untuk menghindari error pengecekan tipe argumen dari Intelephense
        DB::table('laporans')
            ->where('user_id', $userId)
            ->where('status', 'Selesai')
            ->where('created_at', '<', $limitDate)
            ->delete();

        DB::table('laporans')
            ->where('user_id', $userId)
            ->where('status', 'Ditolak')
            ->where('created_at', '<', $limitDate)
            ->delete();

        // Ambil seluruh data laporan milik user yang sedang login beserta relasinya
        $laporans = Laporan::with('user')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        // Format data agar rapi dan kompatibel dengan Blade & Alpine.js
        $defaultReports = $laporans->map(function ($item) {
            $userKecamatan = optional($item->user)->kecamatan ?? (Auth::user()->kecamatan ?? '-');
            $userNamaDesa = optional($item->user)->nama_desa
                ?: (optional($item->user)->desa ?? '-');
            $userName = optional($item->user)->name ?? 'User';

            return [
                'id'             => $item->kode_laporan ?? '#REP-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                'perangkat'      => $item->perangkat ?? '-',
                'jenis'          => $item->jenis_perangkat ?? 'Lainnya',
                'kecamatan'      => $item->kecamatan ?? $userKecamatan,
                'desa'           => $userNamaDesa,
                'pelapor'        => $item->pelapor ?? $userName,
                'kendala'        => $item->kendala ?? '-',
                'created_at'     => $item->created_at ? $item->created_at->format('Y-m-d') : date('Y-m-d'),
                'tanggal_keluar' => $item->tanggal_keluar ?? null,
                'teknisi'        => $item->teknisi ?? 'Belum ditugaskan',
                'status'         => $item->status ?? 'Dalam Perbaikan',
                'prioritas'      => $item->prioritas ?? 'Sedang',
                'foto'           => $item->foto ? asset('storage/' . $item->foto) : null,
                'dokumen'        => $item->dokumen ? asset('storage/' . $item->dokumen) : null,
                'dokumenNama'    => $item->dokumen ? basename($item->dokumen) : 'Dokumen.pdf',
            ];
        })->values()->toArray();

        // Jika ada permintaan AJAX/Polling dari JavaScript, kembalikan dalam bentuk JSON murni
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'reports' => $defaultReports
            ]);
        }

        return view('pelapor.rekap', compact('defaultReports'));
    }
}