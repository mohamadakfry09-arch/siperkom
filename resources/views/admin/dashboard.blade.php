@php
    $defaultReports = $defaultReports ?? [];
    $bulanPilih = $bulanPilih ?? request('bulan', date('m'));
    $tahunPilih = $tahunPilih ?? request('tahun', date('Y'));

    $monthsIndonesian = $monthsIndonesian ?? [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
        '04' => 'April',   '05' => 'Mei',      '06' => 'Juni', 
        '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September', 
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $namaBulanPilih = $namaBulanPilih ?? ($monthsIndonesian[$bulanPilih] ?? ('Bulan ' . $bulanPilih));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Dashboard Utama Admin | SIPERKOM Kabupaten Cirebon</title>
    
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

        @keyframes fadeUp { 
            0% { opacity: 0; transform: translateY(8px); } 
            100% { opacity: 1; transform: translateY(0); } 
        }

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
            html, body {
                height: 100% !important;
                overflow: hidden !important;
            }
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

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #F1ECE1; }
        ::-webkit-scrollbar-thumb { background: var(--mm-600); border-radius: 10px; }
        [x-cloak] { display: none !important; }

        .fade-up { animation: fadeUp 0.45s ease both; }

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

        @media print {
            aside, header { display: none !important; }
            main { height: auto !important; overflow: visible !important; }
        }
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)]"
     x-data="adminDashboard(window.__initialReports)"
     :class="{ 'overflow-hidden touch-none overscroll-none': sidebarOpen }">

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
            class="fixed lg:static top-3.5 left-0 z-[9999] lg:z-auto w-[82vw] max-w-[290px] lg:w-[min(19rem,calc(100vw-1rem))] lg:max-w-none mega-mendung-bg flex flex-col justify-between p-3.5 sm:p-4 lg:p-4.5 text-white shrink-0 shadow-2xl transition-transform duration-300 ease-out h-[78vh] max-h-[580px] lg:h-full lg:max-h-none overflow-hidden lg:overflow-y-auto no-scrollbar touch-pan-y overscroll-none select-none mobile-rounded-sidebar border-0 outline-none">

            <!-- BAGIAN ATAS: USER BADGE, MENU -->
            <div class="space-y-3.5 lg:space-y-4">

                <!-- Admin Profile Card (Modern Glassmorphic Style & Interactive) -->
                <a href="{{ route('admin.profil.edit') }}" 
                   @click="sidebarOpen = false"
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

                <!-- Navigasi Menu Admin -->
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

        <!-- ============ KONTEN UTAMA ADMIN ============ -->
        <main class="flex-1 flex flex-col h-full bg-white lg:rounded-3xl shadow-sm border border-[#E3DCC8] transition-all"
              :class="sidebarOpen ? 'overflow-hidden pointer-events-none touch-none select-none' : 'overflow-y-auto scrollable-content'">

            <!-- Top Header -->
            <header class="bg-white border-b border-[#E3DCC8] px-3 sm:px-6 py-2.5 sm:py-3.5 sticky top-0 z-30 flex items-center justify-between shadow-2xs gap-2 sm:gap-3 shrink-0 lg:rounded-t-3xl">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3">
                    <button type="button" @click="sidebarOpen = true" aria-label="Buka menu" class="lg:hidden w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] text-[var(--mm-900)] shrink-0 hover:bg-[#F3EBD8] active:scale-95 transition flex items-center justify-center shadow-2xs">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="min-w-0">
                        <h2 class="font-display font-bold text-xs sm:text-sm text-[var(--mm-900)] truncate">Dashboard Ringkasan Admin</h2>
                        <p class="text-[9px] text-gray-500 truncate sm:hidden font-medium">Panel Pengelola &amp; Teknisi SIPERKOM</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <!-- Notifikasi Lonceng Admin: Khusus Laporan Masuk Baru dari Pelapor -->
                    <div class="relative" x-data="{ notifOpen: false }">
                        <!-- Backdrop Mobile -->
                        <div x-show="notifOpen" x-cloak @click="notifOpen = false" 
                             class="fixed inset-0 bg-black/25 z-40 sm:hidden backdrop-blur-xs"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"></div>

                        <button type="button" @click="notifOpen = !notifOpen" 
                                class="relative w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] hover:bg-[#F3EBD8] active:scale-95 transition cursor-pointer text-[var(--mm-900)] flex items-center justify-center shadow-2xs shrink-0" 
                                aria-label="Notifikasi Laporan Masuk" title="Notifikasi Laporan Masuk">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--mm-800)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            
                            <!-- Counter Badge: Laporan Masuk Belum Dibaca -->
                            <span x-show="unreadAdminReports.length > 0" x-cloak 
                                  class="absolute -top-1 -right-1 bg-[var(--keraton-maroon)] text-white text-[9px] font-bold min-w-[16px] h-[16px] px-0.5 rounded-full flex items-center justify-center shadow-sm animate-pulse border border-white" 
                                  x-text="unreadAdminReports.length"></span>
                        </button>
                        
                        <!-- Dropdown Notifikasi Admin -->
                        <div x-show="notifOpen" x-cloak @click.away="notifOpen = false" 
                             x-transition:enter="transition ease-out duration-150" 
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95" 
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                             x-transition:leave="transition ease-in duration-100" 
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95" 
                             class="fixed inset-x-3 top-[54px] max-w-sm mx-auto sm:max-w-none sm:absolute sm:inset-x-auto sm:right-0 sm:top-full sm:mt-2 sm:w-[350px] bg-white border border-[#E3DCC8] rounded-2xl shadow-xl z-50 overflow-hidden text-left flex flex-col max-h-[75vh] sm:max-h-[460px]">
                            
                            <div class="px-3.5 py-2.5 border-b border-[#E3DCC8] bg-[var(--batik-cream)] flex justify-between items-center shrink-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-7 h-7 rounded-xl bg-[var(--mm-900)] text-white flex items-center justify-center shrink-0 shadow-xs">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </div>
                                    <div class="leading-tight min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[11.5px] font-bold text-[var(--mm-900)]">Laporan Masuk</span>
                                            <span x-show="unreadAdminReports.length > 0" x-cloak 
                                                  class="bg-[var(--keraton-gold)]/25 text-[var(--mm-900)] border border-[var(--keraton-gold)]/40 text-[8.5px] font-extrabold px-1.5 py-0.2 rounded-md" 
                                                  x-text="unreadAdminReports.length"></span>
                                        </div>
                                        <p class="text-[8.5px] text-gray-500 truncate">Antrean tiket butuh tindakan</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button type="button" x-show="unreadAdminReports.length > 0" 
                                            @click="markAllAdminNotifsRead()" 
                                            class="text-[9.5px] text-blue-700 hover:text-blue-900 font-bold hover:underline transition cursor-pointer">
                                        Tandai dibaca
                                    </button>
                                    <button type="button" @click="notifOpen = false" class="p-0.5 text-gray-400 hover:text-gray-700 rounded transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="overflow-y-auto divide-y divide-[#E3DCC8] bg-white flex-1 p-1">
                                <template x-for="r in notifReports" :key="'admin-notif-' + (r.db_id || r.id)">
                                    <a :href="'{{ route('admin.antrean') }}?open_id=' + (r.db_id || '')" 
                                       @click="markReportRead(r.db_id || r.id)"
                                       class="flex items-start gap-2.5 w-full text-left p-2.5 rounded-xl hover:bg-amber-50/60 transition cursor-pointer group"
                                       :class="{ 'bg-amber-50/40': !seenAdminReportIds.includes(String(r.db_id || r.id)) }">
                                        <div class="shrink-0 mt-0.5">
                                            <img :src="r.pelapor_avatar || '{{ asset('images/pelapor-avatar.svg') }}'" 
                                                 :alt="r.pelapor"
                                                 class="w-8 h-8 rounded-full object-cover shadow-2xs border border-[#E3DCC8] ring-1 ring-white"
                                                 onerror="this.src='{{ asset('images/pelapor-avatar.svg') }}'">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between gap-1 mb-0.5">
                                                <p class="text-[10px] font-bold text-[var(--mm-800)] truncate group-hover:text-blue-700" x-text="(r.id || 'REP') + ' — ' + (r.alamat || r.kecamatan || 'Alamat')"></p>
                                                <span class="text-[8px] font-bold px-1.5 py-0.2 rounded shrink-0 shadow-2xs"
                                                    :class="r.status === 'Menunggu Part' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-amber-100 text-amber-900 border border-amber-300'"
                                                    x-text="r.status === 'Menunggu Part' ? 'Menunggu Part' : 'Laporan Baru'">
                                                </span>
                                            </div>
                                            <p class="text-[9.5px] text-gray-700 font-medium truncate" x-text="(r.perangkat || '-') + ' • ' + (r.pelapor || 'Staf')"></p>
                                            <p class="text-[8.5px] text-gray-500 truncate mt-0.5" x-text="'Kendala: ' + (r.kendala || '-')"></p>
                                            <div class="flex items-center justify-between mt-1 pt-1 border-t border-[#F0EBE0]/80">
                                                <span class="text-[8px] text-gray-400" x-text="'Masuk: ' + (r.tanggal_masuk || '-')"></span>
                                                <span class="text-[8.5px] font-bold text-blue-600 group-hover:underline flex items-center gap-0.5">
                                                    <span>Tindak Lanjut</span>
                                                    <span>→</span>
                                                </span>
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

            <!-- Isi Halaman Dashboard Admin -->
            <div class="p-3 sm:p-6 lg:p-8 space-y-3.5 sm:space-y-5 flex-1 pb-16">
                
                <!-- BANNER SELAMAT DATANG MEGA MENDUNG -->
                <div class="mega-mendung-bg p-3.5 sm:p-6 lg:p-8 rounded-2xl shadow-md text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 relative overflow-hidden border border-[var(--mm-600)]">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="space-y-1 sm:space-y-1.5 relative z-10 min-w-0">
                        <div class="inline-flex items-center gap-1.5 bg-white/15 border border-white/20 px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[9px] sm:text-[11px] text-[var(--keraton-gold-soft)] font-semibold backdrop-blur-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-[var(--keraton-gold)] shrink-0"></span>
                            <span>🏛️ Sistem Terpadu Admin &amp; Teknisi</span>
                        </div>
                        <h2 class="font-display text-lg sm:text-2xl lg:text-3xl font-extrabold tracking-tight break-words">
                            Selamat Datang, {{ Auth::user()->name ?? 'Administrator SIPERKOM' }}!
                        </h2>
                        <p class="text-[10.5px] sm:text-sm text-[var(--mm-200)] max-w-xl leading-relaxed">
                            Pantau ringkasan kinerja perbaikan, status pengerjaan perangkat, dan tindak lanjuti tiket laporan.
                        </p>
                    </div>

                    <div class="relative z-10 shrink-0 w-full md:w-auto flex flex-col items-stretch md:items-end gap-1.5 pt-1 md:pt-0">
                        <a href="{{ route('admin.antrean') }}" 
                           class="w-full md:w-auto justify-center px-4 py-2.5 sm:px-5 sm:py-3 bg-[var(--keraton-gold)] hover:bg-[var(--keraton-gold-soft)] text-[var(--mm-900)] rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer active:scale-98 shadow-md">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span>Buka Antrean Perbaikan</span>
                        </a>
                    </div>
                </div>

                <!-- KOTAK STATISTIK: MOBILE 2-KOLOM KOMPAK & DESKTOP 5-KOLOM SEJAJAR -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-4">
                    <!-- Total Laporan: Spans 2 kolom di HP sangat pas -->
                    <div class="fade-up col-span-2 sm:col-span-1 bg-white p-3 sm:p-5 rounded-2xl shadow-xs border border-[#E3DCC8] flex items-center gap-3">
                        <div class="w-10 h-10 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-[var(--mm-800)]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[var(--mm-800)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Laporan</p>
                            <p class="text-base sm:text-lg font-extrabold font-display text-[var(--mm-900)]">{{ $totalLaporan ?? 0 }}</p>
                        </div>
                    </div>
                    
                    <!-- Dalam Perbaikan -->
                    <div class="fade-up bg-white p-3 sm:p-5 rounded-2xl shadow-xs border border-[#E3DCC8] flex items-center gap-2.5 sm:gap-3" style="animation-delay:.05s">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-amber-100 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-400 truncate">Dalam Proses</p>
                            <p class="text-base sm:text-lg font-extrabold font-display text-amber-700">{{ $dalamPerbaikan ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Menunggu Part -->
                    <div class="fade-up bg-white p-3 sm:p-5 rounded-2xl shadow-xs border border-[#E3DCC8] flex items-center gap-2.5 sm:gap-3" style="animation-delay:.1s">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-400 truncate">Menunggu Part</p>
                            <p class="text-base sm:text-lg font-extrabold font-display text-blue-700">{{ $menungguPart ?? 0 }}</p>
                        </div>
                    </div>

                    <!-- Selesai -->
                    <div class="fade-up bg-white p-3 sm:p-5 rounded-2xl shadow-xs border border-[#E3DCC8] flex items-center gap-2.5 sm:gap-3" style="animation-delay:.15s">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-emerald-100 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-400">Selesai</p>
                            <p class="text-base sm:text-lg font-extrabold font-display text-emerald-700">
                                {{ $selesai ?? 0 }}
                            </p>
                        </div>
                    </div>

                    <!-- Ditolak -->
                    <div class="fade-up bg-white p-3 sm:p-5 rounded-2xl shadow-xs border border-[#E3DCC8] flex items-center gap-2.5 sm:gap-3" style="animation-delay:.2s">
                        <div class="w-9 h-9 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-rose-100 flex items-center justify-center">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-400">Ditolak</p>
                            <p class="text-base sm:text-lg font-extrabold font-display text-rose-700">
                                {{ $ditolak ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                @if(($totalLaporan ?? 0) == 0)
                    <!-- KETERANGAN JIKA BELUM ADA DATA DI BULAN INI -->
                    <div class="bg-amber-50/70 border border-amber-200/90 rounded-2xl p-3.5 sm:p-4 flex items-center gap-3 shadow-2xs">
                        <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-amber-950">Belum Ada Laporan Masuk di Periode {{ $namaBulanPilih }} {{ $tahunPilih }}</p>
                            <p class="text-[10px] sm:text-[11px] text-amber-800 mt-0.5 leading-relaxed">
                                Statistik untuk periode ini masih kosong dan sistem siap mencatat laporan baru. Data histori laporan bulan lalu atau tahun lalu tetap tersimpan aman di sistem dan dapat dibuka kapan saja melalui filter periode di atas.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- MENU PINTASAN CEPAT OPERASIONAL ADMIN (IDENTIK ALUR PELAPOR) -->
                <div class="bg-white rounded-2xl shadow-xs border border-[#E3DCC8] p-3 sm:p-5 space-y-3">
                    <div class="flex items-center justify-between pb-2.5 border-b border-[#E3DCC8]">
                        <div>
                            <h3 class="font-display font-bold text-xs sm:text-sm text-[var(--mm-900)]">Pintasan Cepat Operasional</h3>
                            <p class="text-[10px] sm:text-[11px] text-gray-500">Navigasi langsung ke modul utama pengelolaan SIPERKOM.</p>
                        </div>
                        <span class="inline-flex items-center gap-1 text-[9.5px] sm:text-[10.5px] font-semibold text-[var(--keraton-gold)] bg-[var(--batik-cream)] border border-[#E3DCC8] px-2 py-0.5 rounded-lg shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-[var(--keraton-gold)]"></span>
                            Modul Terpadu
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5 sm:gap-3.5 items-stretch">
                        <!-- 1. Antrean Perbaikan -->
                        <a href="{{ route('admin.antrean') }}" class="bg-gradient-to-b from-[#FBF8EF] to-white hover:border-[var(--keraton-gold)] p-3 rounded-xl border border-[#E3DCC8] flex flex-col justify-between shadow-2xs hover:shadow-xs transition group cursor-pointer">
                            <div>
                                <div class="flex items-center justify-between gap-1 mb-1.5">
                                    <span class="w-7 h-7 rounded-lg bg-[var(--mm-800)] text-white flex items-center justify-center font-black text-xs shadow-2xs group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    </span>
                                    <span class="text-[8.5px] font-extrabold text-amber-900 bg-amber-100/80 px-1.5 py-0.5 rounded">{{ ($dalamPerbaikan ?? 0) + ($menungguPart ?? 0) }} Aktif</span>
                                </div>
                                <h4 class="font-bold text-xs text-[var(--mm-900)] leading-tight">Antrean Perbaikan</h4>
                                <p class="text-[10px] text-gray-500 leading-snug mt-1">Ubah status, riwayat pengerjaan &amp; teknisi.</p>
                            </div>
                            <span class="text-[9px] text-blue-800 font-bold mt-2 pt-1 border-t border-[#E3DCC8]/70 flex items-center justify-between">
                                <span>Kelola Antrean</span>
                                <span>→</span>
                            </span>
                        </a>

                        <!-- 2. Persentase Kerusakan -->
                        <a href="{{ route('admin.persentase') }}" class="bg-gradient-to-b from-[#FBF8EF] to-white hover:border-[var(--keraton-gold)] p-3 rounded-xl border border-[#E3DCC8] flex flex-col justify-between shadow-2xs hover:shadow-xs transition group cursor-pointer">
                            <div>
                                <div class="flex items-center justify-between gap-1 mb-1.5">
                                    <span class="w-7 h-7 rounded-lg bg-[var(--keraton-gold)] text-[var(--mm-900)] flex items-center justify-center font-black text-xs shadow-2xs group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                                    </span>
                                    <span class="text-[8.5px] font-extrabold text-blue-800 bg-blue-100/80 px-1.5 py-0.5 rounded">Statistik</span>
                                </div>
                                <h4 class="font-bold text-xs text-[var(--mm-900)] leading-tight">Persentase Kerusakan</h4>
                                <p class="text-[10px] text-gray-500 leading-snug mt-1">Grafik sebaran perangkat &amp; tren bulanan.</p>
                            </div>
                            <span class="text-[9px] text-amber-900 font-bold mt-2 pt-1 border-t border-[#E3DCC8]/70 flex items-center justify-between">
                                <span>Lihat Grafik</span>
                                <span>→</span>
                            </span>
                        </a>

                        <!-- 3. Rekap Bulanan (Excel) -->
                        <a href="{{ route('admin.rekap') }}" class="bg-gradient-to-b from-[#FBF8EF] to-white hover:border-emerald-500 p-3 rounded-xl border border-[#E3DCC8] flex flex-col justify-between shadow-2xs hover:shadow-xs transition group cursor-pointer">
                            <div>
                                <div class="flex items-center justify-between gap-1 mb-1.5">
                                    <span class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-black text-xs shadow-2xs group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V9a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                                    </span>
                                    <span class="text-[8.5px] font-extrabold text-emerald-800 bg-emerald-100/80 px-1.5 py-0.5 rounded">Ekspor</span>
                                </div>
                                <h4 class="font-bold text-xs text-[var(--mm-900)] leading-tight">Rekap Bulanan (Excel)</h4>
                                <p class="text-[10px] text-gray-500 leading-snug mt-1">Unduh rekap data format Excel &amp; PDF.</p>
                            </div>
                            <span class="text-[9px] text-emerald-800 font-bold mt-2 pt-1 border-t border-[#E3DCC8]/70 flex items-center justify-between">
                                <span>Buka Arsip Rekap</span>
                                <span>→</span>
                            </span>
                        </a>

                        <!-- 4. Manajemen Akun Pelapor -->
                        <a href="{{ route('admin.manajemen') }}" class="bg-gradient-to-b from-[#FBF8EF] to-white hover:border-blue-500 p-3 rounded-xl border border-[#E3DCC8] flex flex-col justify-between shadow-2xs hover:shadow-xs transition group cursor-pointer">
                            <div>
                                <div class="flex items-center justify-between gap-1 mb-1.5">
                                    <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-black text-xs shadow-2xs group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    </span>
                                    <span class="text-[8.5px] font-extrabold text-indigo-800 bg-indigo-100/80 px-1.5 py-0.5 rounded">Akun</span>
                                </div>
                                <h4 class="font-bold text-xs text-[var(--mm-900)] leading-tight">Manajemen Akun</h4>
                                <p class="text-[10px] text-gray-500 leading-snug mt-1">Kelola akun staf pelapor desa &amp; kecamatan.</p>
                            </div>
                            <span class="text-[9px] text-indigo-800 font-bold mt-2 pt-1 border-t border-[#E3DCC8]/70 flex items-center justify-between">
                                <span>Kelola Pengguna</span>
                                <span>→</span>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- KOTAK PEMBERITAHUAN LAPORAN MASUK (FORMAT PEMBERITAHUAN RINGKAS) -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span x-show="notifReports.length > 0" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2" :class="notifReports.length > 0 ? 'bg-amber-500' : 'bg-emerald-500'"></span>
                            </span>
                            <div>
                                <h3 class="font-display text-xs sm:text-sm font-bold text-[var(--mm-900)]">Pemberitahuan Laporan Masuk</h3>
                                <p class="text-[9.5px] sm:text-[10.5px] text-gray-500">Notifikasi status tiket yang membutuhkan tindakan teknisi.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.antrean') }}" class="text-[10px] sm:text-xs font-bold text-blue-700 hover:text-blue-900 hover:underline flex items-center gap-1 shrink-0">
                            <span>Buka Antrean Lengkap</span>
                            <span>→</span>
                        </a>
                    </div>

                    <!-- Jika ada antrean laporan baru / aktif -->
                    <div x-show="notifReports && notifReports.length > 0" class="space-y-2">
                        <template x-for="r in (notifReports || []).slice(0, 3)" :key="'dash-notif-' + (r.db_id || r.id)">
                            <div class="bg-amber-50/70 border border-amber-200/90 p-2.5 sm:p-3 rounded-2xl flex items-center justify-between gap-2.5 shadow-2xs hover:bg-amber-50 transition">
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-900 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="font-mono font-bold text-[9px] px-1.5 py-0.2 rounded bg-[var(--mm-900)] text-white" x-text="r.id"></span>
                                            <span class="font-bold text-[11px] text-[var(--mm-900)] truncate" x-text="r.pelapor + ' (' + (r.alamat || r.desa || r.kecamatan || 'Cirebon') + ')'"></span>
                                            <span class="text-[8px] font-bold px-1.5 py-0.2 rounded-full shadow-2xs"
                                                :class="r.status === 'Menunggu Part' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-amber-100 text-amber-900 border border-amber-300'"
                                                x-text="r.status || 'Dalam Perbaikan'"></span>
                                        </div>
                                        <p class="text-[9.5px] text-gray-600 truncate mt-0.5" x-text="(r.perangkat || '-') + ' • Kendala: ' + (r.kendala || '-')"></p>
                                    </div>
                                </div>
                                <a :href="'{{ route('admin.antrean') }}?open_id=' + (r.db_id || '')" 
                                   class="px-2.5 py-1.5 bg-[var(--mm-800)] hover:bg-[var(--mm-900)] text-white rounded-xl text-[10px] font-bold transition shadow-2xs shrink-0 flex items-center gap-1 active:scale-95">
                                    <span>Tindak Lanjut</span>
                                    <span>→</span>
                                </a>
                            </div>
                        </template>

                        <div x-show="notifReports && notifReports.length > 3" class="text-center pt-0.5">
                            <a href="{{ route('admin.antrean') }}" class="text-[10.5px] font-bold text-blue-700 hover:underline">
                                + Lihat <span x-text="(notifReports || []).length - 3"></span> laporan lainnya di Antrean Perbaikan →
                            </a>
                        </div>
                    </div>

                    <!-- Jika tidak ada antrean laporan baru -->
                    <div x-show="!notifReports || notifReports.length === 0" class="bg-emerald-50/70 border border-emerald-200 p-3 sm:p-4 rounded-2xl flex items-center justify-between gap-3 shadow-2xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-xs sm:text-[13px] text-emerald-950">Tidak Ada Antrean Menumpuk</h4>
                                <p class="text-[10px] text-emerald-800 mt-0.5">Semua laporan telah diproses dan tuntas ditindaklanjuti.</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.antrean') }}" class="text-[10px] font-bold text-emerald-800 hover:underline shrink-0">
                            Cek Antrean →
                        </a>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- ============ TOAST NOTIFIKASI ============ -->
    <div x-show="toast.show && toast.message" x-cloak x-transition
         class="fixed bottom-5 right-5 z-[60] bg-[var(--mm-900)] text-white px-4 py-3 rounded-xl shadow-2xl text-xs font-semibold flex items-center gap-2 border border-white/10">
        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span x-text="toast.message"></span>
    </div>

    <!-- INJEKSI DATA -->
    <script>
        window.__initialReports = {!! json_encode($defaultReports instanceof \Illuminate\Support\Collection ? $defaultReports->values()->all() : array_values($defaultReports ?? [])) !!};
        window.__notifReports = {!! json_encode($notifReports instanceof \Illuminate\Support\Collection ? $notifReports->values()->all() : array_values($notifReports ?? [])) !!};
        window.__flashSuccess = @json(session('success'));
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function adminDashboard(initialReports) {
            return {
                sidebarOpen: false,
                reports: initialReports || [],
                notifReports: window.__notifReports || [],
                seenAdminReportIds: JSON.parse(localStorage.getItem('siperkom_admin_seen_reports') || '[]'),
                toast: { show: false, message: '' },

                init() {
                    if (window.__flashSuccess) {
                        this.$nextTick(() => {
                            this.showToast(window.__flashSuccess);
                        });
                        window.__flashSuccess = null;
                    }

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
                                    this.showToast('🔔 Laporan baru masuk dari ' + (first.pelapor || 'Pelapor') + ' (' + (first.perangkat || 'Perangkat') + ')!');
                                }
                                this.notifReports = data.notifReports;
                            }
                        }
                    } catch (e) {}
                },

                get unreadAdminReports() {
                    return this.notifReports.filter(r => {
                        const id = String(r.db_id || r.id);
                        return !this.seenAdminReportIds.includes(id);
                    });
                },

                markAllAdminNotifsRead() {
                    let seen = [...this.seenAdminReportIds];
                    this.notifReports.forEach(r => {
                        const id = String(r.db_id || r.id);
                        if (!seen.includes(id)) seen.push(id);
                    });
                    this.seenAdminReportIds = seen;
                    localStorage.setItem('siperkom_admin_seen_reports', JSON.stringify(seen));
                    this.showToast('Semua laporan masuk telah ditandai dibaca');
                },

                markReportRead(reportId) {
                    const id = String(reportId);
                    let seen = [...this.seenAdminReportIds];
                    if (!seen.includes(id)) {
                        seen.push(id);
                        this.seenAdminReportIds = seen;
                        localStorage.setItem('siperkom_admin_seen_reports', JSON.stringify(seen));
                    }
                },

                getInitials(name) {
                    if (!name) return 'PL';
                    const parts = name.trim().split(/\s+/);
                    if (parts.length >= 2) {
                        return (parts[0][0] + parts[1][0]).toUpperCase();
                    }
                    return name.substring(0, 2).toUpperCase();
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