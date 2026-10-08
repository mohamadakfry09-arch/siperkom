@php
    // Data laporan ditarik dari database melalui Controller AdminController
    $defaultReports = $defaultReports ?? [];
    $bulanPilih = $bulanPilih ?? request('bulan', date('m'));
    $tahunPilih = $tahunPilih ?? request('tahun', date('Y'));

    $monthsIndonesian = $monthsIndonesian ?? [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
        '04' => 'April',   '05' => 'Mei',      '06' => 'Juni', 
        '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September', 
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Rekap Bulanan &amp; Detail Laporan | SIPERKOM Kabupaten Cirebon</title>
    
    <link rel="icon" href="{{ asset('images/cirebon.png') }}" type="image/png">
    @include('partials.assets')
    @include('partials.session-guard')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { 
            --mm-900: #072544; 
            --mm-800: #0B3D6E; 
            --mm-600: #14588A; 
            --mm-400: #2E9EC4; 
            --mm-200: #A9D8E6; 
            --keraton-gold: #C9A227; 
            --keraton-gold-soft: #E9CE7D; 
            --keraton-maroon: #7A2331; 
            --batik-cream: #FBF6EA; 
            --ink: #1E2A32; 
        }
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Latar Belakang Batik Mega Mendung Otentik */
        .mega-mendung-bg { 
            background-color: #0b2f56; 
            background-image: 
                linear-gradient(180deg, rgba(7, 37, 68, 0.85) 0%, rgba(9, 45, 82, 0.92) 100%),
                url("{{ asset('images/batik mega mendung.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Garis Emas Pembatas */
        .gold-divider { 
            background: linear-gradient(90deg, rgba(201, 162, 39, 0.9) 0%, rgba(201, 162, 39, 0.4) 60%, transparent 100%); 
            height: 1.5px; 
        }

        /* Sudut Melengkung Mobile Drawer */
        .mobile-rounded-sidebar {
            border-top-right-radius: 1.75rem;
            border-bottom-right-radius: 1.75rem;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Hilangkan scrollbar native */
        .no-scrollbar::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }
        .no-scrollbar {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }

        @media (min-width: 1024px) {
            .mobile-rounded-sidebar {
                border-top-right-radius: 1.5rem !important;
                border-bottom-right-radius: 1.5rem !important;
                border-top-left-radius: 1.5rem !important;
                border-bottom-left-radius: 1.5rem !important;
            }
            .desktop-layout {
                height: 100vh !important;
                max-height: 100vh !important;
                height: 100dvh !important;
                max-height: 100dvh !important;
                overflow: hidden !important;
            }
            .desktop-layout > aside {
                width: min(19rem, calc(100vw - 1rem)) !important;
                min-width: min(19rem, calc(100vw - 1rem)) !important;
                max-width: min(19rem, calc(100vw - 1rem)) !important;
                flex-shrink: 0 !important;
                height: 100% !important;
                max-height: 100% !important;
                min-height: 0 !important;
            }
            .desktop-layout > aside .pelapor-mobile-actions {
                position: relative !important;
                bottom: auto !important;
                left: auto !important;
                right: auto !important;
                margin-top: auto !important;
            }
            .desktop-layout > main {
                min-width: 0 !important;
                flex: 1 1 0% !important;
                height: 100% !important;
                max-height: 100% !important;
                min-height: 0 !important;
            }
        }

        /* Scrollbar utama halaman */
        ::-webkit-scrollbar { width: 6px; height: 6px; } 
        ::-webkit-scrollbar-track { background: #F1ECE1; } 
        ::-webkit-scrollbar-thumb { background: var(--mm-600); border-radius: 10px; }
        
        /* Hilangkan garis tebal overflow tabel saat kosong */
        .table-responsive {
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(20, 88, 138, 0.25) transparent;
        }
        .table-responsive::-webkit-scrollbar {
            height: 5px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: transparent;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: rgba(20, 88, 138, 0.25);
            border-radius: 9999px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: var(--mm-600);
        }

        [x-cloak] { display: none !important; }
        
        .scrollable-content {
            -webkit-overflow-scrolling: touch;
            touch-action: pan-y;
            min-width: 0;
        }

        html.overflow-hidden, body.overflow-hidden {
            overflow: hidden !important;
            touch-action: none !important;
            overscroll-behavior: none !important;
            height: 100% !important;
        }

        .recap-row-selesai { border-left: 4px solid #16a34a !important; }
        .recap-row-ditolak { border-left: 4px solid #dc2626 !important; }
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)]"
    x-data="rekapApp(window.__initialReports)" 
    x-init="init()"
    :class="{ 'overflow-hidden touch-none overscroll-none': sidebarOpen || mediaModalOpen }">

    <div class="desktop-layout flex flex-row relative min-h-screen lg:min-h-0 lg:p-3 lg:gap-3 bg-[var(--batik-cream)]">

        <!-- BACKDROP MOBILE -->
        <div x-show="sidebarOpen"
            x-cloak
            x-transition.opacity
            @click="sidebarOpen = false"
            @touchmove.prevent
            @wheel.prevent
            class="fixed inset-0 bg-black/60 z-[9998] lg:hidden transition-opacity touch-none overscroll-none">
        </div>

        <!-- ============ SIDEBAR ADMIN (IDENTIK DENGAN PELAPOR) ============ -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            @touchmove.stop.prevent="if (window.innerWidth < 1024) $event.preventDefault()"
            class="fixed lg:static top-3.5 left-0 z-[9999] lg:z-auto w-[82vw] max-w-[290px] lg:w-[min(19rem,calc(100vw-1rem))] lg:max-w-none mega-mendung-bg flex flex-col justify-between p-3.5 sm:p-4 lg:p-4.5 text-white shrink-0 shadow-2xl transition-transform duration-300 ease-out h-[78vh] max-h-[580px] lg:h-full lg:max-h-none overflow-hidden lg:overflow-y-auto no-scrollbar touch-none lg:touch-auto overscroll-none select-none mobile-rounded-sidebar border-0 outline-none">

            <!-- BAGIAN ATAS: USER BADGE, MENU -->
            <div class="space-y-3.5 lg:space-y-4">

                <!-- Admin Profile Card (Modern Glassmorphic Style & Interactive) -->
                <a href="{{ route('admin.profil.edit') }}" 
                   class="group relative overflow-hidden bg-gradient-to-r from-white/[0.12] via-[#0c3966]/60 to-white/[0.04] hover:from-white/[0.18] hover:via-[#0c3966]/80 hover:to-white/[0.08] active:scale-[0.98] border border-white/15 hover:border-[var(--keraton-gold)]/70 active:border-[var(--keraton-gold)] p-2.5 sm:p-3 rounded-2xl flex items-center gap-2.5 sm:gap-3 shadow-lg shadow-black/20 backdrop-blur-md transition-all duration-200 cursor-pointer select-none touch-manipulation">
                    
                    <!-- Ambient Top Highlight & Soft Glow -->
                    <div class="absolute inset-x-0 top-0 h-[1px] bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
                    <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-[var(--keraton-gold)]/10 rounded-full blur-xl group-hover:bg-[var(--keraton-gold)]/20 transition-all pointer-events-none"></div>

                    <!-- Avatar with Ring and Live Status Dot -->
                    <div class="relative shrink-0">
                        @if(!empty(Auth::user()->avatar))
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                 class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl object-cover ring-2 ring-[var(--keraton-gold)]/80 ring-offset-2 ring-offset-[#072544] shadow-md group-hover:scale-105 group-hover:ring-[var(--keraton-gold)] transition-all duration-200" 
                                 alt="Foto Profil">
                        @else
                            <img src="{{ asset('images/admin-avatar.svg') }}" 
                                 class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl object-cover ring-2 ring-[var(--keraton-gold)]/80 ring-offset-2 ring-offset-[#072544] shadow-md bg-[#072544] group-hover:scale-105 transition-all duration-200" 
                                 alt="Icon Administrator">
                        @endif
                    </div>

                    <!-- User Name & Role Badge -->
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-xs sm:text-[13px] text-white leading-tight tracking-tight truncate group-hover:text-[var(--keraton-gold-soft)] transition-colors" 
                           title="{{ Auth::user()->name ?? 'Administrator' }}">
                            {{ Auth::user()->name ?? 'Administrator' }}
                        </p>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/10 border border-white/20 text-white/70 text-[10px] font-semibold tracking-wide">
                                <svg class="w-2.5 h-2.5 shrink-0 text-[var(--keraton-gold-soft)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Admin &amp; Teknisi</span>
                            </span>
                        </div>
                    </div>

                    <!-- Right Chevron / Action Hint -->
                    <div class="shrink-0 w-6 h-6 rounded-lg bg-white/5 border border-white/10 group-hover:bg-white/15 group-hover:border-[var(--keraton-gold)]/40 flex items-center justify-center text-white/40 group-hover:text-[var(--keraton-gold-soft)] group-hover:translate-x-0.5 transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>

                <!-- Navigasi Panel Menu Admin -->
                <nav class="space-y-1 pt-1" aria-label="Menu Admin">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-white px-2 mb-2">MENU ADMIN</p>

                    <!-- 1. Dashboard Utama -->
                    <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('admin.dashboard') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard Utama</span>
                    </a>

                    <!-- 2. Antrean Perbaikan -->
                    <a href="{{ route('admin.antrean') }}" @click="markAllAdminNotifsRead(); sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.antrean*') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('admin.antrean*') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.antrean*') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        <span>Antrean Perbaikan</span>
                    </a>

                    <!-- 3. Persentase Kerusakan -->
                    <a href="{{ route('admin.persentase') }}" @click="sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.persentase*') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('admin.persentase*') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.persentase*') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        <span>Persentase Kerusakan</span>
                    </a>

                    <!-- 4. Rekap Bulanan -->
                    <a href="{{ route('admin.rekap') }}" @click="sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.rekap*') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('admin.rekap*') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.rekap*') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V9a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                        <span>Rekap Bulanan (Excel)</span>
                    </a>

                    <!-- 5. Manajemen Akun Pelapor -->
                    <a href="{{ route('admin.manajemen') }}" @click="sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('admin.manajemen*') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('admin.manajemen*') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('admin.manajemen*') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Manajemen Akun Pelapor</span>
                    </a>
                </nav>
            </div>

            <!-- BAGIAN BAWAH: TOMBOL KELUAR -->
            <div class="pelapor-mobile-actions relative z-10 pt-4 pb-1 lg:pt-4 lg:pb-0 space-y-2 lg:space-y-2.5 shrink-0 mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center justify-center gap-2 bg-[#7A2331] hover:bg-[#6A1D2A] active:scale-[0.98] text-white py-2.5 rounded-2xl text-xs font-bold transition shadow-md border border-white/10">
                        <svg class="w-4 h-4 shrink-0 translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="translate-y-0.5">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ============ KONTEN UTAMA ============ -->
        <main class="flex-1 flex flex-col h-full bg-white lg:rounded-3xl shadow-sm border border-[#E3DCC8] transition-all"
              :class="sidebarOpen ? 'overflow-hidden pointer-events-none touch-none select-none' : 'overflow-y-auto scrollable-content'">

            <!-- Header Halaman -->
            <header class="bg-white border-b border-[#E3DCC8] px-3 sm:px-6 py-2.5 sm:py-3.5 sticky top-0 z-30 flex items-center justify-between shadow-2xs gap-2 sm:gap-3 shrink-0 lg:rounded-t-3xl">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3">
                    <button type="button" @click="sidebarOpen = true" aria-label="Buka menu" class="lg:hidden w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] text-[var(--mm-900)] shrink-0 hover:bg-[#F3EBD8] active:scale-95 transition flex items-center justify-center shadow-2xs">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="font-display font-bold text-[11px] leading-tight sm:text-sm text-[var(--mm-900)]">Rekapitulasi Akhir &amp; Ekspor Laporan</h2>
                        <p class="text-[9px] sm:text-[10px] text-gray-500">Rekap status selesai &amp; ditolak per periode</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <!-- Notifikasi Lonceng Admin: Khusus Laporan Masuk Baru dari Pelapor -->
                    <div class="relative" x-data="{ notifOpen: false }">
                        <button type="button" @click="notifOpen = !notifOpen" 
                                class="relative w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] hover:bg-[#F3EBD8] active:scale-95 transition cursor-pointer text-[var(--mm-900)] flex items-center justify-center shadow-2xs shrink-0" 
                                aria-label="Notifikasi Laporan Masuk" title="Notifikasi Laporan Masuk">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--mm-800)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            
                            <!-- Counter Badge: Jumlah Laporan Masuk Baru Belum Dibaca Admin -->
                            <span x-show="unreadAdminReports.length > 0" x-cloak 
                                  class="absolute -top-1 -right-1 bg-[var(--keraton-maroon)] text-white text-[9px] font-bold min-w-[16px] h-[16px] px-0.5 rounded-full flex items-center justify-center shadow-sm animate-pulse border border-white" 
                                  x-text="unreadAdminReports.length"></span>
                        </button>
                        
                        <!-- Dropdown Notifikasi Laporan Masuk Admin -->
                        <div x-show="notifOpen" x-cloak @click.away="notifOpen = false" x-transition 
                             class="absolute right-0 mt-3 w-[270px] sm:w-[350px] bg-white border border-[#E3DCC8] rounded-2xl shadow-2xl z-[999] overflow-hidden text-left">
                            <div class="px-3.5 py-2.5 border-b border-[#E3DCC8] bg-[var(--batik-cream)] flex justify-between items-center">
                                <div>
                                    <p class="text-[11px] font-bold text-[var(--mm-900)]">Laporan Masuk (Admin)</p>
                                    <p class="text-[8.5px] text-gray-500">Antrean masuk dari pelapor</p>
                                </div>
                                <span x-show="unreadAdminReports.length > 0" class="text-[9px] text-blue-600 font-semibold cursor-pointer hover:underline" @click="markAllAdminNotifsRead()">Tandai dibaca</span>
                            </div>
                            <div class="max-h-72 overflow-y-auto divide-y divide-[#E3DCC8] bg-white">
                                <template x-for="r in notifReports" :key="'admin-notif-' + (r.db_id || r.id)">
                                    <a :href="'{{ route('admin.antrean') }}?open_id=' + (r.db_id || '')" 
                                       @click="markReportRead(r.db_id || r.id); notifOpen = false"
                                       class="flex items-start gap-2.5 w-full text-left px-3.5 py-2.5 hover:bg-amber-50/60 transition cursor-pointer group"
                                       :class="{ 'bg-amber-50/40': !seenAdminReportIds.includes(String(r.db_id || r.id)) }">
                                        <!-- PP Pelapor Mini -->
                                        <div class="shrink-0 mt-0.5">
                                            <img :src="r.pelapor_avatar || '{{ asset('images/pelapor-avatar.svg') }}'" 
                                                 :alt="r.pelapor"
                                                 class="w-8 h-8 rounded-full object-cover shadow-2xs border border-[#E3DCC8] ring-1 ring-white"
                                                 onerror="this.src='{{ asset('images/pelapor-avatar.svg') }}'">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                                <p class="text-[10px] font-bold text-[var(--mm-800)] truncate group-hover:text-blue-700" x-text="(r.id || 'REP') + ' — ' + (r.alamat || r.kecamatan || 'Alamat')"></p>
                                                <span class="text-[8px] font-bold px-1.5 py-0.2 rounded shrink-0"
                                                      :class="r.status === 'Menunggu Part' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-amber-100 text-amber-900 border border-amber-300'"
                                                      x-text="r.status === 'Menunggu Part' ? 'Menunggu Part' : 'Laporan Baru'">
                                                </span>
                                            </div>
                                            <p class="text-[9.5px] text-gray-700 font-medium truncate" x-text="(r.perangkat || '-') + ' • Pelapor: ' + (r.pelapor || 'Staf')"></p>
                                            <p class="text-[8.5px] text-gray-500 truncate mt-0.5" x-text="'Kendala: ' + (r.kendala || '-')"></p>
                                            <div class="flex items-center justify-between mt-1">
                                                <span class="text-[8px] text-gray-400" x-text="'Masuk: ' + (r.tanggal_masuk || '-')"></span>
                                                <span class="text-[8px] font-semibold text-blue-600 group-hover:underline">Buka Tindak Lanjut →</span>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                                <div x-show="notifReports.length === 0" class="px-3.5 py-6 text-[10px] text-gray-500 text-center bg-white font-medium leading-relaxed">
                                    Tidak ada antrean laporan baru 🎉
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-[78px] shrink-0 border-l border-[#E3DCC8] pl-2 sm:w-auto sm:pl-3 text-right">
                        <p class="text-[10px] font-bold leading-tight whitespace-nowrap sm:text-xs text-[var(--mm-900)]">{{ date('d M Y') }}</p>
                        <p class="text-[8px] leading-tight sm:text-[10px] text-gray-500">Kabupaten Cirebon</p>
                    </div>
                </div>
            </header>

            <!-- Isi Halaman -->
            <div class="p-3.5 sm:p-6 lg:p-8 space-y-4 sm:space-y-6 flex-1 pb-16">
                
                <!-- Filter Bulan & Tahun -->
                <div class="bg-[var(--batik-cream)]/40 p-3.5 sm:p-5 rounded-2xl border border-[#E3DCC8] relative z-20">
                    <form method="GET" action="{{ route('admin.rekap') }}" 
                          class="grid grid-cols-2 sm:flex sm:items-end gap-2.5 sm:gap-4"
                          x-data="{
                              bulanOpen: false,
                              tahunOpen: false,
                              selectedBulan: '{{ $bulanPilih }}',
                              selectedBulanName: '{{ $monthsIndonesian[$bulanPilih] ?? 'Januari' }}',
                              selectedTahun: '{{ $tahunPilih }}',
                              selectBulan(val, name) {
                                  this.selectedBulan = val;
                                  this.selectedBulanName = name;
                                  this.bulanOpen = false;
                              },
                              selectTahun(val) {
                                  this.selectedTahun = val;
                                  this.tahunOpen = false;
                              }
                          }">
                        <input type="hidden" name="bulan" :value="selectedBulan">
                        <input type="hidden" name="tahun" :value="selectedTahun">

                        <!-- Dropdown Kustom: Pilih Bulan -->
                        <div class="col-span-1 relative sm:w-44" @click.outside="bulanOpen = false">
                            <label class="block text-xs font-bold text-[var(--mm-900)] mb-1">Pilih Bulan:</label>
                            <button type="button" 
                                    @click="bulanOpen = !bulanOpen; tahunOpen = false" 
                                    :class="bulanOpen ? 'border-[var(--keraton-gold)] ring-2 ring-[var(--keraton-gold)]/20 bg-amber-50/50' : 'border-[#E3DCC8] bg-white hover:bg-[var(--batik-cream)]/50'"
                                    class="w-full px-3 py-2 rounded-xl border text-xs font-semibold text-[var(--mm-900)] flex items-center justify-between gap-1.5 focus:outline-none transition cursor-pointer select-none">
                                <span x-text="selectedBulanName" class="truncate font-semibold"></span>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200 shrink-0" 
                                     :class="bulanOpen ? 'rotate-180 text-[var(--keraton-gold)]' : ''" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Menu Popup Bulan -->
                            <div x-show="bulanOpen"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                                 x-cloak
                                 class="absolute left-0 mt-1.5 w-52 sm:w-56 bg-white rounded-2xl shadow-xl border border-[#E3DCC8] p-1.5 z-50 ring-1 ring-black/5 max-h-64 overflow-y-auto no-scrollbar">
                                @foreach ($monthsIndonesian as $num => $name)
                                    <button type="button"
                                            @click="selectBulan('{{ $num }}', '{{ $name }}')"
                                            :class="selectedBulan === '{{ $num }}' ? 'bg-[var(--mm-800)] text-white font-bold' : 'text-gray-700 hover:bg-[var(--batik-cream)] hover:text-[var(--mm-900)] font-medium'"
                                            class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition cursor-pointer text-left">
                                        <span>{{ $name }}</span>
                                        <span class="text-[10px] font-mono" :class="selectedBulan === '{{ $num }}' ? 'text-[var(--keraton-gold-soft)] font-bold' : 'text-gray-400'">{{ $num }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Dropdown Kustom: Pilih Tahun -->
                        <div class="col-span-1 relative sm:w-28" @click.outside="tahunOpen = false">
                            <label class="block text-xs font-bold text-[var(--mm-900)] mb-1">Pilih Tahun:</label>
                            <button type="button" 
                                    @click="tahunOpen = !tahunOpen; bulanOpen = false" 
                                    :class="tahunOpen ? 'border-[var(--keraton-gold)] ring-2 ring-[var(--keraton-gold)]/20 bg-amber-50/50' : 'border-[#E3DCC8] bg-white hover:bg-[var(--batik-cream)]/50'"
                                    class="w-full px-3 py-2 rounded-xl border text-xs font-semibold text-[var(--mm-900)] flex items-center justify-between gap-1.5 focus:outline-none transition cursor-pointer select-none">
                                <span x-text="selectedTahun" class="truncate font-semibold"></span>
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200 shrink-0" 
                                     :class="tahunOpen ? 'rotate-180 text-[var(--keraton-gold)]' : ''" 
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Menu Popup Tahun -->
                            <div x-show="tahunOpen"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                                 x-cloak
                                 class="absolute left-0 mt-1.5 w-32 bg-white rounded-2xl shadow-xl border border-[#E3DCC8] p-1.5 z-50 ring-1 ring-black/5 max-h-56 overflow-y-auto no-scrollbar">
                                @for ($y = 2024; $y <= 2030; $y++)
                                    <button type="button"
                                            @click="selectTahun('{{ $y }}')"
                                            :class="selectedTahun === '{{ $y }}' ? 'bg-[var(--mm-800)] text-white font-bold' : 'text-gray-700 hover:bg-[var(--batik-cream)] hover:text-[var(--mm-900)] font-medium'"
                                            class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition cursor-pointer text-left">
                                        <span>{{ $y }}</span>
                                        @if ($y == date('Y'))
                                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded" :class="selectedTahun === '{{ $y }}' ? 'bg-amber-400/20 text-[var(--keraton-gold-soft)]' : 'bg-amber-100 text-amber-800'">Kini</span>
                                        @endif
                                    </button>
                                @endfor
                            </div>
                        </div>

                        <div class="col-span-2 sm:col-span-1 flex items-center gap-2">
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-[var(--mm-800)] hover:bg-[var(--mm-900)] text-white text-xs font-bold rounded-xl transition shadow cursor-pointer">
                                Filter Histori
                            </button>
                            @if($bulanPilih != date('m') || $tahunPilih != date('Y'))
                                <a href="{{ route('admin.rekap') }}" title="Kembali ke Bulan Berjalan" class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 text-xs font-bold rounded-xl transition shadow-xs whitespace-nowrap">
                                    Bulan Ini
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- ================= STATE KOSONG (JIKA BELUM ADA SELESAI / DITOLAK) ================= -->
                <div x-show="recapReports.length === 0" x-cloak class="bg-white p-8 sm:p-14 rounded-3xl border border-[#E3DCC8] text-center shadow-sm space-y-3">
                    <div class="relative w-20 h-20 mx-auto mb-3">
                        <div class="w-20 h-20 bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl flex items-center justify-center text-amber-600 border border-amber-200/80 shadow-lg ring-4 ring-amber-50/60">
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V9a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                        </div>
                    </div>
                    <h4 class="font-display font-bold text-base text-[var(--mm-900)]">Belum Ada Rekap Laporan</h4>
                    <p class="text-xs text-gray-500 max-w-md mx-auto">
                        Data rekapitulasi akan otomatis muncul di halaman ini setelah ada tiket perbaikan yang berstatus <span class="text-emerald-700 font-bold">Selesai</span> atau <span class="text-red-600 font-bold">Ditolak</span>.
                    </p>
                </div>

                <!-- ================= CARD 1: AKUMULASI REKAP BULANAN (HANYA MUNCUL JIKA ADA DATA) ================= -->
                <div x-show="monthlyRecap.length > 0" x-cloak class="bg-white p-3.5 sm:p-6 rounded-2xl shadow-sm border border-[#E3DCC8] space-y-4 sm:space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                        <div>
                            <h3 class="font-display text-base sm:text-lg font-bold text-[var(--mm-900)]">Akumulasi Rekap Tahunan &amp; Bulanan</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Menghitung tiket yang berstatus selesai atau ditolak.</p>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:flex items-center gap-2 w-full sm:w-auto shrink-0">
                            <a href="{{ route('admin.export.excel', ['bulan' => $bulanPilih, 'tahun' => $tahunPilih]) }}" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Unduh Excel</span>
                            </a>
                            <a href="{{ route('admin.export.pdf', ['bulan' => $bulanPilih, 'tahun' => $tahunPilih]) }}" target="_blank" class="px-3.5 py-2 bg-[var(--keraton-maroon)] hover:bg-[#6A1D2A] text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center justify-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span>Unduh PDF</span>
                            </a>
                        </div>
                    </div>

                    <!-- Tampilan Mobile Akumulasi Rekap (block lg:hidden) -->
                    <div class="block lg:hidden space-y-2.5">
                        <template x-for="m in monthlyRecap" :key="'m-recap-' + m.key">
                            <div class="bg-[var(--batik-cream)]/40 rounded-xl border border-[#E3DCC8] p-3 space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-xs text-[var(--mm-900)]" x-text="m.label"></span>
                                    <span class="text-[10px] font-extrabold px-2 py-0.5 rounded-full bg-white border border-[#E3DCC8] text-[var(--mm-800)]" x-text="m.total + ' Tiket'"></span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-[10px]">
                                    <div class="bg-white/80 p-2 rounded-lg border border-emerald-200/60 flex items-center justify-between">
                                        <span class="text-gray-500">Selesai:</span>
                                        <span class="font-bold text-emerald-700" x-text="m.selesai"></span>
                                    </div>
                                    <div class="bg-white/80 p-2 rounded-lg border border-red-200/60 flex items-center justify-between">
                                        <span class="text-gray-500">Ditolak:</span>
                                        <span class="font-bold text-red-600" x-text="m.ditolak"></span>
                                    </div>
                                </div>
                                <div class="space-y-1 pt-0.5">
                                    <div class="flex justify-between text-[9.5px] text-gray-500">
                                        <span>Tingkat Penyelesaian</span>
                                        <span class="font-bold text-gray-700" x-text="m.persen + '%'"></span>
                                    </div>
                                    <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                                        <div class="bg-emerald-500 h-full rounded-full transition-all duration-300" :style="'width: ' + m.persen + '%'"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Tampilan Desktop Akumulasi Rekap (hidden lg:block) -->
                    <div class="hidden lg:block table-responsive rounded-xl border border-[#E3DCC8]">
                        <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
                            <thead>
                                <tr class="bg-[var(--batik-cream)] text-[var(--mm-900)] font-bold border-b border-[#E3DCC8]">
                                    <th class="p-3.5">Periode Rekap</th>
                                    <th class="p-3.5 text-center">Tiket Direkap</th>
                                    <th class="p-3.5 text-center text-emerald-700">Selesai Diperbaiki</th>
                                    <th class="p-3.5 text-center text-red-700">Ditolak / Batal</th>
                                    <th class="p-3.5 text-center">Tingkat Penyelesaian (%)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E3DCC8]">
                                <template x-for="m in monthlyRecap" :key="m.key">
                                    <tr class="hover:bg-amber-50/40 transition">
                                        <td class="p-3.5 font-bold text-[var(--mm-800)]" x-text="m.label"></td>
                                        <td class="p-3.5 text-center font-bold text-gray-800" x-text="m.total"></td>
                                        <td class="p-3.5 text-center font-bold text-emerald-700" x-text="m.selesai"></td>
                                        <td class="p-3.5 text-center font-bold text-red-600" x-text="m.ditolak"></td>
                                        <td class="p-3.5 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <div class="w-24 bg-gray-200 h-2 rounded-full overflow-hidden">
                                                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-300" :style="'width: ' + m.persen + '%'"></div>
                                                </div>
                                                <span class="font-bold text-[11px] text-gray-700" x-text="m.persen + '%'"></span>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= CARD 2: TABEL DETAIL (HANYA MUNCUL JIKA ADA DATA) ================= -->
                <div x-show="recapReports.length > 0" x-cloak class="bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-[#E3DCC8] space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="font-display text-base sm:text-lg font-bold text-[var(--mm-900)]">Daftar Rincian Arsip Rekap</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Daftar laporan berstatus akhir pada periode terpilih.</p>
                        </div>
                        
                        <div class="relative w-full sm:w-80 flex items-center">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" x-model="searchQuery" 
                                   placeholder="Cari ID, pelapor, teknisi..." 
                                   class="w-full pl-9 pr-3 h-8 sm:h-9 bg-white border border-[#E3DCC8] rounded-xl text-xs placeholder:text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-[var(--mm-600)] transition shadow-2xs leading-normal block">
                        </div>
                    </div>

                    <!-- Tampilan Mobile Arsip Rekap (block lg:hidden) -->
                    <div class="block lg:hidden space-y-2.5">
                        <template x-for="r in filteredReports" :key="'m-recap-rep-' + (r.db_id || r.id)">
                            <div class="bg-white rounded-xl border border-[#E3DCC8] shadow-2xs hover:shadow-xs transition-all duration-200 overflow-hidden relative p-3 space-y-2">
                                
                                <!-- Strip Aksen Status di Sisi Kiri -->
                                <div class="absolute left-0 top-0 bottom-0 w-[3.5px] rounded-l-xl"
                                     :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-500' : 'bg-red-400'"></div>

                                <!-- Header Kartu: ID, Tanggal Masuk, dan Status Badge Sebaris -->
                                <div class="flex items-center justify-between gap-1.5 pl-1.5 pb-1 border-b border-slate-100">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="font-mono font-bold text-[10px] px-1.5 py-0.5 rounded bg-[var(--mm-900)] text-white shadow-2xs tracking-wide shrink-0" 
                                              x-text="r.id || ('REP-' + (r.db_id || ''))"></span>
                                        <span class="text-[9.5px] text-gray-500 font-medium truncate">
                                            <span class="text-gray-400">Masuk:</span> <span class="font-semibold text-gray-700" x-text="formatTanggal(r.tanggal || r.created_at)"></span>
                                        </span>
                                    </div>

                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold shadow-2xs whitespace-nowrap shrink-0"
                                          :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200'">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                              :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-500' : 'bg-red-500'"></span>
                                        <span x-text="r.status"></span>
                                    </span>
                                </div>

                                <!-- Baris Pelapor & Info Alamat -->
                                <div class="flex items-center justify-between gap-2 pl-1.5">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <img :src="r.pelapor_avatar || '{{ asset('images/pelapor-avatar.svg') }}'" 
                                             :alt="r.pelapor" 
                                             class="w-7 h-7 rounded-full object-cover shadow-2xs border border-[#E3DCC8] shrink-0 ring-1 ring-white"
                                             onerror="this.src='{{ asset('images/pelapor-avatar.svg') }}'">
                                        <div class="min-w-0">
                                            <p class="font-bold text-[11px] text-[var(--mm-900)] truncate" x-text="r.pelapor"></p>
                                            <p class="text-[9px] text-gray-500 flex items-center gap-0.5 truncate max-w-[140px]" :title="formatAlamat(r)">
                                                <svg class="w-2.5 h-2.5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                <span x-text="formatAlamat(r)"></span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <p class="font-bold text-[11px] text-[var(--mm-800)] truncate max-w-[130px]" x-text="r.perangkat"></p>
                                        <span class="inline-flex items-center gap-1 text-[8.5px] font-bold px-1.5 py-0.2 rounded bg-sky-50 text-sky-700 border border-sky-200">
                                            <span x-text="r.teknisi || 'Fahry Rizky Prayogi'"></span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Kotak Kendala & Keterangan Rekap -->
                                <div class="bg-[var(--batik-cream)]/40 rounded-lg p-2 border border-[#E3DCC8]/70 text-[10px] space-y-1 ml-1.5">
                                    <div class="flex items-start gap-1.5 leading-tight">
                                        <span class="text-[8px] uppercase font-bold text-amber-800 bg-amber-100/90 px-1 py-0.2 rounded border border-amber-200/80 shrink-0">Kendala</span>
                                        <span class="text-gray-700 font-medium text-[9.5px] line-clamp-2" x-text="r.kendala || '-'"></span>
                                    </div>
                                    <div class="flex items-start gap-1.5 leading-tight pt-1 border-t border-[#E3DCC8]/40" x-show="r.keterangan_rekap || r.keterangan || r.catatan">
                                        <span class="text-[8px] uppercase font-bold text-blue-800 bg-blue-100/90 px-1 py-0.2 rounded border border-blue-200/80 shrink-0">Keterangan</span>
                                        <span class="text-gray-600 italic text-[9.5px] truncate" x-text="r.keterangan_rekap || r.keterangan || r.catatan || '-'"></span>
                                    </div>
                                </div>

                                <!-- Footer: Tanggal Selesai & Tombol Bukti -->
                                <div class="flex items-center justify-between pt-1.5 border-t border-slate-100 text-[9px] text-gray-500 pl-1.5 leading-tight">
                                    <div class="flex items-center gap-1 font-semibold text-[8.5px]">
                                        <span class="text-gray-400">Selesai/Rekap:</span>
                                        <span class="text-gray-700 font-bold" x-text="formatTanggal(r.tanggal_keluar || r.updated_at || r.tanggal)"></span>
                                    </div>

                                    <div>
                                        <template x-if="r.foto">
                                            <button type="button" @click="openMedia(r.foto)" class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg font-bold text-[9px] transition cursor-pointer inline-flex items-center gap-1 active:scale-95">
                                                <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span>Bukti Foto</span>
                                            </button>
                                        </template>
                                        <template x-if="!r.foto">
                                            <span class="text-gray-400 italic text-[8.5px]">Tanpa Bukti</span>
                                        </template>
                                    </div>
                                </div>

                            </div>
                        </template>

                        <!-- State Kosong Mobile -->
                        <div x-show="filteredReports.length === 0" class="p-8 text-center text-gray-400 text-xs bg-white rounded-xl border border-[#E3DCC8]">
                            Tidak ada laporan berstatus Selesai atau Ditolak pada filter pencarian / periode ini.
                        </div>
                    </div>

                    <!-- Tampilan Desktop Arsip Rekap (hidden lg:block) -->
                    <div class="hidden lg:block table-responsive rounded-xl border border-[#E3DCC8]">
                        <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
                            <thead>
                                <tr class="bg-[var(--batik-cream)] text-[var(--mm-900)] font-bold border-b border-[#E3DCC8]">
                                    <th class="p-3">ID Laporan</th>
                                    <th class="p-3">Tgl Masuk</th>
                                    <th class="p-3">Tgl Selesai / Rekap</th>
                                    <th class="p-3">Pelapor</th>
                                    <th class="p-3">Alamat</th>
                                    <th class="p-3">Kategori</th>
                                    <th class="p-3">Nama / Tipe Unit</th>
                                    <th class="p-3">Detail Kendala</th>
                                    <th class="p-3 text-center">Bukti Unit</th>
                                    <th class="p-3">Teknisi</th>
                                    <th class="p-3 text-center">Status Rekap</th>
                                    <th class="p-3">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E3DCC8]">
                                <template x-for="r in filteredReports" :key="r.db_id || r.id">
                                    <tr class="hover:bg-amber-50/40 transition"
                                        :class="{
                                            'recap-row-selesai': (r.status || '').trim().toLowerCase() === 'selesai',
                                            'recap-row-ditolak': (r.status || '').trim().toLowerCase() === 'ditolak'
                                        }">
                                        <td class="p-3 font-mono font-bold text-[var(--mm-900)]" x-text="r.id || ('REP-' + r.db_id)"></td>
                                        <td class="p-3 text-gray-600" x-text="formatTanggal(r.tanggal || r.created_at)"></td>
                                        <td class="p-3 text-gray-600 font-medium" x-text="formatTanggal(r.tanggal_keluar || r.updated_at || r.tanggal)"></td>
                                        <td class="p-3">
                                            <div class="flex items-center gap-2.5">
                                                <img :src="r.pelapor_avatar || '{{ asset('images/pelapor-avatar.svg') }}'" 
                                                     :alt="r.pelapor" 
                                                     class="w-7 h-7 rounded-full object-cover shadow-2xs border border-[#E3DCC8] shrink-0 ring-1 ring-white"
                                                     onerror="this.src='{{ asset('images/pelapor-avatar.svg') }}'">
                                                <span class="font-semibold text-[var(--mm-800)] whitespace-nowrap" x-text="r.pelapor || '-'"></span>
                                            </div>
                                        </td>
                                        <td class="p-3 font-medium text-gray-800" x-text="formatAlamat(r)"></td>
                                        <td class="p-3 font-semibold text-[var(--mm-600)]" x-text="r.jenis_perangkat || classifyDevice(r.perangkat)"></td>
                                        <td class="p-3 font-medium text-gray-700" x-text="r.perangkat || '-'"></td>
                                        <td class="p-3 text-gray-600 max-w-[180px] truncate" :title="r.kendala" x-text="r.kendala || '-'"></td>
                                        <td class="p-3 text-center">
                                            <template x-if="r.foto">
                                                <button type="button" @click="openMedia(r.foto)" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-md font-bold text-[10px] transition cursor-pointer">
                                                    Lihat Bukti
                                                </button>
                                            </template>
                                            <template x-if="!r.foto">
                                                <span class="text-gray-400 italic text-[11px]">Tanpa Bukti</span>
                                            </template>
                                        </td>
                                        <td class="p-3 font-medium text-gray-800" x-text="r.teknisi || 'Fahry Rizky Prayogi'"></td>
                                        <td class="p-3 text-center">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold inline-block"
                                                :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-red-100 text-red-800 border border-red-300'"
                                                x-text="r.status"></span>
                                        </td>
                                        <td class="p-3 text-gray-600 text-xs" x-text="r.keterangan_rekap || r.keterangan || '-'"></td>
                                    </tr>
                                </template>
                                <tr x-show="filteredReports.length === 0">
                                    <td colspan="13" class="p-8 text-center text-gray-400 italic">
                                        Tidak ada laporan berstatus Selesai atau Ditolak pada filter pencarian / periode ini.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ============ MODAL PRATINJAU FOTO ============ -->
    <div x-show="mediaModalOpen"
        x-cloak
        x-transition.opacity
        @keydown.escape.window="mediaModalOpen = false"
        class="fixed inset-0 z-[10000] flex items-center justify-center bg-black/80 p-4"
        role="dialog"
        aria-modal="true">
        <div class="bg-white rounded-2xl max-w-xl w-full p-4 space-y-3 shadow-2xl border border-[#E3DCC8] relative flex flex-col max-h-[85vh]"
            @click.away="mediaModalOpen = false">

            <div class="flex justify-between items-center border-b pb-2 px-1">
                <p class="font-display font-bold text-sm text-[var(--mm-900)]">Pratinjau Foto Unit</p>
                <button type="button" @click="mediaModalOpen = false" class="px-2.5 py-1 bg-gray-100 hover:bg-red-600 hover:text-white text-gray-700 rounded-lg text-xs font-bold transition cursor-pointer">
                    Tutup ✕
                </button>
            </div>

            <div class="flex-1 overflow-auto flex items-center justify-center bg-gray-900 rounded-xl p-3 min-h-[40vh]">
                <img :src="mediaSrc.startsWith('http') ? mediaSrc : ('{{ asset('storage') }}/' + mediaSrc)" 
                     x-on:error="if (!$el.dataset.fallback && mediaSrc) { $el.dataset.fallback = '1'; let p = mediaSrc.replace(/^https?:\/\/[^\/]+\/(storage\/)?/, '').replace(/^\/?storage\//, ''); $el.src = '{{ url('/storage-file') }}/' + p; }"
                     class="max-h-[55vh] max-w-full object-contain rounded-lg shadow" 
                     alt="Pratinjau Unit">
            </div>
        </div>
    </div>

    <!-- DATA INJECTION & SCRIPT ALPINE -->
    <script>
        window.__initialReports = {!! json_encode(array_values($defaultReports ?? [])) !!};
        window.__notifReports = {!! json_encode(array_values($notifReports ?? [])) !!};
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        function rekapApp(initialReports) {
            return {
                sidebarOpen: false,
                reports: Array.isArray(initialReports) ? initialReports : [],
                searchQuery: '',
                selectedBulan: '{{ $bulanPilih }}',
                selectedTahun: '{{ $tahunPilih }}',
                mediaModalOpen: false,
                mediaSrc: '',
                notifReports: window.__notifReports || [],
                seenAdminReportIds: JSON.parse(localStorage.getItem('siperkom_admin_seen_reports') || '[]'),

                get unreadAdminReports() {
                    return this.notifReports.filter(r => !this.seenAdminReportIds.includes(String(r.db_id || r.id)));
                },

                markAllAdminNotifsRead() {
                    const ids = this.notifReports.map(r => String(r.db_id || r.id));
                    const merged = Array.from(new Set([...this.seenAdminReportIds, ...ids]));
                    this.seenAdminReportIds = merged;
                    localStorage.setItem('siperkom_admin_seen_reports', JSON.stringify(merged));
                },

                markReportRead(id) {
                    if (!id) return;
                    const strId = String(id);
                    if (!this.seenAdminReportIds.includes(strId)) {
                        this.seenAdminReportIds.push(strId);
                        localStorage.setItem('siperkom_admin_seen_reports', JSON.stringify(this.seenAdminReportIds));
                    }
                },

                init() {
                    setInterval(() => this.syncLiveNotifications(), 2500);
                },

                async syncLiveNotifications() {
                    try {
                        const response = await fetch('{{ route("admin.antrean.live-sync") }}', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            if (data.notifReports) {
                                const oldIds = (this.notifReports || []).map(r => String(r.db_id || r.id));
                                const newIncoming = data.notifReports.filter(r => !oldIds.includes(String(r.db_id || r.id)) && !this.seenAdminReportIds.includes(String(r.db_id || r.id)));
                                if (newIncoming.length > 0 && oldIds.length > 0) {
                                    const first = newIncoming[0];
                                    if (typeof this.showToast === 'function') {
                                        this.showToast('🔔 Laporan baru masuk: ' + (first.pelapor || 'Pelapor') + ' (' + (first.perangkat || 'Perangkat') + ')');
                                    }
                                }
                                this.notifReports = data.notifReports;
                            }
                        }
                    } catch (e) {}
                },

                openMedia(src) {
                    this.mediaSrc = src;
                    this.mediaModalOpen = true;
                },

                getInitials(name) {
                    if (!name) return 'PL';
                    const parts = name.trim().split(/\s+/);
                    if (parts.length >= 2) {
                        return (parts[0][0] + parts[1][0]).toUpperCase();
                    }
                    return name.substring(0, 2).toUpperCase();
                },

                formatAlamat(r) {
                    if (!r) return 'cirebon';
                    const a = (r.alamat || '').trim();
                    if (a && a !== '-' && a.toLowerCase() !== 'belum diatur') return a;
                    const k = (r.kecamatan || '').trim();
                    if (k && k !== '-' && k.toLowerCase() !== 'belum diatur') return k;
                    const d = (r.desa || '').trim();
                    if (d && d !== '-' && d.toLowerCase() !== 'belum diatur') return d;
                    return 'cirebon';
                },

                formatTanggal(tglStr) {
                    if (!tglStr || tglStr === '-') return '-';
                    return String(tglStr).substring(0, 10);
                },

                classifyDevice(perangkat) {
                    if (!perangkat) return 'Lainnya';
                    const p = String(perangkat).toLowerCase();
                    if (p.includes('printer') || p.includes('scanner')) return 'Printer / Scanner';
                    if (p.includes('router') || p.includes('mikrotik') || p.includes('jaringan') || p.includes('wifi')) return 'Jaringan / Router';
                    if (p.includes('laptop')) return 'Laptop';
                    if (p.includes('pc') || p.includes('komputer') || p.includes('cpu')) return 'PC / Komputer';
                    return 'Perangkat Lain';
                },

                // Hanya menyaring tiket yang telah tuntas: Selesai atau Ditolak
                get recapReports() {
                    return this.reports.filter(r => {
                        const st = (r.status || '').trim().toLowerCase();
                        return st === 'selesai' || st === 'ditolak';
                    });
                },

                // Filter tabel detail berdasarkan Periode & Pencarian
                get filteredReports() {
                    const q = (this.searchQuery || '').trim().toLowerCase();
                    return this.recapReports.filter(r => {
                        const tgl = r.tanggal || r.created_at || '';
                        if (tgl.length >= 7) {
                            const [thn, bln] = tgl.substring(0, 7).split('-');
                            if (thn !== this.selectedTahun || bln !== this.selectedBulan) {
                                return false;
                            }
                        }

                        if (!q) return true;

                        return (r.id && String(r.id).toLowerCase().includes(q)) ||
                               ((r.alamat && String(r.alamat).toLowerCase().includes(q)) || (r.kecamatan && String(r.kecamatan).toLowerCase().includes(q))) ||
                               (r.pelapor && String(r.pelapor).toLowerCase().includes(q)) ||
                               (r.teknisi && String(r.teknisi).toLowerCase().includes(q)) ||
                               (r.perangkat && String(r.perangkat).toLowerCase().includes(q));
                    });
                },

                // Rangkuman per bulan khusus untuk Selesai & Ditolak
                get monthlyRecap() {
                    const bulanNamaIndo = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    const groups = {};
                    
                    this.recapReports.forEach(r => {
                        const tgl = r.tanggal || r.created_at || '';
                        if (!tgl || tgl.length < 7) return;
                        
                        const key = tgl.substring(0, 7);
                        if (!groups[key]) {
                            groups[key] = { total: 0, selesai: 0, ditolak: 0 };
                        }
                        
                        const st = (r.status || '').trim().toLowerCase();
                        if (st === 'selesai') {
                            groups[key].selesai++;
                            groups[key].total++;
                        } else if (st === 'ditolak') {
                            groups[key].ditolak++;
                            groups[key].total++;
                        }
                    });

                    return Object.keys(groups).sort().reverse().map(key => {
                        const [year, month] = key.split('-');
                        const g = groups[key];
                        return {
                            key,
                            label: bulanNamaIndo[parseInt(month, 10) - 1] + ' ' + year,
                            total: g.total,
                            selesai: g.selesai,
                            ditolak: g.ditolak,
                            persen: g.total > 0 ? Math.round((g.selesai / g.total) * 100) : 0,
                        };
                    });
                }
            };
        }
    </script>
</body>
</html>