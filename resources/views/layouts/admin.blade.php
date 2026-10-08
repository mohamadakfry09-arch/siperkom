@php
    // Data laporan default jika belum terhubung database secara penuh
    $defaultReports = $defaultReports ?? [
        ['id' => '#REP-2026-001', 'desa' => 'Weru Lor', 'pelapor' => 'Bpk. Ahmad', 'perangkat' => 'PC Admin Desa', 'jenis_perangkat' => 'PC / Komputer', 'kendala' => 'Blue Screen & Tidak Booting', 'status' => 'Dalam Perbaikan', 'prioritas' => 'Tinggi', 'teknisi' => 'Fahry Rizky Prayogi', 'tanggal' => '2026-07-28'],
        ['id' => '#REP-2026-002', 'desa' => 'Palimanan Barat', 'pelapor' => 'Ibu Siti', 'perangkat' => 'Printer Epson L3110', 'jenis_perangkat' => 'Printer / Scanner', 'kendala' => 'Tinta Macet & Bergaris', 'status' => 'Menunggu Part', 'prioritas' => 'Sedang', 'teknisi' => 'Dedi Kurniawan', 'tanggal' => '2026-07-27'],
        ['id' => '#REP-2026-003', 'desa' => 'Gegesik Kulon', 'pelapor' => 'Bpk. Rudi', 'perangkat' => 'Router Mikrotik', 'jenis_perangkat' => 'Jaringan / Router', 'kendala' => 'Jaringan Internet Sering Putus', 'status' => 'Selesai', 'prioritas' => 'Rendah', 'teknisi' => 'Fahry Rizky Prayogi', 'tanggal' => '2026-07-25'],
        ['id' => '#REP-2026-004', 'desa' => 'Sindanglaut', 'pelapor' => 'Ibu Wulan', 'perangkat' => 'Laptop Kaur Keuangan', 'jenis_perangkat' => 'Laptop', 'kendala' => 'Baterai Cepat Habis', 'status' => 'Selesai', 'prioritas' => 'Sedang', 'teknisi' => 'Siti Marlina', 'tanggal' => '2026-06-18'],
        ['id' => '#REP-2026-005', 'desa' => 'Beber', 'pelapor' => 'Bpk. Yanto', 'perangkat' => 'PC Operator SIPADES', 'jenis_perangkat' => 'PC / Komputer', 'kendala' => 'Mati Total', 'status' => 'Selesai', 'prioritas' => 'Tinggi', 'teknisi' => 'Agus Setiawan', 'tanggal' => '2026-06-05'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Bulanan & Ekspor | SIPERKOM</title>
    
    @include('partials.assets')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --mm-900: #072544; --mm-800: #0B3D6E; --mm-600: #14588A; --mm-400: #2E9EC4; --mm-200: #A9D8E6; --keraton-gold: #C9A227; --keraton-gold-soft: #E9CE7D; --keraton-maroon: #7A2331; --batik-cream: #FBF6EA; --ink: #1E2A32; }
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes floatClouds { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
        .mega-mendung-bg { background-color: var(--mm-900); background-image: radial-gradient(circle at 15% 20%, rgba(46,158,196,0.45) 0, transparent 42%), radial-gradient(circle at 80% 15%, rgba(169,216,230,0.25) 0, transparent 38%), radial-gradient(circle at 30% 75%, rgba(20,88,138,0.6) 0, transparent 50%), radial-gradient(circle at 85% 80%, rgba(46,158,196,0.3) 0, transparent 45%), radial-gradient(circle at 55% 45%, rgba(7,37,68,0.4) 0, transparent 55%); background-size: 200px 200px; }
        .cloud-lines { opacity: 0.15; animation: floatClouds 18s ease-in-out infinite; }
        .gold-divider { background: linear-gradient(90deg, transparent, var(--keraton-gold) 20%, var(--keraton-gold) 80%, transparent); height: 2px; }
        ::-webkit-scrollbar { width: 6px; height: 6px; } ::-webkit-scrollbar-track { background: #F1ECE1; } ::-webkit-scrollbar-thumb { background: var(--mm-600); border-radius: 10px; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)]"
    x-data="rekapApp(window.__initialReports)">

    <div class="min-h-screen flex flex-col lg:flex-row relative overflow-x-hidden">

        <!-- BACKDROP MOBILE -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 bg-black/60 z-40 lg:hidden transition-opacity"></div>

        <!-- ============ SIDEBAR ADMIN ============ -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:static inset-y-0 left-0 z-50 w-72 mega-mendung-bg flex flex-col justify-between p-6 text-white shrink-0 shadow-2xl transition-transform duration-300 ease-in-out h-screen overflow-y-auto">

            <svg class="cloud-lines absolute inset-0 w-full h-full pointer-events-none" viewBox="0 0 500 700" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <path d="M-50 120 Q 30 60, 110 120 T 270 120 T 430 120 T 590 120" stroke="white" stroke-width="3" fill="none"/>
                <path d="M-50 220 Q 30 160, 110 220 T 270 220 T 430 220 T 590 220" stroke="white" stroke-width="2" fill="none"/>
            </svg>

            <div class="relative z-10 space-y-6">
                <!-- Branding -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 lg:w-12 lg:h-12 rounded-full bg-white/95 flex items-center justify-center shadow-xl ring-[2.5px] ring-[var(--keraton-gold)]/70 p-1.5 shrink-0 transition-transform duration-200 hover:scale-105">
                            <img src="{{ asset('images/siperkom.png') }}" class="w-full h-full object-contain rounded-full" alt="Logo SIPERKOM">
                        </div>
                        <div class="leading-tight">
                            <div class="flex items-center gap-1.5">
                                <h1 class="font-display font-extrabold text-lg lg:text-xl tracking-tight text-white">SIPERKOM</h1>
                                <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[8.5px] font-bold bg-[var(--keraton-gold)]/20 text-[var(--keraton-gold-soft)] border border-[var(--keraton-gold)]/40 tracking-wider uppercase">Resmi</span>
                            </div>
                            <p class="text-[10px] lg:text-[11px] text-[var(--mm-200)] font-medium mt-0.5 tracking-wide">Pemerintah Kabupaten Cirebon</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden text-white p-1.5 rounded-lg hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="gold-divider w-full opacity-40"></div>

                <!-- Info Pengelola Terpadu (Admin & Teknisi) -->
                <a href="{{ route('admin.profil.edit') }}" class="bg-[var(--mm-800)]/70 hover:bg-[var(--mm-800)] border border-[var(--mm-600)]/50 hover:border-[var(--keraton-gold)]/50 p-3.5 rounded-2xl backdrop-blur-md flex items-center gap-3 shadow-inner transition block cursor-pointer group">
                    <div class="w-10 h-10 rounded-xl bg-[var(--keraton-gold)] text-[var(--mm-900)] font-bold font-display flex items-center justify-center text-sm shadow shrink-0 group-hover:scale-105 transition">
                        {{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}
                    </div>
                    <div class="overflow-hidden flex-1">
                        <p class="font-display font-bold text-sm break-words text-white group-hover:text-[var(--keraton-gold-soft)] transition" title="{{ Auth::user()->name ?? 'Administrator' }}">
                            {{ Auth::user()->name ?? 'Administrator' }}
                        </p>
                        <p class="text-[11px] text-[var(--mm-200)] truncate font-medium">
                            @php
                                $roleUser = strtolower(trim(Auth::user()->role ?? 'admin'));
                            @endphp
                            @if($roleUser === 'admin')
                                Administrator Sistem
                            @elseif($roleUser === 'teknisi')
                                Teknisi Operasional
                            @else
                                {{ ucwords($roleUser) }} Terpadu
                            @endif
                        </p>
                    </div>
                </a>

                <!-- Navigasi -->
                <nav class="space-y-1.5 pt-2">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-[var(--mm-200)]/60 px-3 mb-2">Panel Menu</p>
                    
                    <a href="{{ url('admin/dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl border text-xs font-semibold text-[var(--mm-200)] hover:bg-white/10 hover:text-white border-transparent transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard Utama
                    </a>
                    
                    <a href="{{ url('admin/antrean') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl border text-xs font-semibold text-[var(--mm-200)] hover:bg-white/10 hover:text-white border-transparent transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Antrean Perbaikan
                    </a>
                    
                    <a href="{{ url('admin/persentase') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl border text-xs font-semibold text-[var(--mm-200)] hover:bg-white/10 hover:text-white border-transparent transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        Persentase Kerusakan
                    </a>
                    
                    <a href="{{ url('admin/rekap') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl border text-xs font-semibold bg-white/10 text-white border-white/15 shadow-sm transition">
                        <svg class="w-4 h-4 text-[var(--keraton-gold-soft)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                        Rekap Bulanan (Excel)
                    </a>
                </nav>
            </div>

            <div class="relative z-10 pt-6 border-t border-white/10 space-y-3 mt-4">
                <a href="https://wa.me/6285321812643" target="_blank" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl text-xs font-semibold transition shadow flex items-center justify-center gap-2 border border-emerald-500/30">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.487 1.333 5.006l-1.417 5.176 5.297-1.389c1.474.802 3.136 1.224 4.773 1.225h.004c5.505 0 9.988-4.478 9.989-9.985 0-2.668-1.037-5.176-2.922-7.062a9.923 9.923 0 0 0-7.067-2.955z"/></svg>
                    Bantuan WA
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-[var(--keraton-maroon)] hover:bg-[#6A1D2A] text-white py-2.5 rounded-xl text-xs font-semibold transition shadow flex items-center justify-center gap-2 border border-white/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- ============ KONTEN UTAMA ============ -->
        <main class="flex-1 flex flex-col h-screen overflow-y-auto">

            <!-- Top Header -->
            <header class="bg-white border-b border-[#E3DCC8] px-6 py-4 sticky top-0 z-20 flex items-center justify-between shadow-sm gap-3">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] text-[var(--mm-950)] hover:bg-[#F3EBD8] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h2 class="font-display font-bold text-sm text-[var(--mm-900)] hidden sm:block">Rekap Data & Ekspor</h2>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Notifikasi Global Lonceng -->
                    <div class="relative">
                        <button @click="notifOpen = !notifOpen" class="relative p-2.5 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] hover:bg-[#F3EBD8] transition">
                            <svg class="w-4 h-4 text-[var(--mm-800)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span x-show="stats.perlu_perhatian > 0" x-cloak class="absolute -top-1 -right-1 bg-[var(--keraton-maroon)] text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center" x-text="stats.perlu_perhatian"></span>
                        </button>
                        <div x-show="notifOpen" x-cloak @click.away="notifOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white border border-[#E3DCC8] rounded-2xl shadow-xl z-30 overflow-hidden">
                            <div class="px-4 py-3 border-b border-[#E3DCC8] bg-[var(--batik-cream)] flex justify-between items-center">
                                <span class="text-xs font-bold text-[var(--mm-900)]">Notifikasi Sistem</span>
                                <span class="text-[10px] text-blue-600 font-semibold cursor-pointer hover:underline" @click="showToast('Semua notifikasi ditandai dibaca')">Tandai dibaca</span>
                            </div>
                            <div class="max-h-64 overflow-y-auto divide-y divide-[#E3DCC8]">
                                <template x-for="r in reports.filter(r => r.status !== 'Selesai')" :key="r.id">
                                    <div class="w-full text-left px-4 py-3 hover:bg-gray-50 transition">
                                        <p class="text-[11px] font-bold text-[var(--mm-800)]" x-text="r.id + ' — ' + r.desa"></p>
                                        <p class="text-[10px] text-gray-500 mt-0.5" x-text="'Status: ' + r.status + ' | Teknisi: ' + r.teknisi"></p>
                                    </div>
                                </template>
                                <p x-show="reports.filter(r => r.status !== 'Selesai').length === 0" class="px-4 py-6 text-xs text-gray-400 text-center">Tidak ada antrean tertunda 🎉</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-right pl-2 border-l border-[#E3DCC8]">
                        <p class="text-xs font-bold text-[var(--mm-900)]">{{ date('d M Y') }}</p>
                        <p class="text-[10px] text-gray-500">Kabupaten Cirebon</p>
                    </div>
                </div>
            </header>

            <!-- Isi Halaman Rekap Bulanan -->
            <div class="p-6 sm:p-8 space-y-6 flex-1">
                
                <!-- Card Rekap Bulanan -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-[#E3DCC8] space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
                        <div class="flex-1 pr-4">
                            <h3 class="font-display text-xl font-bold text-[var(--mm-900)]">Rekap Laporan per Bulan</h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                Tabel di bawah menunjukkan jumlah akumulasi laporan dan persentase penyelesaian per bulan. 
                                Anda dapat mengunduh seluruh data (Ringkasan dan Detail Laporan lengkap) ke dalam satu file Excel (.xlsx).
                            </p>
                        </div>
                        
                        <button @click="exportMonthlyExcel()" class="shrink-0 whitespace-nowrap px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition shadow flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/></svg>
                            Unduh Excel Bulanan
                        </button>
                    </div>

                    <div class="overflow-x-auto rounded-xl border border-[#E3DCC8]">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[var(--batik-cream)] text-[var(--mm-900)] text-xs font-bold border-b border-[#E3DCC8]">
                                    <th class="p-4">Bulan & Tahun</th>
                                    <th class="p-4 text-center">Total Laporan</th>
                                    <th class="p-4 text-center">Dalam Perbaikan</th>
                                    <th class="p-4 text-center">Menunggu Part</th>
                                    <th class="p-4 text-center text-emerald-700">Selesai</th>
                                    <th class="p-4 text-center">Persentase Selesai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E3DCC8] text-sm">
                                <template x-for="m in monthlyRecap" :key="m.key">
                                    <tr class="hover:bg-amber-50/40 transition">
                                        <td class="p-4 font-bold text-[var(--mm-800)]" x-text="m.label"></td>
                                        <td class="p-4 text-center font-extrabold" x-text="m.total"></td>
                                        <td class="p-4 text-center text-amber-700 font-semibold" x-text="m.perbaikan"></td>
                                        <td class="p-4 text-center text-blue-700 font-semibold" x-text="m.part"></td>
                                        <td class="p-4 text-center text-emerald-700 font-bold" x-text="m.selesai"></td>
                                        <td class="p-4 text-center">
                                            <div class="flex flex-col items-center gap-1.5">
                                                <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-lg text-xs font-bold" x-text="m.persen + '%'"></span>
                                                <div class="w-full max-w-[80px] h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                                    <div class="h-full bg-emerald-500 rounded-full" :style="'width: ' + m.persen + '%'"></div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="monthlyRecap.length === 0">
                                    <td colspan="6" class="p-8 text-center text-gray-400 text-sm">Belum ada data laporan untuk direkap bulan ini.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ============ TOAST NOTIFIKASI ============ -->
    <div x-show="toast.show && toast.message" x-cloak x-transition
         class="fixed bottom-5 right-5 z-[60] bg-[var(--mm-900)] text-white px-4 py-3 rounded-xl shadow-2xl text-xs font-semibold flex items-center gap-2 border border-white/10 max-w-xs">
        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span x-text="toast.message"></span>
    </div>

    <!-- INJEKSI DATA -->
    <script>
        window.__initialReports = {!! json_encode(array_values($defaultReports)) !!};
    </script>
    
    <!-- Alpine.js & SheetJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
    
    <script>
        function rekapApp(initialReports) {
            return {
                sidebarOpen: false,
                notifOpen: false,
                reports: initialReports || [],
                toast: { show: false, message: '' },

                get stats() {
                    return {
                        perlu_perhatian: this.reports.filter(r => r.status !== 'Selesai').length,
                    };
                },

                classifyDevice(perangkat) {
                    if (!perangkat) return 'Lainnya';
                    const p = perangkat.toLowerCase();
                    if (p.includes('printer') || p.includes('scanner')) return 'Printer / Scanner';
                    if (p.includes('router') || p.includes('mikrotik') || p.includes('jaringan') || p.includes('wifi')) return 'Jaringan / Router';
                    if (p.includes('laptop')) return 'Laptop';
                    if (p.includes('pc') || p.includes('komputer') || p.includes('cpu')) return 'PC / Komputer';
                    return 'Lainnya';
                },

                get monthlyRecap() {
                    const bulanNama = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    const groups = {};
                    
                    this.reports.forEach(r => {
                        const key = r.tanggal ? r.tanggal.slice(0, 7) : '2026-07';
                        if (!groups[key]) groups[key] = { total: 0, perbaikan: 0, part: 0, selesai: 0 };
                        
                        groups[key].total++;
                        if (r.status === 'Dalam Perbaikan') groups[key].perbaikan++;
                        if (r.status === 'Menunggu Part') groups[key].part++;
                        if (r.status === 'Selesai') groups[key].selesai++;
                    });

                    return Object.keys(groups).sort().reverse().map(key => {
                        const [year, month] = key.split('-');
                        const g = groups[key];
                        return {
                            key,
                            label: bulanNama[parseInt(month, 10) - 1] + ' ' + year,
                            total: g.total,
                            perbaikan: g.perbaikan,
                            part: g.part,
                            selesai: g.selesai,
                            persen: g.total ? Math.round((g.selesai / g.total) * 100) : 0,
                        };
                    });
                },

                exportMonthlyExcel() {
                    if (typeof XLSX === 'undefined') {
                        this.showToast('Gagal memuat pustaka Excel. Pastikan koneksi internet lancar.');
                        return;
                    }

                    // 1. Sheet Ringkasan Bulanan
                    const rows = this.monthlyRecap.map(m => ({
                        'Bulan': m.label,
                        'Total Laporan': m.total,
                        'Dalam Perbaikan': m.perbaikan,
                        'Menunggu Part': m.part,
                        'Selesai': m.selesai,
                        'Persentase Selesai': m.persen + '%',
                    }));

                    // 2. Sheet Detail Seluruh Laporan
                    const detailRows = this.reports.map(r => ({
                        'ID Laporan': r.id,
                        'Tanggal Lapor': r.tanggal,
                        'Desa / Instansi': r.desa,
                        'Nama Pelapor': r.pelapor,
                        'Jenis Perangkat': r.jenis_perangkat || this.classifyDevice(r.perangkat),
                        'Nama / Merk Perangkat': r.perangkat,
                        'Detail Kendala': r.kendala,
                        'Tingkat Prioritas': r.prioritas,
                        'Teknisi Bertugas': r.teknisi || 'Belum ditugaskan',
                        'Status Akhir': r.status,
                    }));

                    const wb = XLSX.utils.book_new();
                    const ws1 = XLSX.utils.json_to_sheet(rows);
                    const ws2 = XLSX.utils.json_to_sheet(detailRows);

                    ws1['!cols'] = [{wch: 18}, {wch: 15}, {wch: 16}, {wch: 15}, {wch: 12}, {wch: 20}];
                    ws2['!cols'] = [
                        {wch: 15}, {wch: 15}, {wch: 22}, {wch: 20}, {wch: 22}, 
                        {wch: 25}, {wch: 35}, {wch: 15}, {wch: 20}, {wch: 15}
                    ];

                    XLSX.utils.book_append_sheet(wb, ws1, 'Rekap Bulanan');
                    XLSX.utils.book_append_sheet(wb, ws2, 'Detail Laporan Lengkap');

                    const fileName = 'Rekap_SIPERKOM_' + new Date().toISOString().slice(0, 10) + '.xlsx';
                    XLSX.writeFile(wb, fileName);
                    
                    this.showToast('File Excel Rekap berhasil diunduh!');
                },

                showToast(message) {
                    this.toast = { show: true, message: message };
                    setTimeout(() => { this.toast.show = false; }, 3500);
                }
            };
        }
    </script>
</body>
</html>