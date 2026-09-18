<?php

namespace App\Http\Controllers\Pelapor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $now = Carbon::now('Asia/Jakarta');
        
        // Pelapor hanya melihat data dashboard untuk bulan berjalan
        $laporans = Laporan::with('user')
            ->where('user_id', '=', $userId)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->latest()
            ->get();

        $defaultReports = $laporans->map(function ($item) {
            return [
                'id' => $item->kode_laporan ?? '#CRB-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                'perangkat' => $item->perangkat,
                'kecamatan' => optional($item->user)->kecamatan ?? $item->kecamatan ?? '-',
                'kendala' => $item->kendala,
                'tanggal' => $item->created_at ? $item->created_at->format('Y-m-d') : date('Y-m-d'),
                'teknisi' => $item->teknisi ?? 'Fahri',
                'status' => $item->status,
                'prioritas' => $item->prioritas ?? 'Sedang',
                'foto' => $item->foto ? asset('storage/' . $item->foto) : null,
                'dokumen' => $item->dokumen ? asset('storage/' . $item->dokumen) : null,
                'dokumenNama' => $item->dokumen ? basename($item->dokumen) : 'Dokumen.pdf',
                'riwayat' => []
            ];
        })->toArray();

        $totalLaporan = $laporans->count();
        $dalamPerbaikan = $laporans->where('status', 'Dalam Perbaikan')->count();
        $menungguPart = $laporans->where('status', 'Menunggu Part')->count();
        $selesai = $laporans->where('status', 'Selesai')->count();

        // Hitung laporan aktif untuk anti-spam (Maksimal 2 laporan aktif)
        $activeReportsCount = Laporan::query()
            ->where('user_id', '=', $userId)
            ->whereNotIn('status', ['Selesai', 'Ditolak'])
            ->count();

        return view('pelapor.dashboard', compact(
            'laporans', 'defaultReports', 'totalLaporan', 'dalamPerbaikan', 'menungguPart', 'selesai', 'activeReportsCount'
        ));
    }

    public function riwayat(Request $request)
    {
        $userId = Auth::id();
        $now = Carbon::now('Asia/Jakarta');

        $bulanPilih = $request->input('bulan', $now->format('m'));
        $tahunPilih = $request->input('tahun', $now->format('Y'));

        // Pelapor melihat riwayat berdasarkan filter bulan yang dipilih, tanpa menghapus data database
        $allLaporans = Laporan::with('user')->where('user_id', '=', $userId)->latest()->get();

        $laporans = $allLaporans->filter(function ($item) use ($bulanPilih, $tahunPilih) {
            $tgl = $item->created_at ?? ($item->tanggal ?? null);
            if (!$tgl) return false;
            
            $carbonDate = Carbon::parse($tgl);
            return $carbonDate->format('m') === str_pad($bulanPilih, 2, '0', STR_PAD_LEFT) 
                && $carbonDate->format('Y') === (string)$tahunPilih;
        });

        $defaultReports = $laporans->map(function ($item) {
            return [
                'id' => $item->kode_laporan ?? '#CRB-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                'perangkat' => $item->perangkat,
                'kecamatan' => optional($item->user)->kecamatan ?? $item->kecamatan ?? '-',
                'kendala' => $item->kendala,
                'tanggal' => $item->created_at ? $item->created_at->format('Y-m-d') : date('Y-m-d'),
                'teknisi' => $item->teknisi ?? 'Fahri',
                'status' => $item->status,
                'prioritas' => $item->prioritas ?? 'Sedang',
                'foto' => $item->foto ? asset('storage/' . $item->foto) : null,
                'dokumen' => $item->dokumen ? asset('storage/' . $item->dokumen) : null,
                'dokumenNama' => $item->dokumen ? basename($item->dokumen) : 'Dokumen.pdf',
                'riwayat' => []
            ];
        })->values()->toArray();

        return view('pelapor.riwayat', compact('defaultReports', 'bulanPilih', 'tahunPilih'));
    }

    public function rekap(Request $request)
    {
        $userId = Auth::id();
        $now = Carbon::now('Asia/Jakarta');

        $bulanPilih = $request->input('bulan', $now->format('m'));
        $tahunPilih = $request->input('tahun', $now->format('Y'));

        // Mengambil semua laporan milik pelapor
        $allLaporans = Laporan::with('user')->where('user_id', '=', $userId)->latest()->get();

        // Secara default (jika tidak sedang filter bulan lain), rekap pelapor hanya menampilkan bulan berjalan saat ini
        if (!$request->has('bulan')) {
            $allLaporans = $allLaporans->filter(function ($item) use ($now) {
                $tgl = $item->created_at ?? ($item->tanggal ?? null);
                if (!$tgl) return false;
                $carbonDate = Carbon::parse($tgl);
                return $carbonDate->format('m') === $now->format('m') && $carbonDate->format('Y') === $now->format('Y');
            });
        }

        $filtered = $allLaporans->filter(function ($item) use ($bulanPilih, $tahunPilih) {
            $tgl = $item->created_at ?? ($item->tanggal ?? null);
            if (!$tgl) return false;
            
            $carbonDate = Carbon::parse($tgl);
            return $carbonDate->format('m') === str_pad($bulanPilih, 2, '0', STR_PAD_LEFT) 
                && $carbonDate->format('Y') === (string)$tahunPilih;
        });

        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $laporans = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $filtered->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('pelapor.rekap', compact('laporans', 'bulanPilih', 'tahunPilih'));
    }

    public function create()
    {
        return redirect()->route('pelapor.dashboard', ['open_modal' => 1]);
    }

    public function show(mixed $id)
    {
        return redirect()->route('pelapor.riwayat', ['open_id' => $id]);
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            // === PROTEKSI ANTI-SPAM: MAKSIMAL 2 LAPORAN AKTIF ===
            $activeReportsCount = Laporan::query()
                ->where('user_id', '=', $user->id)
                ->whereNotIn('status', ['Selesai', 'Ditolak'])
                ->count();

            if ($activeReportsCount >= 2) {
                $pesanSpam = 'Anda sudah memiliki 2 laporan aktif yang sedang diproses. Mohon tunggu hingga salah satu laporan selesai ditangani teknisi sebelum membuat laporan baru untuk mencegah spam antrean.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $pesanSpam
                    ], 422);
                }
                return back()->with('error', $pesanSpam);
            }
            // ====================================================

            $request->validate([
                'jenis'           => 'nullable|string|max:255',
                'jenis_perangkat' => 'nullable|string|max:255',
                'perangkat'       => 'required|string|max:255',
                'kendala'         => 'required|string',
                'prioritas'       => 'nullable|string',
                'foto'            => 'nullable|file|image|mimes:jpeg,png,jpg|min:20|max:5120',
                'dokumen'         => 'required|file|mimes:pdf,PDF|min:10|max:10240',
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

            $jenisVal = $request->jenis ?? $request->jenis_perangkat ?? 'Lainnya';

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('laporan/foto', 'public');
            }

            $dokumenPath = null;
            if ($request->hasFile('dokumen')) {
                $dokumenPath = $request->file('dokumen')->store('laporan/dokumen', 'public');
            }

            // === LOGIKA RESET ID LAPORAN JADI 001 SETIAP BULAN BARU (#CRB) ===
            $now = Carbon::now();
            $awalBulan = $now->copy()->startOfMonth()->toDateTimeString();
            $akhirBulan = $now->copy()->endOfMonth()->toDateTimeString();

            $laporanTerakhir = Laporan::query()
                ->where('created_at', '>=', $awalBulan)
                ->where('created_at', '<=', $akhirBulan)
                ->latest('id')
                ->first();

            if ($laporanTerakhir && $laporanTerakhir->kode_laporan) {
                $nomorUrutTerakhir = (int) substr($laporanTerakhir->kode_laporan, -3);
                $nomorBaru = $nomorUrutTerakhir + 1;
            } else {
                $nomorBaru = 1;
            }

            $kodeLaporan = '#CRB-' . $now->format('Y-m') . '-' . str_pad($nomorBaru, 3, '0', STR_PAD_LEFT);
            // =======================================================================

            $userAlamatVal = trim((string)($user->alamat ?? ''));
            if (empty($userAlamatVal) || $userAlamatVal === '-' || strtolower($userAlamatVal) === 'belum diatur') {
                $userAlamatVal = (!empty($user->kecamatan) && $user->kecamatan !== '-') ? $user->kecamatan : 'cirebon';
            }

            $newLaporan = Laporan::create([
                'kode_laporan'    => $kodeLaporan,
                'user_id'         => $user->id,
                'pelapor'         => $user->name ?? 'Pelapor',
                'alamat'          => $userAlamatVal,
                'desa'            => (!empty($user->nama_desa) && $user->nama_desa !== '-') ? $user->nama_desa : ((!empty($user->desa) && $user->desa !== '-') ? $user->desa : 'Cirebon'),
                'kecamatan'       => (!empty($user->kecamatan) && $user->kecamatan !== '-') ? $user->kecamatan : 'Cirebon',
                'jenis_perangkat' => $jenisVal,
                'perangkat'       => $request->perangkat,
                'kendala'         => $request->kendala,
                'prioritas'       => $request->prioritas ?? 'Sedang',
                'teknisi'         => 'Fahri',
                'foto'            => $fotoPath,
                'dokumen'         => $dokumenPath,
                'status'          => 'Dalam Perbaikan',
                'history'         => '[' . now()->format('d-m-Y H:i') . '] Laporan berhasil dikirim oleh pelapor.',
            ]);

            // Sinkronkan langsung codingan DatabaseSeeder.php dengan laporan baru yang masuk
            \App\Services\SeederSyncService::syncAllReportsFromDatabase();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan ' . $kodeLaporan . ' berhasil dikirim ke teknisi.',
                    'laporan' => [
                        'id'               => $newLaporan->kode_laporan,
                        'db_id'            => $newLaporan->id,
                        'kode_laporan'     => $newLaporan->kode_laporan,
                        'perangkat'        => $newLaporan->perangkat,
                        'jenis_perangkat'  => $newLaporan->jenis_perangkat,
                        'kendala'          => $newLaporan->kendala,
                        'catatan'          => null,
                        'keterangan_rekap' => '-',
                        'created_at'       => $newLaporan->created_at ? $newLaporan->created_at->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s'),
                        'tanggal'          => $newLaporan->created_at ? $newLaporan->created_at->format('Y-m-d') : date('Y-m-d'),
                        'tanggal_masuk'    => $newLaporan->created_at ? $newLaporan->created_at->format('Y-m-d') : date('Y-m-d'),
                        'tanggal_keluar'   => '-',
                        'updated_at'       => $newLaporan->updated_at ? $newLaporan->updated_at->format('Y-m-d H:i:s') : null,
                        'status'           => $newLaporan->status,
                        'prioritas'        => $newLaporan->prioritas,
                        'teknisi'          => $newLaporan->teknisi,
                        'pelapor'          => $newLaporan->pelapor,
                        'desa'             => $newLaporan->desa,
                        'kecamatan'        => $newLaporan->kecamatan,
                        'foto'             => $fotoPath ? asset('storage/' . $fotoPath) : null,
                        'dokumen'          => $dokumenPath ? basename($dokumenPath) : null,
                        'dokumen_url'      => $dokumenPath ? asset('storage/' . $dokumenPath) : null,
                    ]
                ]);
            }

            return redirect()->route('pelapor.riwayat')->with('success', 'Laporan berhasil dikirim!');

        } catch (\Illuminate\Validation\ValidationException $ve) {
            $errors = $ve->validator->errors()->all();
            $firstError = $errors[0] ?? 'Data laporan tidak valid.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $firstError,
                    'errors'  => $ve->validator->errors()
                ], 422);
            }

            return back()->withInput()->withErrors($ve->validator);

        } catch (\Throwable $e) {
            Log::error('Gagal menyimpan laporan pelapor: ' . $e->getMessage() . ' di baris ' . $e->getLine());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim laporan: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }

    public function batalkan(mixed $id)
    {
        $userId = Auth::id();
        $laporan = Laporan::query()
            ->where(function($query) use ($id) {
                $query->where('id', '=', $id)
                      ->orWhere('kode_laporan', '=', $id);
            })
            ->where('user_id', '=', $userId)
            ->first();

        if (!$laporan) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan atau Anda tidak memiliki akses.'
                ], 404);
            }
            return back()->with('error', 'Laporan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        if ($laporan->status === 'Selesai') {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan yang sudah selesai tidak dapat dibatalkan.'
                ], 400);
            }
            return back()->with('error', 'Laporan yang sudah selesai tidak dapat dibatalkan.');
        }

        // Hapus file fisik dari storage jika ada
        if ($laporan->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($laporan->foto)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->foto);
        }
        if ($laporan->dokumen && \Illuminate\Support\Facades\Storage::disk('public')->exists($laporan->dokumen)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($laporan->dokumen);
        }

        $kode = $laporan->kode_laporan ?? ('#' . $laporan->id);
        $laporan->delete();

        // Sinkronkan codingan DatabaseSeeder.php jika laporan dibatalkan
        \App\Services\SeederSyncService::syncAllReportsFromDatabase();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Laporan ' . $kode . ' berhasil dibatalkan.'
            ]);
        }

        return redirect()->route('pelapor.riwayat')->with('success', 'Laporan ' . $kode . ' berhasil dibatalkan.');
    }

    public function destroy(mixed $id)
    {
        return $this->batalkan($id);
    }
}