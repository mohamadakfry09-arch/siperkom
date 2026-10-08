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
            'created_at'     => $item->created_at ? $item->created_at->format('Y-m-d H:i') : null,
            'tanggal'        => $item->created_at ? $item->created_at->format('Y-m-d H:i') : null,
            'tanggal_masuk'  => $item->created_at ? $item->created_at->format('Y-m-d H:i') : null,
            'tanggal_keluar' => $item->tanggal_keluar 
                ? (is_string($item->tanggal_keluar) 
                    ? (strlen($item->tanggal_keluar) > 10 ? substr($item->tanggal_keluar, 0, 16) : (substr($item->tanggal_keluar, 0, 10) . ($item->updated_at ? ' ' . $item->updated_at->format('H:i') : ''))) 
                    : $item->tanggal_keluar->format('Y-m-d H:i')) 
                : ($item->updated_at ? $item->updated_at->format('Y-m-d H:i') : '-'),
            'updated_at'     => $item->updated_at ? $item->updated_at->format('Y-m-d H:i') : null,
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

        /* Panel Dropdown Notifikasi Responsif */
        @media (max-width: 639px) {
            .pelapor-notif-backdrop {
                position: fixed !important;
                inset: 0 !important;
                background-color: rgba(0, 0, 0, 0.35) !important;
                backdrop-filter: blur(2px) !important;
                -webkit-backdrop-filter: blur(2px) !important;
                z-index: 9998 !important;
            }
            .pelapor-notif-dropdown {
                position: fixed !important;
                top: 56px !important;
                left: 10px !important;
                right: 10px !important;
                width: auto !important;
                max-width: calc(100vw - 20px) !important;
                box-sizing: border-box !important;
                margin: 0 auto !important;
                z-index: 9999 !important;
            }
        }
        @media (min-width: 640px) {
            .pelapor-notif-backdrop {
                display: none !important;
            }
            .pelapor-notif-dropdown {
                position: absolute !important;
                right: 0 !important;
                top: calc(100% + 8px) !important;
                width: 340px !important;
                z-index: 50 !important;
            }
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

        @media (max-width: 767px) {
            .desktop-layout > aside {
                display: none !important;
            }
        }

        @media (min-width: 768px) {
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
                padding: 0.75rem !important;
                gap: 0.75rem !important;
                box-sizing: border-box !important;
            }
            .desktop-layout > aside {
                display: flex !important;
                width: min(18.5rem, calc(100vw - 1rem)) !important;
                min-width: min(18.5rem, calc(100vw - 1rem)) !important;
                max-width: min(18.5rem, calc(100vw - 1rem)) !important;
                flex-shrink: 0 !important;
                height: 100% !important;
                max-height: 100% !important;
                min-height: 0 !important;
                position: static !important;
                top: auto !important;
                bottom: auto !important;
                border-radius: 1.5rem !important;
                box-sizing: border-box !important;
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
                border-radius: 1.5rem !important;
                box-sizing: border-box !important;
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

        html {
            -webkit-tap-highlight-color: transparent;
            -webkit-text-size-adjust: 100%;
        }

        button, a, input, select, textarea, [role="button"] {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }

        /* ============================================================
           SMOOTH ANIMATION SYSTEM — Rekap Pelapor
           GPU-accelerated transform & spring cubic-bezier
        ============================================================ */

        button, a, input, select, textarea, .card-smooth, [role="button"] {
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        button, a, .btn-touch {
            will-change: transform;
            transition:
                transform 0.16s cubic-bezier(0.34, 1.56, 0.64, 1),
                background-color 0.15s ease,
                border-color 0.15s ease,
                box-shadow 0.2s cubic-bezier(0.34, 1.56, 0.64, 1),
                opacity 0.15s ease,
                color 0.15s ease;
        }

        button:active:not(:disabled),
        a:active,
        .btn-touch:active:not(:disabled) {
            transform: scale(0.96) translateZ(0);
            transition: transform 0.08s ease-out;
        }

        .rounded-2xl, .rounded-xl {
            transition:
                transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1),
                box-shadow 0.3s ease,
                border-color 0.25s ease,
                background-color 0.25s ease;
        }

        a.rounded-2xl:hover, a.rounded-xl:hover {
            transform: translateY(-2px) translateZ(0);
            box-shadow: 0 8px 24px rgba(7, 37, 68, 0.12);
        }

        nav a {
            transition:
                background-color 0.22s ease,
                color 0.22s ease,
                transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1),
                border-color 0.22s ease !important;
        }
        nav a:hover { transform: translateX(3px) translateZ(0); }

        button svg, a svg {
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes pageLoad {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        main {
            animation: pageLoad 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .scrollable-content {
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
            min-width: 0;
        }

        html.overflow-hidden, body.overflow-hidden {
            overflow: hidden !important;
            height: 100% !important;
        }

        /* Indikator status baris rapi dan proporsional */

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)]"
    x-data="pelaporRekapApp(window.__initialReports)" 
    :class="{ 'overflow-hidden overscroll-none': sidebarOpen || detailModalOpen }">

    <div class="desktop-layout flex flex-row relative min-h-screen md:min-h-0 md:p-3 md:gap-3 bg-[var(--batik-cream)]">

        <!-- SIDEBAR PELAPOR (Hanya Tampil di Desktop / Laptop) -->
        <aside class="hidden md:flex md:static w-[min(18.5rem,calc(100vw-1rem))] mega-mendung-bg flex-col justify-between p-4 md:p-4.5 text-white shrink-0 shadow-2xl h-full overflow-y-auto no-scrollbar select-none mobile-rounded-sidebar border-0 outline-none">

            <div class="space-y-3.5 lg:space-y-4">
                
                <!-- User Profile Card (Modern Glassmorphic Style & Interactive) -->
                <a href="{{ route('pelapor.profile.edit') }}" 
                   @click="sidebarOpen = false"
                   class="group relative overflow-hidden bg-gradient-to-r from-white/[0.12] via-[#0c3966]/60 to-white/[0.04] hover:from-white/[0.18] hover:via-[#0c3966]/80 hover:to-white/[0.08] active:scale-[0.98] border border-white/15 hover:border-[var(--keraton-gold)]/70 active:border-[var(--keraton-gold)] p-2.5 sm:p-3 rounded-2xl flex items-center gap-2.5 sm:gap-3 shadow-lg shadow-black/20 backdrop-blur-md transition-all duration-200 cursor-pointer select-none touch-manipulation">
                    
                    <!-- Ambient Top Highlight & Soft Glow -->
                    <div class="absolute inset-x-0 top-0 h-[1px] bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
                    <div class="absolute -right-6 -bottom-6 w-16 h-16 bg-[var(--keraton-gold)]/10 rounded-full blur-xl group-hover:bg-[var(--keraton-gold)]/20 transition-all pointer-events-none"></div>

                    <!-- Avatar with Ring and Live Status Dot -->
                    <div class="relative shrink-0">
                        <img src="{{ Auth::user()->avatar_url }}" 
                             onerror="this.src='{{ asset('images/pelapor-avatar.svg') }}'"
                             class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl object-cover ring-2 ring-[var(--keraton-gold)]/80 ring-offset-2 ring-offset-[#072544] shadow-md group-hover:scale-105 group-hover:ring-[var(--keraton-gold)] transition-all duration-200" 
                             alt="Foto Profil">
                    </div>

                    <!-- User Name & Role Badge -->
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-xs sm:text-[13px] text-white leading-tight tracking-tight truncate group-hover:text-[var(--keraton-gold-soft)] transition-colors" 
                           title="{{ Auth::user()->name ?? 'Pelapor' }}">
                            {{ Auth::user()->name ?? 'Pelapor' }}
                        </p>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/10 border border-white/20 text-white/70 text-[10px] font-semibold tracking-wide">
                                <svg class="w-2.5 h-2.5 shrink-0 text-[var(--keraton-gold-soft)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>Pelapor</span>
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

                <!-- Navigasi Menu Pelapor -->
                <nav class="space-y-1 pt-1" aria-label="Menu Pelapor">
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-white px-2 mb-2">MENU PELAPOR</p>

                    <!-- 1. Dashboard Pelapor -->
                    <a href="{{ route('pelapor.dashboard') }}" @click="sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('pelapor.dashboard') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('pelapor.dashboard') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('pelapor.dashboard') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard Pelapor</span>
                    </a>

                    <!-- 2. Riwayat Laporan Saya -->
                    <a href="{{ route('pelapor.riwayat') }}" @click="markNotifRead(); sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('pelapor.riwayat*') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('pelapor.riwayat*') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('pelapor.riwayat*') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Riwayat Laporan Saya</span>
                    </a>

                    <!-- 3. Rekap Laporan (Aktif) -->
                    <a href="{{ route('pelapor.rekap') }}" @click="markNotifRead(); sidebarOpen = false" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold {{ request()->routeIs('pelapor.rekap*') ? 'bg-white/15 border border-white/20 text-white shadow-inner backdrop-blur-sm' : 'text-blue-100/90 hover:bg-white/10 hover:text-white' }} transition" {{ request()->routeIs('pelapor.rekap*') ? 'aria-current="page"' : '' }}>
                        <svg class="w-4 h-4 {{ request()->routeIs('pelapor.rekap*') ? 'text-[var(--keraton-gold-soft)]' : 'text-blue-200/80' }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2V9a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                        <span>Rekap Laporan</span>
                    </a>
                </nav>
            </div>

            <div class="pelapor-mobile-actions relative z-10 pt-4 pb-1 lg:pt-4 lg:pb-0 space-y-2 lg:space-y-2.5 shrink-0 mt-auto">
                <button type="button" 
                        @click="openLogoutModal()"
                        class="w-full flex items-center justify-center gap-2 bg-[#7A2331] hover:bg-[#6A1D2A] active:scale-[0.98] text-white py-2.5 rounded-2xl text-xs font-bold transition shadow-md border border-white/10 cursor-pointer">
                    <svg class="w-4 h-4 shrink-0 translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="translate-y-0.5">Keluar</span>
                </button>
            </div>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="flex-1 flex flex-col h-full bg-white md:rounded-3xl shadow-sm border border-[#E3DCC8] transition-all"
              :class="sidebarOpen ? 'overflow-hidden pointer-events-none touch-none select-none' : 'overflow-y-auto scrollable-content'">

            <header class="bg-white/95 backdrop-blur-md border-b border-[#E3DCC8] px-3.5 sm:px-6 py-2.5 sm:py-3.5 sticky top-0 z-30 flex items-center justify-between shadow-[0_2px_12px_rgba(7,37,68,0.04)] gap-2.5 sm:gap-3 shrink-0 md:rounded-t-3xl relative">
                <!-- Garis Aksen Emas Halus di Bawah Header -->
                <div class="absolute bottom-0 left-0 right-0 h-[1.5px] bg-gradient-to-r from-transparent via-[var(--keraton-gold)]/50 to-transparent pointer-events-none"></div>

                <!-- Sisi Kiri Mobile: Judul Halaman Bersih & Ringkas -->
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1 md:hidden">
                    <div class="min-w-0">
                        <h2 class="font-display font-bold text-sm sm:text-base text-[var(--mm-900)] tracking-tight truncate">
                            Rekapitulasi Layanan
                        </h2>
                        <p class="text-[10px] text-gray-500 font-medium truncate">
                            Arsip Laporan Selesai
                        </p>
                    </div>
                </div>

                <!-- Tampilan Desktop: Form Pencarian Khusus Layar Lebar untuk Tabel -->
                <div class="hidden md:flex flex-1 min-w-0 max-w-md">
                    <div class="relative w-full flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4 text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" x-model="searchQuery" @input="currentPage = 1" 
                               placeholder="Cari ID tiket, unit, kendala..." 
                               class="w-full pl-10 pr-9 h-9.5 bg-[var(--batik-cream)]/75 hover:bg-[var(--batik-cream)] border border-[#E3DCC8] rounded-xl text-xs placeholder:text-xs text-gray-800 placeholder-gray-400 focus:outline-none focus:border-[var(--mm-600)] focus:bg-white focus:ring-2 focus:ring-[var(--mm-600)]/15 transition shadow-2xs leading-normal block">
                        
                        <!-- Tombol Clear (✕) Cepat saat teks ada -->
                        <button type="button" 
                                x-show="searchQuery && searchQuery.trim().length > 0" 
                                x-cloak 
                                @click="searchQuery = ''; currentPage = 1" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 active:scale-90 transition cursor-pointer" 
                                title="Hapus pencarian">
                            <span class="w-4 h-4 rounded-full bg-gray-200/90 hover:bg-gray-300 flex items-center justify-center text-gray-600 text-[10px] font-bold">✕</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <!-- Notifikasi Lonceng Pelapor (Selesai, Ditolak, & Menunggu Part) -->
                    <div class="relative" x-data="{ notifOpen: false }">
                        <!-- Backdrop Mobile -->
                        <div x-show="notifOpen" x-cloak @click="notifOpen = false" 
                             class="pelapor-notif-backdrop"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"></div>

                        <button type="button" @click="notifOpen = !notifOpen" 
                                class="relative w-10 h-10 sm:w-10.5 sm:h-10.5 rounded-2xl bg-gradient-to-b from-white to-[var(--batik-cream)] border border-[#E3DCC8] hover:border-[var(--keraton-gold)] active:scale-95 transition-all duration-200 cursor-pointer text-[var(--mm-900)] flex items-center justify-center shadow-xs hover:shadow shrink-0" 
                                aria-label="Notifikasi Status Laporan" title="Notifikasi Status Laporan">
                            <svg class="w-5 h-5 text-[var(--mm-900)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            
                            <!-- Counter Badge -->
                            <span x-show="notifReports.length > 0" x-cloak 
                                  class="absolute -top-1 -right-1 bg-gradient-to-r from-rose-600 to-[#7A2331] text-white text-[9px] font-extrabold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center shadow-md border-2 border-white animate-pulse" 
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
                             class="pelapor-notif-dropdown bg-white border border-[#E3DCC8] rounded-2xl shadow-2xl overflow-hidden text-left flex flex-col max-h-[75vh] sm:max-h-[460px]">
                            
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

                    <!-- Tanggal (Hanya tampil di sm ke atas / tablet & desktop agar header mobile lega dan lapang) -->
                    <div class="hidden sm:block border-l border-[#E3DCC8] pl-3 text-right">
                        <p class="text-xs font-bold leading-tight whitespace-nowrap text-[var(--mm-900)]">{{ date('d M Y') }}</p>
                        <p class="text-[10px] leading-tight text-gray-500">Kabupaten Cirebon</p>
                    </div>

                    <!-- Tombol Keluar Cepat Mobile di Header (Elegan Keraton Maroon) -->
                    <button type="button" 
                            @click="openLogoutModal()"
                            class="w-10 h-10 sm:w-10.5 sm:h-10.5 rounded-2xl bg-gradient-to-b from-rose-50/90 to-rose-100/90 border border-rose-200/90 hover:border-rose-300 active:scale-95 text-[#7A2331] flex items-center justify-center shadow-xs hover:shadow active:bg-rose-200 transition-all duration-200 cursor-pointer md:hidden shrink-0" 
                            title="Keluar dari Akun" aria-label="Keluar dari Akun">
                        <svg class="w-5 h-5 text-[#7A2331]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </div>
            </header>

            <!-- Isi Halaman Rekap Pelapor -->
            <div class="p-3.5 sm:p-6 lg:p-8 space-y-3.5 sm:space-y-5 flex-1 lg:pb-8">

                <!-- STATE 1: HASIL CARI KOSONG -->
                <div x-show="recapReports.length > 0 && filteredRecapReports.length === 0" x-cloak class="bg-white p-6 sm:p-10 rounded-2xl border border-[#E3DCC8] text-center shadow-xs space-y-3">
                    <div class="w-12 h-12 mx-auto bg-amber-50 border border-amber-200/70 rounded-2xl flex items-center justify-center text-amber-600 mb-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h4 class="font-display font-bold text-sm sm:text-base text-[var(--mm-900)]">Arsip Rekap Tidak Ditemukan</h4>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto leading-relaxed">
                        Tidak ada arsip yang cocok dengan kata kunci <span class="font-bold text-[var(--mm-900)]" x-text="'&ldquo;' + searchQuery + '&rdquo;'"></span>. Coba cari dengan ID tiket, unit, atau kendala lainnya.
                    </p>
                    <button type="button" @click="searchQuery = ''; currentPage = 1" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] text-xs font-bold text-[var(--mm-900)] hover:bg-[#F3EBD8] active:scale-95 transition cursor-pointer shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Reset Pencarian</span>
                    </button>
                </div>

                <!-- STATE 2: BELUM ADA REKAP LAPORAN SAMA SEKALI -->
                <div x-show="recapReports.length === 0" x-cloak class="bg-white p-5 sm:p-8 rounded-2xl border border-[#E3DCC8] text-center shadow-sm space-y-2.5">
                    <div class="w-12 h-12 mx-auto bg-amber-50 border border-amber-200/80 rounded-2xl flex items-center justify-center text-amber-600 mb-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h4 class="font-display font-bold text-sm sm:text-base text-[var(--mm-900)]">
                        Belum Ada Rekap Laporan
                    </h4>
                    <p class="text-[10px] sm:text-xs leading-relaxed text-gray-500 max-w-sm mx-auto">
                        Tidak ada laporan perbaikan yang telah berstatus Selesai atau Ditolak.
                    </p>
                </div>

                <!-- JIKA ADA DATA -->
                <div x-show="recapReports.length > 0" x-cloak class="space-y-4">
                    
                    <!-- 1. TAMPILAN KHUSUS MOBILE & TABLET: KARTU ELEGAN, KOMPAK & RESPONSIP -->
                    <div class="block md:hidden space-y-3">
                        <div class="flex items-center justify-between px-1">
                            <div>
                                <h3 class="font-display text-sm sm:text-base font-bold text-[var(--mm-900)]">Arsip Rekap Laporan</h3>
                                <p class="text-[10px] sm:text-xs text-gray-500">Daftar perbaikan tuntas • {{ date('d M Y') }}</p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full bg-[var(--batik-cream)] border border-[#E3DCC8] text-[10px] sm:text-xs font-bold text-[var(--mm-800)]"
                                  x-text="filteredRecapReports.length + ' Arsip'"></span>
                        </div>

                        <!-- Grid 1 Kolom di Ponsel, 2 Kolom di Tablet (iPad / Android Tab) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 sm:gap-3 items-stretch">
                            <template x-for="r in filteredRecapReports" :key="'card-' + (r.db_id || r.id)">
                                <div :id="'rekap-card-' + (r.db_id || r.id)"
                                     @click="openDetail(r)"
                                     class="bg-white rounded-xl border border-[#E3DCC8] hover:border-[var(--keraton-gold)]/60 shadow-2xs hover:shadow-xs active:scale-[0.99] transition-all duration-200 overflow-hidden relative p-3 space-y-2 cursor-pointer touch-manipulation flex flex-col justify-between">
                                    
                                    <!-- Strip Aksen Status di Sisi Kiri -->
                                    <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-xl"
                                         :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-500' : 'bg-rose-500'"></div>

                                    <div class="space-y-2 pl-0.5">
                                        <!-- Header Kartu: ID, Tanggal Masuk, dan Status Badge Sebaris -->
                                        <div class="flex items-center justify-between gap-1.5">
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <span class="font-mono font-bold text-[10px] px-2 py-0.5 rounded-md bg-[var(--mm-900)] text-white shadow-2xs tracking-wide shrink-0" 
                                                      x-text="r.kode_laporan || r.id || ('#CRB-' + (r.db_id || ''))"></span>
                                                <span class="text-[9.5px] text-gray-500 font-medium truncate flex items-center gap-1">
                                                    <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    <span x-text="formatTanggal(r.created_at || r.tanggal_masuk || r.tanggal)"></span>
                                                </span>
                                            </div>

                                            <!-- Status Badge -->
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold shadow-2xs whitespace-nowrap shrink-0"
                                                  :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/90' : 'bg-rose-50 text-rose-800 border border-rose-200/90'">
                                                <span class="w-1.5 h-1.5 rounded-full"
                                                      :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                                                <span x-text="r.status"></span>
                                            </span>
                                        </div>

                                        <!-- Baris Utama: Nama Perangkat & Kategori -->
                                        <div>
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <h4 class="font-display font-bold text-[var(--mm-900)] text-xs sm:text-sm capitalize tracking-tight" x-text="r.perangkat || '-'"></h4>
                                                <span class="inline-block px-1.5 py-0.2 rounded text-[8px] font-bold bg-blue-50 text-blue-700 border border-blue-100" 
                                                      x-text="r.jenis_perangkat || r.kategori || classifyDevice(r.perangkat)"></span>
                                            </div>
                                            <p class="text-[9.5px] text-gray-500 font-medium flex items-center gap-1 mt-0.5">
                                                <svg class="w-2.5 h-2.5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="truncate" x-text="r.kecamatan || r.alamat || 'Kabupaten Cirebon'"></span>
                                            </p>
                                        </div>

                                        <!-- Kotak Kendala & Catatan Rekap -->
                                        <div class="bg-gradient-to-b from-[#FDFBF7] to-white rounded-lg p-2 border border-[#E8DFC8] text-[10px] space-y-1.5 shadow-2xs">
                                            <div class="flex items-start gap-1.5 leading-snug">
                                                <span class="text-[7.5px] uppercase font-black text-amber-800 bg-amber-100/90 px-1 py-0.2 rounded border border-amber-200/80 shrink-0 tracking-wider">
                                                    Kendala
                                                </span>
                                                <span class="text-gray-800 font-medium line-clamp-1" x-text="r.kendala || '-'"></span>
                                            </div>

                                            <div class="flex items-start gap-1.5 leading-snug pt-1 border-t border-[#E8DFC8]/60" x-show="r.keterangan_rekap || r.keterangan || r.catatan">
                                                <span class="text-[7.5px] uppercase font-black text-sky-800 bg-sky-100/90 px-1 py-0.2 rounded border border-sky-200/80 shrink-0 tracking-wider">
                                                    Hasil
                                                </span>
                                                <span class="text-sky-950 font-medium italic text-[9.5px] line-clamp-1" x-text="r.keterangan_rekap || r.keterangan || r.catatan || '-'"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer Kartu: Tanggal Selesai & Detail Aksi -->
                                    <div class="flex items-center justify-between pt-2 border-t border-[#E8DFC8]/70 gap-2 pl-0.5 mt-auto">
                                        <div class="flex items-center gap-1 text-[9.5px] text-gray-500">
                                            <span class="text-gray-400" x-text="(r.status || '').trim().toLowerCase() === 'selesai' ? 'Tuntas:' : 'Tanggal:'"></span>
                                            <span class="font-bold text-gray-700" x-text="r.tanggal_keluar ? formatTanggal(r.tanggal_keluar) : (r.updated_at ? formatTanggal(r.updated_at) : '-')"></span>
                                        </div>

                                        <button type="button" 
                                                @click.stop="openDetail(r)" 
                                                class="px-2.5 py-1 bg-[var(--batik-cream)] hover:bg-[#F3EBD8] text-[var(--mm-900)] border border-[#E3DCC8] hover:border-[var(--keraton-gold)] rounded-lg text-[10.5px] font-bold transition-all shadow-2xs hover:shadow-xs flex items-center gap-1 active:scale-95 cursor-pointer touch-manipulation">
                                            <span>Rincian</span>
                                            <svg class="w-3 h-3 text-[var(--keraton-gold)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>

                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- 2. TAMPILAN DESKTOP: TABEL PERSIS LAYOUT ADMIN -->
                    <div class="hidden md:block bg-white p-5 sm:p-6 lg:p-7 rounded-2xl shadow-sm border border-[#E3DCC8] space-y-4">
                        <div class="flex items-center justify-between pb-1">
                            <div>
                                <h3 class="font-display font-bold text-base sm:text-lg text-[var(--mm-900)]">Daftar Rincian Arsip Rekap</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Daftar pengaduan tuntas dari alamat Anda pada periode terpilih.</p>
                            </div>
                        </div>

                        <!-- Table Responsive -->
                        <div class="overflow-x-auto rounded-2xl border border-[#E3DCC8] bg-white">
                            <table class="w-full text-left border-collapse text-xs whitespace-nowrap">
                                <thead>
                                    <tr class="bg-[var(--batik-cream)] text-[var(--mm-900)] font-bold border-b border-[#E3DCC8]">
                                        <th class="py-3 px-3.5">ID</th>
                                        <th class="py-3 px-3.5">TANGGAL MASUK</th>
                                        <th class="py-3 px-3.5">TANGGAL KELUAR</th>
                                        <th class="py-3 px-3.5">NAMA ORANG</th>
                                        <th class="py-3 px-3.5">PERANGKAT</th>
                                        <th class="py-3 px-3.5">KENDALA</th>
                                        <th class="py-3 px-3.5">KETERANGAN</th>
                                        <th class="py-3 px-3.5 text-center">STATUS</th>
                                        <th class="py-3 px-3.5 text-center whitespace-nowrap">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#E3DCC8]">
                                    <template x-for="r in paginatedReports" :key="'row-' + (r.db_id || r.id)">
                                        <tr :id="'row-' + (r.db_id || r.id)" class="hover:bg-amber-50/40 transition">
                                            <!-- 1. ID -->
                                            <td class="py-3 px-3.5 pl-4 relative font-mono font-bold text-[var(--mm-900)] text-xs">
                                                <div class="absolute left-1 top-2.5 bottom-2.5 w-1 rounded-full"
                                                     :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-500' : 'bg-rose-500'"></div>
                                                <span x-text="r.id"></span>
                                            </td>

                                            <!-- 2. Tanggal Masuk -->
                                            <td class="py-3 px-3.5 text-gray-700 font-medium">
                                                <span x-text="formatTanggal(r.created_at || r.tanggal_masuk)"></span>
                                            </td>

                                            <!-- 3. Tanggal Keluar -->
                                            <td class="py-3 px-3.5 text-gray-700 font-medium">
                                                <span x-text="formatTanggal(r.tanggal_keluar || r.updated_at)"></span>
                                            </td>

                                            <!-- 4. Nama Orang (Pelapor) -->
                                            <td class="py-3 px-3.5 font-bold text-[var(--mm-800)]" x-text="r.pelapor || '-'"></td>

                                            <!-- 5. Perangkat -->
                                            <td class="py-3 px-3.5">
                                                <p class="font-bold text-[var(--mm-900)] truncate" x-text="r.perangkat"></p>
                                                <span class="inline-block text-[9.5px] text-gray-500" x-text="r.jenis_perangkat || classifyDevice(r.perangkat)"></span>
                                            </td>

                                            <!-- 6. Kendala -->
                                            <td class="py-3 px-3.5 max-w-[200px] truncate text-gray-700" :title="r.kendala" x-text="r.kendala || '-'"></td>

                                            <!-- 7. Keterangan -->
                                            <td class="py-3 px-3.5 max-w-[200px] truncate text-gray-600 italic" :title="r.keterangan_rekap || r.catatan || r.keterangan" x-text="r.keterangan_rekap || r.catatan || r.keterangan || '-'"></td>

                                            <!-- 8. Status -->
                                            <td class="py-3 px-3.5 text-center">
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold inline-block shadow-xs"
                                                    :class="(r.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-red-100 text-red-800 border border-red-300'"
                                                    x-text="r.status"></span>
                                            </td>

                                            <!-- 9. Aksi -->
                                            <td class="py-3 px-3.5 text-center whitespace-nowrap">
                                                <button type="button" @click="openDetail(r)" class="px-3.5 py-1.5 bg-[var(--batik-cream)] hover:bg-[#F3EBD8] text-[var(--mm-800)] border border-[#E3DCC8] rounded-xl text-xs font-bold transition shadow-xs hover:shadow-sm cursor-pointer">
                                                    Detail
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer Pagination Identik dengan Admin -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2 text-xs text-gray-500">
                            <div>
                                Menampilkan <span class="font-bold text-gray-800" x-text="paginatedReports.length > 0 ? ((currentPage - 1) * perPage + 1) : 0"></span> - <span class="font-bold text-gray-800" x-text="Math.min(currentPage * perPage, filteredRecapReports.length)"></span> dari <span class="font-bold text-gray-800" x-text="filteredRecapReports.length"></span> laporan
                            </div>
                            <div class="flex items-center gap-1.5" x-show="totalPages > 1">
                                <button type="button" @click="currentPage--" :disabled="currentPage <= 1"
                                        class="px-3 py-1.5 rounded-xl border border-[#E3DCC8] bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                                    Sebelumnya
                                </button>
                                <span class="px-3 py-1.5 rounded-xl bg-[var(--batik-cream)] font-bold text-xs text-[var(--mm-900)] border border-[#E3DCC8]" x-text="currentPage + ' / ' + totalPages"></span>
                                <button type="button" @click="currentPage++" :disabled="currentPage >= totalPages"
                                        class="px-3 py-1.5 rounded-xl border border-[#E3DCC8] bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition">
                                    Berikutnya
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PAGINATION JIKA ADA -->
                    @if($laporans instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
                        <div class="pt-2">
                            {{ $laporans->links() }}
                        </div>
                    @endif

                    <!-- Spacer Khusus Mobile dengan jarak ideal (persis sama seperti dashboard) -->
                    <div class="block md:hidden w-full pointer-events-none select-none" style="height: 2.25rem;" aria-hidden="true"></div>

                </div>

            </div>
        </main>
    </div>

    <!-- KOMPONEN NAVIGASI BAWAH MOBILE -->
    @include('pelapor.partials.bottom-nav', ['active' => 'rekap'])

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

    <!-- MODAL DETAIL REKAP LAPORAN (MOBILE & DESKTOP BOTTOM SHEET / MODAL) -->
    <div x-show="detailModalOpen"
         x-cloak
         x-transition.opacity
         @keydown.escape.window="detailModalOpen = false"
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/60 p-0 sm:p-4 backdrop-blur-xs"
         role="dialog"
         aria-modal="true">
        <div class="fixed inset-0" @click="detailModalOpen = false"></div>
        <div class="relative z-10 bg-white rounded-t-[28px] rounded-b-none sm:rounded-2xl max-w-lg w-full shadow-2xl border-t border-x sm:border border-[#E3DCC8] h-[82dvh] max-h-[82dvh] sm:h-auto sm:max-h-[85vh] flex flex-col overflow-hidden"
             @click.stop>
            <!-- Header Modal (Pinned Top) -->
            <div class="px-5 sm:px-6 pt-3.5 sm:pt-5 pb-3 border-b border-[#E3DCC8] shrink-0 bg-white">
                <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-2 sm:hidden shrink-0"></div>
                <div class="flex justify-between items-center">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-xs px-2 py-0.5 rounded-md bg-[var(--mm-900)] text-white shadow-2xs tracking-wide" x-text="currentReport?.kode_laporan || currentReport?.id"></span>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold"
                                  :class="(currentReport?.status || '').trim().toLowerCase() === 'selesai' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-red-100 text-red-800 border border-red-300'"
                                  x-text="currentReport?.status"></span>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-1 truncate">Detail rincian arsip perbaikan perangkat</p>
                    </div>
                    <button type="button" @click="detailModalOpen = false" aria-label="Tutup detail" class="text-gray-400 hover:text-gray-600 text-lg leading-none cursor-pointer w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-100 transition shrink-0">✕</button>
                </div>
            </div>

            <!-- Konten Modal (Scrollable Body) -->
            <div class="flex-1 min-h-0 overflow-y-auto px-5 sm:px-6 py-4 space-y-3.5 overscroll-contain"
                 style="touch-action: pan-y; -webkit-overflow-scrolling: touch;">
                <div class="grid grid-cols-2 gap-2.5 text-xs" x-show="currentReport">
                    <div class="bg-[var(--batik-cream)]/50 rounded-xl p-3 col-span-2 border border-[#E3DCC8]/60">
                        <p class="text-[9.5px] text-gray-500 font-semibold uppercase tracking-wider">Unit Perangkat</p>
                        <div class="flex items-center gap-2 mt-0.5">
                            <p class="font-bold text-[var(--ink)] text-xs sm:text-sm capitalize" x-text="currentReport?.perangkat"></p>
                            <span class="px-1.5 py-0.2 rounded text-[8px] font-bold bg-blue-50 text-blue-700 border border-blue-200" x-text="currentReport?.jenis_perangkat || classifyDevice(currentReport?.perangkat)"></span>
                        </div>
                    </div>
                    <div class="bg-[var(--batik-cream)]/50 rounded-xl p-3 border border-[#E3DCC8]/60">
                        <p class="text-[9.5px] text-gray-500 font-semibold uppercase tracking-wider">Tanggal Masuk</p>
                        <p class="font-bold text-[var(--mm-800)] mt-0.5" x-text="formatTanggal(currentReport?.created_at || currentReport?.tanggal_masuk)"></p>
                    </div>
                    <div class="bg-[var(--batik-cream)]/50 rounded-xl p-3 border border-[#E3DCC8]/60">
                        <p class="text-[9.5px] text-gray-500 font-semibold uppercase tracking-wider">Tanggal Selesai / Keluar</p>
                        <p class="font-bold text-gray-800 mt-0.5" x-text="formatTanggal(currentReport?.tanggal_keluar || currentReport?.updated_at)"></p>
                    </div>
                    <div class="bg-[var(--batik-cream)]/50 rounded-xl p-3 border border-[#E3DCC8]/60">
                        <p class="text-[9.5px] text-gray-500 font-semibold uppercase tracking-wider">Alamat / Lokasi</p>
                        <p class="font-bold text-[var(--ink)] mt-0.5 leading-snug break-words" x-text="currentReport?.alamat || currentReport?.kecamatan || '-'"></p>
                    </div>
                    <div class="bg-[var(--batik-cream)]/50 rounded-xl p-3 border border-[#E3DCC8]/60">
                        <p class="text-[9.5px] text-gray-500 font-semibold uppercase tracking-wider">Teknisi Penanggung Jawab</p>
                        <p class="font-bold text-[var(--mm-800)] mt-0.5 leading-snug break-words" x-text="currentReport?.teknisi && currentReport?.teknisi !== '-' ? currentReport?.teknisi : 'Tim SIPERKOM'"></p>
                    </div>
                    <div class="bg-[var(--batik-cream)]/50 rounded-xl p-3 col-span-2 border border-[#E3DCC8]/60">
                        <p class="text-[9.5px] text-gray-500 font-semibold uppercase tracking-wider">Detail Kendala Kerusakan</p>
                        <p class="font-medium text-[var(--ink)] mt-1 leading-relaxed" x-text="currentReport?.kendala || '-'"></p>
                    </div>
                    <div class="bg-[var(--batik-cream)]/50 rounded-xl p-3 col-span-2 border border-[#E3DCC8]/60" x-show="currentReport?.keterangan_rekap || currentReport?.catatan">
                        <p class="text-[9.5px] text-gray-500 font-semibold uppercase tracking-wider">Catatan / Tindakan Teknisi</p>
                        <p class="font-medium text-gray-800 mt-1 leading-relaxed italic" x-text="currentReport?.keterangan_rekap || currentReport?.catatan || '-'"></p>
                    </div>
                </div>
            </div>

            <!-- Footer Tombol Aksi Modal (Mobile-Friendly Bottom Action Bar) -->
            <div class="shrink-0 bg-white border-t border-slate-100 px-5 sm:px-6 pt-2.5 pb-2.5 sm:pb-3.5 shadow-[0_-4px_16px_rgba(0,0,0,0.04)]"
                 style="padding-bottom: max(0.875rem, env(safe-area-inset-bottom, 0.875rem));">
                <button type="button" @click="detailModalOpen = false" class="w-full py-2.5 sm:py-3 px-4 bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 rounded-xl text-xs sm:text-sm font-bold transition flex items-center justify-center cursor-pointer shadow-2xs active:scale-95">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL KONFIRMASI KELUAR AKUN (MODERN & ELEGAN) ================= -->
    <div x-show="logoutModalOpen" 
         x-cloak
         class="fixed inset-0 z-[80] flex items-center justify-center p-4 sm:p-6"
         @keydown.escape.window="closeLogoutModal()">
        
        <!-- Backdrop Blur Gelap -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"
             @click="closeLogoutModal()"
             x-show="logoutModalOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Kartu Modal Konfirmasi -->
        <div class="relative z-10 w-full max-w-xs sm:max-w-sm bg-white rounded-3xl shadow-2xl p-5 sm:p-6 border border-[#E3DCC8] text-center transform transition-all"
             x-show="logoutModalOpen"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 text-[#7A2331] mx-auto flex items-center justify-center shadow-inner mb-3.5">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>

            <h3 class="font-display font-extrabold text-sm sm:text-base text-[var(--mm-900)]">
                Keluar dari Akun?
            </h3>
            <p class="text-[11px] text-gray-500 mt-1.5 leading-relaxed">
                Apakah Anda yakin ingin mengakhiri sesi login akun SIPERKOM di perangkat ini?
            </p>

            <div class="grid grid-cols-2 gap-2 mt-4 pt-1">
                <button type="button" 
                        @click="closeLogoutModal()"
                        class="w-full py-2.5 px-3 bg-gray-100 hover:bg-gray-200 active:scale-95 text-gray-700 rounded-xl text-xs font-bold transition cursor-pointer">
                    Batal
                </button>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" 
                            class="w-full py-2.5 px-3 bg-[#7A2331] hover:bg-[#6A1D2A] active:scale-95 text-white rounded-xl text-xs font-bold transition shadow-sm cursor-pointer border border-white/10 flex items-center justify-center gap-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Ya, Keluar</span>
                    </button>
                </form>
            </div>
        </div>
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
                detailModalOpen: false,
                currentReport: null,
                searchQuery: '',
                statusFilter: 'Semua',
                deviceFilter: 'Semua',
                currentPage: 1,
                perPage: 5,
                reports: Array.isArray(initialReports) ? initialReports : [],
                logoutModalOpen: false,
                openLogoutModal() {
                    this.logoutModalOpen = true;
                },
                closeLogoutModal() {
                    this.logoutModalOpen = false;
                },
                seenRekapIds: JSON.parse(localStorage.getItem('siperkom_seen_rekap_ids') || '[]'),
                toast: { show: false, message: '', type: 'success' },
                mediaModalOpen: false,
                mediaSrc: '',
                mediaType: '',
                mediaFileName: '',
                openDetail(r) {
                    this.currentReport = r;
                    this.detailModalOpen = true;
                },
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
                    this.$watch('sidebarOpen', (value) => {
                        if (value) {
                            document.documentElement.classList.add('overflow-hidden', 'overscroll-none');
                            document.body.classList.add('overflow-hidden', 'overscroll-none');
                        } else if (!this.mediaModalOpen) {
                            document.documentElement.classList.remove('overflow-hidden', 'overscroll-none');
                            document.body.classList.remove('overflow-hidden', 'overscroll-none');
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
                        const tglFormat = d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
                        if (String(tglStr).includes(':')) {
                            const hours = String(d.getHours()).padStart(2, '0');
                            const minutes = String(d.getMinutes()).padStart(2, '0');
                            return `${tglFormat}, ${hours}:${minutes}`;
                        }
                        return tglFormat;
                    }
                    return String(tglStr).substring(0, 16);
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

                // Filter seluruh data berstatus Selesai atau Ditolak milik pelapor
                get recapReports() {
                    return this.reports.filter(r => {
                        const st = (r.status || '').trim().toLowerCase();
                        return st === 'selesai' || st === 'ditolak';
                    });
                },

                getDeviceCount(category) {
                    const list = this.recapReports;
                    if (category === 'Semua') return list.length;
                    return list.filter(r => this.classifyDevice(r.perangkat) === category).length;
                },

                getStatusCount(status) {
                    const list = this.recapReports;
                    if (status === 'Semua') return list.length;
                    return list.filter(r => (r.status || '').trim().toLowerCase() === status.toLowerCase()).length;
                },

                get filteredRecapReports() {
                    const q = (this.searchQuery || '').trim().toLowerCase();
                    return this.recapReports.filter(r => {
                        const matchStatus = this.statusFilter === 'Semua' || (r.status || '').trim().toLowerCase() === this.statusFilter.toLowerCase();
                        const matchDevice = this.deviceFilter === 'Semua' || this.classifyDevice(r.perangkat) === this.deviceFilter;
                        const matchSearch = !q || 
                            (String(r.id || '')).toLowerCase().includes(q) ||
                            (String(r.perangkat || '')).toLowerCase().includes(q) ||
                            (String(r.kendala || '')).toLowerCase().includes(q) ||
                            (String(r.teknisi || '')).toLowerCase().includes(q) ||
                            (String(r.alamat || '')).toLowerCase().includes(q);
                        return matchStatus && matchDevice && matchSearch;
                    });
                },

                get totalPages() {
                    return Math.ceil(this.filteredRecapReports.length / this.perPage) || 1;
                },

                get paginatedReports() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredRecapReports.slice(start, start + this.perPage);
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