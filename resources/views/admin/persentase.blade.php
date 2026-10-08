@php
    // Data laporan ditarik dari database melalui Controller
    $defaultReports = $defaultReports ?? [];
    $allYearReports = $allYearReports ?? [];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Persentase Kerusakan & Analisis Data | SIPERKOM Kabupaten Cirebon</title>

    <link rel="icon" href="{{ asset('images/cirebon.png') }}" type="image/png">
    @include('partials.assets')
    @include('partials.session-guard')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js CDN untuk Grafik Interaktif -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        @keyframes growBar { 
            0% { width: 0 !important; } 
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

        .mega-mendung-card {
            background-color: var(--mm-900);
            background-image: 
                linear-gradient(to right, rgba(7, 37, 68, 0.90), rgba(11, 61, 110, 0.82)),
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

        ::-webkit-scrollbar { width: 6px; height: 6px; } 
        ::-webkit-scrollbar-track { background: #F1ECE1; } 
        ::-webkit-scrollbar-thumb { background: var(--mm-600); border-radius: 10px; }
        
        [x-cloak] { display: none !important; }
        .donut-container {
            width: 120px;
            height: 120px;
            min-width: 120px;
            min-height: 120px;
            position: relative;
            flex-shrink: 0;
        }
        @media (min-width: 640px) {
            .donut-container {
                width: 136px;
                height: 136px;
                min-width: 136px;
                min-height: 136px;
            }
        }
        .donut-chart-circle {
            width: 100%;
            height: 100%;
            border-radius: 9999px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            transition: background 0.6s ease;
        }
        .donut-hole {
            position: absolute;
            top: 20%;
            left: 20%;
            right: 20%;
            bottom: 20%;
            background: #ffffff;
            border-radius: 9999px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
        }
        .fade-up { animation: fadeUp 0.45s ease both; }
        .grow-bar { animation: growBar 1s ease-out; }
        .chip { transition: all .18s ease; }
        .chip:hover { transform: translateY(-1px); }
        
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

        /* ================= KEBUTUHAN CETAK / EXPORT PDF ================= */
        .print-only { display: none; }

        @media print {
            @page {
                size: A4 landscape;
                margin: 8mm 10mm 8mm 10mm;
            }
            
            /* Sembunyikan elemen antarmuka layar & komponen grafik persentase */
            aside, header, nav, .no-print, .screen-only, [x-cloak] {
                display: none !important;
            }
            
            /* Tampilkan area khusus laporan PDF resmi */
            .print-only {
                display: block !important;
                visibility: visible !important;
            }

            html, body {
                background: #ffffff !important;
                color: #111827 !important;
                font-family: 'Inter', Arial, sans-serif !important;
                font-size: 8pt !important;
                line-height: 1.35 !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                height: auto !important;
                overflow: visible !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .desktop-layout {
                display: block !important;
                padding: 0 !important;
                margin: 0 !important;
                min-height: auto !important;
                background: #ffffff !important;
                overflow: visible !important;
            }

            main {
                display: block !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
                height: auto !important;
                overflow: visible !important;
                background: #ffffff !important;
            }

            /* Kop Surat Resmi Kabupaten Cirebon */
            .kop-surat {
                margin-bottom: 8px;
                padding-bottom: 2px;
            }
            .kop-table {
                width: 100%;
                border-collapse: collapse;
                border: none;
            }
            .kop-table td {
                border: none;
                padding: 0;
                vertical-align: middle;
            }
            .kop-logo {
                width: 60px;
                text-align: center;
                padding-right: 12px;
            }
            .kop-logo img {
                width: 52px;
                height: auto;
                object-fit: contain;
            }
            .kop-text {
                text-align: center;
            }
            .kop-instansi-1 {
                font-size: 10.5pt;
                font-weight: 700;
                color: #072544;
                margin: 0;
                letter-spacing: 0.8px;
                text-transform: uppercase;
            }
            .kop-instansi-2 {
                font-size: 13pt;
                font-weight: 800;
                color: #0B3D6E;
                margin: 1px 0 0 0;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }
            .kop-instansi-3 {
                font-size: 9.5pt;
                font-weight: 700;
                color: #997514;
                margin: 1px 0 0 0;
                letter-spacing: 0.5px;
            }
            .kop-alamat {
                font-size: 7.5pt;
                color: #4b5563;
                margin: 2px 0 0 0;
            }
            .kop-kontak {
                font-size: 7pt;
                color: #6b7280;
                margin: 1px 0 0 0;
            }
            .kop-line-thick {
                height: 2.5px;
                background-color: #072544;
                margin-top: 5px;
            }
            .kop-line-thin {
                height: 1px;
                background-color: #C9A227;
                margin-top: 1.5px;
                margin-bottom: 8px;
            }

            /* Judul Laporan */
            .doc-header {
                text-align: center;
                margin-bottom: 8px;
            }
            .doc-title {
                font-size: 11pt;
                font-weight: 800;
                color: #072544;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin: 0;
            }
            .doc-period {
                font-size: 8.5pt;
                font-weight: 600;
                color: #374151;
                margin-top: 2px;
            }

            /* Ringkasan Metadata Dokumen */
            .doc-meta-box {
                background-color: #f8fafc;
                border: 1px solid #cbd5e1;
                border-radius: 4px;
                padding: 4px 8px;
                margin-bottom: 8px;
            }
            .meta-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 7.5pt;
            }
            .meta-table td {
                border: none;
                padding: 2px 4px;
            }
            .meta-label {
                color: #64748b;
                width: 130px;
                font-weight: 600;
            }
            .meta-val {
                color: #1e293b;
                font-weight: 600;
            }

            /* Ringkasan Eksekutif Hasil Analisis untuk Cetak */
            .print-summary-box {
                background-color: #f8fafc;
                border: 1px solid #cbd5e1;
                border-left: 3.5px solid #0B3D6E;
                border-radius: 4px;
                padding: 5px 8px;
                margin-bottom: 7px;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .print-summary-title {
                font-size: 6.8pt;
                font-weight: 800;
                color: #0B3D6E;
                text-transform: uppercase;
                letter-spacing: 0.4px;
                display: block;
                margin-bottom: 2px;
            }
            .print-summary-text {
                font-size: 7.5pt;
                color: #1e293b;
                margin: 0;
                line-height: 1.35;
            }

            /* 4 Indikator Utama Analisis untuk Cetak (Sesuai Layar) */
            .print-kpi-grid {
                display: flex;
                gap: 8px;
                margin-bottom: 8px;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .print-kpi-card {
                flex: 1;
                background-color: #ffffff;
                border: 1px solid #cbd5e1;
                border-radius: 4px;
                padding: 4px 7px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .print-kpi-lbl {
                font-size: 6pt;
                font-weight: 700;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }
            .print-kpi-val {
                font-size: 8.5pt;
                font-weight: 800;
                color: #072544;
                margin-top: 1px;
            }
            .text-gold { color: #b45309 !important; }
            .text-blue { color: #1d4ed8 !important; }
            .text-emerald { color: #15803d !important; }

            /* Grid Analisis Persentase & Tren untuk Cetak PDF */
            .print-analytics-grid {
                display: flex;
                flex-direction: row;
                gap: 10px;
                margin-bottom: 10px;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            .print-analytics-card {
                flex: 1;
                background-color: #ffffff;
                border: 1px solid #cbd5e1;
                border-radius: 6px;
                padding: 7px 9px;
            }
            .print-card-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                border-bottom: 1px solid #e2e8f0;
                padding-bottom: 4px;
                margin-bottom: 6px;
            }
            .print-card-title {
                font-size: 8pt;
                font-weight: 800;
                color: #072544;
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }
            .print-card-subtitle {
                font-size: 6.8pt;
                color: #64748b;
                margin: 1px 0 0 0;
            }
            .print-badge-total, .print-badge-year {
                font-size: 7pt;
                background-color: #f1f5f9;
                border: 1px solid #cbd5e1;
                padding: 1.5px 6px;
                border-radius: 4px;
                white-space: nowrap;
            }
            .print-badge-label {
                color: #64748b;
                margin-right: 2px;
            }
            .print-badge-val {
                color: #0B3D6E;
                font-weight: 800;
            }

            /* List Perangkat */
            .print-device-list {
                display: flex;
                flex-direction: column;
                gap: 4.5px;
            }
            .print-device-item {
                font-size: 7.2pt;
            }
            .print-device-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2px;
            }
            .print-device-name {
                font-weight: 700;
                color: #1e293b;
            }
            .print-device-pct {
                font-weight: 700;
                color: #0B3D6E;
                font-size: 7pt;
            }
            .print-progress-bg {
                width: 100%;
                height: 5px;
                background-color: #f1f5f9;
                border-radius: 3px;
                overflow: hidden;
                border: 1px solid #e2e8f0;
            }
            .print-progress-bar {
                height: 100%;
                border-radius: 3px;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Tren Bulanan */
            .print-trend-container {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }
            .print-trend-bars {
                display: flex;
                align-items: flex-end;
                height: 46px;
                gap: 3px;
                padding: 4px 2px 2px 2px;
                border-bottom: 1px solid #e2e8f0;
            }
            .print-trend-col {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                height: 100%;
                justify-content: flex-end;
            }
            .print-bar-val {
                font-size: 6pt;
                font-weight: 800;
                color: #072544;
                margin-bottom: 1.5px;
            }
            .print-bar-track {
                width: 100%;
                max-width: 14px;
                flex: 1;
                display: flex;
                align-items: flex-end;
                background-color: #f8fafc;
                border-radius: 2px;
            }
            .print-bar-fill {
                width: 100%;
                background-color: #7A2331 !important;
                border-radius: 2px 2px 0 0;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .print-bar-lbl {
                font-size: 5.8pt;
                color: #64748b;
                margin-top: 2px;
                font-weight: 600;
            }
            .print-trend-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 2px;
                font-size: 6.5pt;
                text-align: center;
            }
            .print-trend-table th {
                padding: 1.5px 1px;
                background-color: #f1f5f9;
                color: #475569;
                font-weight: 700;
                border: 1px solid #e2e8f0;
                font-size: 6pt;
            }
            .print-trend-table td {
                padding: 2px 1px;
                border: 1px solid #e2e8f0;
                font-size: 6.5pt;
            }
            .text-navy { color: #0B3D6E; font-weight: 800; }
            .text-gray { color: #94a3b8; }
            .print-empty-note { font-size: 7pt; color: #94a3b8; text-align: center; padding: 12px 0; }
        }
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)]"
    x-data="persentaseApp(window.__initialReports)"
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

        <!-- ============ KONTEN UTAMA ============ -->
        <main class="flex-1 flex flex-col h-full bg-white lg:rounded-3xl shadow-sm border border-[#E3DCC8] transition-all"
              :class="sidebarOpen ? 'overflow-hidden pointer-events-none touch-none select-none' : 'overflow-y-auto scrollable-content'">

            <!-- Top Header -->
            <header class="bg-white border-b border-[#E3DCC8] px-3 sm:px-6 py-2.5 sm:py-3.5 sticky top-0 z-30 flex items-center justify-between shadow-2xs gap-2 sm:gap-3 shrink-0 lg:rounded-t-3xl">
                <div class="flex min-w-0 flex-1 items-center gap-2 sm:gap-3">
                    <button type="button" @click="sidebarOpen = true" aria-label="Buka menu" class="lg:hidden w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] text-[var(--mm-900)] shrink-0 hover:bg-[#F3EBD8] active:scale-95 transition flex items-center justify-center shadow-2xs">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <h2 class="font-display font-bold text-[11px] leading-tight sm:text-sm text-[var(--mm-900)]">Analisis Data & Persentase Kerusakan</h2>
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

                    <!-- Tombol Cetak Laporan -->
                    <button type="button" @click="window.print()" title="Cetak Laporan Resmi / Simpan sebagai PDF" 
                            class="no-print inline-flex items-center gap-1.5 text-xs font-bold text-white bg-gradient-to-r from-[var(--mm-800)] to-[var(--mm-900)] hover:from-[var(--mm-900)] hover:to-[#031526] border border-[var(--keraton-gold)]/40 px-2.5 sm:px-3.5 py-2 rounded-xl shadow-sm hover:shadow transition-all duration-200 active:scale-95 cursor-pointer shrink-0">
                        <svg class="w-4 h-4 text-[var(--keraton-gold-soft)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1zm8-12V5a1 1 0 00-1-1H8a1 1 0 00-1 1v4h10z"/>
                        </svg>
                        <span class="hidden sm:inline">Cetak Laporan</span>
                        <span class="sm:hidden text-[10px]">Cetak</span>
                    </button>

                    <div class="hidden sm:block w-[78px] shrink-0 border-l border-[#E3DCC8] pl-2 sm:w-auto sm:pl-3 text-right">
                        <p class="text-xs font-bold text-[var(--mm-900)]">{{ date('d M Y') }}</p>
                        <p class="text-[10px] text-gray-500">Kabupaten Cirebon</p>
                    </div>
                </div>
            </header>

            <!-- Isi Halaman Analisis (Khusus Tampilan Layar Web) -->
            <div class="screen-only p-3.5 sm:p-6 lg:p-8 space-y-4 sm:space-y-6 flex-1 pb-16">

                <!-- FORM FILTER BULAN & TAHUN -->
                <div class="bg-white p-3.5 sm:p-5 rounded-2xl shadow-sm border border-[#E3DCC8] relative z-20">
                    @php
                        $monthsIndonesian = [
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
                            '04' => 'April',   '05' => 'Mei',      '06' => 'Juni', 
                            '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September', 
                            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ];
                        $bulanPilih = request('bulan', date('m'));
                        $tahunPilih = (string) request('tahun', date('Y'));
                    @endphp
                    <form method="GET" action="{{ route('admin.persentase') }}" 
                          class="grid grid-cols-2 sm:flex sm:items-end gap-2.5 sm:gap-4"
                          x-data="{
                              bulanOpen: false,
                              tahunOpen: false,
                              selectedBulan: '{{ $bulanPilih }}',
                              selectedBulanName: '{{ $monthsIndonesian[$bulanPilih] ?? 'September' }}',
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
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Bulan:</label>
                            <button type="button" 
                                    @click="bulanOpen = !bulanOpen; tahunOpen = false" 
                                    :class="bulanOpen ? 'border-[var(--keraton-gold)] ring-2 ring-[var(--keraton-gold)]/20 bg-amber-50/50' : 'border-[#E3DCC8] bg-[var(--batik-cream)]/60 hover:bg-[var(--batik-cream)]'"
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
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Pilih Tahun:</label>
                            <button type="button" 
                                    @click="tahunOpen = !tahunOpen; bulanOpen = false" 
                                    :class="tahunOpen ? 'border-[var(--keraton-gold)] ring-2 ring-[var(--keraton-gold)]/20 bg-amber-50/50' : 'border-[#E3DCC8] bg-[var(--batik-cream)]/60 hover:bg-[var(--batik-cream)]'"
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
                                Tampilkan Histori
                            </button>
                            @if($bulanPilih != date('m') || $tahunPilih != date('Y'))
                                <a href="{{ route('admin.persentase') }}" title="Kembali ke Bulan Berjalan" class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-300 text-xs font-bold rounded-xl transition shadow-xs whitespace-nowrap">
                                    Bulan Ini
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- ================= STATE KOSONG (JIKA BELUM ADA LAPORAN PADA PERIODE INI) ================= -->
                <div x-show="filteredReports.length === 0" x-cloak class="bg-white p-8 sm:p-14 rounded-3xl border border-[#E3DCC8] text-center shadow-sm space-y-3">
                    <div class="relative w-20 h-20 mx-auto mb-3">
                        <div class="w-20 h-20 bg-gradient-to-br from-amber-50 to-orange-50 rounded-3xl flex items-center justify-center text-amber-600 border border-amber-200/80 shadow-lg ring-4 ring-amber-50/60">
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="font-display font-bold text-base text-[var(--mm-900)]">Belum Ada Analisis Persentase</h4>
                    <p class="text-xs text-gray-500 max-w-md mx-auto">
                        Data analisis dan persentase kerusakan akan otomatis muncul di halaman ini setelah ada tiket laporan yang tercatat pada periode bulan dan tahun yang dipilih.
                    </p>
                </div>

                <!-- ================= KONTEN ANALISIS & GRAFIK (HANYA TAMPIL JIKA ADA LAPORAN) ================= -->
                <div x-show="filteredReports.length > 0" x-cloak class="space-y-4 sm:space-y-6">
                    <!-- RINGKASAN EKSEKUTIF OTOMATIS DENGAN BATIK MEGA MENDUNG -->
                    <div class="fade-up mega-mendung-card text-white p-5 sm:p-6 rounded-2xl shadow-md flex items-start gap-4 border border-[var(--mm-600)]">
                        <div class="w-11 h-11 shrink-0 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-5 h-5 text-[var(--keraton-gold-soft)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[var(--mm-200)]">Ringkasan Eksekutif</p>
                            <p class="text-xs sm:text-sm leading-relaxed mt-1" x-text="executiveSummary"></p>
                        </div>
                    </div>

                    <!-- KARTU INSIGHT OTOMATIS -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
                        <div class="fade-up bg-white p-3.5 sm:p-5 rounded-2xl shadow-sm border border-[#E3DCC8] flex items-center gap-2.5 sm:gap-3.5">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-[var(--mm-800)]/10 flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--mm-800)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Laporan</p>
                                <p class="text-base sm:text-lg font-extrabold font-display text-[var(--mm-900)]" x-text="filteredReports.length"></p>
                            </div>
                        </div>

                        <div class="fade-up bg-white p-3.5 sm:p-5 rounded-2xl shadow-sm border border-[#E3DCC8] flex items-center gap-2.5 sm:gap-3.5" style="animation-delay:.05s">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-[var(--keraton-gold)]/15 flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--keraton-gold)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-400">Terbanyak</p>
                                <p class="text-xs sm:text-sm font-extrabold font-display text-[var(--mm-900)] truncate" x-text="topDevice"></p>
                            </div>
                        </div>

                        <div class="fade-up bg-white p-3.5 sm:p-5 rounded-2xl shadow-sm border border-[#E3DCC8] flex items-center gap-2.5 sm:gap-3.5" style="animation-delay:.1s">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-blue-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-400">Status Top</p>
                                <p class="text-xs sm:text-sm font-extrabold font-display text-[var(--mm-900)] truncate" x-text="topStatus"></p>
                            </div>
                        </div>

                        <div class="fade-up bg-white p-3.5 sm:p-5 rounded-2xl shadow-sm border border-[#E3DCC8] flex items-center gap-2.5 sm:gap-3.5" style="animation-delay:.15s">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-gray-400">Selesai</p>
                                <p class="text-base sm:text-lg font-extrabold font-display text-[var(--mm-900)]" x-text="completionRate + '%'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik Persentase Jenis Perangkat (Kompak & Elegan) -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-sm border border-[#E3DCC8] space-y-3.5">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-display text-sm sm:text-base font-bold text-[var(--mm-900)]">Persentase Kerusakan Perangkat</h3>
                                <span x-show="activeStatus !== 'all'" class="text-[9px] font-bold uppercase tracking-wider text-[var(--mm-800)] bg-[var(--mm-800)]/10 border border-[var(--mm-800)]/20 px-2 py-0.5 rounded-full" x-text="'Terfilter: ' + activeStatus"></span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-0.5">
                                Grafik ini menunjukkan sebaran jenis perangkat yang paling sering dilaporkan mengalami kerusakan.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-7 pt-1">
                            <div class="donut-container mx-auto sm:mx-0">
                                <div class="donut-chart-circle" :style="'background: conic-gradient(' + deviceChartGradient + ')'"></div>

                                <div class="donut-hole">
                                    <p class="text-xl sm:text-2xl font-extrabold font-display text-[var(--mm-900)] leading-none" x-text="stats.total"></p>
                                    <p class="text-[9px] sm:text-[10px] text-gray-400 font-semibold mt-0.5">Total Laporan</p>
                                </div>
                            </div>

                            <div class="flex-1 w-full min-w-0 space-y-2 sm:space-y-2.5">
                                <template x-for="cat in deviceStats" :key="cat.label">
                                    <div>
                                        <div class="flex items-center justify-between mb-1">
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <span class="w-5 h-5 rounded-md shrink-0 flex items-center justify-center text-white text-[10px]" :style="'background:' + cat.color" x-html="cat.icon"></span>
                                                <span class="text-[11.5px] sm:text-xs font-bold text-[var(--ink)] truncate" x-text="cat.label"></span>
                                            </div>
                                            <span class="text-[11px] font-bold text-[var(--mm-800)] shrink-0 ml-2" x-text="cat.percent + '% (' + cat.count + ' Laporan)'"></span>
                                        </div>
                                        <div class="w-full h-1.5 sm:h-2 bg-[var(--batik-cream)] rounded-full overflow-hidden border border-[#E3DCC8]/80">
                                            <div class="h-full rounded-full grow-bar" :style="'width:' + cat.percent + '%; background:' + cat.color"></div>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="deviceStats.length === 0" class="text-gray-400 text-xs py-3 text-center">
                                    Belum ada data laporan untuk dianalisis pada bulan/tahun ini.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TREN BULANAN & ANALYTICS MENGGUNAKAN CHART.JS (Kompak & Rapi) -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-xs border border-[#E3DCC8] space-y-3.5">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <div>
                                <h3 class="font-display text-sm sm:text-base font-bold text-[var(--mm-900)]">Tren Laporan &amp; Analytics Bulanan</h3>
                                <p class="text-[11px] text-gray-500 mt-0.5 max-w-xl">
                                    Perbandingan jumlah laporan kerusakan yang masuk setiap bulannya secara visual profesional.
                                </p>
                            </div>
                            <span class="text-[10.5px] font-bold text-[var(--mm-800)] bg-[var(--batik-cream)] border border-[#E3DCC8] px-2.5 py-1 rounded-lg" x-text="'Tahun: ' + selectedTahun"></span>
                        </div>

                        <div class="relative w-full h-44 sm:h-52 pt-1">
                            <canvas id="trenBulananChart"></canvas>
                        </div>
                    </div>
                </div>



            </div>

            <!-- ================= DOKUMEN CETAK LAPORAN RESMI (PDF / PRINT ONLY) ================= -->
            <!-- Bagian ini khusus muncul saat klik tombol Cetak Laporan (window.print()) atau export PDF -->
            <div class="print-only">
                <!-- 1. KOP SURAT RESMI KEDINASAN PEMERINTAH KABUPATEN CIREBON -->
                <div class="kop-surat">
                    <table class="kop-table">
                        <tr>
                            <td class="kop-logo">
                                <img src="{{ asset('images/cirebon.png') }}" alt="Logo Kabupaten Cirebon">
                            </td>
                            <td class="kop-text">
                                <h3 class="kop-instansi-1">PEMERINTAH KABUPATEN CIREBON</h3>
                                <h2 class="kop-instansi-2">DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL</h2>
                                <h1 class="kop-instansi-3">SISTEM INFORMASI PELAPORAN KERUSAKAN (SIPERKOM)</h1>
                                <p class="kop-alamat">Jl. Sunan Muria No. 4, Sumber, Kec. Sumber, Kabupaten Cirebon, Jawa Barat 45611</p>
                                <p class="kop-kontak">Website: cirebonkab.go.id | Email: disdukcapil@cirebonkab.go.id</p>
                            </td>
                        </tr>
                    </table>
                    <div class="kop-line-thick"></div>
                    <div class="kop-line-thin"></div>
                </div>

                <!-- 2. JUDUL DOKUMEN & PERIODE -->
                <div class="doc-header">
                    <h2 class="doc-title">LAPORAN PERSENTASE &amp; ANALITIK KERUSAKAN PERANGKAT IT</h2>
                    <p class="doc-period">
                        Periode: <span x-text="namaBulanIndo + ' ' + selectedTahun"></span>
                    </p>
                </div>

                <!-- 3. KOTAK INFORMASI & METADATA RESMI -->
                <div class="doc-meta-box">
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">Tanggal / Jam Cetak:</td>
                            <td class="meta-val">{{ Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</td>
                            <td class="meta-label">Total Tiket Laporan:</td>
                            <td class="meta-val"><b x-text="filteredReports.length + ' Laporan'"></b></td>
                        </tr>
                        <tr>
                            <td class="meta-label">Petugas Administrasi:</td>
                            <td class="meta-val">{{ Auth::user()->name ?? 'Administrator SIPERKOM' }} ({{ ucfirst(Auth::user()->role ?? 'Admin') }})</td>
                            <td class="meta-label">Lingkup Data:</td>
                            <td class="meta-val"><span>Sebaran Kerusakan &amp; Tren Bulanan</span></td>
                        </tr>
                    </table>
                </div>

                <!-- 4. RINGKASAN EKSEKUTIF ANALISIS (SESUAI LAYAR) -->
                <div class="print-summary-box">
                    <span class="print-summary-title">Ringkasan Eksekutif Analisis</span>
                    <p class="print-summary-text" x-text="executiveSummary"></p>
                </div>

                <!-- 5. 4 INDIKATOR UTAMA ANALISIS (SESUAI KARTU LAYAR) -->
                <div class="print-kpi-grid">
                    <div class="print-kpi-card">
                        <span class="print-kpi-lbl">Total Laporan</span>
                        <span class="print-kpi-val" x-text="filteredReports.length + ' Laporan'"></span>
                    </div>
                    <div class="print-kpi-card">
                        <span class="print-kpi-lbl">Perangkat Terbanyak</span>
                        <span class="print-kpi-val text-gold" x-text="topDevice"></span>
                    </div>
                    <div class="print-kpi-card">
                        <span class="print-kpi-lbl">Status Top</span>
                        <span class="print-kpi-val text-blue" x-text="topStatus"></span>
                    </div>
                    <div class="print-kpi-card">
                        <span class="print-kpi-lbl">Tingkat Selesai</span>
                        <span class="print-kpi-val text-emerald" x-text="completionRate + '%'"></span>
                    </div>
                </div>

                <!-- 6. RINCIAN PERSENTASE KERUSAKAN PERANGKAT & TREN ANALYTICS BULANAN -->
                <div class="print-analytics-grid">
                    <!-- Kolom 1: Persentase Kerusakan Perangkat -->
                    <div class="print-analytics-card">
                        <div class="print-card-header">
                            <div>
                                <h4 class="print-card-title">Persentase Kerusakan Perangkat</h4>
                                <p class="print-card-subtitle">Sebaran jenis unit yang dilaporkan mengalami kerusakan</p>
                            </div>
                            <div class="print-badge-total">
                                <span class="print-badge-label">Total:</span>
                                <b class="print-badge-val" x-text="stats.total + ' Laporan'"></b>
                            </div>
                        </div>

                        <div class="print-device-list">
                            <template x-for="cat in deviceStats" :key="'print-dev-' + cat.label">
                                <div class="print-device-item">
                                    <div class="print-device-row">
                                        <span class="print-device-name" x-text="cat.label"></span>
                                        <span class="print-device-pct" x-text="cat.percent + '% (' + cat.count + ' Laporan)'"></span>
                                    </div>
                                    <div class="print-progress-bg">
                                        <div class="print-progress-bar" :style="'width: ' + cat.percent + '%; background-color: ' + cat.color"></div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="deviceStats.length === 0" class="print-empty-note">
                                Belum ada data kerusakan perangkat pada periode ini.
                            </div>
                        </div>
                    </div>

                    <!-- Kolom 2: Tren Laporan & Analytics Bulanan -->
                    <div class="print-analytics-card">
                        <div class="print-card-header">
                            <div>
                                <h4 class="print-card-title">Tren Laporan &amp; Analytics Bulanan</h4>
                                <p class="print-card-subtitle">Perbandingan jumlah laporan kerusakan masuk per bulan</p>
                            </div>
                            <div class="print-badge-year">
                                <span class="print-badge-label">Tahun:</span>
                                <b class="print-badge-val" x-text="selectedTahun"></b>
                            </div>
                        </div>

                        <div class="print-trend-container">
                            <!-- Visual Bar Tren Per Bulan -->
                            <div class="print-trend-bars">
                                <template x-for="(m, idx) in monthlyBreakdown" :key="'print-bar-' + idx">
                                    <div class="print-trend-col">
                                        <span class="print-bar-val" x-text="m.count"></span>
                                        <div class="print-bar-track">
                                            <div class="print-bar-fill" :style="'height: ' + (m.count > 0 ? Math.max(12, Math.round((m.count / maxMonthlyCount) * 100)) : 2) + '%'"></div>
                                        </div>
                                        <span class="print-bar-lbl" x-text="m.label"></span>
                                    </div>
                                </template>
                            </div>

                            <!-- Tabel Ringkasan Angka Bulanan -->
                            <table class="print-trend-table">
                                <thead>
                                    <tr>
                                        <template x-for="(m, idx) in monthlyBreakdown" :key="'print-tbl-th-' + idx">
                                            <th x-text="m.label"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <template x-for="(m, idx) in monthlyBreakdown" :key="'print-tbl-td-' + idx">
                                            <td :class="m.count > 0 ? 'text-navy font-bold' : 'text-gray'" x-text="m.count"></td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
        window.__initialReports = {!! json_encode(array_values($defaultReports ?? [])) !!};
        window.__allYearReports = {!! json_encode(array_values($allYearReports ?? [])) !!};
        window.__notifReports   = {!! json_encode(array_values($notifReports ?? [])) !!};
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        function persentaseApp(initialReports) {
            return {
                sidebarOpen: false,
                reports: initialReports || [],
                allYearReports: window.__allYearReports || [],
                notifReports: window.__notifReports || [],
                seenAdminReportIds: JSON.parse(localStorage.getItem('siperkom_admin_seen_reports') || '[]'),
                selectedBulan: '{{ $bulanPilih }}',
                selectedTahun: '{{ $tahunPilih }}',
                activeStatus: 'all',
                toast: { show: false, message: '' },

                init() {
                    this.initChart();
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

                initChart() {
                    const canvas = document.getElementById('trenBulananChart');
                    if (!canvas) return;
                    const ctx = canvas.getContext('2d');
                    if (!ctx) return;
                    const monthsData = this.calculateMonthlyTrend();

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: monthsData.labels,
                            datasets: [{
                                label: 'Jumlah Laporan',
                                data: monthsData.counts,
                                borderColor: '#7A2331', 
                                backgroundColor: 'rgba(122, 35, 49, 0.08)',
                                borderWidth: 2.5,
                                tension: 0,
                                fill: true,
                                pointBackgroundColor: '#7A2331',
                                pointBorderColor: '#ffffff',
                                pointBorderWidth: 2,
                                pointRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        }
                    });
                },

                calculateMonthlyTrend() {
                    const sourceData = this.allYearReports.length > 0 ? this.allYearReports : this.reports;
                    const withDate = sourceData.filter(r => this.getReportDate(r) !== null);
                    const months = [];
                    const selectedYear = parseInt(this.selectedTahun) || new Date().getFullYear();
                    
                    const bulanIndoLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

                    for (let m = 0; m < 12; m++) {
                        months.push({ 
                            year: selectedYear, 
                            month: m, 
                            label: bulanIndoLabels[m]
                        });
                    }

                    const counts = months.map(m => {
                        return withDate.filter(r => {
                            const raw = r.tanggal || r.created_at || '';
                            if (typeof raw === 'string' && raw.length >= 7) {
                                const [y, mo] = raw.substring(0, 7).split('-');
                                if (parseInt(y, 10) === m.year && (parseInt(mo, 10) - 1) === m.month) {
                                    return true;
                                }
                            }
                            const d = this.getReportDate(r);
                            return d && d.getFullYear() === m.year && d.getMonth() === m.month;
                        }).length;
                    });

                    return {
                        labels: months.map(m => m.label),
                        counts: counts
                    };
                },

                toggleStatusFilter(status) {
                    this.activeStatus = (this.activeStatus === status) ? 'all' : status;
                },

                statusBadgeClass(status) {
                    const s = (status || '').trim();
                    if (s === 'Selesai') return 'bg-emerald-100 text-emerald-800';
                    if (s === 'Dalam Perbaikan') return 'bg-blue-100 text-blue-800';
                    if (s === 'Menunggu Part') return 'bg-amber-100 text-amber-800';
                    if (s === 'Ditolak') return 'bg-red-100 text-red-800';
                    return 'bg-gray-100 text-gray-600';
                },

                getReportDate(r) {
                    const raw = r.tanggal || r.created_at || r.tanggal_lapor || null;
                    if (!raw) return null;
                    const d = new Date(raw);
                    return isNaN(d.getTime()) ? null : d;
                },

                get rangeFilteredReports() {
                    return this.reports.filter(r => {
                        const raw = r.tanggal || r.created_at || '';
                        if (typeof raw === 'string' && raw.length >= 7) {
                            const [y, m] = raw.substring(0, 7).split('-');
                            return m === this.selectedBulan && y === this.selectedTahun;
                        }
                        const d = this.getReportDate(r);
                        if (!d) return false;
                        const m = String(d.getMonth() + 1).padStart(2, '0');
                        const y = String(d.getFullYear());
                        return m === this.selectedBulan && y === this.selectedTahun;
                    });
                },

                get filteredReports() {
                    const byMonthYear = this.rangeFilteredReports;
                    if (this.activeStatus === 'all') return byMonthYear;
                    return byMonthYear.filter(r => (r.status || '').trim() === this.activeStatus);
                },

                get stats() {
                    return { total: this.filteredReports.length };
                },

                get statsPerluPerhatian() {
                    return this.reports.length;
                },

                classifyDevice(perangkat) {
                    if (!perangkat) return 'Lainnya';
                    const p = perangkat.trim().toLowerCase();
                    if (p.includes('komputer') || p.includes('pc') || p.includes('cpu')) return 'Komputer Unit / PC';
                    if (p.includes('laptop')) return 'Laptop';
                    if (p.includes('printer')) return 'Printer';
                    if (p.includes('scanner')) return 'Scanner';
                    if (p.includes('jaringan') || p.includes('router') || p.includes('mikrotik') || p.includes('wifi')) return 'Jaringan / Router';
                    return 'Lainnya';
                },

                deviceIcons: {
                    'Komputer Unit / PC': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v2m6-2v2M5 17h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v9a2 2 0 002 2z"/></svg>',
                    'Laptop': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19h18M5 15h14V6a1 1 0 001-1H6a1 1 0 00-1 1v9z"/></svg>',
                    'Printer': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V4h12v5M6 18h12v4H6v-4zm-3-8h18a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5a1 1 0 011-1z"/></svg>',
                    'Scanner': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10m-10 4h6"/></svg>',
                    'Jaringan / Router': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12.55a11 11 0 0114 0M8.5 16a6 6 0 017 0M12 20h.01"/></svg>',
                    'Lainnya': '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                },

                get deviceStats() {
                    const colors = {
                        'Komputer Unit / PC': '#0B3D6E',
                        'Laptop': '#2E9EC4',
                        'Printer': '#C9A227',
                        'Scanner': '#14588A',
                        'Jaringan / Router': '#7A2331',
                        'Lainnya': '#94A3B8',
                    };
                    const data = this.filteredReports;
                    const total = data.length || 1;
                    const counts = {};

                    data.forEach(r => {
                        const cat = this.classifyDevice(r.perangkat);
                        counts[cat] = (counts[cat] || 0) + 1;
                    });

                    return Object.keys(counts).map(label => ({
                        label,
                        count: counts[label],
                        percent: Math.round((counts[label] / total) * 100),
                        color: colors[label] || '#94A3B8',
                        icon: this.deviceIcons[label] || this.deviceIcons['Lainnya'],
                    })).sort((a, b) => b.count - a.count);
                },

                get deviceChartGradient() {
                    if (this.deviceStats.length === 0) return '#E3DCC8 0% 100%';
                    let acc = 0;
                    const parts = this.deviceStats.map(cat => {
                        const start = acc;
                        acc += cat.percent;
                        return cat.color + ' ' + start + '% ' + acc + '%';
                    });
                    if (acc < 100) parts.push('#E3DCC8 ' + acc + '% 100%');
                    return parts.join(', ');
                },

                get topDevice() {
                    const list = this.deviceStats;
                    return list.length > 0 ? list[0].label : '-';
                },

                get statusStats() {
                    const data = this.rangeFilteredReports;
                    const total = data.length || 1;
                    let selesai = 0, dalamPerbaikan = 0, menungguPart = 0, ditolak = 0;

                    data.forEach(r => {
                        const st = (r.status || '').trim();
                        if (st === 'Selesai') selesai++;
                        else if (st === 'Dalam Perbaikan') dalamPerbaikan++;
                        else if (st === 'Menunggu Part') menungguPart++;
                        else if (st === 'Ditolak') ditolak++;
                    });

                    return {
                        selesai: { count: selesai, percent: Math.round((selesai / total) * 100) },
                        dalamPerbaikan: { count: dalamPerbaikan, percent: Math.round((dalamPerbaikan / total) * 100) },
                        menungguPart: { count: menungguPart, percent: Math.round((menungguPart / total) * 100) },
                        ditolak: { count: ditolak, percent: Math.round((ditolak / total) * 100) }
                    };
                },

                get topStatus() {
                    const s = this.statusStats;
                    const entries = [
                        ['Selesai', s.selesai.count],
                        ['Dalam Perbaikan', s.dalamPerbaikan.count],
                        ['Menunggu Part', s.menungguPart.count],
                        ['Ditolak', s.ditolak.count],
                    ];
                    entries.sort((a, b) => b[1] - a[1]);
                    return entries[0][1] > 0 ? entries[0][0] : '-';
                },

                get completionRate() {
                    return this.statusStats.selesai.percent;
                },

                get executiveSummary() {
                    const total = this.filteredReports.length;
                    if (total === 0) return 'Tidak ada laporan pada bulan dan tahun ini.';
                    return `Tercatat ${total} laporan kerusakan pada periode ini, didominasi perangkat "${this.topDevice}". Tingkat penyelesaian saat ini mencapai ${this.completionRate}%.`;
                },

                get namaBulanIndo() {
                    const months = {
                        '01': 'Januari', '02': 'Februari', '03': 'Maret', 
                        '04': 'April',   '05': 'Mei',      '06': 'Juni', 
                        '07': 'Juli',    '08': 'Agustus',  '09': 'September', 
                        '10': 'Oktober', '11': 'November', '12': 'Desember'
                    };
                    return months[this.selectedBulan] || 'Semua Bulan';
                },

                get monthlyBreakdown() {
                    const trend = this.calculateMonthlyTrend();
                    return trend.labels.map((lbl, idx) => ({
                        label: lbl,
                        count: trend.counts[idx] || 0
                    }));
                },

                get maxMonthlyCount() {
                    const counts = this.monthlyBreakdown.map(m => m.count);
                    const max = Math.max(...counts, 0);
                    return max > 0 ? max : 1;
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