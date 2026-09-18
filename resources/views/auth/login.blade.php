<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk | SIPERKOM Kabupaten Cirebon</title>
    
    <!-- Favicon Resmi Logo Kabupaten Cirebon -->
    <link rel="icon" href="{{ asset('images/cirebon.png') }}" type="image/png">

    <!-- Memuat Berkas Kompilasi Aset CSS & JavaScript Utama via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Style Kustomisasi Tema Megamendung Keraton Cirebon & Performa Animasi Super Smooth -->
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

        html, body {
            height: 100%;
            width: 100%;
        }

        /* Menggunakan font sistem lokal agar loading instan dan super smooth */
        body { 
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            background-color: var(--batik-cream);
            color: var(--ink);
            opacity: 0;
            animation: smoothLoad 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            overflow-x: hidden;
        }

        @keyframes smoothLoad {
            from { opacity: 0; transform: scale(0.99); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .font-display { 
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-weight: 700;
        }

        /* Pengaturan Video Latar Belakang agar Menutupi Panel Secara Sempurna */
        .video-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translate(-50%, -50%);
            object-fit: cover;
            z-index: 0;
        }

        /* Lapisan Gelap Transparan di atas Motif Batik Megamendung */
        .video-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(7, 37, 68, 0.88) 0%, rgba(11, 61, 110, 0.78) 100%);
            z-index: 1;
        }

        /* ===== PANEL RESPONSIF MOBILE-FIRST ===== */
        .responsive-panel {
            width: 100%;
            margin: 0;
            border-radius: 0;
            box-shadow: 0 10px 25px rgba(7, 37, 68, 0.1);
            z-index: 10;
        }

        /* Desktop: Tampilan Tetap Terkunci Bebas Scroll (No-Scroll Viewport) */
        @media (min-width: 1024px) {
            html, body {
                height: 100vh !important;
                max-height: 100vh !important;
                overflow: hidden !important;
                position: fixed;
                inset: 0;
                width: 100%;
            }
            .responsive-panel {
                width: 55%;
                height: 94vh;
                margin: 3vh 0 3vh 3vh;
                border-radius: 36px;
                box-shadow: 10px 0px 30px rgba(7, 37, 68, 0.15);
            }
            .right-form-panel {
                width: 45%;
                height: 100vh;
                max-height: 100vh;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }

        /* ===== FORM PANEL MOBILE OVERLAP EFFECT ===== */
        .right-form-panel {
            position: relative;
        }

        @media (max-width: 1023px) {
            body {
                background-color: #ffffff;
            }
            .right-form-panel {
                margin-top: -26px;
                border-radius: 28px 28px 0 0;
                box-shadow: 0 -10px 35px rgba(7, 37, 68, 0.12);
                z-index: 20;
                background-color: #ffffff !important;
                flex: 1 1 auto;
            }
        }

        .gold-divider {
            background: linear-gradient(90deg, transparent, var(--keraton-gold) 20%, var(--keraton-gold) 80%, transparent);
            height: 2px;
        }

        .input-field {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 46px;
        }
        
        .input-field:focus {
            outline: none;
            border-color: var(--mm-600);
            box-shadow: 0 0 0 4px rgba(20,88,138,0.12);
            background-color: #ffffff;
        }

        .smooth-all {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Touch-friendly button minimum height */
        .btn-touch {
            min-height: 48px;
        }

        /* Safe area untuk notched phones (iPhone X+) */
        @supports (padding: env(safe-area-inset-bottom)) {
            .safe-bottom {
                padding-bottom: calc(12px + env(safe-area-inset-bottom));
            }
        }

        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--batik-cream);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--mm-600);
            border-radius: 4px;
        }

        /* Smooth scroll pada mobile */
        @media (max-width: 1023px) {
            html {
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</head>
<body class="antialiased min-h-screen lg:h-screen lg:overflow-hidden flex flex-col bg-[var(--batik-cream)]" x-data="loginApplicationSystem()">

    <!-- Pembungkus Utama Struktur Halaman Responsif Dua Kolom (Terkunci Bebas Scroll di Desktop) -->
    <div class="flex-1 flex flex-col lg:flex-row w-full min-h-screen lg:h-screen lg:max-h-screen lg:overflow-hidden lg:items-center">

        {{-- =========================================================================
             PANEL KIRI: Kartu Gambar Motif Batik Megamendung & Interaktif JS
             ========================================================================= --}}
        <div class="relative responsive-panel flex flex-col justify-center p-5 pb-9 sm:p-8 sm:pb-12 lg:p-8 lg:px-12 lg:justify-between text-white overflow-hidden bg-[var(--mm-900)] shrink-0">

            <!-- Gambar Latar Belakang Motif Batik Megamendung Khas Cirebon -->
            <img src="{{ asset('images/batik mega mendung.png') }}" class="absolute inset-0 w-full h-full object-cover object-center z-0 select-none pointer-events-none" alt="Batik Megamendung Cirebon" />

            <!-- Lapisan Filter Warna Transparan -->
            <div class="video-overlay"></div>

            <!-- Bagian Header Logo dan Identitas Instansi Kabupaten Cirebon -->
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 lg:w-13 lg:h-13 rounded-full bg-white/95 flex items-center justify-center shadow-xl ring-[2.5px] ring-[var(--keraton-gold)]/70 p-1.5 shrink-0 smooth-all hover:scale-105">
                        <img src="{{ asset('images/siperkom.png') }}" class="w-full h-full object-contain rounded-full" alt="Logo SIPERKOM" />
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="font-display font-extrabold text-lg sm:text-xl lg:text-2xl tracking-tight leading-tight text-white">SIPERKOM</h1>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-[var(--keraton-gold)]/20 text-[var(--keraton-gold-soft)] border border-[var(--keraton-gold)]/40 tracking-wider uppercase">Resmi</span>
                        </div>
                        <p class="text-[10px] sm:text-[11px] lg:text-xs font-medium text-[var(--mm-200)] tracking-wide">Pemerintah Kabupaten Cirebon</p>
                    </div>
                </div>
            </div>

            <!-- Bagian Konten Teks Narasi Deskripsi Utama -->
            <div class="relative z-10 mt-4 sm:mt-6 lg:my-auto lg:py-2">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-[10px] sm:text-xs text-[var(--mm-200)] mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-medium">Layanan Pengaduan Terpadu</span>
                </div>
                <h2 class="font-display text-xl sm:text-3xl lg:text-3xl xl:text-4xl font-extrabold leading-snug sm:leading-tight mb-2 sm:mb-3 text-white">
                    <span class="hidden sm:inline">Sistem Informasi<br />Pelaporan Kerusakan<br />Infrastruktur Kecamatan</span>
                    <span class="sm:hidden">Pelaporan Kerusakan<br />Infrastruktur Kecamatan</span>
                </h2>
                <p class="text-xs text-white/80 leading-relaxed sm:hidden max-w-sm pb-1">
                    Kawal perbaikan fasilitas publik Kabupaten Cirebon secara transparan, cepat, dan akuntabel.
                </p>

                <!-- Teks Interaktif Efek Ketik Dinamis via JavaScript -->
                <p class="hidden sm:block text-[var(--mm-200)] text-xs sm:text-sm max-w-lg leading-relaxed mb-4 min-h-[42px]" x-text="dynamicTypedDescription">
                </p>

                <!-- Kotak Grid Keunggulan Fitur Sistem -->
                <div class="hidden sm:grid grid-cols-2 gap-3 pt-3 border-t border-white/15">
                    <div class="bg-white/10 p-3 rounded-xl border border-white/25 backdrop-blur-md smooth-all hover:bg-white/20 hover:scale-[1.02]">
                        <span class="block font-display font-bold text-xs sm:text-sm text-[var(--keraton-gold)] mb-0.5">Respon Cepat</span>
                        <span class="text-[11px] text-[var(--mm-200)] leading-relaxed">Penanganan tiket aduan langsung terhubung dan terpantau sistem secara realtime.</span>
                    </div>
                    <div class="bg-white/10 p-3 rounded-xl border border-white/25 backdrop-blur-md smooth-all hover:bg-white/20 hover:scale-[1.02]">
                        <span class="block font-display font-bold text-xs sm:text-sm text-[var(--keraton-gold)] mb-0.5">Autentikasi Aman</span>
                        <span class="text-[11px] text-[var(--mm-200)] leading-relaxed">Mendukung perlindungan data berlapis via kredensial akun terenkripsi.</span>
                    </div>
                </div>
            </div>

            <!-- Bagian Footer Kiri Informasi Hak Cipta -->
            <div class="relative z-10 pt-3 border-t border-white/10 hidden lg:block">
                <p class="text-[11px] text-[var(--mm-200)]/70">
                    © {{ date('Y') }} Pemerintah Kabupaten Cirebon — Hak Cipta Dilindungi Undang-Undang.
                </p>
            </div>
        </div>

        {{-- =========================================================================
             PANEL KANAN: FORMULIR AUTENTIKASI MASUK MANUAL PENGGUNA (NON-SCROLL DI DESKTOP)
             ========================================================================= --}}
        <div class="relative right-form-panel flex-1 flex flex-col justify-between lg:justify-center px-5 pt-3.5 sm:pt-6 pb-6 sm:p-8 lg:px-8 xl:px-14 lg:py-4 bg-white lg:bg-transparent z-10 w-full lg:h-screen lg:overflow-hidden">
            <!-- Handle Bar Mobile (Memberi kesan bottom sheet native app modern tepat di atas lekukan) -->
            <div class="w-12 h-1.5 bg-slate-300/80 rounded-full mx-auto mb-3 sm:hidden"></div>

            <div class="w-full max-w-md mx-auto my-auto lg:my-0 py-1 sm:py-2">

                <!-- Judul Sapaan Formulir Pengguna -->
                <div class="mb-3.5 sm:mb-4 lg:mb-5 text-center lg:text-left">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[var(--mm-800)]/5 border border-[var(--mm-800)]/10 text-[11px] font-semibold text-[var(--mm-800)] mb-1.5 sm:hidden">
                        <svg class="w-3.5 h-3.5 text-[var(--keraton-gold)]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
                        </svg>
                        <span>Akses Masuk Akun Pelapor</span>
                    </div>
                    <h3 class="font-display text-xl sm:text-2xl lg:text-2xl xl:text-3xl font-extrabold text-[var(--mm-900)] tracking-tight mb-1">Selamat Datang Kembali</h3>
                    <p class="text-xs sm:text-xs text-slate-500 font-medium">Masuk untuk menyampaikan atau memantau status aduan fasilitas Anda</p>
                </div>

                <!-- Notifikasi Pesan Error Sesi -->
                @if (session('error'))
                    <div x-show="displayErrorAlert" class="mb-3.5 px-4 py-3 rounded-xl bg-[var(--keraton-maroon)]/10 border border-[var(--keraton-maroon)]/30 text-[var(--keraton-maroon)] text-xs sm:text-sm shadow-sm flex items-start justify-between gap-3 transition-all duration-300">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <span class="font-bold block mb-0.5">Akses Masuk Ditolak:</span>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                        <button @click="displayErrorAlert = false" class="text-[var(--keraton-maroon)] hover:opacity-75 font-bold">&times;</button>
                    </div>
                @endif

                <!-- Notifikasi Peringatan Kesalahan Validasi Laravel -->
                @if ($errors->any())
                    <div class="mb-3.5 px-4 py-3 rounded-xl bg-[var(--keraton-maroon)]/10 border border-[var(--keraton-maroon)]/30 text-[var(--keraton-maroon)] text-xs sm:text-sm shadow-sm flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <span class="font-bold block mb-0.5">Perhatian Akses Masuk:</span>
                            <span>{{ $errors->first() }} Silakan lakukan registrasi terlebih dahulu jika belum memiliki akun.</span>
                        </div>
                    </div>
                @endif

                <!-- Notifikasi Pesan Berhasil Sesi -->
                @if (session('status'))
                    <div class="mb-3.5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Form Login Utama Pengguna dengan Integrasi Alpine.js Loading State -->
                <form method="POST" action="{{ route('login') }}" @submit="formSubmissionLoading = true" class="space-y-3 sm:space-y-3.5">
                    @csrf

                    <!-- Kolom Input Username atau Email Pelapor -->
                    <div>
                        <label for="email" class="block text-xs lg:text-sm font-bold text-slate-700 mb-1">Username / Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <svg class="w-5 h-5 text-[var(--mm-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input id="email" name="email" type="text" required autofocus value="{{ old('email') }}"
                                placeholder="Contoh: nama atau nama@gmail.com"
                                class="input-field w-full rounded-xl pl-11 pr-4 py-2 sm:py-2.5 lg:py-2.5 text-xs sm:text-sm bg-slate-50/70 border border-slate-200 text-slate-800 font-medium placeholder:text-slate-400 focus:bg-white focus:border-[var(--mm-600)] focus:ring-4 focus:ring-[var(--mm-600)]/15 transition-all" />
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs font-semibold text-[var(--keraton-maroon)] flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Kolom Input Kata Sandi Akun -->
                    <div>
                        <label for="password" class="block text-xs lg:text-sm font-bold text-slate-700 mb-1">Kata Sandi Akun</label>
                        <div class="relative" x-data="{ revealPasswordInput: false }">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <svg class="w-5 h-5 text-[var(--mm-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input :type="revealPasswordInput ? 'text' : 'password'" id="password" name="password" required
                                placeholder="••••••••••••"
                                class="input-field w-full rounded-xl pl-11 pr-12 py-2 sm:py-2.5 lg:py-2.5 text-xs sm:text-sm bg-slate-50/70 border border-slate-200 text-slate-800 font-medium placeholder:text-slate-400 focus:bg-white focus:border-[var(--mm-600)] focus:ring-4 focus:ring-[var(--mm-600)]/15 transition-all" />
                            <button type="button" @click="revealPasswordInput = !revealPasswordInput" aria-label="Lihat kata sandi" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-[var(--mm-900)] transition-colors focus:outline-none">
                                <svg x-show="!revealPasswordInput" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="revealPasswordInput" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs font-semibold text-[var(--keraton-maroon)] flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Row Aksi Sandi -->
                    <div class="flex items-center justify-between pt-0.5">
                        <span class="text-[11px] text-slate-500 font-medium flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>Sesi Aman Terenkripsi</span>
                        </span>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-[var(--mm-600)] hover:text-[var(--mm-900)] transition-colors">Lupa sandi?</a>
                        @endif
                    </div>

                    <!-- Tombol Eksekusi Masuk Sistem dengan Smooth Loading State -->
                    <button type="submit" :disabled="formSubmissionLoading"
                        class="btn-touch w-full rounded-xl py-3 sm:py-3.5 font-display font-bold text-white text-xs sm:text-sm tracking-wider uppercase
                            bg-gradient-to-r from-[var(--mm-900)] via-[var(--mm-800)] to-[var(--mm-600)]
                            hover:from-[var(--mm-900)] hover:to-[var(--mm-800)]
                            active:scale-[0.98]
                            shadow-lg shadow-[var(--mm-900)]/20 smooth-all
                            border border-[var(--keraton-gold)]/50 focus:outline-none focus:ring-4 focus:ring-[var(--mm-600)]/20
                            flex items-center justify-center gap-2">
                        
                        <!-- Teks Biasa -->
                        <span x-show="!formSubmissionLoading" class="flex items-center gap-2">
                            <span>MASUK</span>
                            <svg class="w-4 h-4 text-[var(--keraton-gold-soft)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </span>

                        <!-- Indikator Loading Saat Diklik -->
                        <span x-show="formSubmissionLoading" class="flex items-center gap-2" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses Masuk...
                        </span>
                    </button>
                </form>

                <!-- Divider Antara Tombol Utama dan Aksi Tambahan -->
                <div class="relative my-3 sm:my-3.5">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="bg-white lg:bg-[var(--batik-cream)] px-3 text-slate-400 font-medium">atau</span>
                    </div>
                </div>

                <!-- Bagian Tautan Navigasi Tambahan (Rapi, Responsif, Tidak Terjepit) -->
                <div class="space-y-2">
                    <a href="{{ route('register') }}"
                       class="btn-touch w-full rounded-xl py-2.5 px-4 text-xs sm:text-sm font-bold text-[var(--mm-900)]
                              bg-white hover:bg-slate-50 border-2 border-slate-200 hover:border-[var(--mm-600)]/40
                              active:scale-[0.98] smooth-all shadow-xs flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-[var(--mm-600)] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>Belum punya akun? <span class="text-[var(--mm-600)]">Daftar Akun Baru</span></span>
                    </a>

                    <a href="https://wa.me/6285321812643?text=Halo%20Admin,%20saya%20butuh%20bantuan%20terkait%20login%20SIPERKOM." target="_blank" rel="noopener noreferrer"
                       class="w-full rounded-xl py-2 px-4 text-xs font-semibold text-emerald-800
                              bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200/80
                              active:scale-[0.98] smooth-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        <span>Butuh bantuan akun? <strong class="text-emerald-700">Hubungi Admin WhatsApp</strong></span>
                    </a>
                </div>
            </div>

            <!-- Bagian Footer Informasi Hak Cipta -->
            <div class="pt-6 pb-2 text-center block lg:hidden safe-bottom">
                <p class="text-[11px] text-slate-400 font-medium">
                    © {{ date('Y') }} Pemerintah Kabupaten Cirebon • Hak Cipta Dilindungi
                </p>
            </div>
        </div>
    </div>

    <!-- Pustaka JavaScript Alpine.js & Skrip Interaktif Kaya Fitur -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        /**
         * Logika Utama Aplikasi Login SIPERKOM Kabupaten Cirebon
         * Mengelola state reaktif menggunakan Alpine.js untuk performa tinggi
         */
        function loginApplicationSystem() {
            return {
                formSubmissionLoading: false,
                displayErrorAlert: true,
                dynamicTypedDescription: '',
                fullDescriptionText: 'Sarana digital terpadu bagi masyarakat pelapor untuk menyampaikan aduan fasilitas serta mengawal percepatan perbaikan infrastruktur secara transparan dan akuntabel.',
                currentCharIndex: 0,
                
                init() {
                    try {
                        sessionStorage.removeItem('siperkom_tab_session');
                    } catch(e) {}

                    // Memulai animasi efek ketik teks narasi secara asinkron saat komponen dimuat
                    this.initializeTypingEffect();
                },

                initializeTypingEffect() {
                    let typingTimer = setInterval(() => {
                        if (this.currentCharIndex < this.fullDescriptionText.length) {
                            this.dynamicTypedDescription += this.fullDescriptionText.charAt(this.currentCharIndex);
                            this.currentCharIndex++;
                        } else {
                            clearInterval(typingTimer);
                        }
                    }, 16); // Kecepatan ketik teks super responsif
                }
            }
        }
    </script>
</body>
</html>