@php
    $defaultReports = $defaultReports ?? [];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Dashboard Pelapor | SIPERKOM Kabupaten Cirebon</title>

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

        /* Latar Belakang Batik Mega Mendung */
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

        @media (prefers-reduced-motion: reduce) {
            * { transition-duration: 0.01ms !important; }
        }

        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 2px solid var(--keraton-gold);
            outline-offset: 2px;
        }

        .scrollable-content {
            -webkit-overflow-scrolling: touch;
            touch-action: pan-y;
        }

        html.overflow-hidden, body.overflow-hidden {
            overflow: hidden !important;
            touch-action: none !important;
            overscroll-behavior: none !important;
            height: 100% !important;
        }

        @media (max-width: 1023px) {
            .mega-mendung-bg {
                overscroll-behavior: none !important;
                touch-action: none !important;
            }
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)]"
    x-data="pelaporApp(window.__initialReports)"
    :class="{ 'overflow-hidden touch-none overscroll-none': reportModalOpen || sidebarOpen }">

    <div class="desktop-layout flex flex-row relative min-h-screen lg:min-h-0 lg:p-3 lg:gap-3 bg-[var(--batik-cream)]">

        <!-- BACKDROP MOBILE -->
        <div x-show="sidebarOpen"
            x-cloak
            x-transition.opacity
            @click="sidebarOpen = false"
            @touchmove.prevent
            @wheel.prevent
            class="fixed inset-0 bg-black/60 z-40 lg:hidden transition-opacity touch-none overscroll-none">
        </div>

        <!-- ============ SIDEBAR PELAPOR (SAMA PERSIS DENGAN REKAP & RIWAYAT) ============ -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            @touchmove.stop.prevent="if (window.innerWidth < 1024) $event.preventDefault()"
            class="fixed lg:static top-3.5 left-0 z-50 lg:z-auto w-[76vw] max-w-[275px] lg:w-[min(18rem,calc(100vw-1rem))] lg:max-w-none mega-mendung-bg flex flex-col justify-between p-4 sm:p-5 lg:p-6 text-white shrink-0 shadow-2xl transition-transform duration-300 ease-out h-[78vh] max-h-[580px] lg:h-full lg:max-h-none overflow-hidden lg:overflow-y-auto no-scrollbar touch-none lg:touch-auto overscroll-none select-none mobile-rounded-sidebar border-0 outline-none">

            <!-- BAGIAN ATAS: USER BADGE, MENU -->
            <div class="space-y-3.5 lg:space-y-4">

                <!-- User Profile Card -->
                <a href="{{ route('pelapor.profile.edit') ?? '#' }}" class="bg-gradient-to-r from-[#0b335c]/85 via-[#0c3966]/80 to-[#072544]/90 border border-blue-300/30 hover:border-blue-400/50 p-3 rounded-2xl flex items-center justify-between transition-all duration-200 group shadow-lg backdrop-blur-sm">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="relative shrink-0">
                            @if(Auth::user()->avatar ?? false)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-blue-400/60 shadow-md" alt="Avatar">
                            @else
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 via-indigo-600 to-[#0b335c] text-white font-black flex items-center justify-center text-xs shadow-md ring-1 ring-white/30 tracking-wider">
                                    {{ strtoupper(substr(Auth::user()->name ?? 'PL', 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1 leading-tight">
                            <p class="font-bold text-xs lg:text-[13px] text-white leading-snug break-words line-clamp-2" title="{{ Auth::user()->name ?? 'Pelapor' }}">
                                {{ Auth::user()->name ?? 'Pelapor' }}
                            </p>
                            <p class="text-[10.5px] text-blue-200/80 truncate capitalize mt-0.5 flex items-center gap-1">
                                <svg class="w-3 h-3 text-amber-400/80 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                <span class="truncate">{{ Auth::user()->alamat ?: (Auth::user()->kecamatan ?? 'Kabupaten Cirebon') }}</span>
                            </p>
                        </div>
                    </div>
                    <svg class="w-4 h-4 text-blue-300/80 group-hover:text-white group-hover:translate-x-0.5 transition shrink-0 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>

                <!-- Navigasi Menu Pelapor -->
                <nav class="space-y-1 pt-1" aria-label="Menu Pelapor">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-white px-2 mb-2">MENU PELAPOR</p>

                    <!-- Dashboard Pelapor (Aktif) -->
                    <a href="{{ route('pelapor.dashboard') }}" @click="sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('pelapor.dashboard') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('pelapor.dashboard') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('pelapor.dashboard') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard Pelapor</span>
                    </a>

                    <!-- Riwayat Laporan Saya -->
                    <a href="{{ route('pelapor.riwayat') }}" @click="markNotifRead(); sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('pelapor.riwayat*') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('pelapor.riwayat*') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('pelapor.riwayat*') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Riwayat Laporan Saya</span>
                    </a>

                    <!-- Rekap Laporan -->
                    <a href="{{ route('pelapor.rekap') ?? '#' }}" @click="markNotifRead(); sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('pelapor.rekap*') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('pelapor.rekap*') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('pelapor.rekap*') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Rekap Laporan</span>
                    </a>
                </nav>
            </div>

            <!-- BAGIAN BAWAH: TOMBOL PAS DI DASAR -->
            <div class="pelapor-mobile-actions relative z-10 pt-4 pb-1 lg:pt-4 lg:pb-0 space-y-2 lg:space-y-2.5 shrink-0 mt-auto">
                <!-- Tombol Keluar -->
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

        <!-- ============ KONTEN UTAMA DASHBOARD ============ -->
        <main class="flex-1 flex flex-col h-full bg-white lg:rounded-3xl shadow-sm border border-[#E3DCC8] transition-all"
              :class="(sidebarOpen || reportModalOpen) ? 'overflow-hidden pointer-events-none touch-none select-none' : 'overflow-y-auto scrollable-content'">

            <header class="bg-white border-b border-[#E3DCC8] px-3 sm:px-6 py-2.5 sm:py-3.5 sticky top-0 z-20 flex items-center justify-between shadow-2xs gap-2 sm:gap-3 shrink-0 lg:rounded-t-3xl">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3">
                    <button type="button" @click="sidebarOpen = true" aria-label="Buka menu" class="lg:hidden w-9 h-9 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] text-[var(--mm-900)] shrink-0 hover:bg-[#F3EBD8] active:scale-95 transition flex items-center justify-center shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="min-w-0">
                        <h2 class="font-display font-bold text-xs sm:text-sm text-[var(--mm-900)] truncate">Dashboard Pelapor</h2>
                        <p class="text-[9px] text-gray-500 truncate sm:hidden font-medium">{{ Auth::user()->alamat ?: (Auth::user()->kecamatan ?? 'Kabupaten Cirebon') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <!-- Notifikasi Lonceng Pelapor (Selesai, Ditolak, & Menunggu Part) -->
                    <div class="relative" x-data="{ notifOpen: false }">
                        <button type="button" @click="notifOpen = !notifOpen" 
                                class="relative w-9 h-9 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] hover:bg-[#F3EBD8] active:scale-95 transition cursor-pointer text-[var(--mm-900)] flex items-center justify-center shadow-2xs" 
                                aria-label="Notifikasi Status Laporan" title="Notifikasi Status Laporan">
                            <svg class="w-5 h-5 text-[var(--mm-800)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            
                            <!-- Counter Badge -->
                            <span x-show="notifReports.length > 0" x-cloak 
                                  class="absolute -top-1 -right-1 bg-[var(--keraton-maroon)] text-white text-[9px] font-bold min-w-[16px] h-[16px] px-0.5 rounded-full flex items-center justify-center shadow-sm animate-pulse border border-white" 
                                  x-text="notifReports.length"></span>
                        </button>
                        
                        <!-- Panel Dropdown Notifikasi Compact & Sleek -->
                        <div x-show="notifOpen" x-cloak @click.away="notifOpen = false" 
                             x-transition:enter="transition ease-out duration-150" 
                             x-transition:enter-start="opacity-0 translate-y-1 scale-95" 
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                             x-transition:leave="transition ease-in duration-100" 
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                             x-transition:leave-end="opacity-0 translate-y-1 scale-95" 
                             class="absolute right-[-15px] sm:right-0 top-full mt-2 w-[290px] max-w-[calc(100vw-2rem)] sm:w-[330px] bg-white border border-[#E3DCC8] rounded-2xl shadow-xl z-50 overflow-hidden text-left flex flex-col max-h-[75vh] sm:max-h-[460px]">
                            
                            <!-- Header Dropdown Rapi & Elegan Sesuai Referensi -->
                            <div class="px-3.5 py-2.5 border-b border-[#E3DCC8] bg-[var(--batik-cream)] flex justify-between items-center shrink-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-7 h-7 rounded-xl bg-[var(--mm-900)] text-white flex items-center justify-center shrink-0 shadow-xs">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </div>
                                    <div class="leading-tight min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-[11.5px] font-bold text-[var(--mm-900)]">Pemberitahuan</span>
                                            <span x-show="notifReports.length > 0" x-cloak 
                                                  class="bg-[var(--keraton-gold)]/25 text-[var(--mm-900)] border border-[var(--keraton-gold)]/40 text-[8.5px] font-extrabold px-1.5 py-0.2 rounded-md" 
                                                  x-text="notifReports.length"></span>
                                        </div>
                                        <p class="text-[8.5px] text-gray-500 truncate">Status perbaikan & antrean</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button type="button" x-show="notifReports.length > 0" 
                                            @click="markNotifRead(); showToast('Semua notifikasi ditandai dibaca')" 
                                            class="text-[9.5px] text-blue-700 hover:text-blue-900 font-bold hover:underline transition cursor-pointer">
                                        Tandai dibaca
                                    </button>
                                    <button type="button" @click="notifOpen = false" 
                                            class="p-0.5 text-gray-400 hover:text-gray-700 rounded transition cursor-pointer" 
                                            aria-label="Tutup">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- List Item Notifikasi -->
                            <div class="overflow-y-auto divide-y divide-[#EFE9DC] bg-white flex-1 p-1">
                                <template x-for="r in notifReports" :key="'notif-' + (r.db_id || r.id)">
                                    <div class="relative group rounded-xl border border-transparent hover:border-[#E3DCC8] hover:bg-[#FBF8F2] transition overflow-hidden">
                                        <a :href="(r.status || '').toLowerCase() === 'menunggu part' ? ('{{ route('pelapor.riwayat') }}?open_id=' + (r.db_id || r.id)) : ('{{ route('pelapor.rekap') }}?open_id=' + (r.db_id || r.id))" 
                                           @click="dismissNotif(r.db_id || r.id, r.status); notifOpen = false" 
                                           class="block p-2.5 w-full cursor-pointer text-left">
                                            <div class="flex items-start gap-2.5">
                                                <!-- Avatar Ikon Status -->
                                                <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 shadow-2xs mt-0.5"
                                                     :class="{
                                                         'bg-emerald-100 text-emerald-700 border border-emerald-300': (r.status || '').toLowerCase() === 'selesai',
                                                         'bg-rose-100 text-rose-700 border border-rose-300': (r.status || '').toLowerCase() === 'ditolak',
                                                         'bg-blue-100 text-blue-700 border border-blue-300': (r.status || '').toLowerCase() === 'menunggu part'
                                                     }">
                                                    <template x-if="(r.status || '').toLowerCase() === 'selesai'">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    </template>
                                                    <template x-if="(r.status || '').toLowerCase() === 'ditolak'">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </template>
                                                    <template x-if="(r.status || '').toLowerCase() === 'menunggu part'">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    </template>
                                                </div>

                                                <!-- Detail Konten Notifikasi -->
                                                <div class="min-w-0 flex-1 pr-5">
                                                    <div class="flex items-center justify-between gap-1">
                                                        <div class="flex items-center gap-1.5 flex-wrap min-w-0">
                                                            <span class="text-[11px] font-bold text-[var(--mm-900)] group-hover:text-blue-700 transition" x-text="r.id"></span>
                                                            <span class="text-[8px] font-extrabold px-1.5 py-0.2 rounded shrink-0 shadow-2xs"
                                                                  :class="{
                                                                      'bg-emerald-100 text-emerald-800 border border-emerald-200': (r.status || '').toLowerCase() === 'selesai',
                                                                      'bg-rose-100 text-rose-800 border border-rose-200': (r.status || '').toLowerCase() === 'ditolak',
                                                                      'bg-blue-100 text-blue-800 border border-blue-200': (r.status || '').toLowerCase() === 'menunggu part'
                                                                  }"
                                                                  x-text="r.status"></span>
                                                        </div>
                                                    </div>

                                                    <p class="text-[10px] font-semibold text-gray-800 mt-0.5 truncate" x-text="r.perangkat || 'Perangkat'"></p>

                                                    <template x-if="(r.status || '').toLowerCase() === 'selesai'">
                                                        <p class="text-[9px] text-emerald-800 leading-snug mt-1 bg-emerald-50/80 p-1.5 rounded-lg border border-emerald-200">
                                                            ✓ Perbaikan selesai & siap diambil di kantor.
                                                        </p>
                                                    </template>
                                                    <template x-if="(r.status || '').toLowerCase() === 'ditolak'">
                                                        <p class="text-[9px] text-rose-800 leading-snug mt-1 bg-rose-50/80 p-1.5 rounded-lg border border-rose-200" 
                                                           x-text="'✕ Ditolak' + (r.catatan ? ': ' + r.catatan : '.')"></p>
                                                    </template>
                                                    <template x-if="(r.status || '').toLowerCase() === 'menunggu part'">
                                                        <p class="text-[9px] text-blue-800 leading-snug mt-1 bg-blue-50/80 p-1.5 rounded-lg border border-blue-200" 
                                                           x-text="'⏳ Menunggu suku cadang' + (r.catatan ? ': ' + r.catatan : '.')"></p>
                                                    </template>

                                                    <!-- Tautan Aksi & Waktu -->
                                                    <div class="flex items-center justify-between mt-1.5 pt-1 border-t border-[#F0EBE0]/80">
                                                        <span class="text-[8.5px] text-gray-400" x-text="formatTanggal(r.tanggal || r.created_at)"></span>
                                                        <span class="text-[9px] font-bold text-blue-700 group-hover:text-blue-900 group-hover:underline flex items-center gap-0.5">
                                                            <span>Buka Laporan</span>
                                                            <svg class="w-2.5 h-2.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                        <!-- Tombol Dismiss Cepat (✕) -->
                                        <button type="button" 
                                                @click.stop.prevent="dismissNotif(r.db_id || r.id, r.status)" 
                                                class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 hover:bg-gray-100 p-1 rounded-lg transition shrink-0 cursor-pointer" 
                                                title="Sembunyikan notifikasi">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>

                                <!-- State Kosong (Empty State) Sesuai Referensi -->
                                <div x-show="notifReports.length === 0" class="px-4 py-7 text-center bg-white flex flex-col items-center justify-center">
                                    <div class="w-11 h-11 rounded-2xl bg-[var(--batik-cream)] border border-[#E8DFC8] flex items-center justify-center text-[var(--mm-800)] mb-2.5 shadow-2xs">
                                        <svg class="w-5 h-5 text-[var(--mm-800)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs font-bold text-[var(--mm-900)]">Tidak Ada Pemberitahuan Baru</p>
                                    <p class="text-[9.5px] text-gray-500 mt-1 max-w-[210px] leading-relaxed">Pembaruan status laporan kerusakan akan otomatis muncul di sini.</p>
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

            <div class="p-3 sm:p-6 lg:p-8 space-y-3.5 sm:space-y-5 flex-1 pb-8">

                <!-- BANNER SELAMAT DATANG -->
                <div class="mega-mendung-bg p-3.5 sm:p-6 rounded-2xl shadow-md text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 relative overflow-hidden">
                    <div class="space-y-1 relative z-10 min-w-0">
                        <div class="inline-flex items-center gap-1.5 bg-white/10 border border-white/20 px-2.5 py-0.5 rounded-full text-[9px] sm:text-[11px] text-[var(--keraton-gold-soft)] font-semibold backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-[var(--keraton-gold)] shrink-0"></span>
                            <span>Portal Resmi SIPERKOM</span>
                        </div>
                        <h2 class="font-display text-lg sm:text-2xl lg:text-3xl font-extrabold tracking-tight break-words">
                            Selamat Datang, {{ Auth::user()->name ?? 'Pelapor' }}!
                        </h2>
                        <p class="text-[10.5px] sm:text-sm text-[var(--mm-200)] max-w-xl leading-relaxed">
                            Layanan terpadu perbaikan fasilitas komputer & printer di lingkungan Pemerintah Kabupaten Cirebon.
                        </p>
                    </div>

                    <div class="relative z-10 shrink-0 w-full md:w-auto flex flex-col items-stretch md:items-end gap-1.5 pt-1 md:pt-0">
                        <button type="button" 
                                @click="openReportModal()" 
                                :class="activeReportsCount >= 2 
                                    ? 'bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 shadow-xs' 
                                    : 'bg-[var(--keraton-gold)] hover:bg-[var(--keraton-gold-soft)] text-[var(--mm-900)] shadow-md'"
                                class="w-full md:w-auto justify-center px-4 py-2.5 sm:px-5 sm:py-3 rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer active:scale-98">
                            <template x-if="activeReportsCount < 2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    Buat Laporan Kerusakan
                                </span>
                            </template>
                            <template x-if="activeReportsCount >= 2">
                                <span class="flex items-center gap-2 text-amber-900">
                                    <svg class="w-4 h-4 text-amber-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    Antrean Aktif Penuh (2/2)
                                </span>
                            </template>
                        </button>
                        <span x-show="activeReportsCount >= 2" x-cloak class="text-[10px] text-amber-200 font-semibold flex items-center gap-1 justify-center md:justify-end">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse shrink-0"></span>
                            <span>Maks. 2 laporan aktif (anti-spam)</span>
                        </span>
                    </div>
                </div>

                <!-- NOTIFIKASI STATUS SELESAI, DITOLAK & MENUNGGU PART UNTUK PELAPOR -->
                @php
                    $listSelesai = collect($laporans ?? [])->where('status', 'Selesai');
                    $listDitolak = collect($laporans ?? [])->where('status', 'Ditolak');
                    $listMenungguPart = collect($laporans ?? [])->where('status', 'Menunggu Part');
                @endphp

                @if($listMenungguPart->count() > 0)
                    @foreach($listMenungguPart->take(2) as $lmp)
                        <div x-data="{ dismissed: false }" x-show="!dismissed && !seenRekapIds.includes('{{ $lmp->id }}_menunggu part')" x-transition class="bg-blue-50/90 border border-blue-200 p-3 sm:p-4 rounded-2xl flex items-start sm:items-center justify-between gap-3 shadow-xs">
                            <a href="{{ route('pelapor.riwayat') }}" @click="dismissNotif('{{ $lmp->id }}', 'Menunggu Part')" class="flex items-start sm:items-center gap-3 min-w-0 flex-1 hover:opacity-95 transition group">
                                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5 sm:mt-0">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2 py-0.5 rounded-md bg-blue-200 text-blue-900 text-[9px] font-extrabold uppercase tracking-wide">Menunggu Part</span>
                                        <p class="text-xs sm:text-sm font-bold text-blue-950 truncate">{{ $lmp->kode_laporan ?? '#REP-'.$lmp->id }} — {{ $lmp->perangkat }}</p>
                                    </div>
                                    <p class="text-[11px] text-blue-800 mt-1 leading-snug">⏳ Menunggu suku cadang/part teknisi{{ $lmp->keterangan_rekap || $lmp->catatan ? ': ' . ($lmp->keterangan_rekap ?? $lmp->catatan) : '.' }} <span class="font-semibold underline group-hover:text-blue-950">Buka Riwayat →</span></p>
                                </div>
                            </a>
                            <button type="button" @click="dismissed = true; dismissNotif('{{ $lmp->id }}', 'Menunggu Part')" class="w-7 h-7 rounded-xl hover:bg-blue-200/60 text-blue-700 hover:text-blue-950 flex items-center justify-center text-xs font-bold transition shrink-0 cursor-pointer" title="Tutup pemberitahuan">✕</button>
                        </div>
                    @endforeach
                @endif

                @if($listSelesai->count() > 0)
                    @foreach($listSelesai->take(2) as $ls)
                        <div x-data="{ dismissed: false }" x-show="!dismissed && !seenRekapIds.includes('{{ $ls->id }}_selesai')" x-transition class="bg-emerald-50/90 border border-emerald-200 p-3 sm:p-4 rounded-2xl flex items-start sm:items-center justify-between gap-3 shadow-xs">
                            <a href="{{ route('pelapor.rekap') }}" @click="dismissNotif('{{ $ls->id }}', 'Selesai')" class="flex items-start sm:items-center gap-3 min-w-0 flex-1 hover:opacity-95 transition group">
                                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5 sm:mt-0">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-200 text-emerald-900 text-[9px] font-extrabold uppercase tracking-wide">Selesai</span>
                                        <p class="text-xs sm:text-sm font-bold text-emerald-950 truncate">{{ $ls->kode_laporan ?? '#REP-'.$ls->id }} — {{ $ls->perangkat }}</p>
                                    </div>
                                    <p class="text-[11px] text-emerald-800 mt-1 leading-snug">✓ Perangkat telah selesai diperbaiki & siap diambil. <span class="font-semibold underline group-hover:text-emerald-950">Lihat di Rekap Laporan →</span></p>
                                </div>
                            </a>
                            <button type="button" @click="dismissed = true; dismissNotif('{{ $ls->id }}', 'Selesai')" class="w-7 h-7 rounded-xl hover:bg-emerald-200/60 text-emerald-700 hover:text-emerald-950 flex items-center justify-center text-xs font-bold transition shrink-0 cursor-pointer" title="Tutup pemberitahuan">✕</button>
                        </div>
                    @endforeach
                @endif

                @if($listDitolak->count() > 0)
                    @foreach($listDitolak->take(2) as $ld)
                        <div x-data="{ dismissed: false }" x-show="!dismissed && !seenRekapIds.includes('{{ $ld->id }}_ditolak')" x-transition class="bg-rose-50/90 border border-rose-200 p-3 sm:p-4 rounded-2xl flex items-start sm:items-center justify-between gap-3 shadow-xs">
                            <a href="{{ route('pelapor.rekap') }}" @click="dismissNotif('{{ $ld->id }}', 'Ditolak')" class="flex items-start sm:items-center gap-3 min-w-0 flex-1 hover:opacity-95 transition group">
                                <div class="w-9 h-9 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5 sm:mt-0">
                                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2 py-0.5 rounded-md bg-rose-200 text-rose-900 text-[9px] font-extrabold uppercase tracking-wide">Ditolak</span>
                                        <p class="text-xs sm:text-sm font-bold text-rose-950 truncate">{{ $ld->kode_laporan ?? '#REP-'.$ld->id }} — {{ $ld->perangkat }}</p>
                                    </div>
                                    <p class="text-[11px] text-rose-800 mt-1 leading-snug">Alasan: {{ $ld->keterangan_rekap ?? $ld->catatan ?? 'Laporan belum memenuhi syarat atau perangkat tidak dapat diperbaiki' }}. <span class="font-semibold underline group-hover:text-rose-950">Lihat Rekap →</span></p>
                                </div>
                            </a>
                            <button type="button" @click="dismissed = true; dismissNotif('{{ $ld->id }}', 'Ditolak')" class="w-7 h-7 rounded-xl hover:bg-rose-200/60 text-rose-700 hover:text-rose-950 flex items-center justify-center text-xs font-bold transition shrink-0 cursor-pointer" title="Tutup pemberitahuan">✕</button>
                        </div>
                    @endforeach
                @endif

                <div class="space-y-4 sm:space-y-5">

                    <!-- PANDUAN ALUR & FORMAT SURAT PERMOHONAN PDF -->
                    <div class="bg-white rounded-2xl shadow-xs border border-[#E3DCC8] p-3 sm:p-5 space-y-3">
                        <div class="flex items-center justify-between pb-2.5 border-b border-[#E3DCC8]">
                            <div>
                                <h3 class="font-display font-bold text-xs sm:text-sm text-[var(--mm-900)]">Alur Layanan & Berkas Pengajuan</h3>
                                <p class="text-[10px] sm:text-[11px] text-gray-500">Prosedur penanganan perangkat & berkas wajib kantor.</p>
                            </div>
                            <span class="inline-flex items-center gap-1 text-[9.5px] sm:text-[10.5px] font-semibold text-[var(--keraton-gold)] bg-[var(--batik-cream)] border border-[#E3DCC8] px-2 py-0.5 rounded-lg shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full bg-[var(--keraton-gold)]"></span>
                                Ketentuan Berkas
                            </span>
                        </div>

                        <!-- 3 KOTAK ALUR UTAMA (3 Kolom Sejajar) -->
                        <div class="grid grid-cols-3 gap-2 sm:gap-3.5 items-stretch">
                            <!-- 1. Kirim Laporan -->
                            <div @click="openReportModal()" class="bg-gradient-to-b from-[#FBF8EF] to-white hover:border-[var(--keraton-gold)] p-2.5 sm:p-3.5 rounded-xl border border-[#E3DCC8] flex flex-col justify-between shadow-2xs hover:shadow-xs transition cursor-pointer group min-h-[112px] sm:min-h-[128px]">
                                <div>
                                    <div class="flex items-center justify-between gap-1 mb-1.5">
                                        <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-[var(--mm-800)] text-white flex items-center justify-center font-black text-[11px] sm:text-xs shadow-2xs group-hover:scale-105 transition-transform">01</span>
                                        <span class="text-[8px] sm:text-[9px] font-extrabold text-blue-800 bg-blue-100/70 px-1.5 py-0.5 rounded">Form</span>
                                    </div>
                                    <h4 class="font-bold text-[10px] sm:text-xs text-[var(--mm-900)] leading-tight">Kirim Laporan</h4>
                                    <p class="text-[8.5px] sm:text-[10.5px] text-gray-600 leading-snug mt-1">
                                        Formulir online & lampirkan surat PDF.
                                    </p>
                                </div>
                                <span class="text-[8px] sm:text-[9.5px] text-blue-800 font-bold mt-2 pt-1 border-t border-[#E3DCC8]/70 flex items-center justify-between">
                                    <span>Buat Tiket</span>
                                    <span>→</span>
                                </span>
                            </div>

                            <!-- 2. Ditinjau Teknisi -->
                            <a href="{{ route('pelapor.riwayat') }}" class="bg-gradient-to-b from-[#FBF8EF] to-white hover:border-[var(--keraton-gold)] p-2.5 sm:p-3.5 rounded-xl border border-[#E3DCC8] flex flex-col justify-between shadow-2xs hover:shadow-xs transition group min-h-[112px] sm:min-h-[128px]">
                                <div>
                                    <div class="flex items-center justify-between gap-1 mb-1.5">
                                        <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-[var(--keraton-gold)] text-[var(--mm-900)] flex items-center justify-center font-black text-[11px] sm:text-xs shadow-2xs group-hover:scale-105 transition-transform">02</span>
                                        <span class="text-[8px] sm:text-[9px] font-extrabold text-amber-900 bg-amber-100/70 px-1.5 py-0.5 rounded">Proses</span>
                                    </div>
                                    <h4 class="font-bold text-[10px] sm:text-xs text-[var(--mm-900)] leading-tight">Ditinjau Teknisi</h4>
                                    <p class="text-[8.5px] sm:text-[10.5px] text-gray-600 leading-snug mt-1">
                                        Pemeriksaan & pengerjaan teknisi.
                                    </p>
                                </div>
                                <span class="text-[8px] sm:text-[9.5px] text-amber-900 font-bold mt-2 pt-1 border-t border-[#E3DCC8]/70 flex items-center justify-between">
                                    <span>Cek Riwayat</span>
                                    <span>→</span>
                                </span>
                            </a>

                            <!-- 3. Perangkat Selesai -->
                            <a href="{{ route('pelapor.rekap') }}" class="bg-gradient-to-b from-[#FBF8EF] to-white hover:border-emerald-500 p-2.5 sm:p-3.5 rounded-xl border border-[#E3DCC8] flex flex-col justify-between shadow-2xs hover:shadow-xs transition group min-h-[112px] sm:min-h-[128px]">
                                <div>
                                    <div class="flex items-center justify-between gap-1 mb-1.5">
                                        <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-black text-[11px] sm:text-xs shadow-2xs group-hover:scale-105 transition-transform">03</span>
                                        <span class="text-[8px] sm:text-[9px] font-extrabold text-emerald-900 bg-emerald-100/70 px-1.5 py-0.5 rounded">Selesai</span>
                                    </div>
                                    <h4 class="font-bold text-[10px] sm:text-xs text-[var(--mm-900)] leading-tight">Perangkat Selesai</h4>
                                    <p class="text-[8.5px] sm:text-[10.5px] text-gray-600 leading-snug mt-1">
                                        Unit normal & siap diambil (BAST).
                                    </p>
                                </div>
                                <span class="text-[8px] sm:text-[9.5px] text-emerald-800 font-bold mt-2 pt-1 border-t border-[#E3DCC8]/70 flex items-center justify-between">
                                    <span>Buka Rekap</span>
                                    <span>→</span>
                                </span>
                            </a>
                        </div>

                        <!-- KOTAK PEMBERITAHUAN WAJIB UPLOAD PDF -->
                        <div class="bg-gradient-to-r from-rose-50/75 via-[#FDFBF7] to-amber-50/40 p-3 sm:p-3.5 rounded-xl border border-rose-200/80 flex items-start sm:items-center gap-2.5 shadow-2xs">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 shadow-2xs">
                                <svg class="w-4 h-4 sm:w-4.5 sm:h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-6 4h4"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <h4 class="font-bold text-[11px] sm:text-xs text-[var(--mm-900)]">Wajib Upload PDF</h4>
                                    <span class="text-[8px] sm:text-[9px] font-extrabold text-rose-700 bg-rose-100/90 px-1.5 py-0.5 rounded">Surat Dinas</span>
                                </div>
                                <p class="text-[9.5px] sm:text-[10.5px] text-gray-600 leading-snug mt-0.5">
                                    Silakan upload laporan perbaikan ke admin dengan melampirkan berkas surat dinas format PDF agar kendala perangkat kantor segera ditangani teknisi.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- KOTAK KONSULTASI WHATSAPP -->
                    <div class="flex items-center justify-between gap-2.5 mega-mendung-bg text-white p-3 sm:p-4 rounded-xl relative overflow-hidden shadow-xs">
                        <div class="relative z-10 space-y-0.5 min-w-0">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
                                <p class="text-xs sm:text-sm font-bold truncate">Butuh Bantuan Mendesak?</p>
                            </div>
                            <p class="text-[10px] sm:text-[11px] text-[var(--mm-200)] leading-tight truncate sm:whitespace-normal">Hubungi teknisi SIPERKOM via WhatsApp jika ada kendala darurat.</p>
                        </div>
                        <div class="relative z-10 shrink-0">
                            <a href="https://wa.me/6285321812643?text=Halo%20Admin%20Teknisi%20SIPERKOM,%20saya%20ingin%20konsultasi%20terkait%20perbaikan." 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="flex items-center gap-1.5 px-3 py-1.5 sm:px-3.5 sm:py-2 bg-[#008f53] hover:bg-[#007b47] text-white rounded-lg text-[11px] sm:text-xs font-bold transition shadow-xs cursor-pointer active:scale-98">
                                <svg class="w-3.5 h-3.5 shrink-0 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.487 1.333 5.006l-1.417 5.176 5.297-1.389c1.474.802 3.136 1.224 4.773 1.225h.004c5.505 0 9.988-4.478 9.989-9.985 0-2.668-1.037-5.176-2.922-7.062a9.923 9.923 0 0 0-7.067-2.955zm5.834 14.161c-.246.692-1.424 1.326-1.964 1.383-.496.052-1.127.078-1.821-.144-.429-.138-.981-.322-1.688-.629-2.978-1.291-4.919-4.321-5.069-4.52-.149-.199-1.218-1.621-1.218-3.092 0-1.471.769-2.195 1.042-2.493.273-.298.595-.372.794-.372.198 0 .397.001.571.01.184.009.432-.07.675.515.248.595.843 2.059.917 2.208.075.149.124.322.025.521-.099.198-.149.322-.298.496-.149.174-.313.388-.447.521-.149.149-.304.312-.131.608.174.298.772 1.274 1.657 2.062 1.139 1.014 2.1 1.327 2.398 1.476.298.149.471.124.645-.075.174-.198.745-.868.943-1.166.198-.298.397-.248.67-.149.273.099 1.737.819 2.035.968.298.149.496.223.57.347.074.124.074.719-.172 1.411z"/>
                                </svg>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </main>

    </div>

    <!-- MODAL FORMULIR LAPORAN KERUSAKAN -->
    <div x-show="reportModalOpen"
        x-cloak
        x-transition.opacity
        @keydown.escape.window="closeReportModal()"
        @click.self="closeReportModal()"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/60 p-0 sm:p-4 backdrop-blur-xs"
        role="dialog"
        aria-modal="true">
        <div class="bg-white rounded-t-[28px] sm:rounded-2xl max-w-lg w-full p-5 sm:p-6 space-y-4 shadow-2xl border border-slate-100 sm:border-[#E3DCC8] max-h-[92vh] sm:max-h-[88vh] overflow-y-auto scrollable-content"
            @click.stop>
            
            <!-- Handle Bar Mobile -->
            <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-1 sm:hidden"></div>

            <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[9px] font-bold bg-[var(--mm-800)]/10 text-[var(--mm-800)] uppercase tracking-wider mb-1">Tiket Baru</span>
                    <h3 class="font-display font-extrabold text-base sm:text-lg text-[var(--mm-900)]">Formulir Laporan Kerusakan</h3>
                </div>
                <button type="button" @click="closeReportModal()" aria-label="Tutup formulir" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors text-sm font-bold">✕</button>
            </div>

            <!-- Peringatan Kuota Laporan Aktif (Anti-Spam) -->
            <div x-show="activeReportsCount >= 2" x-cloak class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-start gap-2">
                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <strong class="font-bold text-amber-900">Batas Laporan Aktif Tercapai (Maks. 2)</strong>
                    <p class="text-[11px] text-amber-800/90 mt-0.5 leading-relaxed">Anda telah memiliki 2 laporan yang sedang diproses teknisi. Laporan ke-3 tidak dapat dibuat guna mencegah penumpukan (spam). Silakan tunggu hingga laporan saat ini selesai.</p>
                </div>
            </div>

            <form @submit.prevent="addReport()" class="space-y-4 text-xs">
                <!-- Dropdown Kustom Jenis Perangkat (Modern & Ramah Sentuhan Mobile) -->
                <div x-data="{ openJenis: false }" class="space-y-1.5">
                    <label class="block font-bold text-slate-700 text-xs">Jenis Perangkat <span class="text-rose-500">*</span></label>
                    <input type="hidden" name="jenis" :value="newReport.jenis" required>
                    
                    <div class="relative">
                        <button type="button" @click="openJenis = !openJenis"
                                class="w-full bg-slate-50 hover:bg-slate-100/70 border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-3 text-xs font-medium text-left flex items-center justify-between gap-2 focus:outline-none focus:border-[var(--mm-600)] focus:ring-2 focus:ring-[var(--mm-600)]/15 transition-all shadow-2xs"
                                :class="newReport.jenis ? 'text-slate-800 bg-white' : 'text-slate-400'">
                            <span class="truncate">
                                <template x-if="newReport.jenis === 'PC'">
                                    <strong class="text-slate-800 font-bold">Komputer Unit / PC</strong>
                                </template>
                                <template x-if="newReport.jenis === 'Laptop'">
                                    <strong class="text-slate-800 font-bold">Laptop</strong>
                                </template>
                                <template x-if="newReport.jenis === 'Printer'">
                                    <strong class="text-slate-800 font-bold">Printer</strong>
                                </template>
                                <template x-if="newReport.jenis === 'Scanner'">
                                    <strong class="text-slate-800 font-bold">Scanner</strong>
                                </template>
                                <template x-if="newReport.jenis === 'Jaringan'">
                                    <strong class="text-slate-800 font-bold">Jaringan / Router</strong>
                                </template>
                                <template x-if="newReport.jenis === 'Lainnya'">
                                    <strong class="text-slate-800 font-bold">Lainnya</strong>
                                </template>
                                <template x-if="!newReport.jenis">
                                    <span>Pilih Jenis Perangkat...</span>
                                </template>
                            </span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0" :class="openJenis && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Menu Pilihan Dropdown Elegan (Bersih & Rapi Tanpa Icon) -->
                        <div x-show="openJenis"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-2 scale-98"
                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                             x-transition:leave-end="opacity-0 -translate-y-2 scale-98"
                             @click.outside="openJenis = false"
                             class="absolute left-0 right-0 mt-1.5 bg-white rounded-xl border border-slate-200 shadow-2xl z-50 overflow-hidden py-1 divide-y divide-slate-50 max-h-60 overflow-y-auto"
                             style="display: none;">
                            
                            <button type="button" @click="newReport.jenis = 'PC'; openJenis = false"
                                    class="w-full text-left px-3.5 py-2.5 sm:py-3 text-xs font-medium hover:bg-slate-50 transition-colors flex items-center justify-between"
                                    :class="newReport.jenis === 'PC' ? 'bg-sky-50/80 text-[var(--mm-900)] font-bold' : 'text-slate-700'">
                                <span>Komputer Unit / PC</span>
                                <span x-show="newReport.jenis === 'PC'" class="text-[var(--mm-600)] font-bold">✓</span>
                            </button>
                            <button type="button" @click="newReport.jenis = 'Laptop'; openJenis = false"
                                    class="w-full text-left px-3.5 py-2.5 sm:py-3 text-xs font-medium hover:bg-slate-50 transition-colors flex items-center justify-between"
                                    :class="newReport.jenis === 'Laptop' ? 'bg-sky-50/80 text-[var(--mm-900)] font-bold' : 'text-slate-700'">
                                <span>Laptop</span>
                                <span x-show="newReport.jenis === 'Laptop'" class="text-[var(--mm-600)] font-bold">✓</span>
                            </button>
                            <button type="button" @click="newReport.jenis = 'Printer'; openJenis = false"
                                    class="w-full text-left px-3.5 py-2.5 sm:py-3 text-xs font-medium hover:bg-slate-50 transition-colors flex items-center justify-between"
                                    :class="newReport.jenis === 'Printer' ? 'bg-sky-50/80 text-[var(--mm-900)] font-bold' : 'text-slate-700'">
                                <span>Printer</span>
                                <span x-show="newReport.jenis === 'Printer'" class="text-[var(--mm-600)] font-bold">✓</span>
                            </button>
                            <button type="button" @click="newReport.jenis = 'Scanner'; openJenis = false"
                                    class="w-full text-left px-3.5 py-2.5 sm:py-3 text-xs font-medium hover:bg-slate-50 transition-colors flex items-center justify-between"
                                    :class="newReport.jenis === 'Scanner' ? 'bg-sky-50/80 text-[var(--mm-900)] font-bold' : 'text-slate-700'">
                                <span>Scanner</span>
                                <span x-show="newReport.jenis === 'Scanner'" class="text-[var(--mm-600)] font-bold">✓</span>
                            </button>
                            <button type="button" @click="newReport.jenis = 'Jaringan'; openJenis = false"
                                    class="w-full text-left px-3.5 py-2.5 sm:py-3 text-xs font-medium hover:bg-slate-50 transition-colors flex items-center justify-between"
                                    :class="newReport.jenis === 'Jaringan' ? 'bg-sky-50/80 text-[var(--mm-900)] font-bold' : 'text-slate-700'">
                                <span>Jaringan / Router</span>
                                <span x-show="newReport.jenis === 'Jaringan'" class="text-[var(--mm-600)] font-bold">✓</span>
                            </button>
                            <button type="button" @click="newReport.jenis = 'Lainnya'; openJenis = false"
                                    class="w-full text-left px-3.5 py-2.5 sm:py-3 text-xs font-medium hover:bg-slate-50 transition-colors flex items-center justify-between"
                                    :class="newReport.jenis === 'Lainnya' ? 'bg-sky-50/80 text-[var(--mm-900)] font-bold' : 'text-slate-700'">
                                <span>Lainnya</span>
                                <span x-show="newReport.jenis === 'Lainnya'" class="text-[var(--mm-600)] font-bold">✓</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Nama / Merk Perangkat -->
                <div class="space-y-1.5">
                    <label for="nama-perangkat" class="block font-bold text-slate-700 text-xs">Nama / Merk Perangkat <span class="text-rose-500">*</span></label>
                    <input id="nama-perangkat" type="text" x-model="newReport.perangkat" required maxlength="100" placeholder="cth. Epson L3110 / PC Admin ASUS"
                           @keydown.enter.prevent
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-3 text-xs font-medium focus:bg-white focus:outline-none focus:border-[var(--mm-600)] focus:ring-2 focus:ring-[var(--mm-600)]/15 transition-all text-slate-800 placeholder:text-slate-400">
                </div>

                <!-- Detail Kerusakan / Kendala -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="detail-kendala" class="font-bold text-slate-700 text-xs">Detail Kerusakan / Kendala <span class="text-rose-500">*</span></label>
                        <span class="text-[10px] text-slate-400 font-medium" x-text="`${newReport.kendala.length}/300`"></span>
                    </div>
                    <textarea id="detail-kendala" x-model="newReport.kendala" required maxlength="300" rows="3" placeholder="Jelaskan kendala fasilitas fisik atau teknis yang dialami..."
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium focus:bg-white focus:outline-none focus:border-[var(--mm-600)] focus:ring-2 focus:ring-[var(--mm-600)]/15 transition-all text-slate-800 placeholder:text-slate-400 resize-none"></textarea>
                </div>

                <!-- Selektor Interaktif Tingkat Urgensi -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700 text-xs">Tingkat Urgensi</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" @click="newReport.prioritas = 'Rendah'"
                                class="py-2.5 px-2 rounded-xl border text-xs font-bold text-center transition-all flex items-center justify-center gap-1.5"
                                :class="newReport.prioritas === 'Rendah' ? 'bg-emerald-50 border-emerald-400 text-emerald-800 ring-2 ring-emerald-400/20 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Rendah</span>
                        </button>
                        <button type="button" @click="newReport.prioritas = 'Sedang'"
                                class="py-2.5 px-2 rounded-xl border text-xs font-bold text-center transition-all flex items-center justify-center gap-1.5"
                                :class="newReport.prioritas === 'Sedang' ? 'bg-amber-50 border-amber-400 text-amber-800 ring-2 ring-amber-400/20 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>Sedang</span>
                        </button>
                        <button type="button" @click="newReport.prioritas = 'Tinggi'"
                                class="py-2.5 px-2 rounded-xl border text-xs font-bold text-center transition-all flex items-center justify-center gap-1.5"
                                :class="newReport.prioritas === 'Tinggi' ? 'bg-rose-50 border-rose-400 text-rose-800 ring-2 ring-rose-400/20 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span>Tinggi</span>
                        </button>
                    </div>
                </div>

                <!-- Unggah Foto Fisik Unit (Opsional) -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700 text-xs">Unggah Foto Unit Barang <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input id="upload-foto" type="file" accept="image/*" x-ref="fotoInput" @change="handleFileUpload($event, 'foto')" class="hidden">
                    
                    <div x-show="!newReport.foto">
                        <button type="button" @click="$refs.fotoInput.click()"
                                class="w-full py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-[var(--mm-600)] hover:bg-slate-50/80 active:scale-[0.99] transition-all flex items-center justify-center gap-2 text-slate-600 font-semibold text-xs">
                            <svg class="w-4 h-4 text-[var(--mm-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Pilih Foto dari Galeri / Kamera</span>
                        </button>
                        <p class="text-[10px] text-slate-400 mt-1">Format: JPG, JPEG, PNG (Min. 20 KB, Maks. 5 MB)</p>
                    </div>

                    <div x-show="newReport.foto" x-cloak class="relative mt-2">
                        <img :src="newReport.foto" class="w-full max-h-36 object-cover rounded-xl border border-slate-200 shadow-sm" alt="Pratinjau foto kerusakan">
                        <button type="button" @click="clearFile('foto')" aria-label="Hapus foto"
                                class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-black/70 text-white text-[11px] font-bold flex items-center gap-1 hover:bg-black/90 shadow">
                            <span>✕ Hapus</span>
                        </button>
                    </div>
                </div>

                <!-- Unggah Dokumen Pendukung PDF (Wajib) -->
                <div class="space-y-1.5">
                    <label class="block font-bold text-slate-700 text-xs">Unggah Dokumen Pendukung <span class="text-rose-500 font-bold">* (PDF Wajib)</span></label>
                    <input id="upload-dokumen" type="file" accept="application/pdf" x-ref="dokumenInput" @change="handleFileUpload($event, 'dokumen')" class="hidden">
                    
                    <div x-show="!newReport.dokumen">
                        <button type="button" @click="$refs.dokumenInput.click()"
                                class="w-full py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-[var(--mm-600)] hover:bg-slate-50/80 active:scale-[0.99] transition-all flex items-center justify-center gap-2 text-slate-600 font-semibold text-xs">
                            <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Pilih Dokumen PDF Surat / Berita Acara</span>
                        </button>
                        <p class="text-[10px] text-slate-400 mt-1">Format: PDF (Min. 10 KB, Maks. 10 MB). Contoh: Surat Pengantar / Berita Acara</p>
                    </div>

                    <div x-show="newReport.dokumen" x-cloak class="mt-1 flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 rounded-xl px-3.5 py-2.5">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="truncate flex-1">
                            <span class="text-xs font-bold text-emerald-900 block truncate" x-text="newReport.dokumenNama"></span>
                            <span class="text-[10px] text-emerald-600 font-medium">✓ Berkas PDF siap dikirim</span>
                        </div>
                        <button type="button" @click="clearFile('dokumen')" aria-label="Hapus dokumen" class="p-1 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors">✕</button>
                    </div>
                </div>

                <!-- Tombol Batal & Kirim -->
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100">
                    <button type="button" @click="closeReportModal()" :disabled="submitting"
                            class="btn-touch px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-xs transition disabled:opacity-50">
                        Batal
                    </button>
                    <button type="submit" :disabled="submitting || activeReportsCount >= 2"
                            :class="activeReportsCount >= 2 ? 'opacity-50 cursor-not-allowed bg-slate-300 text-slate-500' : 'bg-gradient-to-r from-[var(--mm-900)] to-[var(--mm-800)] hover:from-[var(--mm-800)] hover:to-[var(--mm-900)] text-white'"
                            class="btn-touch px-5 py-2.5 rounded-xl font-bold text-xs transition shadow-lg shadow-[var(--mm-900)]/20 active:scale-[0.98] flex items-center gap-2 border border-[var(--keraton-gold)]/40">
                        <svg x-show="submitting" x-cloak class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span x-text="submitting ? 'Mengirim...' : (activeReportsCount >= 2 ? 'Antrean Penuh' : 'Kirim Laporan')"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TOAST NOTIFIKASI -->
    <div x-show="toast.show" x-cloak x-transition
        class="fixed bottom-5 right-5 z-[60] text-white px-4 py-3 rounded-xl shadow-2xl text-xs font-semibold flex items-center gap-2.5 border border-white/10 max-w-sm"
        :class="toast.type === 'error' ? 'bg-[var(--keraton-maroon)]' : (toast.type === 'info' ? 'bg-blue-800' : 'bg-[var(--mm-900)]')">
        <template x-if="toast.type === 'error'">
            <svg class="w-4 h-4 text-rose-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
        </template>
        <template x-if="toast.type !== 'error'">
            <svg class="w-4 h-4 text-emerald-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </template>
        <span x-text="toast.message" class="leading-snug"></span>
    </div>

    <script>
        window.__initialReports = {!! json_encode($defaultReports instanceof \Illuminate\Support\Collection ? $defaultReports->values()->all() : array_values($defaultReports)) !!};
        window.__activeReportsCount = {{ $activeReportsCount ?? 0 }};
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function emptyReportForm() {
            return { jenis: '', perangkat: '', kendala: '', prioritas: 'Sedang', foto: null, dokumen: null, dokumenNama: null };
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('pelaporApp', (initialReports) => ({
                sidebarOpen: false,
                reports: initialReports || [],
                activeReportsCount: window.__activeReportsCount || 0,
                seenRekapIds: JSON.parse(localStorage.getItem('siperkom_seen_rekap_ids') || '[]'),
                toast: { show: false, message: '', type: 'success' },
                reportModalOpen: false,
                submitting: false,
                newReport: emptyReportForm(),
                get notifReports() {
                    return this.reports.filter(r => {
                        const st = (r.status || '').trim().toLowerCase();
                        const isNotif = (st === 'selesai' || st === 'ditolak' || st === 'menunggu part');
                        if (!isNotif) return false;
                        const repId = String(r.db_id || r.id);
                        const key = repId + '_' + st;
                        return !this.seenRekapIds.includes(key);
                    });
                },

                markNotifRead() {
                    let seen = [...this.seenRekapIds];
                    this.reports
                        .filter(r => {
                            const st = (r.status || '').trim().toLowerCase();
                            return st === 'selesai' || st === 'ditolak' || st === 'menunggu part';
                        })
                        .forEach(r => {
                            const repId = String(r.db_id || r.id);
                            const st = (r.status || '').trim().toLowerCase();
                            const key = repId + '_' + st;
                            if (!seen.includes(key)) seen.push(key);
                        });
                    this.seenRekapIds = seen;
                    localStorage.setItem('siperkom_seen_rekap_ids', JSON.stringify(seen));
                },

                dismissNotif(id, status) {
                    const repId = String(id);
                    const st = (status || '').trim().toLowerCase();
                    const key = st ? (repId + '_' + st) : repId;
                    let seen = [...this.seenRekapIds];
                    if (!seen.includes(key)) seen.push(key);
                    this.seenRekapIds = seen;
                    localStorage.setItem('siperkom_seen_rekap_ids', JSON.stringify(this.seenRekapIds));
                },

                formatTanggal(tgl) {
                    if (!tgl) return '-';
                    const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                    const d = new Date(tgl);
                    if (isNaN(d)) return tgl;
                    return d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
                },

                init() {
                    this.$watch('sidebarOpen', (value) => {
                        if (value) {
                            document.documentElement.classList.add('overflow-hidden', 'touch-none', 'overscroll-none');
                            document.body.classList.add('overflow-hidden', 'touch-none', 'overscroll-none');
                        } else if (!this.reportModalOpen) {
                            document.documentElement.classList.remove('overflow-hidden', 'touch-none', 'overscroll-none');
                            document.body.classList.remove('overflow-hidden', 'touch-none', 'overscroll-none');
                        }
                    });
                    this.$watch('reportModalOpen', (value) => {
                        if (value) {
                            document.documentElement.classList.add('overflow-hidden', 'touch-none', 'overscroll-none');
                            document.body.classList.add('overflow-hidden', 'touch-none', 'overscroll-none');
                        } else if (!this.sidebarOpen) {
                            document.documentElement.classList.remove('overflow-hidden', 'touch-none', 'overscroll-none');
                            document.body.classList.remove('overflow-hidden', 'touch-none', 'overscroll-none');
                        }
                    });

                    // Cek jika diarahkan setelah ganti nama untuk langsung buka modal buat laporan
                    const urlParams = new URLSearchParams(window.location.search);
                    if (urlParams.get('open_modal') === '1' || urlParams.get('create_report') === '1') {
                        this.$nextTick(() => {
                            this.openReportModal();
                        });
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }

                    // Real-time Polling Live Sync tanpa refresh setiap 2.5 detik
                    setInterval(() => this.syncLiveData(), 2500);
                },

                async syncLiveData() {
                    try {
                        const response = await fetch('{{ route("pelapor.live-sync") }}', {
                            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            if (data.reports) {
                                data.reports.forEach(newR => {
                                    const oldR = this.reports.find(r => (r.db_id || r.id) == (newR.db_id || newR.id));
                                    if (oldR && oldR.status !== newR.status) {
                                        if (newR.status === 'Selesai') {
                                            this.showToast('Laporan ' + newR.id + ' telah SELESAI diperbaiki!', 'success');
                                        } else if (newR.status === 'Ditolak') {
                                            this.showToast('Laporan ' + newR.id + ' DITOLAK oleh teknisi.', 'error');
                                        } else if (newR.status === 'Menunggu Part') {
                                            this.showToast('Laporan ' + newR.id + ' berstatus MENUNGGU PART dari teknisi.', 'info');
                                        }
                                    }
                                });

                                this.reports = data.reports;
                                this.activeReportsCount = data.activeReportsCount ?? this.activeReportsCount;
                            }
                        }
                    } catch (e) {}
                },

                openReportModal() {
                    if (this.activeReportsCount >= 2) {
                        this.showToast('Batas maksimal 2 laporan aktif tercapai. Mohon tunggu teknisi menyelesaikan laporan Anda saat ini untuk mencegah spam.', 'error');
                        return;
                    }
                    this.newReport = emptyReportForm();
                    this.reportModalOpen = true;
                },

                closeReportModal() {
                    if (this.submitting) return;
                    this.reportModalOpen = false;
                },

                clearFile(target) {
                    if (target === 'foto') {
                        this.newReport.foto = null;
                        if (this.$refs.fotoInput) this.$refs.fotoInput.value = '';
                    } else {
                        this.newReport.dokumen = null;
                        this.newReport.dokumenNama = null;
                        if (this.$refs.dokumenInput) this.$refs.dokumenInput.value = '';
                    }
                },

                handleFileUpload(event, target) {
                    const file = event.target.files[0];
                    if (!file) return;

                    if (target === 'foto') {
                        if (!file.type.startsWith('image/')) {
                            this.showToast('File harus berupa gambar (JPG, JPEG, PNG).', 'error');
                            event.target.value = '';
                            return;
                        }
                        if (file.size < 20 * 1024) {
                            this.showToast('Ukuran foto terlalu kecil, minimal 20 KB.', 'error');
                            event.target.value = '';
                            return;
                        }
                        if (file.size > 5 * 1024 * 1024) {
                            this.showToast('Ukuran foto maksimal 5 MB.', 'error');
                            event.target.value = '';
                            return;
                        }
                    }

                    if (target === 'dokumen') {
                        if (file.type !== 'application/pdf') {
                            this.showToast('Dokumen pendukung harus berformat PDF.', 'error');
                            event.target.value = '';
                            return;
                        }
                        if (file.size < 10 * 1024) {
                            this.showToast('Ukuran dokumen PDF terlalu kecil, minimal 10 KB.', 'error');
                            event.target.value = '';
                            return;
                        }
                        if (file.size > 10 * 1024 * 1024) {
                            this.showToast('Ukuran dokumen PDF maksimal 10 MB.', 'error');
                            event.target.value = '';
                            return;
                        }
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        if (target === 'foto') {
                            this.newReport.foto = e.target.result;
                        } else if (target === 'dokumen') {
                            this.newReport.dokumen = e.target.result;
                            this.newReport.dokumenNama = file.name;
                        }
                    };
                    reader.onerror = () => this.showToast('Gagal membaca file, silakan coba lagi.', 'error');
                    reader.readAsDataURL(file);
                },

                async addReport() {
                    if (this.submitting) return;

                    if (this.activeReportsCount >= 2) {
                        this.showToast('Batas maksimal 2 laporan aktif tercapai. Laporan ke-3 tidak dapat dibuat guna mencegah spam antrean.', 'error');
                        return;
                    }

                    if (!this.newReport.jenis || !this.newReport.perangkat.trim() || !this.newReport.kendala.trim()) {
                        this.showToast('Mohon lengkapi seluruh data laporan terlebih dahulu.', 'error');
                        return;
                    }

                    const dokumenInput = this.$refs.dokumenInput;
                    if (!dokumenInput || !dokumenInput.files[0]) {
                        this.showToast('Dokumen pendukung PDF wajib diunggah.', 'error');
                        return;
                    }

                    this.submitting = true;

                    const formData = new FormData();
                    formData.append('jenis', this.newReport.jenis);
                    formData.append('perangkat', this.newReport.perangkat.trim());
                    formData.append('kendala', this.newReport.kendala.trim());
                    formData.append('prioritas', this.newReport.prioritas);
                    formData.append('dokumen', dokumenInput.files[0]);

                    const fotoInput = this.$refs.fotoInput;
                    if (fotoInput && fotoInput.files[0]) {
                        formData.append('foto', fotoInput.files[0]);
                    }

                    formData.append('_token', '{{ csrf_token() }}');

                    try {
                        const response = await fetch('{{ route("pelapor.laporan.store") }}', {
                            method: 'POST',
                            headers: { 
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest' 
                            },
                            body: formData
                        });

                        const result = await response.json().catch(() => null);

                        if (response.status === 419) {
                            this.showToast('Sesi kedaluwarsa. Memuat ulang halaman...', 'error');
                            setTimeout(() => window.location.reload(), 1500);
                            return;
                        }

                        if (!response.ok) {
                            const errMsg = (result && (result.message || (result.errors && Object.values(result.errors).flat().join(' ')))) 
                                ? (result.message || Object.values(result.errors).flat().join(' '))
                                : ('Terjadi kesalahan pada server (Status: ' + response.status + ')');
                            throw new Error(errMsg);
                        }

                        if (result && result.success) {
                            this.showToast(result.message || 'Laporan berhasil dikirim ke teknisi.', 'success');
                            this.reportModalOpen = false;
                            if (result.laporan) {
                                this.reports.unshift(result.laporan);
                                this.activeReportsCount++;
                            }
                            this.newReport = emptyReportForm();
                            if (this.$refs.fotoInput) this.$refs.fotoInput.value = '';
                            if (this.$refs.dokumenInput) this.$refs.dokumenInput.value = '';
                            this.syncLiveData();
                        } else {
                            this.showToast((result && result.message) || 'Gagal mengirim laporan.', 'error');
                        }
                    } catch (error) {
                        this.showToast(error.message, 'error');
                        console.error('Error detail:', error);
                    } finally {
                        this.submitting = false;
                    }
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message: message, type: type };
                    setTimeout(() => { this.toast.show = false; }, 5000);
                }
            }));
        });
    </script>
</body>
</html>