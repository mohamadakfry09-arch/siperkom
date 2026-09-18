@php
    $reportsFinal = $reports ?? [];
    $daftarTeknisiFinal = $daftarTeknisi ?? ['Fahri'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Antrean Perbaikan & Riwayat | SIPERKOM Kabupaten Cirebon</title>
    
    <link rel="icon" href="{{ asset('images/cirebon.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                height: 100vh;
                overflow: hidden;
            }
        }

        /* Tabel Responsif dengan scrollbar halus & elegan persis Rekap */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(20, 88, 138, 0.25) transparent;
            -webkit-overflow-scrolling: touch;
            touch-action: pan-x;
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

        ::-webkit-scrollbar { width: 6px; height: 6px; } 
        ::-webkit-scrollbar-track { background: #F1ECE1; } 
        ::-webkit-scrollbar-thumb { background: var(--mm-600); border-radius: 10px; }
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
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)]"
    x-data="antreanApp()" x-init="init()"
    :class="{ 'overflow-hidden touch-none overscroll-none': sidebarOpen || modalOpen || detailModalOpen || mediaModalOpen }">

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
            class="fixed lg:static top-3.5 left-0 z-[9999] lg:z-auto w-[76vw] max-w-[275px] lg:w-[min(18rem,calc(100vw-1rem))] lg:max-w-none mega-mendung-bg flex flex-col justify-between p-4 sm:p-5 lg:p-6 text-white shrink-0 shadow-2xl transition-transform duration-300 ease-out h-[78vh] max-h-[580px] lg:h-full lg:max-h-none overflow-hidden lg:overflow-y-auto no-scrollbar touch-none lg:touch-auto overscroll-none select-none mobile-rounded-sidebar border-0 outline-none">

            <!-- BAGIAN ATAS: USER BADGE, MENU -->
            <div class="space-y-3.5 lg:space-y-4">

                <!-- Admin Profile Card -->
                <div class="bg-gradient-to-r from-[#0b335c]/85 via-[#0c3966]/80 to-[#072544]/90 border border-blue-300/30 hover:border-[var(--keraton-gold)]/40 p-3 rounded-2xl flex items-center gap-3 shadow-lg backdrop-blur-sm transition-all duration-200">
                    <div class="relative shrink-0">
                        @if(!empty(Auth::user()->avatar))
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-[var(--keraton-gold)]/80 shadow-md" alt="Foto Profil">
                        @else
                            <img src="{{ asset('images/admin-avatar.svg') }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-[var(--keraton-gold)]/80 shadow-md bg-[#072544]" alt="Icon Administrator">
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-xs lg:text-[13px] text-white leading-snug break-words line-clamp-2" title="{{ Auth::user()->name ?? 'Administrator' }}">
                            {{ Auth::user()->name ?? 'Administrator' }}
                        </p>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-500/15 border border-emerald-400/30 text-emerald-200 text-[10px] font-semibold tracking-wide shadow-xs">
                                <span class="relative flex h-1.5 w-1.5 shrink-0">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-400"></span>
                                </span>
                                <span>Admin &amp; Teknisi</span>
                            </span>
                        </div>
                    </div>
                </div>

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
              :class="(sidebarOpen || modalOpen || detailModalOpen || mediaModalOpen) ? 'overflow-hidden pointer-events-none touch-none select-none' : 'overflow-y-auto scrollable-content'">

            <!-- Top Header -->
            <header class="bg-white border-b border-[#E3DCC8] px-3 sm:px-6 py-3 sm:py-4 sticky top-0 z-30 flex items-center justify-between shadow-sm gap-2 sm:gap-3 shrink-0 lg:rounded-t-3xl">
                <div class="flex items-center gap-2 sm:gap-3 w-full max-w-xs md:max-w-md">
                    <button type="button" @click="sidebarOpen = true" aria-label="Buka menu" class="lg:hidden p-2 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] text-[var(--mm-950)] hover:bg-[#F3EBD8] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" placeholder="Cari ID, alamat, atau pelapor..." class="w-full pl-10 pr-4 py-2 bg-[var(--batik-cream)]/60 border border-[#E3DCC8] rounded-xl text-xs focus:outline-none focus:border-[var(--mm-600)] transition">
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <!-- Notifikasi Lonceng Admin: Khusus Laporan Masuk Baru dari Pelapor -->
                    <div class="relative" x-data="{ notifOpen: false }">
                        <button type="button" @click="notifOpen = !notifOpen" class="relative p-2 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] hover:bg-[#F3EBD8] transition cursor-pointer" aria-label="Notifikasi Laporan Masuk">
                            <svg class="w-4 h-4 text-[var(--mm-800)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            
                            <!-- Counter Badge: Jumlah Laporan Masuk Baru Belum Dibaca Admin -->
                            <span x-show="unreadAdminReports.length > 0" x-cloak class="absolute -top-1 -right-1 bg-[var(--keraton-maroon)] text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center animate-pulse" x-text="unreadAdminReports.length"></span>
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
                                    <div @click="openReportFromNotif(r); notifOpen = false" 
                                         class="flex items-start gap-2.5 w-full text-left px-3.5 py-2.5 hover:bg-amber-50/60 transition cursor-pointer group"
                                         :class="{ 'bg-amber-50/40': !seenAdminReportIds.includes(String(r.db_id || r.id)) }">
                                        <!-- PP Pelapor Mini -->
                                        <div class="shrink-0 mt-0.5">
                                            <template x-if="r.pelapor_avatar">
                                                <img :src="r.pelapor_avatar" class="w-8 h-8 rounded-full object-cover shadow-2xs border border-[#E3DCC8] ring-1 ring-white">
                                            </template>
                                            <template x-if="!r.pelapor_avatar">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-600 via-indigo-600 to-[#0b335c] text-white font-black flex items-center justify-center text-[10px] shadow-2xs border border-blue-300 ring-1 ring-white" x-text="getInitials(r.pelapor)"></div>
                                            </template>
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
                                    </div>
                                </template>
                                <div x-show="notifReports.length === 0" class="px-3.5 py-6 text-[10px] text-gray-500 text-center bg-white font-medium leading-relaxed">
                                    Tidak ada antrean laporan baru 🎉
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hidden sm:block w-[78px] shrink-0 border-l border-[#E3DCC8] pl-2 sm:w-auto sm:pl-3 text-right">
                        <p class="text-[10px] font-bold leading-tight whitespace-nowrap sm:text-xs text-[var(--mm-900)]">{{ date('d M Y') }}</p>
                        <p class="text-[8px] leading-tight sm:text-[10px] text-gray-500">Kabupaten Cirebon</p>
                    </div>
                </div>
            </header>

            <div class="p-3.5 sm:p-6 lg:p-8 space-y-4 sm:space-y-6 flex-1 pb-16">

                <!-- ================= STATE KOSONG ANTREAN (JIKA BELUM ADA LAPORAN) ================= -->
                <div x-show="reports.length === 0" x-cloak class="bg-white p-6 sm:p-12 rounded-3xl border border-[#E3DCC8] text-center shadow-sm space-y-3">
                    <div class="w-16 h-16 mx-auto bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h4 class="font-display font-bold text-base text-[var(--mm-900)]">Belum Ada Antrean Laporan</h4>
                    <p class="text-xs text-gray-500 max-w-md mx-auto">
                        Data antrean perbaikan akan otomatis muncul di halaman ini setelah ada tiket perbaikan yang berstatus <span class="text-amber-700 font-bold">Dalam Perbaikan</span> atau <span class="text-blue-700 font-bold">Menunggu Part</span>.
                    </p>
                </div>

                <!-- ================= TABEL DAFTAR ANTREAN (HANYA MUNCUL JIKA ADA DATA) ================= -->
                <div x-show="reports.length > 0" class="bg-white p-3.5 sm:p-6 lg:p-8 rounded-2xl shadow-sm border border-[#E3DCC8] space-y-4">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 sm:gap-4">
                        <div>
                            <h3 class="font-display font-bold text-lg text-[var(--mm-900)]">Daftar Antrean Penanganan</h3>
                            <p class="text-xs text-gray-500">Ubah status progres perbaikan, hapus laporan, atau lihat detail riwayat dari tabel di bawah.</p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2.5">
                            <!-- Custom Dropdown Jenis Perangkat -->
                            <div class="relative" x-data="{ openDevice: false }" @click.outside="openDevice = false">
                                <button type="button"
                                        @click="openDevice = !openDevice"
                                        :class="deviceFilter !== 'Semua' ? 'border-[var(--keraton-gold)] bg-amber-50/80 text-[var(--mm-900)] ring-1 ring-[var(--keraton-gold)]/40 shadow-xs' : 'border-[#E3DCC8] bg-[var(--batik-cream)] text-[var(--mm-800)] hover:bg-[#efe8d5]'"
                                        class="h-9 inline-flex items-center justify-between gap-2 border rounded-xl px-3 text-[11px] font-bold shadow-xs transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-[var(--keraton-gold)]/30 select-none">
                                    <div class="flex items-center gap-2">
                                        <span :class="deviceFilter !== 'Semua' ? 'bg-[var(--keraton-gold)] text-white' : 'bg-white/90 text-[var(--keraton-gold)] border border-[#E3DCC8]'"
                                              class="w-5 h-5 rounded-lg flex items-center justify-center shadow-2xs shrink-0 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                            </svg>
                                        </span>
                                        <span class="tracking-tight max-w-[130px] sm:max-w-[160px] truncate" x-text="deviceFilter === 'Semua' ? 'Semua Perangkat' : deviceFilter"></span>
                                        <span class="text-[9px] font-bold px-1.5 py-0.2 rounded-md transition-colors"
                                              :class="deviceFilter !== 'Semua' ? 'bg-[var(--keraton-gold)]/20 text-amber-900' : 'bg-black/5 text-gray-500'"
                                              x-text="getDeviceCount(deviceFilter)"></span>
                                    </div>
                                    <div class="flex items-center gap-1 pl-1">
                                        <!-- Quick Reset button if filtered -->
                                        <span x-show="deviceFilter !== 'Semua'" 
                                              @click.stop="deviceFilter = 'Semua'; currentPage = 1" 
                                              title="Reset ke Semua Perangkat"
                                              class="w-4 h-4 rounded-full bg-amber-200/70 hover:bg-amber-300 text-amber-900 flex items-center justify-center text-[10px] leading-none transition-colors cursor-pointer">✕</span>
                                        <svg class="w-3.5 h-3.5 text-[var(--mm-800)]/70 transition-transform duration-200 shrink-0"
                                             :class="openDevice ? 'rotate-180 text-[var(--keraton-gold)]' : ''"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </button>

                                <!-- Dropdown Panel -->
                                <div x-show="openDevice"
                                     x-transition:enter="transition ease-out duration-180"
                                     x-transition:enter-start="opacity-0 translate-y-1.5 scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave="transition ease-in duration-100"
                                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                     x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                     class="absolute left-0 lg:left-auto lg:right-0 mt-2 w-72 max-w-[calc(100vw-2.5rem)] bg-white/98 backdrop-blur-md rounded-2xl shadow-2xl border border-[#E3DCC8] p-2 z-[60] ring-1 ring-black/5"
                                     style="display: none;">
                                    
                                    <div class="px-2.5 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-gray-400 flex items-center justify-between border-b border-gray-100 mb-1.5">
                                        <span>Filter Jenis Perangkat</span>
                                        <button type="button" 
                                                x-show="deviceFilter !== 'Semua'" 
                                                @click="deviceFilter = 'Semua'; currentPage = 1; openDevice = false" 
                                                class="text-[9.5px] font-bold text-amber-700 hover:text-amber-900 hover:underline">
                                            Reset Filter
                                        </button>
                                    </div>

                                    <div class="space-y-1">
                                        <!-- Semua Perangkat -->
                                        <button type="button"
                                                @click="deviceFilter = 'Semua'; currentPage = 1; openDevice = false"
                                                :class="deviceFilter === 'Semua' ? 'bg-[var(--batik-cream)] text-[var(--mm-900)] font-bold border border-[#E3DCC8]' : 'text-gray-700 hover:bg-[#FAF7EE] hover:text-[var(--mm-900)] font-medium border border-transparent'"
                                                class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-all cursor-pointer group text-left">
                                            <div class="flex items-center gap-2.5">
                                                <span :class="deviceFilter === 'Semua' ? 'bg-[var(--keraton-gold)] text-white' : 'bg-amber-100/70 text-amber-700 group-hover:bg-amber-200'"
                                                      class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                                </span>
                                                <span class="text-[11.5px]">Semua Perangkat</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md"
                                                      :class="deviceFilter === 'Semua' ? 'bg-[var(--keraton-gold)] text-white' : 'bg-gray-100 text-gray-500'"
                                                      x-text="getDeviceCount('Semua')"></span>
                                                <svg x-show="deviceFilter === 'Semua'" class="w-4 h-4 text-[var(--keraton-gold)] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </button>

                                        <!-- Komputer Unit / PC -->
                                        <button type="button"
                                                @click="deviceFilter = 'Komputer Unit / PC'; currentPage = 1; openDevice = false"
                                                :class="deviceFilter === 'Komputer Unit / PC' ? 'bg-[var(--batik-cream)] text-[var(--mm-900)] font-bold border border-[#E3DCC8]' : 'text-gray-700 hover:bg-[#FAF7EE] hover:text-[var(--mm-900)] font-medium border border-transparent'"
                                                class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-all cursor-pointer group text-left">
                                            <div class="flex items-center gap-2.5">
                                                <span :class="deviceFilter === 'Komputer Unit / PC' ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700 group-hover:bg-blue-200'"
                                                      class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                </span>
                                                <span class="text-[11.5px]">Komputer Unit / PC</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md"
                                                      :class="deviceFilter === 'Komputer Unit / PC' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-500'"
                                                      x-text="getDeviceCount('Komputer Unit / PC')"></span>
                                                <svg x-show="deviceFilter === 'Komputer Unit / PC'" class="w-4 h-4 text-[var(--keraton-gold)] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </button>

                                        <!-- Laptop -->
                                        <button type="button"
                                                @click="deviceFilter = 'Laptop'; currentPage = 1; openDevice = false"
                                                :class="deviceFilter === 'Laptop' ? 'bg-[var(--batik-cream)] text-[var(--mm-900)] font-bold border border-[#E3DCC8]' : 'text-gray-700 hover:bg-[#FAF7EE] hover:text-[var(--mm-900)] font-medium border border-transparent'"
                                                class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-all cursor-pointer group text-left">
                                            <div class="flex items-center gap-2.5">
                                                <span :class="deviceFilter === 'Laptop' ? 'bg-indigo-600 text-white' : 'bg-indigo-100 text-indigo-700 group-hover:bg-indigo-200'"
                                                      class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6a2 2 0 012-2h14a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V6zm-2 13h22"/></svg>
                                                </span>
                                                <span class="text-[11.5px]">Laptop</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md"
                                                      :class="deviceFilter === 'Laptop' ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-500'"
                                                      x-text="getDeviceCount('Laptop')"></span>
                                                <svg x-show="deviceFilter === 'Laptop'" class="w-4 h-4 text-[var(--keraton-gold)] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </button>

                                        <!-- Printer -->
                                        <button type="button"
                                                @click="deviceFilter = 'Printer'; currentPage = 1; openDevice = false"
                                                :class="deviceFilter === 'Printer' ? 'bg-[var(--batik-cream)] text-[var(--mm-900)] font-bold border border-[#E3DCC8]' : 'text-gray-700 hover:bg-[#FAF7EE] hover:text-[var(--mm-900)] font-medium border border-transparent'"
                                                class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-all cursor-pointer group text-left">
                                            <div class="flex items-center gap-2.5">
                                                <span :class="deviceFilter === 'Printer' ? 'bg-rose-600 text-white' : 'bg-rose-100 text-rose-700 group-hover:bg-rose-200'"
                                                      class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                                </span>
                                                <span class="text-[11.5px]">Printer</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md"
                                                      :class="deviceFilter === 'Printer' ? 'bg-rose-600 text-white' : 'bg-gray-100 text-gray-500'"
                                                      x-text="getDeviceCount('Printer')"></span>
                                                <svg x-show="deviceFilter === 'Printer'" class="w-4 h-4 text-[var(--keraton-gold)] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </button>

                                        <!-- Scanner -->
                                        <button type="button"
                                                @click="deviceFilter = 'Scanner'; currentPage = 1; openDevice = false"
                                                :class="deviceFilter === 'Scanner' ? 'bg-[var(--batik-cream)] text-[var(--mm-900)] font-bold border border-[#E3DCC8]' : 'text-gray-700 hover:bg-[#FAF7EE] hover:text-[var(--mm-900)] font-medium border border-transparent'"
                                                class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-all cursor-pointer group text-left">
                                            <div class="flex items-center gap-2.5">
                                                <span :class="deviceFilter === 'Scanner' ? 'bg-emerald-600 text-white' : 'bg-emerald-100 text-emerald-700 group-hover:bg-emerald-200'"
                                                      class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                </span>
                                                <span class="text-[11.5px]">Scanner</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md"
                                                      :class="deviceFilter === 'Scanner' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-500'"
                                                      x-text="getDeviceCount('Scanner')"></span>
                                                <svg x-show="deviceFilter === 'Scanner'" class="w-4 h-4 text-[var(--keraton-gold)] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </button>

                                        <!-- Jaringan / Router -->
                                        <button type="button"
                                                @click="deviceFilter = 'Jaringan / Router'; currentPage = 1; openDevice = false"
                                                :class="deviceFilter === 'Jaringan / Router' ? 'bg-[var(--batik-cream)] text-[var(--mm-900)] font-bold border border-[#E3DCC8]' : 'text-gray-700 hover:bg-[#FAF7EE] hover:text-[var(--mm-900)] font-medium border border-transparent'"
                                                class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-all cursor-pointer group text-left">
                                            <div class="flex items-center gap-2.5">
                                                <span :class="deviceFilter === 'Jaringan / Router' ? 'bg-cyan-600 text-white' : 'bg-cyan-100 text-cyan-700 group-hover:bg-cyan-200'"
                                                      class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                                </span>
                                                <span class="text-[11.5px]">Jaringan / Router</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md"
                                                      :class="deviceFilter === 'Jaringan / Router' ? 'bg-cyan-600 text-white' : 'bg-gray-100 text-gray-500'"
                                                      x-text="getDeviceCount('Jaringan / Router')"></span>
                                                <svg x-show="deviceFilter === 'Jaringan / Router'" class="w-4 h-4 text-[var(--keraton-gold)] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </button>

                                        <!-- Lainnya -->
                                        <button type="button"
                                                @click="deviceFilter = 'Lainnya'; currentPage = 1; openDevice = false"
                                                :class="deviceFilter === 'Lainnya' ? 'bg-[var(--batik-cream)] text-[var(--mm-900)] font-bold border border-[#E3DCC8]' : 'text-gray-700 hover:bg-[#FAF7EE] hover:text-[var(--mm-900)] font-medium border border-transparent'"
                                                class="w-full flex items-center justify-between px-2.5 py-2 rounded-xl text-xs transition-all cursor-pointer group text-left">
                                            <div class="flex items-center gap-2.5">
                                                <span :class="deviceFilter === 'Lainnya' ? 'bg-slate-600 text-white' : 'bg-slate-100 text-slate-700 group-hover:bg-slate-200'"
                                                      class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                                                </span>
                                                <span class="text-[11.5px]">Lainnya</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md"
                                                      :class="deviceFilter === 'Lainnya' ? 'bg-slate-600 text-white' : 'bg-gray-100 text-gray-500'"
                                                      x-text="getDeviceCount('Lainnya')"></span>
                                                <svg x-show="deviceFilter === 'Lainnya'" class="w-4 h-4 text-[var(--keraton-gold)] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Filter Tabs with Live Report Counts -->
                            <div class="flex items-center gap-1 bg-[var(--batik-cream)] p-1 rounded-xl border border-[#E3DCC8] overflow-x-auto no-scrollbar shadow-2xs max-w-full">
                                <template x-for="opt in ['Semua', 'Dalam Perbaikan', 'Menunggu Part', 'Selesai', 'Ditolak']" :key="opt">
                                    <button type="button"
                                            @click="statusFilter = opt; currentPage = 1"
                                            :class="statusFilter === opt ? 'bg-[var(--mm-800)] text-white shadow' : 'text-[var(--mm-800)] hover:bg-white/80'"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10.5px] font-bold whitespace-nowrap transition-all cursor-pointer">
                                        <span x-text="opt"></span>
                                        <span :class="statusFilter === opt ? 'bg-white/20 text-white' : 'bg-black/5 text-[var(--mm-900)]/70'"
                                              class="text-[9px] px-1.5 py-0.2 rounded-full font-extrabold"
                                              x-text="getStatusCount(opt)"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Indikator Filter Aktif & Reset Cepat (Hanya Tampil Jika Terfilter) -->
                    <div x-show="deviceFilter !== 'Semua' || statusFilter !== 'Semua' || searchQuery.trim() !== ''"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="flex flex-wrap items-center justify-between gap-2 px-3.5 py-2 bg-[var(--batik-cream)]/70 border border-[#E3DCC8] rounded-xl text-[11px] text-[var(--mm-800)]"
                         style="display: none;">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-bold flex items-center gap-1.5 text-[var(--mm-900)]">
                                <svg class="w-3.5 h-3.5 text-[var(--keraton-gold)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                Filter Aktif:
                            </span>
                            <span x-show="deviceFilter !== 'Semua'" class="bg-white border border-[#E3DCC8] text-[var(--mm-900)] font-bold px-2 py-0.5 rounded-lg inline-flex items-center gap-1.5 shadow-2xs">
                                <span>Perangkat: <span class="text-amber-800" x-text="deviceFilter"></span></span>
                                <button type="button" @click="deviceFilter = 'Semua'; currentPage = 1" class="hover:text-red-500 font-bold ml-0.5 cursor-pointer">✕</button>
                            </span>
                            <span x-show="statusFilter !== 'Semua'" class="bg-white border border-[#E3DCC8] text-[var(--mm-900)] font-bold px-2 py-0.5 rounded-lg inline-flex items-center gap-1.5 shadow-2xs">
                                <span>Status: <span class="text-blue-800" x-text="statusFilter"></span></span>
                                <button type="button" @click="statusFilter = 'Semua'; currentPage = 1" class="hover:text-red-500 font-bold ml-0.5 cursor-pointer">✕</button>
                            </span>
                            <span x-show="searchQuery.trim() !== ''" class="bg-white border border-[#E3DCC8] text-[var(--mm-900)] font-bold px-2 py-0.5 rounded-lg inline-flex items-center gap-1.5 shadow-2xs">
                                <span>Pencarian: "<span class="text-emerald-800" x-text="searchQuery"></span>"</span>
                                <button type="button" @click="searchQuery = ''; currentPage = 1" class="hover:text-red-500 font-bold ml-0.5 cursor-pointer">✕</button>
                            </span>
                        </div>
                        <button type="button" 
                                @click="deviceFilter = 'Semua'; statusFilter = 'Semua'; searchQuery = ''; currentPage = 1" 
                                class="text-[10px] font-bold text-red-600 hover:text-red-800 hover:underline cursor-pointer">
                            Hapus Semua Filter
                        </button>
                    </div>

                    <!-- ================= 1. TAMPILAN MOBILE: KARTU ELEGAN, KOMPAK & RAPI (block lg:hidden) ================= -->
                    <div class="block lg:hidden space-y-2.5">
                        <template x-for="r in paginatedReports" :key="'m-card-' + (r.db_id || r.id)">
                            <div class="bg-white rounded-xl border border-[#E3DCC8] shadow-2xs hover:shadow-xs transition-all duration-200 overflow-hidden relative p-3 space-y-2">
                                
                                <!-- Strip Aksen Status di Sisi Kiri -->
                                <div class="absolute left-0 top-0 bottom-0 w-[3.5px] rounded-l-xl"
                                     :class="{
                                         'bg-amber-400': (r.status || '').trim() === 'Dalam Perbaikan',
                                         'bg-sky-500': (r.status || '').trim() === 'Menunggu Part',
                                         'bg-emerald-500': (r.status || '').trim() === 'Selesai',
                                         'bg-red-400': (r.status || '').trim() === 'Ditolak',
                                     }"></div>

                                <!-- Header Kartu: ID, Tgl Masuk, Status & Prioritas Sebaris -->
                                <div class="flex items-center justify-between gap-1.5 pl-1.5 pb-1 border-b border-slate-100">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="font-mono font-bold text-[10px] px-1.5 py-0.5 rounded bg-[var(--mm-900)] text-white shadow-2xs tracking-wide shrink-0" x-text="r.id"></span>
                                        <span class="text-[9.5px] text-gray-500 font-medium truncate flex items-center gap-1">
                                            <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span x-text="formatTanggal(r.tanggal_masuk ?? r.tanggal)"></span>
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-1 shrink-0">
                                        <!-- Status Badge -->
                                        <span :class="statusBadgeClass(r.status)" 
                                              class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold shadow-2xs whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full shrink-0"
                                                  :class="{
                                                      'bg-amber-500 animate-pulse': (r.status || '').trim() === 'Dalam Perbaikan',
                                                      'bg-blue-500': (r.status || '').trim() === 'Menunggu Part',
                                                      'bg-emerald-500': (r.status || '').trim() === 'Selesai',
                                                      'bg-red-500': (r.status || '').trim() === 'Ditolak'
                                                  }"></span>
                                            <span x-text="r.status"></span>
                                        </span>

                                        <!-- Prioritas Badge -->
                                        <span :class="priorityBadgeClass(r.prioritas)" class="px-1.5 py-0.5 rounded text-[8.5px] font-bold whitespace-nowrap" x-text="r.prioritas || 'Sedang'"></span>
                                    </div>
                                </div>

                                <!-- Baris Pelapor & Info Alamat -->
                                <div class="flex items-center justify-between gap-2 pl-1.5">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <template x-if="r.pelapor_avatar">
                                            <img :src="r.pelapor_avatar" :alt="r.pelapor" class="w-7 h-7 rounded-full object-cover shadow-2xs border border-[#E3DCC8] shrink-0 ring-1 ring-white">
                                        </template>
                                        <template x-if="!r.pelapor_avatar">
                                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-600 via-indigo-600 to-[#0b335c] text-white font-black flex items-center justify-center text-[9px] shrink-0 shadow-2xs border border-blue-300 ring-1 ring-white" x-text="getInitials(r.pelapor)"></div>
                                        </template>
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
                                            <svg class="w-2.5 h-2.5 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span x-text="r.teknisi || 'Fahri'"></span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Kotak Kendala / Catatan -->
                                <div class="bg-[var(--batik-cream)]/40 rounded-lg p-2 border border-[#E3DCC8]/70 text-[10px] space-y-1 ml-1.5">
                                    <div class="flex items-start gap-1.5 leading-tight">
                                        <span class="text-[8px] uppercase font-bold text-amber-800 bg-amber-100/90 px-1 py-0.2 rounded border border-amber-200/80 shrink-0">Kendala</span>
                                        <span class="text-gray-700 font-medium text-[9.5px] line-clamp-2" x-text="r.kendala || '-'"></span>
                                    </div>
                                    <div class="flex items-start gap-1.5 leading-tight pt-1 border-t border-[#E3DCC8]/40" x-show="r.catatan || r.keterangan || r.keterangan_rekap">
                                        <span class="text-[8px] uppercase font-bold text-blue-800 bg-blue-100/90 px-1 py-0.2 rounded border border-blue-200/80 shrink-0">Catatan</span>
                                        <span class="text-gray-600 italic text-[9.5px] truncate" x-text="r.catatan || r.keterangan || r.keterangan_rekap || '-'"></span>
                                    </div>
                                </div>

                                <!-- Footer: Lampiran + Tombol Aksi Admin -->
                                <div class="flex items-center justify-between pt-1.5 border-t border-slate-100 gap-1.5 pl-1.5">
                                    <div class="flex items-center gap-1 shrink-0">
                                        <button type="button" x-show="r.foto" @click="openMedia(r.foto, 'image')" class="px-2 py-1 rounded-lg text-[9px] font-bold bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 cursor-pointer inline-flex items-center gap-1 active:scale-95">
                                            <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span>Foto</span>
                                        </button>
                                        <button type="button" x-show="r.dokumen" @click="openMedia(r.dokumen, 'pdf')" class="px-2 py-1 rounded-lg text-[9px] font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 cursor-pointer inline-flex items-center gap-1 active:scale-95">
                                            <svg class="w-3 h-3 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                            <span>PDF</span>
                                        </button>
                                        <span x-show="!r.foto && !r.dokumen" class="text-[9px] text-gray-400 italic">Tanpa lampiran</span>
                                    </div>

                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <button type="button" @click="openDetail(r)" class="px-2.5 py-1.5 bg-white hover:bg-amber-50 text-[var(--mm-900)] border border-[#E3DCC8] hover:border-[var(--keraton-gold)] rounded-xl text-[10px] font-bold transition shadow-2xs inline-flex items-center gap-1 cursor-pointer active:scale-95">
                                            <svg class="w-3 h-3 text-[var(--keraton-gold)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Detail</span>
                                        </button>
                                        <button type="button" @click="openStatusModal(r)" class="px-2.5 py-1.5 bg-gradient-to-r from-[var(--mm-800)] to-[var(--mm-900)] text-white rounded-xl text-[10px] font-bold transition shadow-xs inline-flex items-center gap-1 cursor-pointer active:scale-95 border border-white/10">
                                            <svg class="w-3 h-3 text-[var(--keraton-gold-soft)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Status</span>
                                        </button>
                                        <button type="button" @click="deleteReport(r.db_id, r.id)" class="p-1.5 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white rounded-xl text-[10px] font-bold transition border border-rose-200 shadow-2xs inline-flex items-center cursor-pointer active:scale-95" title="Hapus Laporan">
                                            <svg class="w-3.5 h-3.5 text-rose-500 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </template>

                        <!-- State Kosong Filter Mobile -->
                        <div x-show="filteredReports.length === 0" class="p-8 text-center text-gray-400 text-xs bg-white rounded-xl border border-[#E3DCC8]">
                            Tidak ada antrean laporan yang sesuai dengan filter atau kata kunci pencarian.
                        </div>
                    </div>

                    <!-- ================= 2. TAMPILAN DESKTOP: TABEL LENGKAP (hidden lg:block) ================= -->
                    <div class="hidden lg:block table-responsive rounded-2xl border border-[#E3DCC8] bg-white shadow-xs">
                        <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
                            <thead>
                                <tr class="bg-gradient-to-r from-[var(--batik-cream)] via-[#FDFBF7] to-[var(--batik-cream)] border-b border-[#E3DCC8] text-[var(--mm-900)]">
                                    <th class="px-4 py-3.5 font-extrabold uppercase tracking-wider text-[11px]">ID &amp; Tanggal</th>
                                    <th class="px-4 py-3.5 font-extrabold uppercase tracking-wider text-[11px]">Pelapor &amp; Alamat</th>
                                    <th class="px-4 py-3.5 font-extrabold uppercase tracking-wider text-[11px]">Perangkat &amp; Kendala</th>
                                    <th class="px-3.5 py-3.5 font-extrabold uppercase tracking-wider text-[11px]">Teknisi</th>
                                    <th class="px-3.5 py-3.5 text-center font-extrabold uppercase tracking-wider text-[11px]">Prioritas</th>
                                    <th class="px-3.5 py-3.5 text-center font-extrabold uppercase tracking-wider text-[11px]">Status</th>
                                    <th class="px-3.5 py-3.5 text-center font-extrabold uppercase tracking-wider text-[11px]">Lampiran</th>
                                    <th class="px-4 py-3.5 text-center font-extrabold uppercase tracking-wider text-[11px]">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E3DCC8]/70 bg-white">
                                <template x-for="r in paginatedReports" :key="r.db_id">
                                    <tr class="hover:bg-amber-50/50 transition-colors group">
                                        <!-- 1. ID & Tanggal Masuk -->
                                        <td class="px-4 py-3.5">
                                            <div class="font-mono font-extrabold text-[var(--mm-900)] text-xs group-hover:text-[var(--mm-600)] transition-colors" x-text="r.id"></div>
                                            <div class="text-[10px] text-gray-500 font-medium flex items-center gap-1 mt-0.5">
                                                <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span x-text="formatTanggal(r.tanggal_masuk ?? r.tanggal)"></span>
                                            </div>
                                        </td>

                                        <!-- 2. Pelapor & Alamat -->
                                        <td class="px-4 py-3.5">
                                            <div class="flex items-center gap-2.5">
                                                <template x-if="r.pelapor_avatar">
                                                    <img :src="r.pelapor_avatar" :alt="r.pelapor" class="w-9 h-9 rounded-full object-cover shadow-2xs border border-[#E3DCC8] shrink-0 ring-2 ring-white">
                                                </template>
                                                <template x-if="!r.pelapor_avatar">
                                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 via-indigo-600 to-[#0b335c] text-white font-black flex items-center justify-center text-[10.5px] shrink-0 shadow-2xs border border-blue-300 ring-2 ring-white" x-text="getInitials(r.pelapor)"></div>
                                                </template>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-xs text-[var(--mm-900)] truncate group-hover:text-[var(--mm-600)] transition-colors" x-text="r.pelapor"></p>
                                                    <p class="text-[10px] text-gray-500 flex items-center gap-1 mt-0.5 truncate max-w-[150px]" :title="formatAlamat(r)">
                                                        <svg class="w-2.5 h-2.5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                        <span x-text="formatAlamat(r)"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- 3. Perangkat & Kendala -->
                                        <td class="px-4 py-3.5 max-w-[210px]">
                                            <div class="font-bold text-xs text-[var(--mm-800)] truncate" x-text="r.perangkat"></div>
                                            <div class="text-[10.5px] text-gray-500 truncate mt-0.5" :title="r.kendala">
                                                <span class="text-gray-400 font-medium">Kendala:</span> <span class="text-gray-700 font-medium" x-text="r.kendala || '-'"></span>
                                            </div>
                                        </td>

                                        <!-- 4. Teknisi -->
                                        <td class="px-3.5 py-3.5">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl text-[10.5px] bg-sky-50 text-sky-800 border border-sky-200 font-bold shadow-2xs">
                                                <svg class="w-3 h-3 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                <span x-text="r.teknisi || 'Fahri'"></span>
                                            </span>
                                        </td>

                                        <!-- 5. Prioritas -->
                                        <td class="px-3 py-3.5 text-center">
                                            <span :class="priorityBadgeClass(r.prioritas)" class="px-2.5 py-0.5 rounded-full text-[10px] font-bold shadow-2xs border inline-block" x-text="r.prioritas"></span>
                                        </td>

                                        <!-- 6. Status Pengerjaan -->
                                        <td class="px-3.5 py-3.5 text-center">
                                            <span :class="statusBadgeClass(r.status)" class="px-2.5 py-1 rounded-full text-[10.5px] font-extrabold shadow-2xs border inline-flex items-center gap-1.5" x-text="r.status"></span>
                                        </td>

                                        <!-- 7. Lampiran -->
                                        <td class="px-3 py-3.5 text-center">
                                            <div class="inline-flex items-center justify-center gap-1.5">
                                                <button type="button" x-show="r.foto" @click="openMedia(r.foto, 'image')" title="Lihat Foto Kerusakan" class="px-2 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 transition shadow-2xs border border-emerald-200 inline-flex items-center gap-1 text-[10.5px] font-bold cursor-pointer active:scale-95">
                                                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    <span>Foto</span>
                                                </button>
                                                <button type="button" x-show="r.dokumen" @click="openMedia(r.dokumen, 'pdf')" title="Lihat Dokumen PDF" class="px-2 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 transition shadow-2xs border border-rose-200 inline-flex items-center gap-1 text-[10.5px] font-bold cursor-pointer active:scale-95">
                                                    <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    <span>PDF</span>
                                                </button>
                                                <span x-show="!r.foto && !r.dokumen" class="text-gray-400 italic text-[11px]">-</span>
                                            </div>
                                        </td>

                                        <!-- 8. Aksi -->
                                        <td class="px-4 py-3.5 text-center">
                                            <div class="inline-flex items-center gap-1.5 justify-center">
                                                <button type="button" @click="openDetail(r)" class="px-2.5 py-1.5 bg-white hover:bg-amber-50/80 text-[var(--mm-900)] border border-[#E3DCC8] hover:border-[var(--keraton-gold)] rounded-xl text-[11px] font-bold transition shadow-2xs inline-flex items-center gap-1 cursor-pointer active:scale-95">
                                                    <svg class="w-3 h-3 text-[var(--keraton-gold)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    <span>Detail</span>
                                                </button>
                                                <button type="button" @click="openStatusModal(r)" class="px-2.5 py-1.5 bg-gradient-to-r from-[var(--mm-800)] to-[var(--mm-900)] hover:from-[var(--mm-600)] hover:to-[var(--mm-800)] text-white rounded-xl text-[11px] font-bold transition shadow-xs inline-flex items-center gap-1 cursor-pointer active:scale-95 border border-white/10">
                                                    <svg class="w-3 h-3 text-[var(--keraton-gold-soft)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    <span>Status</span>
                                                </button>
                                                <button type="button" @click="deleteReport(r.db_id, r.id)" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white rounded-xl text-[11px] font-bold transition border border-rose-200 hover:border-rose-600 shadow-2xs inline-flex items-center gap-1 cursor-pointer active:scale-95 group">
                                                    <svg class="w-3 h-3 text-rose-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="filteredReports.length === 0">
                                    <td colspan="8" class="p-8 text-center text-gray-400 text-xs">
                                        Tidak ada antrean laporan yang sesuai dengan filter atau kata kunci pencarian.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2" x-show="filteredReports.length > 0">
                        <p class="text-[11px] text-gray-500">
                            Menampilkan <span class="font-bold text-[var(--mm-800)]" x-text="filteredReports.length === 0 ? 0 : ((currentPage - 1) * perPage + 1)"></span>
                            - <span class="font-bold text-[var(--mm-800)]" x-text="Math.min(currentPage * perPage, filteredReports.length)"></span>
                            dari <span class="font-bold text-[var(--mm-800)]" x-text="filteredReports.length"></span> laporan
                        </p>
                        <div class="flex items-center gap-1.5">
                            <button @click="currentPage = Math.max(1, currentPage - 1)" :disabled="currentPage === 1"
                                    class="px-3 py-1 rounded-lg text-[10px] font-bold border border-[#E3DCC8] text-[var(--mm-800)] disabled:opacity-30 hover:bg-[var(--batik-cream)] transition">Sebelumnya</button>
                            <span class="text-[11px] font-semibold text-[var(--mm-800)] px-2" x-text="currentPage + ' / ' + totalPages"></span>
                            <button @click="currentPage = Math.min(totalPages, currentPage + 1)" :disabled="currentPage === totalPages"
                                    class="px-3 py-1 rounded-lg text-[10px] font-bold border border-[#E3DCC8] text-[var(--mm-800)] disabled:opacity-30 hover:bg-[var(--batik-cream)] transition">Berikutnya</button>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <div x-show="mediaModalOpen"
         x-cloak
         x-transition.opacity
         @keydown.escape.window="mediaModalOpen = false"
         class="fixed inset-0 z-[90] flex items-center justify-center bg-black/80 p-4"
         role="dialog"
         aria-modal="true">
        <div class="bg-white rounded-2xl max-w-2xl w-full p-4 space-y-3 shadow-2xl border border-[#E3DCC8] relative flex flex-col max-h-[85vh]"
             @click.away="mediaModalOpen = false"
             @contextmenu.prevent>

            <div class="flex justify-between items-center border-b pb-2 px-2">
                <p class="font-display font-bold text-sm text-[var(--mm-900)]" x-text="mediaType === 'image' ? 'Pratinjau Foto Kerusakan' : 'Pratinjau Dokumen Pendukung'"></p>
                
                <div class="flex items-center gap-2">
                    <a :href="getDownloadUrl(mediaSrc)" 
                       download 
                       target="_blank"
                       class="inline-flex items-center gap-1 px-3 py-1 bg-[var(--mm-800)] hover:bg-[var(--mm-900)] text-white rounded-xl text-xs font-semibold transition shadow">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh
                    </a>
                    <button type="button" @click="mediaModalOpen = false" aria-label="Tutup" class="text-gray-500 hover:text-red-600 text-lg font-bold px-2 py-0.5 rounded-lg hover:bg-gray-100 transition">✕</button>
                </div>
            </div>

            <div class="flex-1 overflow-auto flex items-center justify-center bg-gray-900 rounded-xl p-3 min-h-[50vh] max-h-[65vh] relative select-none">
                <template x-if="mediaType === 'image'">
                    <div class="relative w-full h-full flex items-center justify-center">
                        <img :src="mediaSrc.startsWith('http') ? mediaSrc : ('{{ asset('storage') }}/' + mediaSrc)" 
                             x-on:error="if (!$el.dataset.fallback && mediaSrc) { $el.dataset.fallback = '1'; let p = mediaSrc.replace(/^https?:\/\/[^\/]+\/(storage\/)?/, '').replace(/^\/?storage\//, ''); $el.src = '{{ url('/storage-file') }}/' + p; }"
                             class="max-h-[55vh] max-w-full object-contain rounded-lg pointer-events-none select-none shadow-md" 
                             alt="Pratinjau Foto">
                    </div>
                </template>

                <template x-if="mediaType === 'pdf'">
                    <iframe :src="getPdfUrl(mediaSrc) + '#toolbar=0&navpanes=0&scrollbar=0'" class="w-full h-[55vh] rounded-lg bg-white border-0" title="Pratinjau PDF"></iframe>
                </template>
            </div>
        </div>
    </div>

    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/60 p-0 sm:p-4">
        <div class="bg-white rounded-t-[28px] sm:rounded-2xl max-w-md w-full p-5 sm:p-6 space-y-4 shadow-2xl border border-[#E3DCC8] max-h-[90vh] sm:max-h-[85vh] overflow-y-auto" @click.away="modalOpen = false">
            <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-1 sm:hidden"></div>
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-display font-bold text-base text-[var(--mm-900)]">Perbarui Status Pengerjaan</h3>
                <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <div class="space-y-3 text-xs" x-show="currentReport">
                <p>Mengubah status untuk laporan: <strong x-text="currentReport?.id" class="text-[var(--mm-800)]"></strong> — <span x-text="currentReport?.pelapor"></span></p>

                <div class="space-y-1">
                    <label class="font-semibold text-gray-700">Teknisi Bertugas:</label>
                    <select x-model="currentReport.teknisi" class="w-full bg-[var(--batik-cream)] border border-[#E3DCC8] rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[var(--mm-600)]">
                        <option value="Fahri">Fahri</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="font-semibold text-gray-700">Pilih Status Baru:</label>
                    <select x-model="currentStatus" class="w-full bg-[var(--batik-cream)] border border-[#E3DCC8] rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[var(--mm-600)]">
                        <option value="Dalam Perbaikan">Dalam Perbaikan</option>
                        <option value="Menunggu Part">Menunggu Part</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>

                <div class="space-y-1" x-show="currentStatus === 'Selesai'">
                    <label class="font-semibold text-gray-700">Tanggal Keluar:</label>
                    <input type="date" x-model="currentTanggalKeluar" class="w-full bg-[var(--batik-cream)] border border-[#E3DCC8] rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[var(--mm-600)]">
                </div>

                <div class="space-y-1">
                    <label class="font-semibold text-gray-700">Catatan / Keterangan Progres (Opsional):</label>
                    <textarea x-model="currentCatatan" rows="3" placeholder="Masukkan keterangan pengerjaan atau alasan penolakan..." class="w-full bg-[var(--batik-cream)] border border-[#E3DCC8] rounded-xl px-3 py-2 text-xs font-medium focus:outline-none focus:border-[var(--mm-600)]"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button @click="modalOpen = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">Batal</button>
                <button @click="saveStatus()" class="px-4 py-2 bg-[var(--mm-800)] hover:bg-[var(--mm-900)] text-white rounded-xl text-xs font-semibold transition shadow">Simpan & Perbarui</button>
            </div>
        </div>
    </div>

    <div x-show="detailModalOpen" x-cloak class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/60 p-0 sm:p-4">
        <div class="bg-white rounded-t-[28px] sm:rounded-2xl max-w-lg w-full p-5 sm:p-6 space-y-4 shadow-2xl border border-[#E3DCC8] max-h-[90vh] sm:max-h-[85vh] overflow-y-auto" @click.away="detailModalOpen = false">
            <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-1 sm:hidden"></div>
            <div class="flex justify-between items-center border-b pb-3">
                <div>
                    <h3 class="font-display font-bold text-base text-[var(--mm-900)]" x-text="currentReport?.id"></h3>
                    <p class="text-[10px] text-gray-500">Detail & riwayat penanganan laporan perangkat</p>
                </div>
                <button @click="detailModalOpen = false" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs" x-show="currentReport">
                <div class="bg-[var(--batik-cream)] rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 font-semibold">Tanggal Masuk</p>
                    <p class="font-bold text-[var(--mm-800)]" x-text="formatTanggal(currentReport?.tanggal_masuk ?? currentReport?.tanggal)"></p>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 font-semibold">Tanggal Keluar</p>
                    <p class="font-bold" :class="currentReport?.tanggal_keluar ? 'text-[var(--mm-800)]' : 'text-gray-400 italic'"
                       x-text="currentReport?.tanggal_keluar ? formatTanggal(currentReport.tanggal_keluar) : 'Belum keluar'"></p>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3 flex items-center gap-2.5">
                    <template x-if="currentReport?.pelapor_avatar">
                        <img :src="currentReport?.pelapor_avatar" class="w-9 h-9 rounded-full object-cover shadow-2xs border border-[#E3DCC8] ring-1 ring-white shrink-0">
                    </template>
                    <template x-if="!currentReport?.pelapor_avatar">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 via-indigo-600 to-[#0b335c] text-white font-black flex items-center justify-center text-xs shadow-2xs border border-blue-300 ring-1 ring-white shrink-0" x-text="getInitials(currentReport?.pelapor)"></div>
                    </template>
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-500 font-semibold">Nama Pelapor</p>
                        <p class="font-bold text-[var(--ink)] truncate" x-text="currentReport?.pelapor"></p>
                    </div>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 font-semibold">Alamat</p>
                    <p class="font-bold text-[var(--ink)]" x-text="formatAlamat(currentReport)"></p>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 font-semibold">Perangkat</p>
                    <p class="font-bold text-[var(--ink)]" x-text="currentReport?.perangkat"></p>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 font-semibold">Prioritas</p>
                    <span :class="priorityBadgeClass(currentReport?.prioritas)" class="inline-block mt-0.5 px-2.5 py-1 rounded-lg text-[10px] font-bold" x-text="currentReport?.prioritas"></span>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 font-semibold">Status Saat Ini</p>
                    <span :class="statusBadgeClass(currentReport?.status)" class="inline-block mt-0.5 px-2.5 py-1 rounded-lg text-[10px] font-bold" x-text="currentReport?.status"></span>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3 col-span-2">
                    <p class="text-[10px] text-gray-500 font-semibold">Kendala / Kerusakan</p>
                    <p class="font-medium text-[var(--ink)]" x-text="currentReport?.kendala"></p>
                </div>
            </div>

            <div class="space-y-2 pt-2" x-show="currentReport">
                <p class="font-display font-bold text-sm text-[var(--mm-900)]">Riwayat Penanganan (History Log)</p>
                <div class="relative pl-4 border-l-2 border-[var(--mm-200)] space-y-4">
                    <template x-for="(h, i) in (currentReport?.riwayat || [])" :key="i">
                        <div class="relative">
                            <span class="absolute -left-[21px] top-0.5 w-3 h-3 rounded-full bg-[var(--keraton-gold)] ring-4 ring-white"></span>
                            <p class="text-[10px] text-gray-500 font-semibold" x-text="h.tanggal"></p>
                            <p class="text-xs font-bold text-[var(--mm-800)]" x-text="h.status"></p>
                            <p class="text-[11px] text-gray-600" x-text="h.catatan"></p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t">
                <button @click="detailModalOpen = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition">Tutup</button>
            </div>
        </div>
    </div>

    <div x-show="toast.show" x-cloak x-transition
         class="fixed bottom-5 right-5 z-[60] bg-[var(--mm-900)] text-white px-4 py-3 rounded-xl shadow-2xl text-xs font-semibold flex items-center gap-2 border border-white/10">
        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span x-text="toast.message"></span>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        function antreanApp() {
            return {
                sidebarOpen: false,
                searchQuery: '',
                statusFilter: 'Semua',
                deviceFilter: 'Semua',
                currentPage: 1,
                perPage: 5,
                reports: @json($reportsFinal),
                notifReports: @json($notifReports ?? []),
                seenAdminReportIds: JSON.parse(localStorage.getItem('siperkom_admin_seen_reports') || '[]'),
                daftarTeknisi: ['Fahri'],
                toast: { show: false, message: '' },
                
                modalOpen: false,
                currentReport: null,
                currentStatus: '',
                currentCatatan: '',
                currentTanggalKeluar: '',
                detailModalOpen: false,
                
                mediaModalOpen: false,
                mediaSrc: '',
                mediaType: '',

                get unreadAdminReports() {
                    return this.notifReports.filter(r => {
                        const id = String(r.db_id || r.id);
                        return !this.seenAdminReportIds.includes(id);
                    });
                },

                markAllAdminNotifsRead(showToast = false) {
                    let seen = [...this.seenAdminReportIds];
                    this.notifReports.forEach(r => {
                        const id = String(r.db_id || r.id);
                        if (!seen.includes(id)) seen.push(id);
                    });
                    this.seenAdminReportIds = seen;
                    localStorage.setItem('siperkom_admin_seen_reports', JSON.stringify(seen));
                    if (showToast) {
                        this.showToast('Semua laporan masuk telah ditandai dibaca');
                    }
                },

                openReportFromNotif(r) {
                    const id = String(r.db_id || r.id);
                    let seen = [...this.seenAdminReportIds];
                    if (!seen.includes(id)) {
                        seen.push(id);
                        this.seenAdminReportIds = seen;
                        localStorage.setItem('siperkom_admin_seen_reports', JSON.stringify(seen));
                    }
                    
                    const target = this.reports.find(item => (item.db_id || item.id) == (r.db_id || r.id));
                    if (target) {
                        this.openDetail(target);
                    } else {
                        this.searchQuery = r.id;
                    }
                },

                init() {
                    // Otomatis hilangkan angka notifikasi laporan saat admin membuka antrean tanpa harus klik lonceng
                    this.markAllAdminNotifsRead(false);

                    this.$watch('statusFilter', () => { this.currentPage = 1; });
                    this.$watch('deviceFilter', () => { this.currentPage = 1; });

                    const urlParams = new URLSearchParams(window.location.search);
                    const openId = urlParams.get('open_id');
                    if (openId) {
                        const targetReport = this.reports.find(r => r.db_id == openId);
                        if (targetReport) {
                            this.openDetail(targetReport);
                        }
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }

                    // Real-time Live Sync Polling tanpa refresh setiap 2.5 detik
                    setInterval(() => this.syncLiveData(), 2500);
                },

                async syncLiveData() {
                    if (this.modalOpen || this.detailModalOpen || this.mediaModalOpen) return;

                    try {
                        const response = await fetch('{{ route("admin.antrean.live-sync") }}', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            if (data.reports) {
                                const existingIds = this.reports.map(r => r.db_id);
                                const newIncoming = data.reports.filter(r => !existingIds.includes(r.db_id));
                                if (newIncoming.length > 0 && this.reports.length > 0) {
                                    this.showToast('🔔 ' + newIncoming.length + ' laporan baru masuk antrean dari pelapor!');
                                }

                                this.reports = data.reports;
                            }
                            if (data.notifReports) {
                                this.notifReports = data.notifReports;
                                this.markAllAdminNotifsRead(false);
                            }
                        }
                    } catch (e) {}
                },

                openMedia(src, type) {
                    this.mediaSrc = src;
                    this.mediaType = type;
                    this.mediaModalOpen = true;
                },

                getDownloadUrl(src) {
                    if (!src) return '#';
                    if (src.startsWith('http')) return src;
                    return "{{ asset('storage') }}/" + src;
                },

                getPdfUrl(src) {
                    if (!src) return '';
                    if (src.startsWith('http')) return src;
                    const filename = src.split('/').pop();
                    return "{{ url('/pelapor/dokumen-preview') }}/" + encodeURIComponent(filename);
                },

                classifyDevice(perangkat) {
                    if (!perangkat) return 'Lainnya';
                    const p = perangkat.trim();
                    if (p.includes('Komputer') || p.includes('PC') || p.includes('CPU')) return 'Komputer Unit / PC';
                    if (p.includes('Laptop')) return 'Laptop';
                    if (p.includes('Printer')) return 'Printer';
                    if (p.includes('Scanner')) return 'Scanner';
                    if (p.includes('Jaringan') || p.includes('Router') || p.includes('Mikrotik') || p.includes('WiFi')) return 'Jaringan / Router';
                    return 'Lainnya';
                },

                getDeviceCount(category) {
                    if (!this.reports || !this.reports.length) return 0;
                    if (category === 'Semua') return this.reports.length;
                    return this.reports.filter(r => this.classifyDevice(r.perangkat) === category).length;
                },

                getStatusCount(status) {
                    if (!this.reports || !this.reports.length) return 0;
                    if (status === 'Semua') return this.reports.length;
                    return this.reports.filter(r => r.status === status).length;
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

                formatTanggal(raw) {
                    if (!raw) return '-';
                    const d = new Date(raw);
                    if (isNaN(d.getTime())) return raw;
                    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                },

                get filteredReports() {
                    const q = this.searchQuery.trim().toLowerCase();
                    return this.reports.filter(r => {
                        const matchStatus = this.statusFilter === 'Semua' || r.status === this.statusFilter;
                        const deviceCat = this.classifyDevice(r.perangkat);
                        const matchDevice = this.deviceFilter === 'Semua' || deviceCat === this.deviceFilter;
                        const matchQuery = !q ||
                            (r.id || '').toLowerCase().includes(q) ||
                            (r.alamat || '').toLowerCase().includes(q) ||
                            (r.kecamatan || '').toLowerCase().includes(q) ||
                            (r.pelapor || '').toLowerCase().includes(q);
                        return matchStatus && matchDevice && matchQuery;
                    });
                },

                get totalPages() {
                    return Math.max(1, Math.ceil(this.filteredReports.length / this.perPage));
                },

                get paginatedReports() {
                    if (this.currentPage > this.totalPages) this.currentPage = this.totalPages;
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredReports.slice(start, start + this.perPage);
                },

                statusBadgeClass(status) {
                    return {
                        'Dalam Perbaikan': 'bg-amber-50 text-amber-900 border-amber-300',
                        'Menunggu Part': 'bg-blue-50 text-blue-900 border-blue-300',
                        'Selesai': 'bg-emerald-50 text-emerald-900 border-emerald-300',
                        'Ditolak': 'bg-rose-50 text-rose-900 border-rose-300',
                    }[status] || 'bg-gray-50 text-gray-700 border-gray-200';
                },

                priorityBadgeClass(priority) {
                    return {
                        'Darurat': 'bg-rose-600 text-white border-rose-700 font-extrabold',
                        'Tinggi': 'bg-rose-50 text-rose-800 border-rose-200',
                        'Sedang': 'bg-amber-50 text-amber-800 border-amber-200',
                        'Rendah': 'bg-slate-50 text-slate-700 border-slate-200',
                    }[priority] || 'bg-gray-50 text-gray-600 border-gray-200';
                },

                openStatusModal(r) {
                    this.currentReport = r;
                    this.currentStatus = r.status;
                    this.currentCatatan = '';
                    this.currentTanggalKeluar = r.tanggal_keluar || new Date().toISOString().slice(0, 10);
                    this.modalOpen = true;
                },

                saveStatus() {
                    if (!this.currentReport) return;

                    const tanggalKeluarBaru = this.currentStatus === 'Selesai' ? this.currentTanggalKeluar : this.currentReport.tanggal_keluar;
                    this.showToast('Menyimpan perubahan...');

                    fetch('/admin/laporan/' + this.currentReport.db_id + '/status', {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            status: this.currentStatus,
                            teknisi: this.currentReport.teknisi,
                            catatan: this.currentCatatan,
                            tanggal_keluar: tanggalKeluarBaru
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            this.currentReport.status = this.currentStatus;
                            this.currentReport.tanggal_keluar = tanggalKeluarBaru;
                            
                            // Menyimpan catatan ke keterangan_rekap agar langsung sinkron dengan halaman Rekap
                            this.currentReport.keterangan_rekap = this.currentCatatan || ('Status diperbarui menjadi ' + this.currentStatus);

                            if (!this.currentReport.riwayat) this.currentReport.riwayat = [];
                            this.currentReport.riwayat.push({
                                tanggal: new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + ' ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
                                status: this.currentStatus,
                                catatan: this.currentCatatan || ('Status diperbarui menjadi ' + this.currentStatus)
                            });

                            this.showToast('Status berhasil diperbarui!');
                            this.modalOpen = false;
                        } else {
                            alert('Gagal memperbarui status: ' + (data.message || 'Kesalahan server'));
                        }
                    })
                    .catch(err => {
                        alert('Terjadi kesalahan jaringan.');
                    });
                },

                openDetail(r) {
                    this.currentReport = r;
                    this.detailModalOpen = true;
                },

                deleteReport(dbId, kodeId) {
                    if (confirm('Apakah kamu yakin ingin menghapus laporan ' + kodeId + ' ini secara permanen?')) {
                        fetch('/admin/laporan/' + dbId, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).then(res => res.json()).then(data => {
                            if(data.success) {
                                this.reports = this.reports.filter(r => r.db_id !== dbId);
                                this.showToast('Laporan ' + kodeId + ' berhasil dihapus.');
                            } else {
                                alert(data.message || 'Gagal menghapus laporan.');
                            }
                        }).catch(err => {
                            this.reports = this.reports.filter(r => r.db_id !== dbId);
                            this.showToast('Laporan ' + kodeId + ' dihapus dari antrean.');
                        });
                    }
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