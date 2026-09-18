@php
    $defaultReports = $defaultReports ?? [];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Manajemen Akun Pelapor | SIPERKOM Kabupaten Cirebon</title>
    
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
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)]"
    x-data="manajemenApp()"
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

            <!-- Top Header -->
            <header class="bg-white border-b border-[#E3DCC8] px-3 sm:px-6 py-3 sm:py-4 sticky top-0 z-30 flex items-center justify-between shadow-sm gap-2 sm:gap-3 shrink-0 lg:rounded-t-3xl">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3">
                    <button type="button" @click="sidebarOpen = true" aria-label="Buka menu" class="lg:hidden p-2 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] text-[var(--mm-900)] shrink-0 hover:bg-[#F3EBD8] transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="font-display font-bold text-[11px] leading-tight sm:text-sm text-[var(--mm-900)]">Manajemen Akun Pelapor</h2>
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
                                    <a :href="'{{ route('admin.antrean') }}?open_id=' + (r.db_id || '')" 
                                       @click="markReportRead(r.db_id || r.id); notifOpen = false"
                                       class="block w-full text-left px-3.5 py-2.5 hover:bg-amber-50/60 transition cursor-pointer group"
                                       :class="{ 'bg-amber-50/40': !seenAdminReportIds.includes(String(r.db_id || r.id)) }">
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
                                    </a>
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

            <!-- Isi Konten Manajemen Pelapor -->
            <div class="p-3.5 sm:p-6 lg:p-8 space-y-4 sm:space-y-6 flex-1 pb-16">
                <div class="bg-white p-4 sm:p-7 lg:p-8 rounded-2xl sm:rounded-3xl shadow-sm border border-[#E3DCC8]">
                    
                    <!-- Header Judul, Stats, dan Tombol Tambah -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6 pb-5 border-b border-[#E3DCC8]/80">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-[var(--batik-cream)] border border-[#E3DCC8] flex items-center justify-center text-[var(--keraton-gold)] shadow-xs shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base sm:text-lg font-extrabold font-display text-[var(--mm-900)] tracking-tight">Daftar Akun Terdaftar</h3>
                                    <span class="text-[11px] font-extrabold text-[var(--mm-900)] bg-[var(--batik-cream)] border border-[#E3DCC8] px-2.5 py-0.5 rounded-lg shadow-xs">
                                        {{ isset($users) ? $users->count() : 0 }} Pelapor
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">Kelola akun pelapor, hak akses, dan data pengguna sistem SIPERKOM.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap w-full sm:w-auto">
                            <!-- Live Search Filter -->
                            <div class="relative flex-1 sm:w-56 md:w-64">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input type="text" 
                                       x-model="searchQuery" 
                                       placeholder="Cari nama, email, alamat..." 
                                       class="w-full pl-8 pr-7 py-2 text-xs rounded-xl border border-[#E3DCC8] bg-[var(--batik-cream)]/30 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[var(--mm-800)]/25 focus:border-[var(--mm-800)] transition placeholder-gray-400 shadow-xs">
                                <button type="button" 
                                        x-show="searchQuery" 
                                        @click="searchQuery = ''" 
                                        class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-gray-600 cursor-pointer">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <!-- Tombol Tambah Akun -->
                            <button type="button" 
                                    @click="openAddModal = true" 
                                    class="px-3.5 py-2 rounded-xl bg-gradient-to-r from-[var(--mm-800)] to-[var(--mm-900)] hover:from-[var(--mm-600)] hover:to-[var(--mm-800)] text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-md shadow-blue-900/10 cursor-pointer active:scale-95 border border-white/10 shrink-0">
                                <svg class="w-3.5 h-3.5 text-[var(--keraton-gold-soft)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <span>Tambah Akun</span>
                            </button>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow-xs">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-xs font-semibold flex items-center gap-2 shadow-xs">
                            <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-xs space-y-1 shadow-xs">
                            <p class="font-bold flex items-center gap-1.5 text-red-900">
                                <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <span>Mohon periksa kembali formulir:</span>
                            </p>
                            <ul class="list-disc list-inside text-red-700 pl-1 space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @isset($users)
                        <!-- ================= TAMPILAN MOBILE: KARTU AKUN MODERN ================= -->
                        <div class="md:hidden space-y-3.5">
                            @forelse($users as $user)
                            <div x-show="userMatches('{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->alamat ?? '') }}')"
                                 x-transition
                                 class="bg-white p-4.5 rounded-2xl border border-[#E3DCC8] shadow-xs hover:shadow-md transition space-y-3.5" 
                                 x-data="{ showPassword: false }">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        @if(!empty($user->avatar))
                                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-[#E3DCC8] shrink-0 ring-2 ring-white">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 via-indigo-600 to-[#0b335c] text-white font-black flex items-center justify-center text-xs shrink-0 shadow-xs ring-2 ring-white">
                                                {{ strtoupper(substr($user->name ?? 'P', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-sm text-[var(--mm-900)] truncate">{{ $user->name }}</h4>
                                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200 shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> PELAPOR
                                    </span>
                                </div>

                                <div class="text-xs bg-[var(--batik-cream)]/60 p-2.5 rounded-xl border border-[#E3DCC8]/70 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    <span class="font-medium text-[var(--mm-900)] truncate">{{ (!empty($user->alamat) && $user->alamat !== '-') ? $user->alamat : 'cirebon' }}</span>
                                </div>

                                <!-- Password Card -->
                                <div class="flex items-center justify-between text-xs bg-slate-50 px-3 py-2 rounded-xl border border-slate-200/80">
                                    <span class="text-gray-500 text-[11px] font-semibold">Kata Sandi:</span>
                                    <div class="flex items-center gap-2 font-mono">
                                        <span x-show="!showPassword" class="tracking-widest text-gray-400 font-bold select-none text-xs">••••••••</span>
                                        <span x-show="showPassword" x-cloak class="text-[var(--mm-900)] font-bold text-xs select-all">{{ $user->password ?? 'password' }}</span>
                                        <button @click="showPassword = !showPassword" type="button" class="text-gray-400 hover:text-[var(--mm-600)] p-0.5 cursor-pointer">
                                            <svg x-show="!showPassword" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <svg x-show="showPassword" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411l0 0L21 21"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Aksi -->
                                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                                    <form action="{{ route('admin.manajemen.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($user->name) }} secara permanen? Codingan seeder dan data akun ini akan ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-600 hover:text-white rounded-xl transition font-bold text-xs inline-flex items-center gap-1.5 cursor-pointer active:scale-95 border border-rose-200">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Hapus Akun</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="p-8 text-center bg-[var(--batik-cream)]/30 rounded-2xl border border-dashed border-[#E3DCC8]">
                                <div class="w-12 h-12 rounded-2xl bg-[var(--batik-cream)] border border-[#E3DCC8] flex items-center justify-center text-[var(--keraton-gold)] mx-auto mb-3 shadow-xs">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <p class="font-bold text-sm text-[var(--mm-900)]">Belum Ada Akun Pelapor</p>
                                <p class="text-xs text-gray-500 mt-1">Daftar akun staf pelapor masih kosong. Klik tombol "+ Tambah Akun" untuk mendaftarkan akun baru.</p>
                            </div>
                            @endforelse
                        </div>

                        <!-- ================= TAMPILAN DESKTOP/LAPTOP (TABEL PREMIUM) ================= -->
                        <div class="hidden md:block rounded-2xl border border-[#E3DCC8] bg-white shadow-xs overflow-x-auto">
                            <table class="w-full divide-y divide-[#E3DCC8]/70 text-xs text-left">
                                <thead class="bg-gradient-to-r from-[var(--batik-cream)] via-[#FDFBF7] to-[var(--batik-cream)] border-b border-[#E3DCC8]">
                                    <tr>
                                        <th class="px-5 py-3.5 text-left font-extrabold uppercase tracking-wider text-[11px] text-[var(--mm-900)]">Pengguna &amp; Alamat</th>
                                        <th class="px-4 py-3.5 text-left font-extrabold uppercase tracking-wider text-[11px] text-[var(--mm-900)]">Email (Gmail)</th>
                                        <th class="px-4 py-3.5 text-left font-extrabold uppercase tracking-wider text-[11px] text-[var(--mm-900)] whitespace-nowrap">Kata Sandi</th>
                                        <th class="px-4 py-3.5 text-left font-extrabold uppercase tracking-wider text-[11px] text-[var(--mm-900)] whitespace-nowrap">Peran (Role)</th>
                                        <th class="px-5 py-3.5 text-center font-extrabold uppercase tracking-wider text-[11px] text-[var(--mm-900)] whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse($users as $user)
                                    <tr x-show="userMatches('{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->alamat ?? '') }}')"
                                        x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        class="hover:bg-amber-50/30 transition-colors group">
                                        
                                        <!-- 1. Kolom Nama & Avatar -->
                                        <td class="px-5 py-3.5">
                                            <div class="flex items-center gap-3">
                                                @if(!empty($user->avatar))
                                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                                                         class="w-10 h-10 rounded-full object-cover shadow-sm border border-[#E3DCC8] shrink-0 ring-2 ring-white">
                                                @else
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 via-indigo-600 to-[#0b335c] text-white font-black flex items-center justify-center text-xs shrink-0 shadow-sm border border-blue-300 ring-2 ring-white">
                                                        {{ strtoupper(substr($user->name ?? 'P', 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div class="min-w-0">
                                                    <p class="font-bold text-xs lg:text-sm text-[var(--mm-900)] group-hover:text-[var(--mm-600)] transition-colors">
                                                        {{ $user->name }}
                                                    </p>
                                                    <p class="text-[11px] text-gray-500 flex items-center gap-1 mt-0.5">
                                                        <svg class="w-3 h-3 text-amber-600/80 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                                        <span>{{ (!empty($user->alamat) && $user->alamat !== '-') ? $user->alamat : 'cirebon' }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- 2. Kolom Email -->
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <div class="flex items-center gap-1.5 text-gray-600 text-xs font-medium">
                                                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                <span>{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        
                                        <!-- 3. Kolom Sandi Teks Biasa Interaktif -->
                                        <td class="px-4 py-3.5 whitespace-nowrap" x-data="{ showPassword: false }">
                                            <div class="inline-flex items-center gap-2 bg-slate-50 border border-slate-200/90 hover:border-slate-300 px-3 py-1.5 rounded-xl shadow-xs transition">
                                                <span x-show="!showPassword" class="tracking-widest font-mono text-gray-400 select-none text-xs">••••••••</span>
                                                <span x-show="showPassword" x-cloak class="font-mono text-[var(--mm-900)] font-bold text-xs select-all">
                                                    {{ $user->password ?? 'password' }}
                                                </span>
                                                <button type="button" @click="showPassword = !showPassword" 
                                                        class="text-gray-400 hover:text-[var(--mm-600)] transition p-0.5 cursor-pointer" 
                                                        :title="showPassword ? 'Sembunyikan sandi' : 'Lihat sandi'">
                                                    <svg x-show="!showPassword" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    <svg x-show="showPassword" x-cloak class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411l0 0L21 21"/></svg>
                                                </button>
                                            </div>
                                        </td>

                                        <!-- 4. Kolom Role -->
                                        <td class="px-4 py-3.5 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10.5px] font-extrabold uppercase tracking-wide bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 border border-blue-200 shadow-xs">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                PELAPOR
                                            </span>
                                        </td>

                                        <!-- 5. Kolom Aksi -->
                                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                            <form action="{{ route('admin.manajemen.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($user->name) }} secara permanen? Codingan seeder dan data akun ini akan ikut terhapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white rounded-xl transition-all duration-150 font-bold text-xs cursor-pointer active:scale-95 border border-rose-200 hover:border-rose-600 shadow-xs hover:shadow group">
                                                    <svg class="w-3.5 h-3.5 text-rose-500 group-hover:text-white transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                                            <div class="max-w-xs mx-auto flex flex-col items-center justify-center text-center">
                                                <div class="w-12 h-12 rounded-2xl bg-[var(--batik-cream)] border border-[#E3DCC8] flex items-center justify-center text-[var(--keraton-gold)] mb-3 shadow-xs">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                                </div>
                                                <p class="font-bold text-sm text-[var(--mm-900)]">Belum Ada Akun Pelapor</p>
                                                <p class="text-xs text-gray-500 mt-1">Daftar akun staf pelapor masih kosong. Klik tombol "+ Tambah Akun" untuk mendaftarkan akun baru.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-xs text-gray-400 py-8">Belum ada data akun.</p>
                    @endisset
                </div>
            </div>
        </main>
    </div>

    <!-- ============ MODAL TAMBAH AKUN PELAPOR ============ -->
    <div x-show="openAddModal" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-0 sm:p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div @click.away="openAddModal = false"
             class="bg-white rounded-t-[28px] sm:rounded-3xl max-w-md w-full p-5 sm:p-6 shadow-2xl border border-[#E3DCC8] relative overflow-hidden max-h-[90vh] sm:max-h-none overflow-y-auto"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100">
            <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-1 sm:hidden"></div>
            
            <div class="flex items-center justify-between pb-4 border-b border-[#E3DCC8] mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-[var(--batik-cream)] border border-[#E3DCC8] flex items-center justify-center text-[var(--mm-900)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-sm text-[var(--mm-900)]">Tambah Akun Pelapor</h3>
                        <p class="text-[11px] text-gray-500">Buat akun untuk staf pelapor baru</p>
                    </div>
                </div>
                <button type="button" @click="openAddModal = false" class="p-1.5 rounded-xl hover:bg-gray-100 text-gray-400 hover:text-gray-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @if ($errors->any())
                <div class="mb-3.5 p-3 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs space-y-1">
                    <p class="font-bold text-red-900 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Mohon periksa kembali data Anda:</span>
                    </p>
                    <ul class="list-disc list-inside text-red-700 pl-1 space-y-0.5 text-[11px]">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.manajemen.store') }}" method="POST" class="space-y-3.5 text-xs">
                @csrf
                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Nama Lengkap Pelapor <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="Contoh: Budi Santoso"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--mm-800)] text-xs">
                    @error('name')
                        <p class="text-red-600 text-[10.5px] font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Email / Akun Gmail <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="nama@gmail.com"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--mm-800)] text-xs">
                    @error('email')
                        <p class="text-red-600 text-[10.5px] font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div x-data="{ showPass: false }">
                    <label class="block font-semibold text-gray-700 mb-1">Kata Sandi Awal <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input :type="showPass ? 'text' : 'password'" name="password" required placeholder="Minimal 6 karakter"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--mm-800)] text-xs pr-10">
                        <button type="button" @click="showPass = !showPass" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411l0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-600 text-[10.5px] font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold text-gray-700 mb-1">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Contoh: Jl. Sunan Kalijaga No. 7, Sumber"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[var(--mm-800)] text-xs">
                </div>

                <div class="pt-3 flex items-center justify-end gap-2 border-t border-[#E3DCC8]">
                    <button type="button" @click="openAddModal = false" class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 font-semibold transition">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[var(--mm-800)] hover:bg-[var(--mm-900)] text-white font-bold transition shadow-sm">Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ TOAST NOTIFIKASI ============ -->
    <div x-show="toast.show" x-cloak x-transition
         class="fixed bottom-5 right-5 z-[60] bg-[var(--mm-900)] text-white px-4 py-3 rounded-xl shadow-2xl text-xs font-semibold flex items-center gap-2 border border-white/10">
        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span x-text="toast.message"></span>
    </div>

    <!-- INJEKSI DATA LAPORAN UNTUK NOTIFIKASI -->
    <script>
        window.__notifReports = {!! json_encode(array_values($notifReports ?? [])) !!};
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function manajemenApp() {
            return {
                sidebarOpen: false,
                openAddModal: {{ $errors->any() ? 'true' : 'false' }},
                notifReports: window.__notifReports || [],
                seenAdminReportIds: JSON.parse(localStorage.getItem('siperkom_admin_seen_reports') || '[]'),
                toast: { show: false, message: '' },
                searchQuery: '',

                userMatches(name, email, alamat) {
                    if (!this.searchQuery) return true;
                    const q = this.searchQuery.toLowerCase().trim();
                    return (name && name.toLowerCase().includes(q)) ||
                           (email && email.toLowerCase().includes(q)) ||
                           (alamat && alamat.toLowerCase().includes(q));
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
                                    this.showToast('🔔 Laporan baru masuk: ' + (first.pelapor || 'Pelapor') + ' (' + (first.perangkat || 'Perangkat') + ')');
                                }
                                this.notifReports = data.notifReports;
                            }
                        }
                    } catch (e) {}
                },

                get unreadAdminReports() {
                    return this.notifReports.filter(r => !this.seenAdminReportIds.includes(String(r.db_id || r.id)));
                },

                markAllAdminNotifsRead() {
                    const ids = this.notifReports.map(r => String(r.db_id || r.id));
                    const merged = Array.from(new Set([...this.seenAdminReportIds, ...ids]));
                    this.seenAdminReportIds = merged;
                    localStorage.setItem('siperkom_admin_seen_reports', JSON.stringify(merged));
                    this.showToast('Semua notifikasi laporan masuk ditandai dibaca');
                },

                markReportRead(id) {
                    if (!id) return;
                    const strId = String(id);
                    if (!this.seenAdminReportIds.includes(strId)) {
                        this.seenAdminReportIds.push(strId);
                        localStorage.setItem('siperkom_admin_seen_reports', JSON.stringify(this.seenAdminReportIds));
                    }
                },

                showToast(message) {
                    this.toast = { show: true, message: message };
                    setTimeout(() => { this.toast.show = false; }, 3500);
                }
            }
        }
    </script>
</body>
</html>