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

        [x-cloak] { display: none !important; }

        @media (prefers-reduced-motion: reduce) {
            * { transition-duration: 0.01ms !important; }
        }

        a:focus-visible, button:focus-visible, input:focus-visible, select:focus-visible, textarea:focus-visible {
            outline: 2px solid var(--keraton-gold);
            outline-offset: 2px;
        }

        html {
            -webkit-tap-highlight-color: transparent;
            -webkit-text-size-adjust: 100%;
        }

        button, a, input, select, textarea, [role="button"] {
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }

        /* ============================================================
           SMOOTH ANIMATION SYSTEM — Semua Elemen Pelapor
           Menggunakan GPU-accelerated transform & spring cubic-bezier
        ============================================================ */

        /* --- Global: Semua elemen interaktif pakai GPU layer --- */
        button, a, input, select, textarea,
        .card-smooth, [role="button"] {
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        /* --- Transisi default semua tombol & link --- */
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

        /* --- Tap/press feedback yang fluid --- */
        button:active:not(:disabled),
        a:active,
        .btn-touch:active:not(:disabled) {
            transform: scale(0.96) translateZ(0);
            transition: transform 0.08s ease-out;
        }

        /* --- Kartu statistik & info: hover naik halus --- */
        .rounded-2xl, .rounded-xl {
            transition:
                transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1),
                box-shadow 0.3s ease,
                border-color 0.25s ease,
                background-color 0.25s ease;
        }

        /* --- Hover naik di kartu statistik --- */
        a.rounded-2xl:hover, a.rounded-xl:hover {
            transform: translateY(-2px) translateZ(0);
            box-shadow: 0 8px 24px rgba(7, 37, 68, 0.12);
        }


        /* === MODAL: Entrance smooth dari bawah === */
        .modal-enter {
            animation: modalSlideUp 0.38s cubic-bezier(0.34, 1.2, 0.64, 1) forwards;
        }
        .modal-leave {
            animation: modalSlideDown 0.28s cubic-bezier(0.4, 0, 1, 1) forwards;
        }

        @keyframes modalSlideUp {
            from { opacity: 0; transform: translateY(24px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes modalSlideDown {
            from { opacity: 1; transform: translateY(0) scale(1); }
            to   { opacity: 0; transform: translateY(20px) scale(0.97); }
        }

        /* === NOTIFIKASI DROPDOWN: Smooth scale+fade === */
        [x-show][x-transition] {
            transform-origin: top right;
        }

        /* === TOAST / ALERT: Slide masuk dari kanan === */
        .toast-enter {
            animation: toastIn 0.4s cubic-bezier(0.34, 1.4, 0.64, 1) forwards;
        }
        @keyframes toastIn {
            from { opacity: 0; transform: translateX(60px) scale(0.95); }
            to   { opacity: 1; transform: translateX(0) scale(1); }
        }

        /* === BADGE & COUNTER: Bounce muncul === */
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* === SMOOTH SCROLL untuk konten utama === */
        .scrollable-content {
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
            min-width: 0;
        }

        /* === PAGE LOAD: Fade in halus === */
        @keyframes pageLoad {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        main.pelapor-main-container {
            animation: pageLoad 0.35s ease forwards;
        }

        /* === MENU ITEM SIDEBAR: Hover smooth === */
        nav a {
            transition:
                background-color 0.22s ease,
                color 0.22s ease,
                transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1),
                border-color 0.22s ease !important;
        }
        nav a:hover {
            transform: translateX(3px) translateZ(0);
        }

        /* === AVATAR/PROFIL CARD: Smooth scale === */
        .group:hover .group-hover\:scale-105 {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* === INPUT FOCUS: Smooth border & shadow === */
        input, select, textarea {
            transition:
                border-color 0.22s ease,
                box-shadow 0.22s ease,
                background-color 0.22s ease;
        }

        /* === ICON DALAM TOMBOL: Rotate/scale micro-animation === */
        button svg, a svg {
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        html.overflow-hidden, body.overflow-hidden {
            overflow: hidden !important;
            height: 100% !important;
        }

        /* Reduced motion: hormati preferensi aksesibilitas */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)]"
    x-data="pelaporApp(window.__initialReports)"
    :class="{ 'overflow-hidden touch-none overscroll-none': reportModalOpen || sidebarOpen }">

    <div class="desktop-layout flex flex-row relative min-h-screen md:min-h-0 md:p-3 md:gap-3 bg-[var(--batik-cream)]">

        <!-- ============ SIDEBAR PELAPOR (Hanya Tampil di Desktop / Laptop) ============ -->
        <aside class="hidden md:flex md:static w-[min(18.5rem,calc(100vw-1rem))] mega-mendung-bg flex-col justify-between p-4 md:p-4.5 text-white shrink-0 shadow-2xl h-full overflow-y-auto no-scrollbar select-none mobile-rounded-sidebar border-0 outline-none">

            <!-- BAGIAN ATAS: USER BADGE, MENU -->
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

        <!-- ============ KONTEN UTAMA DASHBOARD ============ -->
        <main class="flex-1 flex flex-col h-full bg-white md:rounded-3xl shadow-sm border border-[#E3DCC8] transition-all"
              :class="(sidebarOpen || reportModalOpen) ? 'overflow-hidden pointer-events-none touch-none select-none' : 'overflow-y-auto scrollable-content'">

            <header class="bg-white/95 backdrop-blur-md border-b border-[#E3DCC8] px-3.5 sm:px-6 py-2.5 sm:py-3.5 sticky top-0 z-30 flex items-center justify-between shadow-[0_2px_12px_rgba(7,37,68,0.04)] gap-2.5 sm:gap-3 shrink-0 md:rounded-t-3xl relative">
                <!-- Garis Aksen Emas Halus di Bawah Header -->
                <div class="absolute bottom-0 left-0 right-0 h-[1.5px] bg-gradient-to-r from-transparent via-[var(--keraton-gold)]/50 to-transparent pointer-events-none"></div>

                <!-- Sisi Kiri: Judul Halaman Bersih & Ringkas -->
                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0 flex-1">
                    <div class="min-w-0">
                        <h2 class="font-display font-bold text-sm sm:text-base text-[var(--mm-900)] tracking-tight truncate">
                            Dashboard Pelapor
                        </h2>
                        <p class="text-[10px] sm:text-xs text-gray-500 font-medium truncate">
                            SIPERKOM Kabupaten Cirebon
                        </p>
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

            <div class="p-3 sm:p-6 lg:p-8 space-y-3.5 sm:space-y-5 flex-1 lg:pb-8">

                <!-- BANNER SELAMAT DATANG MEGA MENDUNG PELAPOR -->
                <div class="mega-mendung-bg p-3.5 sm:p-6 lg:p-8 rounded-2xl shadow-md text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-3 sm:gap-4 relative overflow-hidden border border-[var(--mm-600)]">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                    <!-- KIRI: Identitas & Teks Selamat Datang -->
                    <div class="space-y-1 sm:space-y-1.5 relative z-10 min-w-0">
                        <div class="inline-flex items-center gap-1.5 bg-white/15 border border-white/20 px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[9px] sm:text-[11px] text-[var(--keraton-gold-soft)] font-semibold backdrop-blur-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-[var(--keraton-gold)] shrink-0"></span>
                            <span>🏛️ Portal Resmi SIPERKOM</span>
                        </div>
                        <h2 class="font-display text-lg sm:text-2xl lg:text-3xl font-extrabold tracking-tight break-words">
                            Selamat Datang, {{ Auth::user()->name ?? 'Pelapor' }}!
                        </h2>
                        <p class="text-[10.5px] sm:text-sm text-[var(--mm-200)] max-w-xl leading-relaxed">
                            Layanan terpadu aduan dan perbaikan fasilitas komputer &amp; printer Pemkab Cirebon.
                        </p>
                    </div>

                    <!-- KANAN: Tombol Buat Laporan (Persis Sejajar & Responsif seperti Tombol Banner) -->
                    <div class="relative z-10 shrink-0 w-full md:w-auto flex flex-col items-stretch md:items-end gap-1.5 pt-1 md:pt-0">
                        <button type="button"
                                @click="openReportModal()"
                                :class="activeReportsCount >= 2
                                    ? 'bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 shadow-xs'
                                    : 'bg-[var(--keraton-gold)] hover:bg-[var(--keraton-gold-soft)] text-[var(--mm-900)] shadow-md'"
                                class="w-full md:w-auto justify-center px-4 py-2.5 sm:px-5 sm:py-3 bg-[var(--keraton-gold)] hover:bg-[var(--keraton-gold-soft)] text-[var(--mm-900)] rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer active:scale-98 shadow-md">
                            <template x-if="activeReportsCount < 2">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                    <span>Buat Laporan Kerusakan</span>
                                </span>
                            </template>
                            <template x-if="activeReportsCount >= 2">
                                <span class="flex items-center gap-2 text-amber-900">
                                    <svg class="w-4 h-4 text-amber-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    <span>Antrean Aktif Penuh (2/2)</span>
                                </span>
                            </template>
                        </button>
                        <span x-show="activeReportsCount >= 2" x-cloak class="text-[10px] text-amber-200 font-semibold flex items-center gap-1 justify-center md:justify-end">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse shrink-0"></span>
                            <span>Maks. 2 laporan aktif (anti-spam)</span>
                        </span>
                    </div>
                </div>


                @if(($totalLaporan ?? 0) == 0)
                    <!-- PEMBERITAHUAN KOSONG JIKA BELUM ADA LAPORAN -->
                    <div class="bg-white p-4 sm:p-6 rounded-2xl border border-[#E3DCC8] shadow-xs text-center space-y-2">
                        <div class="w-11 h-11 mx-auto bg-amber-50 border border-amber-200/80 rounded-xl flex items-center justify-center text-amber-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a2 2 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <h4 class="font-display font-bold text-xs sm:text-sm text-[var(--mm-900)]">
                            Belum Ada Pengajuan Laporan
                        </h4>
                        <p class="text-[10px] sm:text-[11px] text-gray-500 max-w-md mx-auto leading-relaxed">
                            Anda belum memiliki riwayat pengaduan fasilitas IT. Silakan klik tombol <strong>Buat Laporan Kerusakan</strong> di atas untuk membuat laporan aduan baru.
                        </p>
                    </div>
                @endif

                <!-- NOTIFIKASI STATUS SELESAI, DITOLAK & MENUNGGU PART UNTUK PELAPOR -->
                @php
                    $sourceNotifs = collect($allUserLaporans ?? $laporans ?? []);
                    $listSelesai = $sourceNotifs->where('status', 'Selesai');
                    $listDitolak = $sourceNotifs->where('status', 'Ditolak');
                    $listMenungguPart = $sourceNotifs->where('status', 'Menunggu Part');
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

                    <!-- KOTAK KONSULTASI WHATSAPP (LENGKAP, JELAS & BEBAS CACAT BORDER) -->
                    <div class="mega-mendung-bg text-white p-3.5 sm:p-4.5 rounded-2xl relative overflow-hidden shadow-md border-0">
                        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                            <!-- Teks Informasi -->
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse shrink-0"></span>
                                    <h4 class="text-xs sm:text-sm font-bold tracking-tight text-white">Butuh Bantuan Mendesak?</h4>
                                </div>
                                <p class="text-[11px] sm:text-xs text-[var(--mm-200)] leading-relaxed">
                                    Hubungi teknisi SIPERKOM via WhatsApp jika Anda membutuhkan konsultasi cepat atau kendala perbaikan darurat.
                                </p>
                            </div>

                            <!-- Tombol WhatsApp Mobile-Friendly -->
                            <div class="shrink-0 w-full sm:w-auto">
                                <a href="https://wa.me/6285321812643?text=Halo%20Admin%20Teknisi%20SIPERKOM,%20saya%20ingin%20konsultasi%20terkait%20perbaikan." 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-950/20 active:scale-[0.97] cursor-pointer border border-emerald-400/30">
                                    <svg class="w-4 h-4 shrink-0 fill-current" viewBox="0 0 24 24">
                                        <path d="M12.012 2c-5.506 0-9.989 4.478-9.99 9.984 0 1.764.459 3.487 1.333 5.006l-1.417 5.176 5.297-1.389c1.474.802 3.136 1.224 4.773 1.225h.004c5.505 0 9.988-4.478 9.989-9.985 0-2.668-1.037-5.176-2.922-7.062a9.923 9.923 0 0 0-7.067-2.955zm5.834 14.161c-.246.692-1.424 1.326-1.964 1.383-.496.052-1.127.078-1.821-.144-.429-.138-.981-.322-1.688-.629-2.978-1.291-4.919-4.321-5.069-4.52-.149-.199-1.218-1.621-1.218-3.092 0-1.471.769-2.195 1.042-2.493.273-.298.595-.372.794-.372.198 0 .397.001.571.01.184.009.432-.07.675.515.248.595.843 2.059.917 2.208.075.149.124.322.025.521-.099.198-.149.322-.298.496-.149.174-.313.388-.447.521-.149.149-.304.312-.131.608.174.298.772 1.274 1.657 2.062 1.139 1.014 2.1 1.327 2.398 1.476.298.149.471.124.645-.075.174-.198.745-.868.943-1.166.198-.298.397-.248.67-.149.273.099 1.737.819 2.035.968.298.149.496.223.57.347.074.124.074.719-.172 1.411z"/>
                                    </svg>
                                    <span>Hubungi via WhatsApp</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Spacer Khusus Mobile dengan jarak ideal (tidak dempet dan tidak bolong) -->
                    <div class="block md:hidden w-full pointer-events-none select-none" style="height: 2.25rem;" aria-hidden="true"></div>

                </div>

            </div>
        </main>

    </div>

    <!-- KOMPONEN NAVIGASI BAWAH MOBILE -->
    @include('pelapor.partials.bottom-nav', ['active' => 'dashboard'])

    <!-- MODAL FORMULIR LAPORAN KERUSAKAN (PREMIUM NATIVE MOBILE BOTTOM SHEET) -->
    <div x-show="reportModalOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="closeReportModal()"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-black/60 backdrop-blur-xs p-0 sm:p-4"
        role="dialog"
        aria-modal="true">
        
        <!-- Backdrop Dimmer Dismiss -->
        <div class="fixed inset-0" @click="closeReportModal()"></div>
        
        <!-- Kartu Modal / Bottom Sheet -->
        <div x-show="reportModalOpen"
            x-transition:enter="transition transform ease-[cubic-bezier(0.16,1,0.3,1)] duration-300"
            x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
            x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
            x-transition:leave="transition transform ease-[cubic-bezier(0.4,0,0.2,1)] duration-200"
            x-transition:leave-start="translate-y-0 sm:scale-100 sm:opacity-100"
            x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
            class="relative z-10 w-full sm:max-w-lg bg-white rounded-t-[28px] rounded-b-none sm:rounded-3xl shadow-2xl border-t border-x border-slate-100 sm:border-[#E3DCC8] sm:border-b flex flex-col max-h-[92vh] sm:max-h-[85vh] overflow-hidden"
            style="touch-action: pan-y;"
            @click.stop>
            
            <!-- Header Modal (Pinned di Atas) -->
            <div class="shrink-0 bg-white px-5 sm:px-6 pt-3 sm:pt-5 pb-3 border-b border-slate-100 select-none">
                <!-- Handle Bar Mobile -->
                <div class="w-12 h-1.5 bg-slate-300 rounded-full mx-auto mb-2.5 sm:hidden"></div>

                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] flex items-center justify-center text-[var(--keraton-gold)] shadow-2xs shrink-0">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> TIKET BARU
                            </span>
                            <h3 class="font-display font-extrabold text-base sm:text-lg text-[var(--mm-900)] leading-tight truncate mt-0.5">Formulir Laporan Kerusakan</h3>
                        </div>
                    </div>
                    <button type="button" @click="closeReportModal()" aria-label="Tutup formulir" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-500 hover:text-slate-800 flex items-center justify-center transition text-sm font-bold cursor-pointer shrink-0">✕</button>
                </div>

                <!-- Peringatan Kuota Laporan Aktif (Anti-Spam) -->
                <div x-show="activeReportsCount >= 2" x-cloak class="mt-2.5 p-2.5 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <strong class="font-bold text-amber-900">Batas Laporan Aktif Tercapai (Maks. 2)</strong>
                        <p class="text-[10.5px] text-amber-800/90 leading-relaxed">Maksimal 2 laporan aktif dalam antrean. Selesaikan laporan yang ada sebelum membuat laporan baru.</p>
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <form @submit.prevent="addReport()" class="flex-1 flex flex-col min-h-0 overflow-hidden text-xs">
                <!-- Area Scrollable Formulir yang Mulus & Responsif -->
                <div class="flex-1 overflow-y-auto px-5 sm:px-6 py-4 space-y-4 overscroll-contain touch-pan-y"
                     style="-webkit-overflow-scrolling: touch; overscroll-behavior: contain;">
                    <!-- Input Jenis Perangkat (Ketik Manual Murni) -->
                    <div class="space-y-1.5">
                        <label for="jenis-perangkat" class="block font-bold text-slate-700 text-xs">Jenis Perangkat <span class="text-rose-500">*</span></label>
                        <input id="jenis-perangkat" type="text" x-model="newReport.jenis" required maxlength="100"
                               placeholder="cth. Laptop / Komputer / Printer / Scanner"
                               @keydown.enter.prevent
                               autocomplete="off"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 sm:py-3 text-xs font-medium focus:bg-white focus:outline-none focus:border-[var(--mm-600)] focus:ring-2 focus:ring-[var(--mm-600)]/15 transition-all text-slate-800 placeholder:text-slate-400">
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
                                    class="py-2.5 px-2 rounded-xl border text-xs font-bold text-center transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                    :class="newReport.prioritas === 'Rendah' ? 'bg-emerald-50 border-emerald-400 text-emerald-800 ring-2 ring-emerald-400/20 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Rendah</span>
                            </button>
                            <button type="button" @click="newReport.prioritas = 'Sedang'"
                                    class="py-2.5 px-2 rounded-xl border text-xs font-bold text-center transition-all flex items-center justify-center gap-1.5 cursor-pointer"
                                    :class="newReport.prioritas === 'Sedang' ? 'bg-amber-50 border-amber-400 text-amber-800 ring-2 ring-amber-400/20 shadow-xs' : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100'">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span>Sedang</span>
                            </button>
                            <button type="button" @click="newReport.prioritas = 'Tinggi'"
                                    class="py-2.5 px-2 rounded-xl border text-xs font-bold text-center transition-all flex items-center justify-center gap-1.5 cursor-pointer"
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
                                    class="w-full py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 active:scale-[0.99] transition-all flex items-center justify-center gap-2 text-slate-600 font-semibold text-xs cursor-pointer bg-slate-50/40">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Ambil Foto atau Pilih dari Galeri</span>
                            </button>
                            <p class="text-[10px] text-slate-400 mt-1">Format: JPG, JPEG, PNG (Maks. 5 MB). Bisa lewat kamera atau galeri.</p>
                        </div>

                        <div x-show="newReport.foto" x-cloak class="relative mt-2">
                            <img :src="newReport.foto" class="w-full max-h-48 object-cover rounded-xl border border-slate-200 shadow-sm" alt="Pratinjau foto kerusakan">
                            <button type="button" @click="clearFile('foto')" aria-label="Hapus foto"
                                    class="absolute top-2 right-2 px-2.5 py-1 rounded-full bg-black/70 text-white text-[11px] font-bold flex items-center gap-1 hover:bg-black/90 shadow cursor-pointer">
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
                                    class="w-full py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 hover:border-[var(--mm-600)] hover:bg-slate-50/80 active:scale-[0.99] transition-all flex items-center justify-center gap-2 text-slate-600 font-semibold text-xs cursor-pointer">
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
                            <button type="button" @click="clearFile('dokumen')" aria-label="Hapus dokumen" class="p-1 rounded-lg text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-colors cursor-pointer">✕</button>
                        </div>
                    </div>

                    <!-- Spacer bawah agar scroll nyaman dan lega -->
                    <div class="h-3"></div>
                </div>

                <!-- Footer Tombol Aksi (Fixed Sticky di Bagian Bawah Layar) -->
                <div class="shrink-0 bg-white/95 backdrop-blur-sm border-t border-slate-100 px-5 sm:px-6 py-3.5 sm:py-4 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] z-20"
                     style="padding-bottom: max(1rem, env(safe-area-inset-bottom, 1rem));">
                    <div class="flex items-center gap-3">
                        <!-- Tombol Batal (1/3 Lebar, Elegan & Mudah Dicapai Jempol) -->
                        <button type="button" @click="closeReportModal()" :disabled="submitting"
                                class="w-1/3 py-3 px-4 bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 rounded-xl font-bold text-xs transition flex items-center justify-center cursor-pointer shadow-2xs">
                            Batal
                        </button>

                        <!-- Tombol Kirim Laporan (2/3 Lebar, Gradient Mega Mendung Mewah & Berisi) -->
                        <button type="submit" :disabled="submitting || activeReportsCount >= 2"
                                :class="activeReportsCount >= 2 
                                    ? 'opacity-50 cursor-not-allowed bg-slate-300 text-slate-500' 
                                    : 'bg-gradient-to-r from-[var(--mm-900)] to-[var(--mm-800)] hover:from-[var(--mm-800)] hover:to-[var(--mm-900)] text-white shadow-lg shadow-[var(--mm-900)]/25 active:scale-[0.98] border border-[var(--keraton-gold)]/50'"
                                class="flex-1 py-3 px-5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-2 cursor-pointer">
                            <svg x-show="submitting" x-cloak class="w-4 h-4 animate-spin shrink-0" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span x-text="submitting ? 'Mengirim Laporan...' : (activeReportsCount >= 2 ? 'Antrean Penuh' : 'Kirim Laporan')"></span>
                            <svg x-show="!submitting && activeReportsCount < 2" class="w-4 h-4 text-[var(--keraton-gold)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL KAMERA LANGSUNG (WEBCAM LAPTOP / KAMERA HP) ================= -->
    <div x-show="cameraModalOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.escape.window="stopCamera()"
        class="fixed inset-0 z-[70] flex items-center justify-center bg-black/85 backdrop-blur-md p-3 sm:p-6"
        role="dialog"
        aria-modal="true">
        
        <div class="relative w-full max-w-lg bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border border-white/20 flex flex-col max-h-[92vh]">
            <!-- Header Kamera -->
            <div class="px-4 py-3 bg-slate-800/80 border-b border-white/10 flex items-center justify-between text-white shrink-0">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                    <h3 class="font-bold text-xs sm:text-sm">Kamera Laptop / Perangkat</h3>
                </div>
                <button type="button" @click="stopCamera()" class="p-1 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition cursor-pointer" aria-label="Tutup kamera">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Viewfinder Area -->
            <div class="relative bg-black flex-1 flex items-center justify-center min-h-[260px] sm:min-h-[340px] overflow-hidden">
                <!-- Video Stream Element -->
                <video x-ref="videoPreview" autoplay playsinline muted class="w-full h-full object-cover"></video>

                <!-- Loading State -->
                <div x-show="cameraLoading" class="absolute inset-0 bg-slate-900 flex flex-col items-center justify-center text-white gap-2">
                    <svg class="w-7 h-7 animate-spin text-[var(--keraton-gold)]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-300">Menghubungkan kamera...</span>
                </div>

                <!-- Error State (Izin ditolak atau tidak ada webcam) -->
                <div x-show="cameraError" x-cloak class="absolute inset-0 bg-slate-900 p-6 flex flex-col items-center justify-center text-center text-white gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-rose-300 mb-1">Akses Kamera Gagal</h4>
                        <p class="text-xs text-slate-400 max-w-xs" x-text="cameraError"></p>
                    </div>
                    <button type="button" @click="stopCamera()" class="mt-2 px-4 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold transition cursor-pointer">Tutup</button>
                </div>

                <!-- Viewfinder Guideline / Focus Ring -->
                <div x-show="!cameraLoading && !cameraError" class="pointer-events-none absolute inset-6 sm:inset-10 border-2 border-white/30 rounded-2xl flex items-center justify-center">
                    <div class="w-8 h-8 border border-white/20 rounded-full"></div>
                </div>
            </div>

            <!-- Footer Kontrol Jepret -->
            <div x-show="!cameraError" class="p-4 bg-slate-950 flex items-center justify-center gap-4 shrink-0 border-t border-white/10">
                <button type="button" @click="stopCamera()" class="px-4 py-2.5 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 text-xs font-bold transition cursor-pointer">
                    Batal
                </button>
                <button type="button" @click="capturePhoto()" 
                        class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-[var(--keraton-gold)] to-[var(--keraton-gold-soft)] text-[var(--mm-900)] font-extrabold text-xs sm:text-sm shadow-lg shadow-[var(--keraton-gold)]/20 active:scale-95 transition flex items-center gap-2 cursor-pointer ring-4 ring-white/20">
                    <svg class="w-4 h-4 text-[var(--mm-900)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                        <circle cx="12" cy="12" r="3" fill="currentColor"/>
                    </svg>
                    <span>Jepret Foto</span>
                </button>
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


    <script>
        window.__initialReports = {!! json_encode($defaultReports instanceof \Illuminate\Support\Collection ? $defaultReports->values()->all() : array_values($defaultReports ?? [])) !!};
        window.__activeReportsCount = {{ $activeReportsCount ?? 0 }};
        window.__flashSuccess = @json(session('success'));
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
                cameraModalOpen: false,
                cameraLoading: false,
                cameraError: null,
                cameraStream: null,
                capturedFile: null,
                newReport: emptyReportForm(),
                logoutModalOpen: false,
                openLogoutModal() {
                    this.logoutModalOpen = true;
                },
                closeLogoutModal() {
                    this.logoutModalOpen = false;
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
                            document.documentElement.classList.add('overflow-hidden');
                            document.body.classList.add('overflow-hidden');
                        } else if (!this.reportModalOpen) {
                            document.documentElement.classList.remove('overflow-hidden');
                            document.body.classList.remove('overflow-hidden');
                        }
                    });
                    this.$watch('reportModalOpen', (value) => {
                        if (value) {
                            document.documentElement.classList.add('overflow-hidden');
                            document.body.classList.add('overflow-hidden');
                        } else if (!this.sidebarOpen) {
                            document.documentElement.classList.remove('overflow-hidden');
                            document.body.classList.remove('overflow-hidden');
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

                    // Tampilkan toast notif jika ada pesan sukses dari server (misal setelah reset sandi)
                    if (window.__flashSuccess) {
                        this.$nextTick(() => {
                            this.showToast(window.__flashSuccess, 'success');
                        });
                        window.__flashSuccess = null;
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
                    this.stopCamera();
                    this.reportModalOpen = false;
                },

                async openCamera() {
                    this.cameraError = null;
                    this.cameraLoading = true;
                    this.cameraModalOpen = true;
                    try {
                        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                            throw new Error('Browser Anda tidak mendukung akses kamera langsung.');
                        }
                        const stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } },
                            audio: false
                        });
                        this.cameraStream = stream;
                        this.$nextTick(() => {
                            if (this.$refs.videoPreview) {
                                this.$refs.videoPreview.srcObject = stream;
                                this.$refs.videoPreview.play().catch(() => {});
                            }
                        });
                    } catch (err) {
                        console.error('Camera error:', err);
                        this.cameraError = (err.name === 'NotAllowedError' || err.name === 'PermissionDeniedError')
                            ? 'Izin kamera ditolak. Silakan izinkan akses kamera di pengaturan browser Anda.' 
                            : (err.message || 'Tidak dapat mengakses kamera.');
                    } finally {
                        this.cameraLoading = false;
                    }
                },

                stopCamera() {
                    if (this.cameraStream) {
                        this.cameraStream.getTracks().forEach(track => track.stop());
                        this.cameraStream = null;
                    }
                    this.cameraModalOpen = false;
                },

                capturePhoto() {
                    const video = this.$refs.videoPreview;
                    if (!video) return;
                    const canvas = document.createElement('canvas');
                    canvas.width = video.videoWidth || 640;
                    canvas.height = video.videoHeight || 480;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    canvas.toBlob((blob) => {
                        if (!blob) return;
                        const file = new File([blob], 'foto-kamera-' + Date.now() + '.jpg', { type: 'image/jpeg' });
                        this.capturedFile = file;
                        this.newReport.foto = canvas.toDataURL('image/jpeg', 0.85);
                        if (this.$refs.fotoInput) this.$refs.fotoInput.value = '';
                        this.stopCamera();
                        this.showToast('Foto berhasil diambil dari kamera!', 'success');
                    }, 'image/jpeg', 0.85);
                },

                clearFile(target) {
                    if (target === 'foto') {
                        this.newReport.foto = null;
                        this.capturedFile = null;
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
                            this.capturedFile = null;
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

                    if (!this.newReport.jenis || !this.newReport.jenis.trim() || !this.newReport.perangkat.trim() || !this.newReport.kendala.trim()) {
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
                    formData.append('jenis', this.newReport.jenis.trim());
                    formData.append('perangkat', this.newReport.perangkat.trim());
                    formData.append('kendala', this.newReport.kendala.trim());
                    formData.append('prioritas', this.newReport.prioritas);
                    formData.append('dokumen', dokumenInput.files[0]);

                    const fotoInput = this.$refs.fotoInput;
                    if (this.capturedFile) {
                        formData.append('foto', this.capturedFile);
                    } else if (fotoInput && fotoInput.files[0]) {
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
                            this.capturedFile = null;
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