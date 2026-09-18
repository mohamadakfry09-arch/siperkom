@php
    $sourceCollection = isset($allReports) ? $allReports : ($laporans instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $laporans->getCollection() : collect($laporans ?? []));
    $reportsData = $sourceCollection->map(function ($item) {
        return [
            'id'             => $item->kode_laporan ?? '#CRB-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
            'db_id'          => $item->id,
            'perangkat'      => $item->perangkat ?? '-',
            'jenis_perangkat'=> $item->jenis_perangkat ?? $item->kategori ?? null,
            'kendala'        => $item->kendala ?? '-',
            'catatan'        => $item->keterangan_rekap ?? $item->catatan ?? $item->keterangan ?? null,
            'keterangan_rekap' => $item->keterangan_rekap ?? $item->catatan ?? $item->keterangan ?? '-',
            'created_at'     => $item->created_at ? $item->created_at->format('Y-m-d') : null,
            'tanggal'        => $item->created_at ? $item->created_at->format('Y-m-d') : null,
            'tanggal_keluar' => $item->tanggal_keluar ?? null,
            'teknisi'        => $item->teknisi ?? '-',
            'status'         => $item->status ?? 'Dalam Perbaikan',
            'prioritas'      => $item->prioritas ?? 'Sedang',
            'foto'           => $item->foto ? (filter_var($item->foto, FILTER_VALIDATE_URL) ? $item->foto : asset('storage/' . $item->foto)) : null,
            'dokumen'        => $item->dokumen  ? basename($item->dokumen) : null,
            'dokumen_url'    => $item->dokumen ? (filter_var($item->dokumen, FILTER_VALIDATE_URL) ? $item->dokumen : asset('storage/' . $item->dokumen)) : null,
            'alamat'         => $item->alamat ?? optional($item->user)->alamat ?? $item->kecamatan ?? optional($item->user)->kecamatan ?? Auth::user()->alamat ?? Auth::user()->kecamatan ?? '-',
            'kecamatan'      => $item->alamat ?? $item->kecamatan ?? optional($item->user)->alamat ?? optional($item->user)->kecamatan ?? Auth::user()->alamat ?? Auth::user()->kecamatan ?? '-',
        ];
    })->values()->toArray();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Riwayat Laporan Saya | SIPERKOM Kabupaten Cirebon</title>

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

        .table-responsive-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            touch-action: pan-x;
            cursor: grab;
            user-select: none;
        }

        .table-responsive-wrapper:active {
            cursor: grabbing;
        }

        .table-responsive-wrapper::-webkit-scrollbar {
            height: 8px !important;
            display: block !important;
        }
        .table-responsive-wrapper::-webkit-scrollbar-track {
            background: #EFE9D8 !important;
            border-radius: 0 0 10px 10px;
        }
        .table-responsive-wrapper::-webkit-scrollbar-thumb {
            background: var(--mm-600) !important;
            border-radius: 10px;
        }

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
        }
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)]"
    x-data="riwayatApp(window.__initialReports)"
    :class="{ 'overflow-hidden touch-none overscroll-none': detailModalOpen || confirmCancelOpen || mediaModalOpen || sidebarOpen }">

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

        <!-- ============ SIDEBAR PELAPOR ============ -->
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

                    <!-- Dashboard Pelapor -->
                    <a href="{{ route('pelapor.dashboard') }}" @click="sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('pelapor.dashboard') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('pelapor.dashboard') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('pelapor.dashboard') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard Pelapor</span>
                    </a>

                    <!-- Riwayat Laporan Saya (Aktif) -->
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

        <!-- ============ KONTEN UTAMA RIWAYAT ============ -->
        <main class="flex-1 flex flex-col h-full bg-white lg:rounded-3xl shadow-sm border border-[#E3DCC8] transition-all"
              :class="(sidebarOpen || detailModalOpen || confirmCancelOpen || mediaModalOpen) ? 'overflow-hidden pointer-events-none touch-none select-none' : 'overflow-y-auto scrollable-content'">

            <header class="bg-white border-b border-[#E3DCC8] px-3 sm:px-6 py-3 sm:py-4 sticky top-0 z-20 flex items-center justify-between shadow-sm gap-2 sm:gap-3 shrink-0 lg:rounded-t-3xl">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3">
                    <button type="button" @click="sidebarOpen = true" aria-label="Buka menu" class="lg:hidden w-9 h-9 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] text-[var(--mm-900)] shrink-0 hover:bg-[#F3EBD8] active:scale-95 transition flex items-center justify-center shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="font-display font-bold text-[11px] leading-tight sm:text-sm text-[var(--mm-900)]">Riwayat Pengaduan Kerusakan</h2>
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
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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

            <div class="p-4 sm:p-8 space-y-6 flex-1 pb-10">

                <!-- ================= STATE KOSONG: MUNCUL SAAT BELUM ADA LAPORAN ================= -->
                <div x-show="filteredReports.length === 0" x-cloak class="bg-white p-6 sm:p-12 rounded-3xl border border-[#E3DCC8] text-center shadow-sm space-y-3">
                    <div class="w-16 h-16 mx-auto bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h4 class="font-display font-bold text-base text-[var(--mm-900)]">Belum Ada Laporan</h4>
                    <p class="text-xs text-gray-500 max-w-md mx-auto leading-relaxed">
                        Belum ada laporan pengaduan yang dibuat. Laporan Anda akan tampil di sini setelah dikirim.
                    </p>
                </div>

                <!-- ================= DAFTAR RIWAYAT: HANYA MUNCUL JIKA ADA LAPORAN ================= -->
                <div x-show="filteredReports.length > 0" x-cloak class="space-y-4">

                    <!-- ================= 1. TAMPILAN MOBILE (KARTU ELEGAN, KOMPAK & RAPI) ================= -->
                    <div class="block lg:hidden space-y-2.5">
                        <div class="flex items-center justify-between px-1">
                            <div>
                                <h3 class="font-display text-sm font-bold text-[var(--mm-900)]">Riwayat Laporan Aktif</h3>
                                <p class="text-[10px] text-gray-500">Daftar perbaikan perangkat yang sedang diproses</p>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-[var(--batik-cream)] border border-[#E3DCC8] text-[10px] font-bold text-[var(--mm-800)]"
                                  x-text="filteredReports.length + ' Laporan'"></span>
                        </div>

                        <template x-for="(r, index) in filteredReports" :key="'card-' + r.id">
                            <div class="bg-white rounded-lg border border-[#E3DCC8] shadow-2xs hover:shadow-xs transition-all duration-200 overflow-hidden relative p-2 space-y-1">
                                
                                <!-- Strip Aksen Status di Sisi Kiri -->
                                <div class="absolute left-0 top-0 bottom-0 w-[3px] rounded-l-lg"
                                     :class="{
                                         'bg-amber-400': (r.status || '').trim() === 'Dalam Perbaikan',
                                         'bg-sky-500': (r.status || '').trim() === 'Menunggu Part',
                                         'bg-emerald-500': (r.status || '').trim() === 'Selesai',
                                         'bg-red-400': (r.status || '').trim() === 'Ditolak',
                                     }"></div>

                                <!-- Header Kartu: ID, Tgl Masuk, Status & Prioritas Sebaris -->
                                <div class="flex items-center justify-between gap-1 pl-1 pb-0.5 border-b border-slate-100/80">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="font-mono font-bold text-[9.5px] px-1.5 py-0.2 rounded bg-[var(--mm-900)] text-white shadow-2xs tracking-wide shrink-0" x-text="r.id"></span>
                                        <span class="text-[9px] text-gray-500 font-medium truncate">
                                            <span class="text-gray-400">Masuk:</span> <span class="font-semibold text-gray-700" x-text="formatTanggal(r.created_at || r.tanggal_masuk)"></span>
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-1 shrink-0">
                                        <!-- Status Badge -->
                                        <span :class="statusBadgeClass(r.status)" 
                                              class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded-full text-[8.5px] font-bold shadow-2xs whitespace-nowrap">
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
                                        <span :class="priorityBadgeClass(r.prioritas)" class="px-1.5 py-0.2 rounded text-[8px] font-bold whitespace-nowrap">
                                            <span x-text="r.prioritas || 'Sedang'"></span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Detail Unit Perangkat & Teknisi (Kompak Sebaris) -->
                                <div class="flex items-center justify-between gap-1.5 text-[11px] pl-1 leading-tight">
                                    <div class="flex items-center gap-1 min-w-0">
                                        <span class="font-bold text-[var(--mm-900)] text-[11px] truncate capitalize" x-text="r.perangkat || '-'"></span>
                                        <span class="inline-block px-1 py-0.2 rounded text-[7.5px] font-bold bg-blue-50 text-blue-700 border border-blue-100/80 shrink-0" 
                                              x-text="r.jenis_perangkat || r.kategori || classifyDevice(r.perangkat)"></span>
                                    </div>
                                    <div class="flex items-center gap-1 text-[9px] text-gray-500 shrink-0">
                                        <span class="text-gray-400">Teknisi:</span>
                                        <span class="font-bold text-gray-700 truncate max-w-[90px]" :class="r.teknisi && r.teknisi !== '-' ? 'text-[var(--mm-800)]' : 'text-gray-400 italic'" x-text="r.teknisi && r.teknisi !== '-' ? r.teknisi : 'Menunggu'"></span>
                                    </div>
                                </div>

                                <!-- Kotak Kendala & Catatan Teknisi (Super Ramping & Rapi) -->
                                <div class="bg-[var(--batik-cream)]/35 rounded-md px-1.5 py-1 border border-[#E3DCC8]/60 text-[10px] space-y-0.5 ml-1">
                                    <div class="flex items-baseline gap-1 leading-tight">
                                        <span class="text-[7.5px] uppercase font-bold text-amber-800 bg-amber-100/90 px-1 py-0.2 rounded border border-amber-200/80 shrink-0 tracking-wide">
                                            Kendala
                                        </span>
                                        <span class="text-gray-700 font-medium text-[9.5px] truncate" x-text="r.kendala || '-'"></span>
                                    </div>

                                    <div class="flex items-baseline gap-1 leading-tight pt-0.5 border-t border-[#E3DCC8]/40" x-show="r.catatan || r.keterangan || r.keterangan_rekap">
                                        <span class="text-[7.5px] uppercase font-bold text-blue-800 bg-blue-100/90 px-1 py-0.2 rounded border border-blue-200/80 shrink-0 tracking-wide">
                                            Catatan
                                        </span>
                                        <span class="text-gray-600 italic text-[9px] truncate" x-text="r.catatan || r.keterangan || r.keterangan_rekap || '-'"></span>
                                    </div>
                                </div>

                                <!-- Footer Aksi & Detail -->
                                <div class="flex items-center justify-between pt-0.5 border-t border-slate-100 gap-1.5 pl-1 leading-tight">
                                    <div class="flex items-center gap-1 shrink-0">
                                        <button type="button" x-show="r.foto" @click="openMedia(r.foto, 'image')" class="px-1.5 py-0.2 rounded text-[8.5px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 cursor-pointer">Foto</button>
                                        <a x-show="r.dokumen" :href="getPdfUrl(r.dokumen)" target="_blank" class="px-1.5 py-0.2 rounded text-[8.5px] font-semibold bg-red-50 text-red-700 border border-red-200 cursor-pointer">PDF</a>
                                        <span class="text-[8.5px] text-gray-400 italic truncate max-w-[120px]" x-show="!r.foto && !r.dokumen" x-text="r.tanggal_keluar ? ('Est: ' + formatTanggal(r.tanggal_keluar)) : 'Dalam proses'"></span>
                                    </div>

                                    <button type="button" 
                                            @click="openDetail(r)" 
                                            class="px-2 py-0.5 bg-[var(--batik-cream)] hover:bg-[#F3EBD8] text-[var(--mm-800)] border border-[#E3DCC8] rounded text-[9.5px] font-bold transition shadow-2xs flex items-center gap-0.5 cursor-pointer">
                                        <span>Detail</span>
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        </template>
                    </div>

                    <!-- ================= 2. TAMPILAN DESKTOP (TABEL LENGKAP) ================= -->
                    <div class="hidden lg:block bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-[#E3DCC8] space-y-4">
                        <div>
                            <h3 class="font-display text-lg font-bold text-[var(--mm-900)]">Daftar Riwayat Laporan</h3>
                            <p class="text-xs text-gray-500">Daftar perbaikan perangkat yang sedang aktif diproses oleh teknisi.</p>
                        </div>

                        <div class="rounded-xl border border-[#E3DCC8] shadow-inner bg-white overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-full">
                                <thead>
                                    <tr class="bg-[var(--batik-cream)] text-[var(--mm-900)] text-xs font-bold border-b border-[#E3DCC8]">
                                        <th class="p-3 whitespace-nowrap">ID Laporan</th>
                                        <th class="p-3 whitespace-nowrap">Masuk</th>
                                        <th class="p-3 whitespace-nowrap">Perangkat</th>
                                        <th class="p-3">Kendala</th>
                                        <th class="p-3 whitespace-nowrap">Teknisi</th>
                                        <th class="p-3 whitespace-nowrap">Prioritas</th>
                                        <th class="p-3 whitespace-nowrap">Status</th>
                                        <th class="p-3 text-center whitespace-nowrap">Lampiran</th>
                                        <th class="p-3 text-center whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs divide-y divide-[#E3DCC8]">
                                    <template x-for="(r, index) in filteredReports" :key="r.id">
                                        <tr class="hover:bg-amber-50/45 transition">
                                            <td class="p-3 font-bold text-[var(--mm-800)] whitespace-nowrap" x-text="r.id"></td>
                                            <td class="p-3 text-gray-500 whitespace-nowrap" x-text="formatTanggal(r.created_at || r.tanggal_masuk)"></td>
                                            <td class="p-3 font-medium text-gray-800 max-w-[130px] truncate" :title="r.perangkat" x-text="r.perangkat"></td>
                                            <td class="p-3 text-gray-600 max-w-[180px] xl:max-w-[260px] truncate" :title="r.kendala" x-text="r.kendala"></td>
                                            <td class="p-3 whitespace-nowrap">
                                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-sky-100 text-sky-800" x-text="r.teknisi || 'Belum'"></span>
                                            </td>
                                            <td class="p-3 whitespace-nowrap">
                                                <span :class="priorityBadgeClass(r.prioritas)" class="px-2 py-0.5 rounded text-[10px] font-semibold" x-text="r.prioritas || 'Sedang'"></span>
                                            </td>
                                            <td class="p-3 whitespace-nowrap">
                                                <span :class="statusBadgeClass(r.status)" class="px-2.5 py-1 rounded-lg text-[10px] font-bold" x-text="r.status"></span>
                                            </td>
                                            <td class="p-3 text-center whitespace-nowrap">
                                                <div class="inline-flex items-center justify-center gap-1.5">
                                                    <button type="button" x-show="r.foto" @click="openMedia(r.foto, 'image')" title="Lihat Foto" class="p-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 transition shadow-xs border border-emerald-200 inline-flex items-center justify-center cursor-pointer">
                                                        <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </button>
                                                    <a x-show="r.dokumen" 
                                                       :href="getPdfUrl(r.dokumen)" 
                                                       target="_blank" 
                                                       rel="noopener noreferrer" 
                                                       title="Buka Dokumen PDF" 
                                                       class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 transition shadow-xs border border-red-200 inline-flex items-center justify-center cursor-pointer">
                                                        <svg class="w-4 h-4 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-6 4h4"/>
                                                        </svg>
                                                    </a>
                                                    <span x-show="!r.foto && !r.dokumen" class="text-gray-400 italic">-</span>
                                                </div>
                                            </td>
                                            <td class="p-3 text-center whitespace-nowrap">
                                                <div class="flex items-center justify-center">
                                                    <button type="button" @click="openDetail(r)" class="px-3.5 py-1.5 bg-[var(--batik-cream)] hover:bg-[#F3EBD8] text-[var(--mm-800)] border border-[#E3DCC8] rounded-xl text-xs font-bold transition shadow-xs hover:shadow-sm cursor-pointer">
                                                        Detail
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($laporans instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                        <div class="pt-2">
                            {{ $laporans->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </main>

    </div>

    <!-- MODAL PRATINJAU MEDIA -->
    <div x-show="mediaModalOpen"
         x-cloak
         x-transition.opacity
         @keydown.escape.window="closeMediaModal()"
         class="fixed inset-0 z-[90] flex items-center justify-center bg-black/80 p-3 sm:p-4"
         role="dialog"
         aria-modal="true">
        <div class="bg-white rounded-2xl w-full p-3.5 sm:p-5 space-y-3 shadow-2xl border border-[#E3DCC8] relative flex flex-col transition-all"
             :class="mediaType === 'image' ? 'max-w-4xl h-[92vh] sm:h-auto sm:max-h-[94vh]' : 'max-w-md'"
             @click.away="closeMediaModal()">

            <!-- Header Modal -->
            <div class="flex justify-between items-center gap-2 border-b pb-2 px-1">
                <div class="flex items-center gap-2 min-w-0">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 border"
                         :class="mediaType === 'image' ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-red-50 text-red-600 border-red-200'">
                        <template x-if="mediaType === 'image'">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </template>
                        <template x-if="mediaType === 'pdf'">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-6 4h4"/>
                            </svg>
                        </template>
                    </div>
                    <div class="min-w-0">
                        <p class="font-display font-bold text-xs sm:text-sm text-[var(--mm-900)] leading-tight truncate" x-text="mediaType === 'image' ? 'Pratinjau Foto Kerusakan' : 'Pratinjau Dokumen Pendukung'"></p>
                        <p class="text-[10px] text-gray-500 truncate" x-show="mediaType === 'pdf'" x-text="mediaFileName || 'Dokumen PDF'"></p>
                    </div>
                </div>
                
                <div class="flex items-center gap-1.5">
                    <button type="button" @click="closeMediaModal()" aria-label="Tutup" class="text-gray-400 hover:text-gray-700 hover:bg-gray-100 text-base font-bold w-8 h-8 rounded-xl flex items-center justify-center transition cursor-pointer">✕</button>
                </div>
            </div>

            <!-- Area Konten Media -->
            <div class="flex-1 overflow-hidden flex flex-col rounded-xl relative border border-[#E3DCC8]/80 bg-white"
                 :class="mediaType === 'image' ? 'min-h-[55vh]' : ''">
                <!-- Foto -->
                <template x-if="mediaType === 'image'">
                    <div class="relative w-full h-full flex items-center justify-center p-2 overflow-auto bg-slate-900">
                        <img :src="mediaSrc.startsWith('http') ? mediaSrc : ('{{ asset('storage') }}/' + mediaSrc)" 
                             x-on:error="if (!$el.dataset.fallback && mediaSrc) { $el.dataset.fallback = '1'; let p = mediaSrc.replace(/^https?:\/\/[^\/]+\/(storage\/)?/, '').replace(/^\/?storage\//, ''); $el.src = '{{ url('/storage-file') }}/' + p; }"
                             class="max-h-[70vh] max-w-full object-contain rounded-lg shadow-lg" 
                             alt="Pratinjau Foto">
                    </div>
                </template>

                <!-- PDF Dokumen / Surat -->
                <template x-if="mediaType === 'pdf'">
                    <div class="w-full flex flex-col items-center justify-center p-5 sm:p-7 text-center bg-linear-to-b from-amber-50/40 to-white">
                        <div class="w-16 h-16 rounded-2xl bg-red-50 border border-red-200 flex items-center justify-center mb-3 shadow-xs">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 13h6m-6 4h4"/>
                            </svg>
                        </div>
                        <h4 class="font-display font-bold text-base text-[var(--mm-900)] mb-1">Surat / Dokumen Pendukung</h4>
                        <p class="text-[11px] text-gray-500 max-w-xs mb-3 truncate font-mono bg-white px-2.5 py-1 rounded-lg border border-[#E3DCC8]" x-text="mediaFileName || 'dokumen-pendukung.pdf'"></p>
                        
                        <p class="text-xs text-gray-600 max-w-xs mb-5 leading-relaxed">
                            Dokumen surat resmi dapat langsung Anda lihat secara penuh dan jernih, atau diunduh ke memori perangkat.
                        </p>

                        <div class="flex flex-col gap-2.5 w-full">
                            <a :href="getPdfUrl(mediaSrc)" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="w-full py-2.5 px-4 rounded-xl bg-[var(--keraton-maroon)] hover:bg-[#600e12] text-white font-bold text-xs flex items-center justify-center gap-2 transition shadow-md active:scale-95 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                                <span>Buka & Lihat Surat (Layar Penuh)</span>
                            </a>

                            <a :href="getDownloadUrl(mediaSrc)" 
                               download 
                               class="w-full py-2.5 px-4 rounded-xl bg-white hover:bg-gray-50 text-[var(--mm-900)] border border-[#E3DCC8] font-bold text-xs flex items-center justify-center gap-2 transition shadow-xs active:scale-95 cursor-pointer">
                                <svg class="w-4 h-4 text-[var(--mm-800)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span>Unduh Berkas PDF</span>
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL LAPORAN -->
    <div x-show="detailModalOpen"
         x-cloak
         x-transition.opacity
         @keydown.escape.window="detailModalOpen = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
         role="dialog"
         aria-modal="true">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl border border-[#E3DCC8] max-h-[85vh] overflow-y-auto scrollable-content"
             @click.away="detailModalOpen = false">
            <div class="flex justify-between items-center border-b pb-3">
                <div>
                    <h3 class="font-display font-bold text-base text-[var(--mm-900)]" x-text="currentReport?.id"></h3>
                    <p class="text-[10px] text-gray-500">Detail & riwayat penanganan laporan Anda</p>
                </div>
                <button type="button" @click="detailModalOpen = false" aria-label="Tutup detail" class="text-gray-400 hover:text-gray-600 text-lg leading-none cursor-pointer">✕</button>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs" x-show="currentReport">
                <div class="bg-[var(--batik-cream)] rounded-xl p-3 col-span-2">
                    <p class="text-[10px] text-gray-500 font-semibold">Perangkat</p>
                    <p class="font-bold text-[var(--ink)]" x-text="currentReport?.perangkat"></p>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 font-semibold">Alamat</p>
                    <p class="font-bold text-[var(--ink)]" x-text="currentReport?.alamat || currentReport?.kecamatan || '{{ Auth::user()->alamat ?? Auth::user()->kecamatan ?? '-' }}'"></p>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 font-semibold">Prioritas</p>
                    <span :class="priorityBadgeClass(currentReport?.prioritas)" class="inline-block mt-0.5 px-2.5 py-1 rounded-lg text-[10px] font-bold" x-text="currentReport?.prioritas || 'Sedang'"></span>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 font-semibold">Tanggal Masuk</p>
                    <p class="font-bold text-[var(--mm-800)]" x-text="formatTanggal(currentReport?.created_at)"></p>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 font-semibold">Tanggal Keluar</p>
                    <p class="font-bold" :class="currentReport?.tanggal_keluar ? 'text-[var(--mm-800)]' : 'text-gray-400 italic'"
                       x-text="currentReport?.tanggal_keluar ? formatTanggal(currentReport.tanggal_keluar) : 'Belum keluar'"></p>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 font-semibold">Status Saat Ini</p>
                    <span :class="statusBadgeClass(currentReport?.status)" class="inline-block mt-0.5 px-2.5 py-1 rounded-lg text-[10px] font-bold" x-text="currentReport?.status"></span>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3 col-span-2">
                    <p class="text-[10px] text-gray-500 font-semibold">Kendala / Kerusakan</p>
                    <p class="font-medium text-[var(--ink)]" x-text="currentReport?.kendala"></p>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3 col-span-2">
                    <p class="text-[10px] text-gray-500 font-semibold">Teknisi Penanggung Jawab</p>
                    <p class="font-medium text-[var(--ink)]" x-text="currentReport?.teknisi || 'Belum ditugaskan'"></p>
                </div>
                <div class="bg-[var(--batik-cream)] rounded-xl p-3 col-span-2" x-show="currentReport?.foto || currentReport?.dokumen">
                    <p class="text-[10px] text-gray-500 font-semibold mb-1.5">Berkas / Lampiran Pendukung</p>
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" x-show="currentReport?.foto" @click="openMedia(currentReport.foto, 'image')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-xl text-xs font-semibold transition shadow-xs cursor-pointer">
                            <img src="{{ asset('icon pelapor/photo.png') }}" alt="Foto" class="w-4 h-4 object-contain">
                            <span>Lihat Foto Kerusakan</span>
                        </button>
                        <a x-show="currentReport?.dokumen" 
                           :href="getPdfUrl(currentReport?.dokumen)" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-red-50 text-red-800 border border-red-200 rounded-xl text-xs font-semibold transition shadow-xs cursor-pointer">
                            <img src="{{ asset('icon pelapor/pdf.png') }}" alt="PDF" class="w-4 h-4 object-contain">
                            <span>Lihat Dokumen PDF</span>
                            <svg class="w-3.5 h-3.5 text-red-600 shrink-0 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center gap-2 pt-3 border-t">
                <template x-if="currentReport && (currentReport.status || '').toLowerCase() !== 'selesai' && (currentReport.status || '').toLowerCase() !== 'ditolak'">
                    <button type="button" 
                            @click="batalkanLaporan(currentReport.db_id || currentReport.id)"
                            :disabled="cancelling"
                            class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold transition cursor-pointer flex items-center gap-1.5 active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span x-text="cancelling ? 'Membatalkan...' : 'Batalkan Laporan'"></span>
                    </button>
                </template>
                <div class="ml-auto">
                    <button type="button" @click="detailModalOpen = false" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition cursor-pointer">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFIKASI -->
    <div x-show="toast.show" x-cloak x-transition
         class="fixed bottom-5 right-5 z-[80] text-white px-4 py-3 rounded-xl shadow-2xl text-xs font-semibold flex items-center gap-2.5 border border-white/10 max-w-sm"
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
        window.__initialReports = {!! json_encode(array_values($reportsData ?? [])) !!};
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('touchScroll', () => ({
                init() {
                    const slider = this.$el;
                    let isDown = false;
                    let startX;
                    let scrollLeft;

                    slider.addEventListener('mousedown', (e) => {
                        isDown = true;
                        slider.classList.add('active');
                        startX = e.pageX - slider.offsetLeft;
                        scrollLeft = slider.scrollLeft;
                    });
                    slider.addEventListener('mouseleave', () => {
                        isDown = false;
                        slider.classList.remove('active');
                    });
                    slider.addEventListener('mouseup', () => {
                        isDown = false;
                        slider.classList.remove('active');
                    });
                    slider.addEventListener('mousemove', (e) => {
                        if (!isDown) return;
                        e.preventDefault();
                        const x = e.pageX - slider.offsetLeft;
                        const walk = (x - startX) * 1.5;
                        slider.scrollLeft = scrollLeft - walk;
                    });
                }
            }));

            Alpine.data('riwayatApp', (initialReports) => ({
                sidebarOpen: false,
                reports: initialReports || [],
                seenRekapIds: JSON.parse(localStorage.getItem('siperkom_seen_rekap_ids') || '[]'),
                detailModalOpen: false,
                currentReport: null,
                mediaModalOpen: false,
                mediaSrc: '',
                mediaType: '',
                mediaFileName: '',
                toast: { show: false, message: '', type: 'success' },
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

                init() {
                    // Otomatis bersihkan badge lonceng saat berada di halaman riwayat tanpa perlu klik lonceng
                    this.markNotifRead();

                    this.$watch('sidebarOpen', (value) => {
                        if (value) {
                            document.documentElement.classList.add('overflow-hidden', 'touch-none', 'overscroll-none');
                            document.body.classList.add('overflow-hidden', 'touch-none', 'overscroll-none');
                        } else if (!this.detailModalOpen && !this.mediaModalOpen) {
                            document.documentElement.classList.remove('overflow-hidden', 'touch-none', 'overscroll-none');
                            document.body.classList.remove('overflow-hidden', 'touch-none', 'overscroll-none');
                        }
                    });

                    // Cek query open_id untuk auto buka detail dari notifikasi
                    const urlParams = new URLSearchParams(window.location.search);
                    const openId = urlParams.get('open_id');
                    if (openId) {
                        this.$nextTick(() => {
                            const found = this.reports.find(r => String(r.db_id || r.id) === String(openId));
                            if (found) {
                                this.openDetail(found);
                            }
                        });
                    }

                    // Real-time polling live sync tanpa refresh setiap 2.5 detik
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
                                            this.showToast('Laporan ' + newR.id + ' telah SELESAI diperbaiki! Berpindah ke Rekap.', 'success');
                                        } else if (newR.status === 'Ditolak') {
                                            this.showToast('Laporan ' + newR.id + ' DITOLAK oleh teknisi. Berpindah ke Rekap.', 'error');
                                        } else if (newR.status === 'Menunggu Part') {
                                            this.showToast('Laporan ' + newR.id + ' berstatus MENUNGGU PART dari teknisi.', 'info');
                                        }
                                    }
                                });

                                this.reports = data.reports;
                                this.markNotifRead();
                            }
                        }
                    } catch (e) {}
                },

                get filteredReports() {
                    return this.reports.filter(r => {
                        const st = (r.status || '').trim();
                        return st !== 'Selesai' && st !== 'Ditolak';
                    });
                },

                openMedia(src, type) {
                    if (type === 'pdf') {
                        const url = this.getPdfUrl(src);
                        if (url) {
                            window.open(url, '_blank', 'noopener,noreferrer');
                        }
                    }
                    this.mediaSrc = src;
                    this.mediaType = type;
                    this.mediaFileName = src ? src.split('/').pop() : 'Dokumen';
                    this.mediaModalOpen = true;
                },

                closeMediaModal() {
                    this.mediaModalOpen = false;
                },

                getPdfUrl(src) {
                    if (!src) return '';
                    if (src.startsWith('http')) return src;
                    const filename = src.split('/').pop();
                    return "{{ url('/dokumen-preview') }}/" + encodeURIComponent(filename);
                },

                getDownloadUrl(src) {
                    if (!src) return '#';
                    if (src.startsWith('http')) return src;
                    const filename = src.split('/').pop();
                    return "{{ url('/dokumen-preview') }}/" + encodeURIComponent(filename) + "?download=1";
                },

                statusBadgeClass(status) {
                    return {
                        'Dalam Perbaikan': 'bg-amber-100 text-amber-800',
                        'Menunggu Part': 'bg-blue-100 text-blue-800',
                        'Selesai': 'bg-emerald-100 text-emerald-800',
                    }[status] || 'bg-gray-100 text-gray-700';
                },

                priorityBadgeClass(priority) {
                    return {
                        'Tinggi': 'bg-red-100 text-red-700',
                        'Sedang': 'bg-amber-100 text-amber-700',
                        'Rendah': 'bg-gray-100 text-gray-600',
                    }[priority] || 'bg-gray-100 text-gray-600';
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

                formatTanggal(tgl) {
                    if (!tgl) return '-';
                    const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                    const d = new Date(tgl);
                    if (isNaN(d)) return tgl;
                    return d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
                },

                openDetail(r) {
                    this.currentReport = r;
                    this.detailModalOpen = true;
                },

                cancelling: false,
                async batalkanLaporan(id) {
                    if (!confirm('Apakah Anda yakin ingin membatalkan laporan pengaduan ini? Tindakan ini akan menghapus antrean dan tidak dapat dibatalkan.')) {
                        return;
                    }
                    this.cancelling = true;
                    try {
                        const response = await fetch('{{ url("/pelapor/laporan") }}/' + encodeURIComponent(id) + '/batalkan', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const res = await response.json();
                        if (response.ok && res.success) {
                            this.showToast(res.message || 'Laporan berhasil dibatalkan.', 'success');
                            this.detailModalOpen = false;
                            this.reports = this.reports.filter(r => String(r.db_id || r.id) !== String(id) && String(r.id) !== String(id));
                        } else {
                            this.showToast((res && res.message) || 'Gagal membatalkan laporan.', 'error');
                        }
                    } catch (err) {
                        this.showToast('Terjadi kesalahan jaringan atau sistem.', 'error');
                    } finally {
                        this.cancelling = false;
                    }
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message: message, type: type };
                    setTimeout(() => { this.toast.show = false; }, 3500);
                }
            }));
        });
    </script>
</body>
</html>