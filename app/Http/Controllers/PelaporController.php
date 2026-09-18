<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Carbon\Carbon;

class PelaporController extends Controller
{
    /**
     * Menampilkan halaman dashboard pelapor dengan ringkasan statistik dinamis.
     */
    public function index(): View
    {
        $userId = Auth::id();

        // Mengambil laporan terbaru milik user yang sedang login menggunakan query()
        $reports = Laporan::query()
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Variabel statistik dinamis untuk kartu di dashboard
        $totalLaporan   = Laporan::query()->where('user_id', $userId)->count();
        $dalamPerbaikan = Laporan::query()->where('user_id', $userId)->where('status', 'Dalam Perbaikan')->count();
        $menungguPart   = Laporan::query()->where('user_id', $userId)->where('status', 'Menunggu Part')->count();
        $selesai        = Laporan::query()->where('user_id', $userId)->where('status', 'Selesai')->count();

        // Ubah laporan ke format array/collection untuk ditampilkan di front-end jika diperlukan
        $defaultReports = $reports->map(function ($item) {
            return [
                'id'             => $item->kode_laporan ?? '#REP-' . $item->id,
                'perangkat'      => $item->perangkat ?? '-',
                'kendala'        => $item->kendala ?? '-',
                'tanggal_masuk'  => $item->created_at ? $item->created_at->format('Y-m-d') : null,
                'tanggal_keluar' => $item->tanggal_keluar ?? null,
                'teknisi'        => $item->teknisi ?? '-',
                'status'         => $item->status ?? 'Dalam Perbaikan',
                'foto'           => $item->foto ? (filter_var($item->foto, FILTER_VALIDATE_URL) ? $item->foto : asset('storage/' . $item->foto)) : null,
                'dokumen'        => $item->dokumen ? basename($item->dokumen) : null,
            ];
        });

        return view('pelapor.dashboard', compact(
            'defaultReports', 
            'totalLaporan', 
            'dalamPerbaikan', 
            'menungguPart', 
            'selesai'
        ));
    }

    public function riwayat(): View
    {
        $userId = Auth::id();
        $laporans = Laporan::query()
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        return view('pelapor.riwayat', compact('laporans'));
    }

    public function rekap(): View
    {
        $userId = Auth::id();
        $defaultReports = Laporan::query()
            ->where('user_id', $userId)
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id'             => $item->kode_laporan ?? '#REP-' . $item->id,
                    'perangkat'      => $item->perangkat ?? '-',
                    'kendala'        => $item->kendala ?? '-',
                    'tanggal_masuk'  => $item->created_at ? $item->created_at->format('Y-m-d') : null,
                    'tanggal_keluar' => $item->tanggal_keluar ?? null,
                    'teknisi'        => $item->teknisi ?? '-',
                    'status'         => $item->status ?? 'Dalam Perbaikan',
                    'foto'           => $item->foto ? (filter_var($item->foto, FILTER_VALIDATE_URL) ? $item->foto : asset('storage/' . $item->foto)) : null,
                    'dokumen'        => $item->dokumen ? basename($item->dokumen) : null,
                ];
            })->values()->toArray();

        return view('pelapor.rekap', compact('defaultReports'));
    }

    /**
     * Menyimpan laporan baru dengan format ID otomatis reset setiap bulan baru.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'perangkat' => 'required|string|max:255',
                'kendala'   => 'required|string',
                'prioritas' => 'nullable|string',
                'foto'      => 'nullable|file|image|mimes:jpeg,png,jpg|min:20|max:5120',
                'dokumen'   => 'required|file|mimes:pdf,PDF|min:10|max:10240',
            ], [
                'perangkat.required' => 'Nama perangkat / unit barang wajib diisi.',
                'kendala.required'   => 'Kendala / kerusakan wajib diisi.',
                'foto.image'         => 'Berkas foto harus berupa gambar yang valid (JPG, JPEG, PNG).',
                'foto.mimes'         => 'Format foto harus berupa JPG, JPEG, atau PNG.',
                'foto.min'           => 'Ukuran foto kerusakan minimal 20 KB.',
                'foto.max'           => 'Ukuran foto kerusakan maksimal 5 MB.',
                'dokumen.required'   => 'Dokumen pendukung (PDF) wajib diunggah.',
                'dokumen.mimes'      => 'Dokumen pendukung harus berformat PDF.',
                'dokumen.min'        => 'Ukuran berkas PDF minimal 10 KB.',
                'dokumen.max'        => 'Ukuran berkas PDF maksimal 10 MB.',
            ]);

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('laporan/foto', 'public');
            }

            $dokumenPath = null;
            if ($request->hasFile('dokumen')) {
                $dokumenPath = $request->file('dokumen')->store('laporan/dokumen', 'public');
            }

            // === LOGIKA RESET ID LAPORAN JADI 001 SETIAP BULAN BARU ===
            $now = Carbon::now();
            $awalBulan = $now->copy()->startOfMonth()->toDateTimeString();
            $akhirBulan = $now->copy()->endOfMonth()->toDateTimeString();

            // Cek laporan terakhir di bulan yang sedang berjalan
            $laporanTerakhir = Laporan::query()
                ->where('created_at', '>=', $awalBulan)
                ->where('created_at', '<=', $akhirBulan)
                ->latest('id')
                ->first();

            if ($laporanTerakhir && $laporanTerakhir->kode_laporan) {
                // Ambil 3 digit angka terakhir, lalu tambahkan 1
                $nomorUrutTerakhir = (int) substr($laporanTerakhir->kode_laporan, -3);
                $nomorBaru = $nomorUrutTerakhir + 1;
            } else {
                // Jika belum ada laporan di bulan ini, reset mulai dari 1
                $nomorBaru = 1;
            }

            // Format hasil akhir: #REP-TahunBulan-NomorUrut (Contoh: #REP-2026-08-001)
            $kodeLaporan = '#REP-' . $now->format('Y-m') . '-' . str_pad($nomorBaru, 3, '0', STR_PAD_LEFT);
            // ========================================================

            $user = Auth::user();

            Laporan::create([
                'kode_laporan' => $kodeLaporan,
                'user_id'      => $user->id,
                'pelapor'      => $user->name,
                'kecamatan'    => $user->kecamatan ?? '-',
                'perangkat'    => $request->perangkat,
                'kendala'      => $request->kendala,
                'prioritas'    => $request->prioritas ?? 'Sedang',
                'teknisi'      => 'Fahri',
                'foto'         => $fotoPath,
                'dokumen'      => $dokumenPath,
                'status'       => 'Dalam Perbaikan',
            ]);

            // Sinkronkan codingan DatabaseSeeder.php secara realtime
            \App\Services\SeederSyncService::syncAllReportsFromDatabase();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan ' . $kodeLaporan . ' berhasil dikirim.'
                ]);
            }

            return redirect()->route('pelapor.riwayat')->with('success', 'Laporan berhasil dikirim!');

        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan laporan: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }
}