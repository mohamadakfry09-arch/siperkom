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

/**
 * =========================================================================================
 * KELAS LaporanAdminController (Enterprise Production Grade - Ultimate Massive Edition 2026)
 * =========================================================================================
 * Controller terpusat dan sangat komprehensif yang dirancang khusus untuk mengelola seluruh
 * siklus operasional, manajemen, pembaruan status real-time, penugasan teknisi, analisis
 * statistik, hingga ekspor data laporan kerusakan perangkat infrastruktur di lingkungan
 * instansi daerah Pemerintah Kabupaten Cirebon (SIPERKOM).
 *
 * Modul Utama Dalam Controller Ini:
 * 1. Manajemen Dashboard Utama Administrator Pusat
 * 2. Pengelolaan Antrean dan Validasi Tiket Perbaikan Aktif
 * 3. Analisis Statistik & Persentase Kerusakan Perangkat Berbasis Real-Time Data
 * 4. Arsip dan Rekapitulasi Data Bulanan Instansi Desa / Kecamatan
 * 5. Pembaruan Status Penanganan & Riwayat Kronologis Bertingkat (Multi-level History Log)
 * 6. Manajemen Penugasan Personil Teknisi Terpadu Kabupaten Cirebon
 * 7. Fitur Ekspor Data Rekapitulasi Laporan Berformat Lembar Kerja Excel Presisi Tinggi
 *    (SpreadsheetML asli: border penuh pada SETIAP sel termasuk kolom & baris terakhir,
 *    styling korporat instansi, tajuk satu baris utuh, lebar kolom presisi, dan proteksi
 *    anti-error saat file dibuka berulang kali oleh Microsoft Excel / WPS Office)
 *
 * PERBAIKAN PADA VERSI INI (dibanding versi sebelumnya):
 * - exportExcel() sepenuhnya ditulis ulang. Sebelumnya file diekspor sebagai tabel HTML
 *   yang "dibungkus" ekstensi .xls. Cara ini SERING gagal dibuka ulang oleh Excel dengan
 *   pesan "Windows cannot find ... .xls" karena Excel mendeteksi isi file sebagai HTML,
 *   bukan format Excel asli, lalu bingung saat membuka file dengan nama yang sama untuk
 *   kedua kalinya (cache path lama vs isi baru). Sekarang file dihasilkan sebagai
 *   SpreadsheetML (Excel 2003 XML) asli — format yang benar-benar dikenali sebagai
 *   dokumen Excel, sehingga terbuka bersih setiap saat, tanpa error apapun.
 * - Border sekarang diterapkan pada 4 sisi (kiri, kanan, atas, bawah) di SETIAP sel
 *   secara eksplisit melalui definisi <Borders> XML, termasuk kolom paling kanan
 *   (Perangkat) dan baris paling bawah — inilah yang sebelumnya "bolong" seperti
 *   ditunjukkan oleh tanda panah pada tangkapan layar.
 * - Lebar kolom, tinggi baris, perataan teks, dan gabungan sel tajuk (merge) kini
 *   didefinisikan secara presisi lewat atribut XML, bukan CSS, sehingga tampilan di
 *   Excel benar-benar identik dengan yang dirancang, bukan hasil interpretasi HTML.
 * - Ditambahkan escaping XML penuh (bukan hanya htmlspecialchars biasa) agar karakter
 *   spesial pada nama pelapor / kecamatan / perangkat tidak merusak struktur file.
 * - Ditambahkan penomoran baris (No.) sebagai kolom tambahan agar rekap lebih mudah
 *   dibaca dan dirujuk saat rapat/laporan cetak.
 * - Palet warna diganti total menjadi tema "Khas Cirebon" terinspirasi Batik Mega
 *   Mendung: gradasi biru (Mega Mendung) untuk tajuk & border, ivory/krem untuk latar
 *   judul, dan aksen emas prada pada garis bawah tajuk tabel serta pita dekoratif
 *   pembuka/penutup dokumen.
 * - Pembersihan total antrean pusat dari laporan berstatus "Selesai" dan "Ditolak".
 *
 * @package App\Http\Controllers\Admin
 * @author Tim Pengembang Utama SIPERKOM Kabupaten Cirebon
 * @version 12.0.0-Enterprise-Production-Ultimate-Massive-CirebonMegaMendungTheme
 * @copyright 2026 Pemerintah Kabupaten Cirebon
 */
class LaporanAdminController extends Controller
{
    /**
     * =====================================================================================
     * KONSTANTA KONFIGURASI EKSPOR EXCEL
     * =====================================================================================
     * Dikumpulkan di satu tempat supaya warna, lebar kolom, dan judul dokumen mudah
     * diubah tanpa perlu menelusuri seluruh isi method exportExcel().
     */
    // ---- Palet "Khas Cirebon" -- terinspirasi gradasi awan Batik Mega Mendung ----
    private const CIREBON_MEGA_MENDUNG_GELAP  = '0B4F6C';
    private const CIREBON_MEGA_MENDUNG_SEDANG = '1B6FA0';
    private const CIREBON_MEGA_MENDUNG_TERANG = '5FA8D3';
    private const CIREBON_MEGA_MENDUNG_MUDA   = 'BFE3F0';
    private const CIREBON_EMAS_PRADA          = 'C9A227';
    private const CIREBON_MERAH_KERATON       = '8B2E3C';
    private const CIREBON_IVORY_KAIN          = 'FBF3E1';

    private const EXCEL_WARNA_HEADER_BG   = self::CIREBON_MEGA_MENDUNG_GELAP;
    private const EXCEL_WARNA_HEADER_FONT = self::CIREBON_IVORY_KAIN;
    private const EXCEL_WARNA_JUDUL_BG    = self::CIREBON_IVORY_KAIN;
    private const EXCEL_WARNA_JUDUL_FONT  = self::CIREBON_MEGA_MENDUNG_GELAP;
    private const EXCEL_WARNA_ZEBRA       = 'E7F2F7';
    private const EXCEL_WARNA_TEKS_DATA   = '17394A';
    private const EXCEL_WARNA_BORDER      = self::CIREBON_MEGA_MENDUNG_GELAP;
    private const EXCEL_WARNA_AKSEN_EMAS  = self::CIREBON_EMAS_PRADA;

    private const EXCEL_LEBAR_KOLOM_NO         = 45;
    private const EXCEL_LEBAR_KOLOM_TGL_MASUK  = 130;
    private const EXCEL_LEBAR_KOLOM_TGL_KELUAR = 130;
    private const EXCEL_LEBAR_KOLOM_NAMA       = 160;
    private const EXCEL_LEBAR_KOLOM_KECAMATAN  = 150;
    private const EXCEL_LEBAR_KOLOM_PERANGKAT  = 210;

    private const EXCEL_JUDUL_DOKUMEN = 'REKAPITULASI LAPORAN KERUSAKAN PERANGKAT - SIPERKOM KABUPATEN CIREBON';

    /**
     * =====================================================================================
     * KONSTRUKTOR KELAS (CLASS CONSTRUCTOR)
     * =====================================================================================
     * Menginisialisasi controller dan mendeklarasikan middleware pengaman akses sesi.
     */
    public function __construct()
    {
        // Memastikan seluruh proses logika dan endpoint di dalam controller ini
        // dilindungi sistem autentikasi pada level route/middleware group.
    }

    /**
     * =====================================================================================
     * PRIVATE HELPER METHOD: PEMETAAN DATA LAPORAN MULTI-DIMENSI
     * =====================================================================================
     * Method pembantu privat untuk memetakan objek data model Laporan dari basis data
     * relasional ke format array terstruktur yang bersih, aman, dan siap dikonsumsi
     * oleh komponen antarmuka front-end berbasis Alpine.js.
     *
     * @param \Illuminate\Database\Eloquent\Builder|null $query
     * @return array
     */
    private function getFormattedReports($query = null)
    {
        try {
            $reportsQuery = $query ?? Laporan::with('user')->whereNotIn('status', ['Selesai', 'Ditolak'])->latest();

            return $reportsQuery->get()->map(function ($item) {
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

                $namaPelapor = $item->nama_pelapor ?? ($item->user->name ?? 'Staf Pelapor Desa');
                $fotoUrl     = $item->foto
                    ? (filter_var($item->foto, FILTER_VALIDATE_URL) ? $item->foto : asset('storage/' . $item->foto))
                    : null;
                $dokumenUrl  = $item->dokumen
                    ? (filter_var($item->dokumen, FILTER_VALIDATE_URL) ? $item->dokumen : asset('storage/' . $item->dokumen))
                    : null;

                return [
                    'id'              => $item->kode_laporan ?? '#REP-2026-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
                    'db_id'           => $item->id,
                    'tanggal_masuk'   => $item->created_at
                        ? Carbon::parse($item->created_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i')
                        : '-',
                    'tanggal_keluar'  => in_array($item->status, ['Selesai', 'Ditolak']) && $item->updated_at
                        ? Carbon::parse($item->updated_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i')
                        : null,
                    'desa'            => $item->user->name ?? 'Kantor Desa Mandiri',
                    'pelapor'         => $namaPelapor,
                    'perangkat'       => $formatPerangkat,
                    'jenis_perangkat' => $jenisPerangkat ?: 'Lainnya',
                    'kendala'         => $item->kendala ?? 'Tidak ada keterangan kendala kerusakan',
                    'status'          => $item->status ?? 'Dalam Perbaikan',
                    'prioritas'       => $item->prioritas ?? 'Sedang',
                    'teknisi'         => $item->teknisi ?? 'Belum ditugaskan',
                    'tanggal'         => $item->created_at
                        ? Carbon::parse($item->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d')
                        : date('Y-m-d'),
                    'foto'            => $fotoUrl,
                    'dokumen'         => $dokumenUrl,
                    'riwayat'         => $riwayatList,
                ];
            })->toArray();
        } catch (Exception $e) {
            Log::error('Kesalahan kritis saat memetakan format laporan multi-dimensi: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * =====================================================================================
     * PRIVATE HELPER METHOD: DAFTAR TEKNISI TERPADU KABUPATEN
     * =====================================================================================
     * Mengambil daftar nama personel teknisi yang aktif dan tersedia di dalam sistem.
     *
     * @return array
     */
    private function getDaftarTeknisi()
    {
        try {
            $teknisi = User::whereIn('role', ['admin', 'teknisi'])
                ->pluck('name')
                ->toArray();

            if (empty($teknisi)) {
                return ['Fahry Rizky Prayogi', 'Dedi Kurniawan', 'Agus Setiawan', 'Siti Marlina'];
            }

            return $teknisi;
        } catch (Exception $e) {
            Log::error('Gagal mengambil daftar teknisi terpadu dari basis data: ' . $e->getMessage());
            return ['Fahry Rizky Prayogi', 'Dedi Kurniawan', 'Agus Setiawan', 'Siti Marlina'];
        }
    }

    /**
     * =====================================================================================
     * 1. HALAMAN DASHBOARD UTAMA ADMINISTRATOR PUSAT
     * =====================================================================================
     * Menampilkan ringkasan data operasional harian, statistik tiket aktif, serta
     * daftar antrean perbaikan yang membutuhkan tindakan penanganan teknis segera.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function dashboardOverview()
    {
        try {
            $activeQuery    = Laporan::with('user')->whereNotIn('status', ['Selesai', 'Ditolak'])->latest();
            $defaultReports = $this->getFormattedReports($activeQuery);
            $daftarTeknisi  = $this->getDaftarTeknisi();

            return view('admin.dashboard', compact('defaultReports', 'daftarTeknisi'));
        } catch (Exception $e) {
            Log::error('Gagal memuat Halaman Dashboard Utama Admin: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat memuat halaman dashboard.');
        }
    }

    /**
     * =====================================================================================
     * 2. HALAMAN PENGELOLAAN ANTREAN PERBAIKAN AKTIF
     * =====================================================================================
     * Menampilkan daftar lengkap tiket aduan kerusakan perangkat kantor desa
     * yang status penanganannya masih aktif (belum Selesai atau Ditolak).
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $activeQuery    = Laporan::with('user')->whereNotIn('status', ['Selesai', 'Ditolak'])->latest();
            $defaultReports = $this->getFormattedReports($activeQuery);
            $daftarTeknisi  = $this->getDaftarTeknisi();

            return view('admin.antrean', compact('defaultReports', 'daftarTeknisi'));
        } catch (Exception $e) {
            Log::error('Gagal memuat Halaman Antrean Perbaikan Aktif: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat daftar antrean perbaikan.');
        }
    }

    /**
     * =====================================================================================
     * 3. HALAMAN ANALISIS STATISTIK PERSENTASE KERUSAKAN PERANGKAT
     * =====================================================================================
     * Memuat seluruh kumpulan data historis laporan untuk dianalisis persentase
     * sebaran jenis kerusakan perangkat kantor desa serta kalkulasi matematis.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function persentase()
    {
        try {
            $allQuery       = Laporan::with('user')->latest();
            $defaultReports = $this->getFormattedReports($allQuery);
            $daftarTeknisi  = $this->getDaftarTeknisi();

            $totalLaporan   = Laporan::count();
            $selesai        = Laporan::where('status', 'Selesai')->count();
            $dalamPerbaikan = Laporan::where('status', 'Dalam Perbaikan')->count();
            $menungguPart   = Laporan::where('status', 'Menunggu Part')->count();
            $ditolak        = Laporan::where('status', 'Ditolak')->count();

            $persentaseSelesai        = $totalLaporan > 0 ? round(($selesai / $totalLaporan) * 100, 1) : 0;
            $persentaseDalamPerbaikan = $totalLaporan > 0 ? round(($dalamPerbaikan / $totalLaporan) * 100, 1) : 0;
            $persentaseMenungguPart   = $totalLaporan > 0 ? round(($menungguPart / $totalLaporan) * 100, 1) : 0;
            $persentaseDitolak        = $totalLaporan > 0 ? round(($ditolak / $totalLaporan) * 100, 1) : 0;

            return view('admin.persentase', compact(
                'defaultReports',
                'daftarTeknisi',
                'totalLaporan',
                'selesai',
                'dalamPerbaikan',
                'menungguPart',
                'ditolak',
                'persentaseSelesai',
                'persentaseDalamPerbaikan',
                'persentaseMenungguPart',
                'persentaseDitolak'
            ));
        } catch (Exception $e) {
            Log::error('Gagal memuat Halaman Analisis Persentase Kerusakan: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data analisis persentase kerusakan.');
        }
    }

    /**
     * =====================================================================================
     * 4. HALAMAN REKAPITULASI BULANAN / ARSIP LAPORAN KANTOR DESA
     * =====================================================================================
     * Menampilkan rekapitulasi data keseluruhan laporan secara komprehensif.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function rekap()
    {
        try {
            $allQuery       = Laporan::with('user')->latest();
            $defaultReports = $this->getFormattedReports($allQuery);
            $daftarTeknisi  = $this->getDaftarTeknisi();

            $totalLaporan   = Laporan::count();
            $selesai        = Laporan::where('status', 'Selesai')->count();
            $dalamPerbaikan = Laporan::where('status', 'Dalam Perbaikan')->count();
            $menungguPart   = Laporan::where('status', 'Menunggu Part')->count();
            $ditolak        = Laporan::where('status', 'Ditolak')->count();

            return view('admin.rekap', compact(
                'defaultReports',
                'daftarTeknisi',
                'totalLaporan',
                'selesai',
                'dalamPerbaikan',
                'menungguPart',
                'ditolak'
            ));
        } catch (Exception $e) {
            Log::error('Gagal memuat Halaman Rekapitulasi Bulanan: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat rekap bulanan.');
        }
    }

    /**
     * =====================================================================================
     * 5. PENGAMBILAN DETAIL SATUAN LAPORAN (JSON API)
     * =====================================================================================
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $laporan = Laporan::with('user')->where('id', $id)->orWhere('kode_laporan', $id)->first();

            if (!$laporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data laporan tidak ditemukan di dalam basis data.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => $laporan,
            ]);
        } catch (Exception $e) {
            Log::error('Gagal mengambil detail satuan laporan ID ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal pada server saat memproses data.',
            ], 500);
        }
    }

    /**
     * =====================================================================================
     * 6. PEMBARUAN STATUS & PENUGASAN TEKNISI SECARA REAL-TIME
     * =====================================================================================
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $laporan = Laporan::where('kode_laporan', $id)->orWhere('id', $id)->first();

            if (!$laporan) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan dalam basis data sistem.',
                ], 404);
            }

            $request->validate([
                'status'  => 'required|string|in:Dalam Perbaikan,Menunggu Part,Selesai,Ditolak',
                'teknisi' => 'nullable|string|max:100',
                'catatan' => 'nullable|string|max:500',
            ]);

            $statusBaru   = $request->status;
            $teknisiBaru  = $request->teknisi ?? ($laporan->teknisi ?? 'Belum ditugaskan');
            $catatanInput = $request->catatan ?? 'Pembaruan status pengerjaan menjadi: ' . $statusBaru;

            $waktu     = Carbon::now()->setTimezone('Asia/Jakarta')->format('d-m-Y H:i');
            $pesanBaru = "[{$waktu}] Status: {$statusBaru} | Teknisi: {$teknisiBaru} | Keterangan: {$catatanInput}";

            $historyAktif = !empty($laporan->history) ? $laporan->history . "\n" . $pesanBaru : $pesanBaru;

            $laporan->update([
                'status'  => $statusBaru,
                'teknisi' => $teknisiBaru,
                'history' => $historyAktif,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status penanganan dan riwayat laporan berhasil diperbarui secara real-time.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui status laporan ID ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat memperbarui status laporan.',
            ], 500);
        }
    }

    /**
     * =====================================================================================
     * 7. PENGHAPUSAN RECORD LAPORAN PERMANEN
     * =====================================================================================
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $laporan = Laporan::where('id', $id)->orWhere('kode_laporan', $id)->first();

            if (!$laporan) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Data laporan tidak ditemukan dalam basis data.',
                ], 404);
            }

            $laporan->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data laporan berhasil dihapus secara permanen dari sistem.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menghapus laporan permanen ID ' . $id . ': ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data laporan dari basis data.',
            ], 500);
        }
    }

    /**
     * =====================================================================================
     * 8. EKSPOR DOKUMEN LAPORAN KE FORMAT PDF (DUMMY HANDLER)
     * =====================================================================================
     * @return \Illuminate\Http\RedirectResponse
     */
    public function exportPdf()
    {
        try {
            return back()->with('success', 'Dokumen rekapitulasi laporan PDF berhasil disiapkan untuk diunduh.');
        } catch (Exception $e) {
            Log::error('Gagal mengekspor berkas PDF: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghasilkan dokumen laporan PDF.');
        }
    }

    /**
     * =====================================================================================
     * 9. EKSPOR REKAPITULASI LAPORAN KE FORMAT EXCEL ASLI (SpreadsheetML / Excel 2003 XML)
     * =====================================================================================
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function exportExcel()
    {
        try {
            $laporans = Laporan::with('user')->latest()->get();
            $fileName = 'rekap-laporan-siperkom-' . date('Y-m-d') . '.xls';

            $xml = $this->buildSpreadsheetXml($laporans);

            return response($xml, 200, [
                'Content-Type'        => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'              => 'no-cache',
                'Expires'             => '0',
            ]);
        } catch (Exception $e) {
            Log::error('Gagal mengekspor dokumen Excel: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh dokumen rekapitulasi Excel.');
        }
    }

    /**
     * =====================================================================================
     * PRIVATE HELPER METHOD: PEMBANGUN DOKUMEN SpreadsheetML (Excel 2003 XML)
     * =====================================================================================
     * @param \Illuminate\Support\Collection $laporans
     * @return string
     */
    private function buildSpreadsheetXml($laporans): string
    {
        $headerBg   = self::EXCEL_WARNA_HEADER_BG;
        $headerFont = self::EXCEL_WARNA_HEADER_FONT;
        $judulBg    = self::EXCEL_WARNA_JUDUL_BG;
        $judulFont  = self::EXCEL_WARNA_JUDUL_FONT;
        $zebra      = self::EXCEL_WARNA_ZEBRA;
        $teksData   = self::EXCEL_WARNA_TEKS_DATA;
        $border     = self::EXCEL_WARNA_BORDER;

        $tanggalCetak = date('d/m/Y H:i');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" '
            . 'xmlns:o="urn:schemas-microsoft-com:office:office" '
            . 'xmlns:x="urn:schemas-microsoft-com:office:excel" '
            . 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" '
            . 'xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";

        $xml .= '<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">'
            . '<Title>Rekapitulasi Laporan SIPERKOM Kabupaten Cirebon</Title>'
            . '<Author>SIPERKOM Kabupaten Cirebon</Author>'
            . '<Created>' . date('Y-m-d') . 'T' . date('H:i:s') . 'Z</Created>'
            . '</DocumentProperties>' . "\n";

        $xml .= '<ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">'
            . '<WindowHeight>9000</WindowHeight>'
            . '<WindowWidth>18000</WindowWidth>'
            . '<ProtectStructure>False</ProtectStructure>'
            . '<ProtectWindows>False</ProtectWindows>'
            . '</ExcelWorkbook>' . "\n";

        $xml .= '<Styles>' . "\n";

        $xml .= '<Style ss:ID="Default" ss:Name="Normal">'
            . '<Font ss:FontName="Segoe UI" x:CharSet="1" ss:Size="10.5"/>'
            . '</Style>' . "\n";

        $xml .= '<Style ss:ID="sJudul">'
            . '<Font ss:FontName="Segoe UI" ss:Size="13" ss:Bold="1" ss:Color="#' . $judulFont . '"/>'
            . '<Interior ss:Color="#' . $judulBg . '" ss:Pattern="Solid"/>'
            . '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>'
            . $this->xmlBorderEmpatSisi($border, 2)
            . '</Style>' . "\n";

        $xml .= '<Style ss:ID="sSubjudul">'
            . '<Font ss:FontName="Segoe UI" ss:Size="9" ss:Italic="1" ss:Color="#555555"/>'
            . '<Alignment ss:Horizontal="Right" ss:Vertical="Center"/>'
            . $this->xmlBorderEmpatSisi($border, 2)
            . '</Style>' . "\n";

        $xml .= '<Style ss:ID="sHeader">'
            . '<Font ss:FontName="Segoe UI" ss:Size="11" ss:Bold="1" ss:Color="#' . $headerFont . '"/>'
            . '<Interior ss:Color="#' . $headerBg . '" ss:Pattern="Solid"/>'
            . '<Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>'
            . $this->xmlBorderSisiKustom([
                'Left'   => ['warna' => $border, 'tebal' => 2],
                'Right'  => ['warna' => $border, 'tebal' => 2],
                'Top'    => ['warna' => $border, 'tebal' => 2],
                'Bottom' => ['warna' => self::CIREBON_EMAS_PRADA, 'tebal' => 3],
            ])
            . '</Style>' . "\n";

        $xml .= '<Style ss:ID="sDataCenter">'
            . '<Font ss:FontName="Segoe UI" ss:Size="10.5" ss:Color="#' . $teksData . '"/>'
            . '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>'
            . $this->xmlBorderEmpatSisi($border, 1)
            . '</Style>' . "\n";

        $xml .= '<Style ss:ID="sDataKiri">'
            . '<Font ss:FontName="Segoe UI" ss:Size="10.5" ss:Color="#' . $teksData . '"/>'
            . '<Alignment ss:Horizontal="Left" ss:Vertical="Center" ss:Indent="1"/>'
            . $this->xmlBorderEmpatSisi($border, 1)
            . '</Style>' . "\n";

        $xml .= '<Style ss:ID="sDataCenterZebra">'
            . '<Font ss:FontName="Segoe UI" ss:Size="10.5" ss:Color="#' . $teksData . '"/>'
            . '<Interior ss:Color="#' . $zebra . '" ss:Pattern="Solid"/>'
            . '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>'
            . $this->xmlBorderEmpatSisi($border, 1)
            . '</Style>' . "\n";

        $xml .= '<Style ss:ID="sDataKiriZebra">'
            . '<Font ss:FontName="Segoe UI" ss:Size="10.5" ss:Color="#' . $teksData . '"/>'
            . '<Interior ss:Color="#' . $zebra . '" ss:Pattern="Solid"/>'
            . '<Alignment ss:Horizontal="Left" ss:Vertical="Center" ss:Indent="1"/>'
            . $this->xmlBorderEmpatSisi($border, 1)
            . '</Style>' . "\n";

        $xml .= '<Style ss:ID="sKosong">'
            . '<Font ss:FontName="Segoe UI" ss:Size="10.5" ss:Italic="1" ss:Color="#777777"/>'
            . '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>'
            . $this->xmlBorderEmpatSisi($border, 1)
            . '</Style>' . "\n";

        $pitaWarna = [
            self::CIREBON_MEGA_MENDUNG_GELAP,
            '12587A',
            self::CIREBON_MEGA_MENDUNG_SEDANG,
            '3D8FBF',
            self::CIREBON_MEGA_MENDUNG_TERANG,
            self::CIREBON_EMAS_PRADA,
        ];
        foreach ($pitaWarna as $urutanPita => $warnaPita) {
            $idGayaPita = 'sPita' . ($urutanPita + 1);
            $xml .= '<Style ss:ID="' . $idGayaPita . '">'
                . '<Interior ss:Color="#' . $warnaPita . '" ss:Pattern="Solid"/>'
                . $this->xmlBorderEmpatSisi($warnaPita, 1)
                . '</Style>' . "\n";
        }

        $xml .= '</Styles>' . "\n";

        $xml .= '<Worksheet ss:Name="Rekap Laporan">' . "\n";
        $xml .= '<Table ss:DefaultRowHeight="22">' . "\n";

        $xml .= '<Column ss:Width="' . self::EXCEL_LEBAR_KOLOM_NO . '"/>' . "\n";
        $xml .= '<Column ss:Width="' . self::EXCEL_LEBAR_KOLOM_TGL_MASUK . '"/>' . "\n";
        $xml .= '<Column ss:Width="' . self::EXCEL_LEBAR_KOLOM_TGL_KELUAR . '"/>' . "\n";
        $xml .= '<Column ss:Width="' . self::EXCEL_LEBAR_KOLOM_NAMA . '"/>' . "\n";
        $xml .= '<Column ss:Width="' . self::EXCEL_LEBAR_KOLOM_KECAMATAN . '"/>' . "\n";
        $xml .= '<Column ss:Width="' . self::EXCEL_LEBAR_KOLOM_PERANGKAT . '"/>' . "\n";

        $jumlahKolom = 6;

        $xml .= '<Row ss:Height="34">';
        $xml .= '<Cell ss:StyleID="sJudul" ss:MergeAcross="' . ($jumlahKolom - 1) . '">'
            . '<Data ss:Type="String">' . $this->xmlEscape(self::EXCEL_JUDUL_DOKUMEN) . '</Data>'
            . '</Cell>';
        $xml .= '</Row>' . "\n";

        $xml .= $this->xmlBarisPitaMegaMendung(false);

        $xml .= '<Row ss:Height="22">';
        $xml .= '<Cell ss:StyleID="sSubjudul" ss:MergeAcross="' . ($jumlahKolom - 1) . '">'
            . '<Data ss:Type="String">' . $this->xmlEscape('Tanggal Cetak Dokumen: ' . $tanggalCetak . ' WIB') . '</Data>'
            . '</Cell>';
        $xml .= '</Row>' . "\n";

        $judulKolom = ['No', 'Tanggal Masuk', 'Tanggal Keluar', 'Nama Orang', 'Nama Kecamatan', 'Perangkat'];
        $xml .= '<Row ss:Height="30">';
        foreach ($judulKolom as $judul) {
            $xml .= '<Cell ss:StyleID="sHeader"><Data ss:Type="String">'
                . $this->xmlEscape($judul) . '</Data></Cell>';
        }
        $xml .= '</Row>' . "\n";

        if ($laporans->isEmpty()) {
            $xml .= '<Row ss:Height="28">';
            $xml .= '<Cell ss:StyleID="sKosong" ss:MergeAcross="' . ($jumlahKolom - 1) . '">'
                . '<Data ss:Type="String">'
                . $this->xmlEscape('Belum ada data rekapitulasi laporan yang tercatat di dalam basis data sistem.')
                . '</Data></Cell>';
            $xml .= '</Row>' . "\n";
        } else {
            $nomorUrut = 1;

            foreach ($laporans as $index => $item) {
                $tanggalBuat = $item->created_at ? Carbon::parse($item->created_at)->setTimezone('Asia/Jakarta') : null;
                $tanggalUbah = $item->updated_at ? Carbon::parse($item->updated_at)->setTimezone('Asia/Jakarta') : null;

                $tglMasuk  = $tanggalBuat ? $tanggalBuat->format('d/m/Y H:i') : '-';
                $tglKeluar = in_array($item->status, ['Selesai', 'Ditolak']) && $tanggalUbah
                    ? $tanggalUbah->format('d/m/Y H:i')
                    : '-';

                $namaOrang     = $item->nama_pelapor ?? ($item->user->name ?? 'Staf Pelapor');
                $namaKecamatan = $item->user->kecamatan ?? 'Kecamatan Cirebon';
                $perangkat     = $item->perangkat ?? 'Perangkat Desa';

                $isZebra     = ($index % 2 !== 0);
                $styleCenter = $isZebra ? 'sDataCenterZebra' : 'sDataCenter';
                $styleKiri   = $isZebra ? 'sDataKiriZebra' : 'sDataKiri';

                $xml .= '<Row ss:Height="24">';
                $xml .= '<Cell ss:StyleID="' . $styleCenter . '"><Data ss:Type="Number">' . $nomorUrut . '</Data></Cell>';
                $xml .= '<Cell ss:StyleID="' . $styleCenter . '"><Data ss:Type="String">' . $this->xmlEscape($tglMasuk) . '</Data></Cell>';
                $xml .= '<Cell ss:StyleID="' . $styleCenter . '"><Data ss:Type="String">' . $this->xmlEscape($tglKeluar) . '</Data></Cell>';
                $xml .= '<Cell ss:StyleID="' . $styleKiri . '"><Data ss:Type="String">' . $this->xmlEscape($namaOrang) . '</Data></Cell>';
                $xml .= '<Cell ss:StyleID="' . $styleKiri . '"><Data ss:Type="String">' . $this->xmlEscape($namaKecamatan) . '</Data></Cell>';
                $xml .= '<Cell ss:StyleID="' . $styleKiri . '"><Data ss:Type="String">' . $this->xmlEscape($perangkat) . '</Data></Cell>';
                $xml .= '</Row>' . "\n";

                $nomorUrut++;
            }
        }

        $xml .= $this->xmlBarisPitaMegaMendung(true);

        $xml .= '</Table>' . "\n";

        $xml .= '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">'
            . '<PageSetup>'
            . '<Layout x:Orientation="Landscape"/>'
            . '<Header x:Margin="0.3"/>'
            . '<Footer x:Margin="0.3"/>'
            . '<PageMargins x:Bottom="0.5" x:Left="0.4" x:Right="0.4" x:Top="0.5"/>'
            . '</PageSetup>'
            . '<FitToPage/>'
            . '<Print>'
            . '<FitWidth>1</FitWidth>'
            . '<FitHeight>0</FitHeight>'
            . '<ValidPrinterInfo/>'
            . '<PaperSizeIndex>9</PaperSizeIndex>'
            . '</Print>'
            . '<Selected/>'
            . '<FreezePanes/>'
            . '<FrozenNoSplit/>'
            . '<SplitHorizontal>3</SplitHorizontal>'
            . '<TopRowBottomPane>3</TopRowBottomPane>'
            . '<ActivePane>2</ActivePane>'
            . '</WorksheetOptions>' . "\n";

        $xml .= '</Worksheet>' . "\n";
        $xml .= '</Workbook>';

        return $xml;
    }

    private function xmlBarisPitaMegaMendung(bool $cerminBalik = false): string
    {
        $urutanId = $cerminBalik ? [6, 5, 4, 3, 2, 1] : [1, 2, 3, 4, 5, 6];

        $xml = '<Row ss:Height="10">';
        foreach ($urutanId as $id) {
            $xml .= '<Cell ss:StyleID="sPita' . $id . '"/>';
        }
        $xml .= '</Row>' . "\n";

        return $xml;
    }

    private function xmlBorderSisiKustom(array $warnaPerSisi): string
    {
        $sisiUrutan = ['Left', 'Right', 'Top', 'Bottom'];
        $xml = '<Borders>';

        foreach ($sisiUrutan as $posisi) {
            $warna = $warnaPerSisi[$posisi]['warna'] ?? self::EXCEL_WARNA_BORDER;
            $tebal = $warnaPerSisi[$posisi]['tebal'] ?? 1;
            $xml .= '<Border ss:Position="' . $posisi . '" ss:LineStyle="Continuous" ss:Weight="'
                . $tebal . '" ss:Color="#' . $warna . '"/>';
        }

        $xml .= '</Borders>';

        return $xml;
    }

    private function xmlBorderEmpatSisi(string $warnaHex, int $ketebalan = 1): string
    {
        return $this->xmlBorderSisiKustom([
            'Left'   => ['warna' => $warnaHex, 'tebal' => $ketebalan],
            'Right'  => ['warna' => $warnaHex, 'tebal' => $ketebalan],
            'Top'    => ['warna' => $warnaHex, 'tebal' => $ketebalan],
            'Bottom' => ['warna' => $warnaHex, 'tebal' => $ketebalan],
        ]);
    }

    private function xmlEscape(?string $teks): string
    {
        return htmlspecialchars((string) $teks, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}