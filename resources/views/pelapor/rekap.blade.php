@php
    $sourceCollection = isset($allReports) ? $allReports : ($laporans instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $laporans->getCollection() : collect($laporans ?? []));
    $reportsData = $sourceCollection->map(function ($item) {
        return [
            'id'             => $item->kode_laporan ?? '#CRB-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
            'kode_laporan'   => $item->kode_laporan ?? '#CRB-' . str_pad($item->id, 3, '0', STR_PAD_LEFT),
            'db_id'          => $item->id,
            'perangkat'      => $item->perangkat ?? '-',
            'jenis_perangkat'=> $item->jenis_perangkat ?? $item->kategori ?? null,
            'kendala'        => $item->kendala ?? '-',
            'catatan'        => $item->keterangan_rekap ?? $item->catatan ?? $item->keterangan ?? '-',
            'keterangan_rekap' => $item->keterangan_rekap ?? $item->catatan ?? $item->keterangan ?? '-',
            'created_at'     => $item->created_at ? $item->created_at->format('Y-m-d') : null,
            'tanggal'        => $item->created_at ? $item->created_at->format('Y-m-d') : null,
            'tanggal_masuk'  => $item->created_at ? $item->created_at->format('Y-m-d') : null,
            'tanggal_keluar' => $item->tanggal_keluar ? (is_string($item->tanggal_keluar) ? substr($item->tanggal_keluar, 0, 10) : $item->tanggal_keluar->format('Y-m-d')) : ($item->updated_at ? $item->updated_at->format('Y-m-d') : '-'),
            'updated_at'     => $item->updated_at ? $item->updated_at->format('Y-m-d') : null,
            'teknisi'        => $item->teknisi ?? '-',
            'status'         => $item->status ?? 'Selesai',
            'prioritas'      => $item->prioritas ?? 'Sedang',
            'pelapor'        => $item->pelapor ?? optional($item->user)->name ?? Auth::user()->name ?? '-',
            'desa'           => optional($item->user)->nama_desa ?? optional($item->user)->desa ?? $item->desa ?? Auth::user()->nama_desa ?? Auth::user()->desa ?? '-',
            'alamat'         => $item->alamat ?? optional($item->user)->alamat ?? $item->kecamatan ?? optional($item->user)->kecamatan ?? Auth::user()->alamat ?? Auth::user()->kecamatan ?? '-',
            'kecamatan'      => $item->alamat ?? $item->kecamatan ?? optional($item->user)->alamat ?? optional($item->user)->kecamatan ?? Auth::user()->alamat ?? Auth::user()->kecamatan ?? '-',
            'foto'           => $item->foto ? (filter_var($item->foto, FILTER_VALIDATE_URL) ? $item->foto : asset('storage/' . $item->foto)) : null,
            'dokumen'        => $item->dokumen  ? basename($item->dokumen) : null,
            'dokumen_url'    => $item->dokumen ? (filter_var($item->dokumen, FILTER_VALIDATE_URL) ? $item->dokumen : asset('storage/' . $item->dokumen)) : null,
        ];
    })->values()->toArray();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Rekap Laporan Saya | SIPERKOM Kabupaten Cirebon</title>

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

        /* Latar Belakang Batik Mega Mendung Pelapor */
        .mega-mendung-bg {
            background-color: #0b2f56;
            background-image: 
                linear-gradient(180deg, rgba(7, 37, 68, 0.85) 0%, rgba(9, 45, 82, 0.92) 100%),
                url("{{ asset('images/batik mega mendung.png') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .gold-divider {
            background: linear-gradient(90deg, rgba(201, 162, 39, 0.9) 0%, rgba(201, 162, 39, 0.4) 60%, transparent 100%);
            height: 1.5px;
        }

        .mobile-rounded-sidebar {
            border-top-right-radius: 1.75rem;
            border-bottom-right-radius: 1.75rem;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

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

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: rgba(20, 88, 138, 0.3) transparent;
        }
        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: #F4EFE6;
            border-radius: 9999px;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: var(--mm-600);
            border-radius: 9999px;
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

        @media (max-width: 1023px) {
            .mega-mendung-bg {
                overscroll-behavior: none !important;
                touch-action: none !important;
            }
        }

        .recap-row-selesai { border-left: 4px solid #16a34a !important; }
        .recap-row-ditolak { border-left: 4px solid #dc2626 !important; }
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)]"
    x-data="pelaporRekapApp(window.__initialReports)" 
    :class="{ 'overflow-hidden touch-none overscroll-none': sidebarOpen }">

    <div class="desktop-layout flex flex-row relative min-h-screen lg:min-h-0 lg:p-3 lg:gap-3 bg-[var(--batik-cream)]">

        <!-- BACKDROP MOBILE -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" @touchmove.prevent @wheel.prevent class="fixed inset-0 bg-black/60 z-[9998] lg:hidden transition-opacity touch-none overscroll-none"></div>

        <!-- SIDEBAR PELAPOR -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            @touchmove.stop.prevent="if (window.innerWidth < 1024) $event.preventDefault()"
            class="fixed lg:static top-3.5 left-0 z-[9999] lg:z-auto w-[76vw] max-w-[275px] lg:w-[min(18rem,calc(100vw-1rem))] lg:max-w-none mega-mendung-bg flex flex-col justify-between p-4 sm:p-5 lg:p-6 text-white shrink-0 shadow-2xl transition-transform duration-300 ease-out h-[78vh] max-h-[580px] lg:h-full lg:max-h-none overflow-hidden lg:overflow-y-auto no-scrollbar touch-none lg:touch-auto overscroll-none select-none mobile-rounded-sidebar border-0 outline-none">

            <div class="space-y-3.5 lg:space-y-4">
                
                <a href="{{ route('pelapor.profile.edit') ?? '#' }}" class="bg-gradient-to-r from-[#0b335c]/85 via-[#0c3966]/80 to-[#072544]/90 border border-blue-300/30 hover:border-blue-400/50 p-3 rounded-2xl flex items-center justify-between transition-all duration-200 group shadow-lg backdrop-blur-sm">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="relative shrink-0">
                            @if(Auth::user()->avatar ?? false)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-blue-400/60 shadow-md" alt="Foto Profil">
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

                <nav class="space-y-1 pt-1" aria-label="Menu Pelapor">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-white px-2 mb-2">MENU PELAPOR</p>
                    
                    <a href="{{ route('pelapor.dashboard') }}" @click="sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('pelapor.dashboard') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('pelapor.dashboard') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('pelapor.dashboard') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard Pelapor</span>
                    </a>
                    
                    <a href="{{ route('pelapor.riwayat') }}" @click="markNotifRead(); sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('pelapor.riwayat*') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('pelapor.riwayat*') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('pelapor.riwayat*') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Riwayat Laporan Saya</span>
                    </a>
                    
                    <a href="{{ route('pelapor.rekap') }}" @click="markNotifRead(); sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('pelapor.rekap*') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('pelapor.rekap*') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('pelapor.rekap*') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Rekap Laporan</span>
                    </a>
                </nav>
            </div>

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

        <!-- KONTEN UTAMA -->
        <main class="min-w-0 flex-1 flex flex-col h-full bg-white lg:rounded-3xl shadow-sm border border-[#E3DCC8] transition-all"
              :class="sidebarOpen ? 'overflow-hidden pointer-events-none touch-none select-none' : 'overflow-y-auto scrollable-content'">

            <!-- Top Header -->
            <header class="bg-white border-b border-[#E3DCC8] px-3 sm:px-6 py-3 sm:py-4 sticky top-0 z-30 flex items-center justify-between shadow-sm gap-2 sm:gap-3 shrink-0 lg:rounded-t-3xl">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3">
                    <button type="button" @click="sidebarOpen = true" aria-label="Buka menu" class="lg:hidden w-9 h-9 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] text-[var(--mm-900)] shrink-0 hover:bg-[#F3EBD8] active:scale-95 transition flex items-center justify-center shadow-2xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="font-display font-bold text-[11px] leading-tight sm:text-sm text-[var(--mm-900)]">Rekapitulasi Laporan Saya</h2>
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

            <!-- Isi Halaman Rekap Pelapor -->
            <div class="p-4 sm:p-6 lg:p-8 space-y-5 flex-1 pb-16">

                <!-- STATE KOSONG -->
                <div x-show="recapReports.length === 0" x-cloak class="bg-white p-4 sm:p-6 rounded-2xl border border-[#E3DCC8] text-center shadow-sm space-y-2">
                    <div class="w-12 h-12 mx-auto bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a2 2 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h4 class="font-display font-bold text-sm text-[var(--mm-900)]" x-text="reports.length === 0 ? 'Belum Ada Laporan' : 'Belum Ada Rekap Laporan'"></h4>
                    <p class="text-[10px] leading-relaxed text-gray-500 max-w-xs mx-auto" x-show="reports.length > 0">
                        Tabel rekapitulasi akan otomatis muncul di sini setelah ada laporan pengaduan Anda yang telah diputuskan <span class="text-emerald-700 font-bold">Selesai</span> atau <span class="text-red-600 font-bold">Ditolak</span> oleh teknisi.
                    </p>
                    <p class="text-[10px] leading-relaxed text-gray-500 max-w-xs mx-auto" x-show="reports.length === 0">
                        Belum ada laporan pengaduan yang dibuat. Laporan Anda akan tampil di sini setelah dikirim.
                    </p>
                </div>

                <!-- JIKA ADA DATA -->
                <div x-show="recapReports.length > 0" x-cloak class="space-y-4">
                    
                    <!-- 1. TAMPILAN KHUSUS MOBILE: KARTU ELEGAN, KOMPAK & RAPI -->
                    <div class="block lg:hidden space-y-2.5">
                        <div class="flex items-center justify-between px-1">
                            <div>
                                <h3 class="font-display text-sm font-bold text-[var(--mm-900)]">Arsip Rekap Laporan</h3>
                                <p class="text-[10px] text-gray-500">Daftar perbaikan yang telah selesai atau ditolak</p>
                            </div>
                            <span class="px-2 py-0.5 rounded-full bg-[var(--batik-cream)] border border-[#E3DCC8] text-[10px] font-bold text-[var(--mm-800)]"
                                  x-text="recapReports.length + ' Arsip'"></span>
                        </div>

                        <template x-for="r in recapReports" :key="'card-' + (r.db_id || r.id)">
                            <div :id="'rekap-card-' + (r.db_id || r.id)" class="bg-white rounded-lg border border-[#E3DCC8] shadow-2xs hover:shadow-xs transition-all duration-200 overflow-hidden relative p-2 space-y-1">
                                
                                <!-- Strip Aksen Status di Sisi Kiri -->
                                <div class="absolute left-0 top-0 bottom-0 w-[3px] rounded-l-lg"
                                     :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-500' : 'bg-red-400'"></div>

                                <!-- Header Kartu: ID, Tanggal Masuk, dan Status Badge Sebaris -->
                                <div class="flex items-center justify-between gap-1 pl-1 pb-0.5 border-b border-slate-100/80">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="font-mono font-bold text-[9.5px] px-1.5 py-0.2 rounded bg-[var(--mm-900)] text-white shadow-2xs tracking-wide shrink-0" 
                                              x-text="r.kode_laporan || r.id || ('#CRB-' + (r.db_id || ''))"></span>
                                        <span class="text-[9px] text-gray-500 font-medium truncate">
                                            <span class="text-gray-400">Masuk:</span> <span class="font-semibold text-gray-700" x-text="formatTanggal(r.created_at || r.tanggal_masuk || r.tanggal)"></span>
                                        </span>
                                    </div>

                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.2 rounded-full text-[8.5px] font-bold shadow-2xs whitespace-nowrap shrink-0"
                                          :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200'">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                              :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-500' : 'bg-red-500'"></span>
                                        <span x-text="r.status"></span>
                                    </span>
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
                                        <span class="font-bold text-gray-700 truncate max-w-[90px]" :class="r.teknisi && r.teknisi !== '-' ? 'text-[var(--mm-800)]' : 'text-gray-400 italic'" x-text="r.teknisi && r.teknisi !== '-' ? r.teknisi : 'Tim SIPERKOM'"></span>
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

                                    <div class="flex items-baseline gap-1 leading-tight pt-0.5 border-t border-[#E3DCC8]/40" x-show="r.keterangan_rekap || r.keterangan || r.catatan">
                                        <span class="text-[7.5px] uppercase font-bold text-blue-800 bg-blue-100/90 px-1 py-0.2 rounded border border-blue-200/80 shrink-0 tracking-wide">
                                            Catatan
                                        </span>
                                        <span class="text-gray-600 italic text-[9px] truncate" x-text="r.keterangan_rekap || r.keterangan || r.catatan || '-'"></span>
                                    </div>
                                </div>

                                <!-- Footer Kartu: Lokasi & Status Penyelesaian -->
                                <div class="flex items-center justify-between pt-0.5 border-t border-slate-100 text-[9px] text-gray-500 pl-1 leading-tight">
                                    <span class="flex items-center gap-1 min-w-0 truncate text-gray-500">
                                        <svg class="w-2.5 h-2.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate max-w-[150px]" x-text="r.alamat || r.kecamatan || 'Kabupaten Cirebon'"></span>
                                    </span>
                                    <div class="flex items-center gap-1 font-semibold shrink-0 text-[8.5px]">
                                        <span class="text-gray-400" x-show="r.tanggal_keluar || r.updated_at" x-text="'Keluar: ' + formatTanggal(r.tanggal_keluar || r.updated_at)"></span>
                                        <span class="px-1 py-0.2 rounded font-bold" 
                                              :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700'"
                                              x-text="(r.status || '').trim().toLowerCase() === 'selesai' ? '✓ Tuntas' : '✕ Ditolak'"></span>
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>

                    <!-- 2. TAMPILAN DESKTOP: TABEL -->
                    <div class="hidden lg:block bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-[#E3DCC8] space-y-3">
                        <div>
                            <h3 class="font-display text-base font-bold text-[var(--mm-900)]">Daftar Rincian Arsip Rekap</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Daftar pengaduan tuntas dari alamat Anda pada periode terpilih.</p>
                        </div>

                        <div class="table-responsive rounded-xl border border-[#E3DCC8]">
                            <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
                                <thead>
                                    <tr class="bg-[var(--batik-cream)] text-[var(--mm-900)] font-bold border-b border-[#E3DCC8]">
                                        <th class="p-3">ID</th>
                                        <th class="p-3">Tgl Masuk</th>
                                        <th class="p-3">Tgl Keluar</th>
                                        <th class="p-3">Pelapor</th>
                                        <th class="p-3">Alamat</th>
                                        <th class="p-3">Kategori</th>
                                        <th class="p-3">Nama / Tipe Unit</th>
                                        <th class="p-3">Detail Kendala</th>
                                        <th class="p-3">Teknisi</th>
                                        <th class="p-3 text-center">Status</th>
                                        <th class="p-3">Catatan</th>
                                        <th class="p-3 text-center whitespace-nowrap">Lampiran</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#E3DCC8]">
                                    <template x-for="r in recapReports" :key="'row-' + (r.db_id || r.id)">
                                        <tr :id="'row-' + (r.db_id || r.id)" class="hover:bg-amber-50/40 transition"
                                            :class="{
                                                'recap-row-selesai': (r.status || '').trim().toLowerCase() === 'selesai',
                                                'recap-row-ditolak': (r.status || '').trim().toLowerCase() === 'ditolak'
                                            }">
                                            <td class="p-3 font-mono font-bold text-[var(--mm-900)]" x-text="r.id"></td>
                                            <td class="p-3 text-gray-600" x-text="formatTanggal(r.created_at || r.tanggal_masuk)"></td>
                                            <td class="p-3 text-gray-600 font-medium" x-text="formatTanggal(r.tanggal_keluar)"></td>
                                            <td class="p-3 font-semibold text-[var(--mm-800)]" x-text="r.pelapor"></td>
                                            <td class="p-3 font-medium text-gray-800" x-text="r.alamat || r.kecamatan"></td>
                                            <td class="p-3 font-semibold text-[var(--mm-600)]" x-text="r.jenis_perangkat || classifyDevice(r.perangkat)"></td>
                                            <td class="p-3 font-medium text-gray-700" x-text="r.perangkat"></td>
                                            <td class="p-3 text-gray-600 max-w-[200px] truncate" :title="r.kendala" x-text="r.kendala"></td>
                                            <td class="p-3 font-medium text-gray-800" x-text="r.teknisi"></td>
                                            <td class="p-3 text-center">
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold inline-block shadow-xs"
                                                    :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-red-100 text-red-800 border border-red-300'"
                                                    x-text="r.status"></span>
                                            </td>
                                            <td class="p-3 text-gray-600 text-xs max-w-[200px] truncate" :title="r.keterangan_rekap" x-text="r.keterangan_rekap"></td>
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
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- PAGINATION JIKA ADA -->
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

    <!-- INJEKSI DATA DAN SCRIPT ALPINE -->
    <script>
        window.__initialReports = {!! json_encode(array_values($reportsData ?? [])) !!};
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        function pelaporRekapApp(initialReports) {
            return {
                sidebarOpen: false,
                reports: Array.isArray(initialReports) ? initialReports : [],
                seenRekapIds: JSON.parse(localStorage.getItem('siperkom_seen_rekap_ids') || '[]'),
                toast: { show: false, message: '', type: 'success' },
                mediaModalOpen: false,
                mediaSrc: '',
                mediaType: '',
                mediaFileName: '',
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
                    // Otomatis hilangkan angka notifikasi saat pelapor berada di halaman rekap
                    this.markNotifRead();

                    this.$watch('sidebarOpen', (value) => {
                        if (value) {
                            document.documentElement.classList.add('overflow-hidden', 'touch-none', 'overscroll-none');
                            document.body.classList.add('overflow-hidden', 'touch-none', 'overscroll-none');
                        } else if (!this.mediaModalOpen) {
                            document.documentElement.classList.remove('overflow-hidden', 'touch-none', 'overscroll-none');
                            document.body.classList.remove('overflow-hidden', 'touch-none', 'overscroll-none');
                        }
                    });

                    // Cek query open_id untuk auto scroll & highlight arsip dari notifikasi
                    const urlParams = new URLSearchParams(window.location.search);
                    const openId = urlParams.get('open_id');
                    if (openId) {
                        this.$nextTick(() => {
                            const found = this.reports.find(r => String(r.db_id) === String(openId) || String(r.id) === String(openId) || String(r.kode_laporan) === String(openId));
                            const targetId = found ? (found.db_id || found.id) : openId;
                            const el = document.getElementById('rekap-card-' + targetId) || document.getElementById('row-' + targetId);
                            if (el) {
                                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                el.classList.add('ring-2', 'ring-amber-500');
                                setTimeout(() => el.classList.remove('ring-2', 'ring-amber-500'), 3000);
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
                                            this.showToast('Laporan ' + newR.id + ' telah SELESAI diperbaiki!', 'success');
                                        } else if (newR.status === 'Ditolak') {
                                            this.showToast('Laporan ' + newR.id + ' DITOLAK oleh teknisi.', 'error');
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

                formatTanggal(tglStr) {
                    if (!tglStr || tglStr === '-') return '-';

                    const cleanStr = String(tglStr).trim().replace(' ', 'T');
                    const d = new Date(cleanStr);
                    if (!isNaN(d.getTime())) {
                        const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                        return d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
                    }
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

                // Filter data berstatus Selesai atau Ditolak
                get recapReports() {
                    return this.reports.filter(r => {
                        const st = (r.status || '').trim().toLowerCase();
                        return st === 'selesai' || st === 'ditolak';
                    });
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message: message, type: type };
                    setTimeout(() => { this.toast.show = false; }, 3500);
                }
            };
        }
    </script>
</body>
</html>