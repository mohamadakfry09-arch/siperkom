<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class AntreanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Laporan::with('user')
                ->whereNotIn('status', ['Selesai', 'Ditolak'])
                ->latest();

            if ($request->has('status') && $request->status !== 'Semua') {
                $query->where('status', $request->status);
            }

            $reports = $query->get()->map(function($item) {
                $foto = $item->foto;
                if ($foto) {
                    $foto = filter_var($foto, FILTER_VALIDATE_URL) ? $foto : asset('storage/' . $foto);
                } else {
                    $foto = 'https://picsum.photos/600/400';
                }

                $dokumen = $item->dokumen;
                if ($dokumen) {
                    $dokumen = filter_var($dokumen, FILTER_VALIDATE_URL) ? $dokumen : asset('storage/' . $dokumen);
                } else {
                    $dokumen = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
                }

                $userObj = $item->user ?: User::where('name', $item->pelapor)->orWhere('email', 'nifary@gmail.com')->first();
                $alamat = optional($userObj)->alamat
                    ?: ($item->alamat 
                    ?: (optional($userObj)->kecamatan
                    ?: (optional($userObj)->nama_desa
                    ?: ($item->kecamatan ?? ($item->desa ?? 'cirebon')))));

                if (empty($alamat) || $alamat === '-' || strtolower(trim($alamat)) === 'belum diatur') {
                    $alamat = optional($userObj)->alamat ?: 'cirebon';
                }
                if ($alamat === '-' || empty($alamat)) {
                    $alamat = 'cirebon';
                }

                return [
                    'id'               => $item->kode_laporan ?? '#REP-' . $item->id,
                    'db_id'            => $item->id,
                    'pelapor'          => $item->user->name ?? $item->nama_pelapor ?? 'Staf',
                    'pelapor_avatar'   => optional($item->user)->avatar_url ?: (optional($item->user)->avatar ? (filter_var($item->user->avatar, FILTER_VALIDATE_URL) ? $item->user->avatar : asset('storage/' . $item->user->avatar)) : asset('images/pelapor-avatar.svg')),
                    'alamat'           => $alamat,
                    'desa'             => optional($item->user)->nama_desa
                        ?: (optional($item->user)->desa
                        ?: ($item->desa ?? '-')),
                    'kecamatan'        => optional($item->user)->kecamatan ?? $item->kecamatan ?? 'Belum diatur',
                    'perangkat'        => ($item->jenis_perangkat ?? 'Lainnya') . ' - ' . ($item->perangkat ?? '-'),
                    'kendala'          => $item->kendala ?? '-',
                    'status'           => $item->status ?? 'Dalam Perbaikan',
                    'prioritas'        => $item->prioritas ?? 'Sedang',
                    'teknisi'          => $item->teknisi ?? 'Fahry Rizky Prayogi',
                    'tanggal_masuk'    => $item->created_at ? $item->created_at->format('d/m/Y') : '-',
                    'keterangan'       => $item->keterangan_rekap ?? $item->kendala ?? '-',
                    'keterangan_rekap' => $item->keterangan_rekap ?? '-',
                    'foto'             => $foto,
                    'dokumen'          => $dokumen,
                ];
            });

            // Notifikasi Laporan Masuk untuk Admin: Hanya laporan baru & antrean aktif yang membutuhkan tindakan teknisi
            $notifReports = Laporan::with('user')
                ->whereNotIn('status', ['Selesai', 'Ditolak'])
                ->latest()
                ->take(15)
                ->get()
                ->map(function($item) {
                    return [
                        'id'               => $item->kode_laporan ?? '#REP-' . $item->id,
                        'db_id'            => $item->id,
                        'pelapor'          => $item->user->name ?? $item->nama_pelapor ?? 'Staf Pelapor',
                        'pelapor_avatar'   => optional($item->user)->avatar_url ?: (optional($item->user)->avatar ? (filter_var($item->user->avatar, FILTER_VALIDATE_URL) ? $item->user->avatar : asset('storage/' . $item->user->avatar)) : asset('images/pelapor-avatar.svg')),
                        'desa'             => optional($item->user)->nama_desa ?: (optional($item->user)->desa ?: ($item->desa ?? '-')),
                        'kecamatan'        => optional($item->user)->kecamatan ?? $item->kecamatan ?? 'Belum diatur',
                        'perangkat'        => ($item->jenis_perangkat ?? 'Lainnya') . ' - ' . ($item->perangkat ?? '-'),
                        'kendala'          => $item->kendala ?? '-',
                        'status'           => $item->status ?? 'Dalam Perbaikan',
                        'prioritas'        => $item->prioritas ?? 'Sedang',
                        'teknisi'          => $item->teknisi ?? 'Fahry Rizky Prayogi',
                        'tanggal_masuk'    => $item->created_at ? $item->created_at->format('d/m/Y') : '-',
                    ];
                });

            return view('admin.antrean', compact('reports', 'notifReports'));
        } catch (Exception $e) {
            Log::error('Gagal memuat Antrean: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data antrean.');
        }
    }

    public function updateStatus(Request $request, int $id)
    {
        try {
            $laporan = Laporan::findOrFail($id);
            
            $request->validate([
                'status' => 'required|string|in:Dalam Perbaikan,Menunggu Part,Selesai,Ditolak'
            ]);

            $catatanInput = $request->input('catatan') ?? $request->input('keterangan') ?? $request->input('keterangan_rekap');
            
            if (!empty($catatanInput)) {
                $catatanBaru = trim($catatanInput);
            } else {
                $catatanBaru = !empty($laporan->keterangan_rekap) ? $laporan->keterangan_rekap : ('Status diperbarui menjadi ' . $request->status);
            }

            $laporan->status = $request->status;
            $laporan->Status = $request->status;
            $laporan->keterangan_rekap = $catatanBaru;

            if (in_array($request->status, ['Selesai', 'Ditolak'], true) && empty($laporan->tanggal_keluar)) {
                $laporan->tanggal_keluar = now();
            }
            
            $laporan->save();

            // Catat notifikasi aktivitas (termasuk Selesai & Ditolak)
            try {
                \App\Models\Notification::create([
                    'title'   => 'Laporan ' . $request->status . ': ' . ($laporan->kode_laporan ?? '#'.$laporan->id),
                    'message' => 'Laporan ' . $laporan->perangkat . ' (' . ($laporan->pelapor ?? 'Pelapor') . ') telah berstatus ' . $request->status . '. ' . $catatanBaru,
                    'is_read' => false,
                ]);
            } catch (\Throwable $th) {
                // Abaikan jika error log notifikasi
            }

            return response()->json([
                'success' => true,
                'message' => 'Status laporan berhasil diperbarui.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'ERROR: ' . $e->getMessage() . ' pada baris ' . $e->getLine()
            ], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $laporan = Laporan::findOrFail($id);
            $laporan->delete();

            // Sinkronkan codingan DatabaseSeeder.php
            \App\Services\SeederSyncService::syncAllReportsFromDatabase();

            return redirect()->route('admin.antrean')->with('success', 'Laporan berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal menghapus laporan: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data.');
        }
    }

    /**
     * Endpoint Sinkronisasi Real-Time (Live Sync tanpa refresh) untuk Antrean Admin.
     */
    public function liveSync(Request $request)
    {
        $status = $request->query('status');
        $query = Laporan::with('user')
            ->whereNotIn('status', ['Selesai', 'Ditolak'])
            ->orderBy('created_at', 'desc');

        if ($status && $status !== 'Semua') {
            $query->where('status', $status);
        }

        $reports = $query->get()->map(function($item) {
            $foto = $item->foto;
            if ($foto) {
                $foto = filter_var($foto, FILTER_VALIDATE_URL) ? $foto : asset('storage/' . $foto);
            } else {
                $foto = 'https://picsum.photos/600/400';
            }

            $dokumen = $item->dokumen;
            if ($dokumen) {
                $dokumen = filter_var($dokumen, FILTER_VALIDATE_URL) ? $dokumen : asset('storage/' . $dokumen);
            } else {
                $dokumen = 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf';
            }

            $userObj = $item->user ?: User::where('name', $item->pelapor)->orWhere('email', 'nifary@gmail.com')->first();
            $alamat = optional($userObj)->alamat
                ?: ($item->alamat 
                ?: (optional($userObj)->kecamatan
                ?: (optional($userObj)->nama_desa
                ?: ($item->kecamatan ?? ($item->desa ?? 'cirebon')))));

            if (empty($alamat) || $alamat === '-' || strtolower(trim($alamat)) === 'belum diatur') {
                $alamat = optional($userObj)->alamat ?: 'cirebon';
            }
            if ($alamat === '-' || empty($alamat)) {
                $alamat = 'cirebon';
            }

            return [
                'id'               => $item->kode_laporan ?? '#REP-' . $item->id,
                'db_id'            => $item->id,
                'pelapor'          => $item->user->name ?? $item->nama_pelapor ?? 'Staf',
                'pelapor_avatar'   => optional($item->user)->avatar_url ?: (optional($item->user)->avatar ? (filter_var($item->user->avatar, FILTER_VALIDATE_URL) ? $item->user->avatar : asset('storage/' . $item->user->avatar)) : asset('images/pelapor-avatar.svg')),
                'alamat'           => $alamat,
                'desa'             => optional($item->user)->nama_desa
                    ?: (optional($item->user)->desa
                    ?: ($item->desa ?? 'Cirebon')),
                'kecamatan'        => optional($item->user)->kecamatan ?? $item->kecamatan ?? 'Cirebon',
                'perangkat'        => ($item->jenis_perangkat ?? 'Lainnya') . ' - ' . ($item->perangkat ?? '-'),
                'kendala'          => $item->kendala ?? '-',
                'status'           => $item->status ?? 'Dalam Perbaikan',
                'prioritas'        => $item->prioritas ?? 'Sedang',
                'teknisi'          => $item->teknisi ?? 'Fahry Rizky Prayogi',
                'tanggal_masuk'    => $item->created_at ? $item->created_at->format('d/m/Y') : '-',
                'tanggal_keluar'   => $item->tanggal_keluar ? (is_string($item->tanggal_keluar) ? substr($item->tanggal_keluar, 0, 10) : $item->tanggal_keluar->format('Y-m-d')) : null,
                'keterangan'       => $item->keterangan_rekap ?? $item->kendala ?? '-',
                'keterangan_rekap' => $item->keterangan_rekap ?? '-',
                'foto'             => $foto,
                'dokumen'          => $dokumen,
            ];
        });

        // Notifikasi laporan masuk untuk Admin
        $notifReports = Laporan::with('user')
            ->whereNotIn('status', ['Selesai', 'Ditolak'])
            ->latest()
            ->take(15)
            ->get()
            ->map(function($item) {
                $userObj = $item->user ?: User::where('name', $item->pelapor)->orWhere('email', 'nifary@gmail.com')->first();
                $alamat = optional($userObj)->alamat
                    ?: ($item->alamat 
                    ?: (optional($userObj)->kecamatan
                    ?: (optional($userObj)->nama_desa
                    ?: ($item->kecamatan ?? ($item->desa ?? 'cirebon')))));

                if (empty($alamat) || $alamat === '-' || strtolower(trim($alamat)) === 'belum diatur') {
                    $alamat = optional($userObj)->alamat ?: 'cirebon';
                }
                if ($alamat === '-' || empty($alamat)) {
                    $alamat = 'cirebon';
                }

                return [
                    'id'            => $item->kode_laporan ?? '#REP-' . $item->id,
                    'db_id'         => $item->id,
                    'pelapor'       => $item->user->name ?? $item->nama_pelapor ?? 'Staf Pelapor',
                    'pelapor_avatar'=> optional($item->user)->avatar_url ?: (optional($item->user)->avatar ? (filter_var($item->user->avatar, FILTER_VALIDATE_URL) ? $item->user->avatar : asset('storage/' . $item->user->avatar)) : asset('images/pelapor-avatar.svg')),
                    'alamat'        => $alamat,
                    'desa'          => optional($item->user)->nama_desa ?: (optional($item->user)->desa ?: ($item->desa ?? '-')),
                    'kecamatan'     => optional($item->user)->kecamatan ?? $item->kecamatan ?? 'Belum diatur',
                    'perangkat'     => ($item->jenis_perangkat ?? 'Lainnya') . ' - ' . ($item->perangkat ?? '-'),
                    'kendala'       => $item->kendala ?? '-',
                    'status'        => $item->status ?? 'Dalam Perbaikan',
                    'prioritas'     => $item->prioritas ?? 'Sedang',
                    'teknisi'       => $item->teknisi ?? 'Fahry Rizky Prayogi',
                    'tanggal_masuk' => $item->created_at ? $item->created_at->format('d/m/Y') : '-',
                ];
            });

        return response()->json([
            'success'      => true,
            'reports'      => $reports,
            'notifReports' => $notifReports,
            'total'        => $reports->count(),
            'timestamp'    => now()->timestamp
        ]);
    }
}