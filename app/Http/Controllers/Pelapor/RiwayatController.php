<?php

namespace App\Http\Controllers\Pelapor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    // Halaman Riwayat Laporan Saya (Hanya menampilkan yang belum Selesai atau Ditolak, dengan filter bulan/tahun)
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $bulanPilih = $request->input('bulan', Carbon::now('Asia/Jakarta')->format('m'));
        $tahunPilih = $request->input('tahun', Carbon::now('Asia/Jakarta')->format('Y'));

        $allReports = Laporan::where('user_id', $userId)
            ->whereNotIn('status', ['Selesai', 'Ditolak'])
            ->latest()
            ->get();

        $filtered = $allReports->filter(function ($item) use ($bulanPilih, $tahunPilih) {
            $tgl = $item->created_at ?? ($item->tanggal ?? null);
            if (!$tgl) return false;
            
            $carbonDate = Carbon::parse($tgl);
            return $carbonDate->format('m') === str_pad($bulanPilih, 2, '0', STR_PAD_LEFT) 
                && $carbonDate->format('Y') === (string)$tahunPilih;
        });

        $reports = $filtered->map(function ($item) {
            return [
                'id' => $item->kode_laporan ?? '#REP-2026-' . str_pad((string)$item->id, 3, '0', STR_PAD_LEFT),
                'perangkat' => trim($item->jenis_perangkat ?? '') . ' - ' . trim($item->perangkat ?? ''),
                'kecamatan' => optional($item->user)->kecamatan ?? '-',
                'kendala' => $item->kendala ?? '-',
                'tanggal_masuk' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                'tanggal_keluar' => null,
                'teknisi' => $item->teknisi ?? '-',
                'status' => $item->status ?? 'Dalam Perbaikan',
                'prioritas' => $item->prioritas ?? 'Sedang',
                'foto' => $item->foto ? asset('storage/' . $item->foto) : null,
                'dokumen' => $item->dokumen ? asset('storage/' . $item->dokumen) : null,
                'dokumenNama' => $item->dokumen ? basename($item->dokumen) : null,
                'riwayat' => []
            ];
        })->values();

        return view('pelapor.riwayat', [
            'defaultReports' => $reports->toArray(),
            'bulanPilih' => $bulanPilih,
            'tahunPilih' => $tahunPilih
        ]);
    }

    // Halaman Rekap Laporan (Hanya menampilkan laporan yang sudah Selesai atau Ditolak, dengan filter bulan/tahun & pagination)
    public function rekap(Request $request)
    {
        $userId = Auth::id();
        
        $bulanPilih = $request->input('bulan', Carbon::now('Asia/Jakarta')->format('m'));
        $tahunPilih = $request->input('tahun', Carbon::now('Asia/Jakarta')->format('Y'));

        $allReports = Laporan::with('user')
            ->where('user_id', $userId)
            ->whereIn('status', ['Selesai', 'Ditolak'])
            ->latest()
            ->get();

        $filtered = $allReports->filter(function ($item) use ($bulanPilih, $tahunPilih) {
            $tgl = $item->created_at ?? ($item->tanggal ?? null);
            if (!$tgl) return false;
            
            $carbonDate = Carbon::parse($tgl);
            return $carbonDate->format('m') === str_pad($bulanPilih, 2, '0', STR_PAD_LEFT) 
                && $carbonDate->format('Y') === (string)$tahunPilih;
        });

        $mappedReports = $filtered->map(function ($item) {
            return [
                'id' => $item->kode_laporan ?? '#REP-2026-' . str_pad((string)$item->id, 3, '0', STR_PAD_LEFT),
                'perangkat' => trim($item->jenis_perangkat ?? '') . ' - ' . trim($item->perangkat ?? ''),
                'kecamatan' => optional($item->user)->kecamatan ?? '-',
                'kendala' => $item->kendala ?? '-',
                'tanggal_masuk' => $item->created_at ? $item->created_at->format('Y-m-d') : '-',
                'tanggal_keluar' => $item->updated_at ? $item->updated_at->format('Y-m-d') : '-',
                'teknisi' => $item->teknisi ?? '-',
                'status' => $item->status,
                'prioritas' => $item->prioritas ?? 'Sedang',
                'foto' => $item->foto ? asset('storage/' . $item->foto) : null,
                'dokumen' => $item->dokumen ? asset('storage/' . $item->dokumen) : null,
                'dokumenNama' => $item->dokumen ? basename($item->dokumen) : null,
            ];
        })->values();

        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $mappedReports->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $laporans = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $mappedReports->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('pelapor.rekap', compact('laporans', 'bulanPilih', 'tahunPilih'));
    }
}