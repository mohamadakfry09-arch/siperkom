<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pemulihan Sandi | SIPERKOM Kabupaten Cirebon</title>
    
    <!-- Favicon Logo Kabupaten Cirebon -->
    <link rel="icon" href="{{ asset('images/cirebon.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('images/cirebon.png') }}" type="image/png">

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
                width: 52%;
                height: 94vh;
                margin: 3vh 0 3vh 3vh;
                border-radius: 36px;
                box-shadow: 10px 0px 30px rgba(7, 37, 68, 0.15);
            }
            .right-form-wrapper {
                width: 48%;
                height: 100vh;
                max-height: 100vh;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }

        /* ===== FORM PANEL MOBILE OVERLAP EFFECT ===== */
        .right-form-wrapper {
            position: relative;
        }

        @media (max-width: 1023px) {
            body {
                background-color: #ffffff;
            }
            .right-form-wrapper {
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

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--batik-cream); }
        ::-webkit-scrollbar-thumb { background: var(--mm-600); border-radius: 4px; }

        [x-cloak] {
            display: none !important;
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
<body class="antialiased min-h-screen lg:h-screen lg:overflow-hidden flex flex-col bg-[var(--batik-cream)]">

    <!-- Pembungkus Utama Struktur Halaman Dua Kolom (Terkunci Bebas Scroll di Desktop) -->
    <div class="flex-1 flex flex-col lg:flex-row w-full min-h-screen lg:h-screen lg:max-h-screen lg:overflow-hidden lg:items-center">

        <!-- =========================================================================
             PANEL KIRI: GAMBAR MOTIF MEGAMENDUNG & IDENTITAS SISTEM
             ========================================================================= -->
        <div class="relative responsive-panel flex flex-col justify-center p-5 pb-10 sm:p-8 sm:pb-12 lg:p-8 lg:px-12 lg:justify-between text-white overflow-hidden bg-[var(--mm-900)] shrink-0">

            <!-- Gambar Latar Belakang Motif Batik Megamendung Khas Cirebon -->
            <img src="{{ asset('images/batik mega mendung.png') }}" class="absolute inset-0 w-full h-full object-cover object-center z-0 select-none pointer-events-none" alt="Batik Megamendung Cirebon" />

            <!-- Lapisan Filter Warna Transparan -->
            <div class="video-overlay"></div>

            <!-- Bagian Header Logo dan Identitas Instansi -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-10 h-10 sm:w-12 sm:h-12 lg:w-13 lg:h-13 rounded-full bg-white/95 flex items-center justify-center shadow-xl ring-[2.5px] ring-[var(--keraton-gold)]/70 p-1.5 shrink-0 smooth-all hover:scale-105">
                    <img src="{{ asset('images/siperkom.png') }}" class="w-full h-full object-contain rounded-full" alt="Logo SIPERKOM" />
                </div>
                <div>
                    <h1 class="font-display font-extrabold text-lg sm:text-xl lg:text-2xl tracking-tight leading-tight">SIPERKOM</h1>
                    <p class="text-[10px] sm:text-[11px] lg:text-xs font-semibold text-[var(--mm-200)] tracking-wide mt-0.5">Pemerintah Kabupaten Cirebon</p>
                </div>
            </div>

            <!-- Bagian Konten Teks Narasi Deskripsi Utama -->
            <div class="relative z-10 mt-4 sm:mt-6 lg:my-auto lg:py-2 space-y-2 sm:space-y-3">
                <div class="space-y-2 sm:space-y-3">
                    <div class="gold-divider w-16"></div>
                    <h2 class="font-display text-2xl sm:text-3xl lg:text-3xl xl:text-4xl font-extrabold leading-snug sm:leading-tight tracking-tight">
                        Pemulihan<br />Akses Akun Anda
                    </h2>
                    <p class="text-[var(--mm-200)] text-xs sm:text-sm max-w-md leading-relaxed font-medium">
                        Layanan pemulihan kata sandi mandiri secara instan dan aman untuk sistem SIPERKOM Pemerintah Kabupaten Cirebon.
                    </p>
                </div>
            </div>

            <!-- Bagian Footer Kiri Informasi Hak Cipta -->
            <div class="relative z-10 pt-3 border-t border-white/10 hidden lg:block">
                <p class="text-[11px] text-[var(--mm-200)]/70">
                    © {{ date('Y') }} Pemerintah Kabupaten Cirebon — Hak Cipta Dilindungi Undang-Undang.
                </p>
            </div>
        </div>

        <!-- =========================================================================
             PANEL KANAN: FORMULIR PEMULIHAN SANDI MANDIRI & LANGSUNG LOGIN (NON-SCROLL DI DESKTOP)
             ========================================================================= -->
        <div class="right-form-wrapper flex-1 flex flex-col justify-between lg:justify-center px-5 pt-3.5 sm:pt-6 pb-6 sm:p-8 lg:px-8 xl:px-14 lg:py-4 bg-white lg:bg-transparent z-10 w-full lg:h-screen lg:overflow-hidden"
             x-data="{ showPassword: false, showPasswordConfirm: false, formLoading: false }">
            <!-- Handle Bar Mobile -->
            <div class="w-12 h-1.5 bg-slate-300/80 rounded-full mx-auto mb-3 sm:hidden"></div>

            <div class="w-full max-w-md mx-auto my-auto lg:my-0 py-1 sm:py-2 space-y-3 sm:space-y-3.5">

                <!-- Header Form -->
                <div class="text-left">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-[var(--mm-200)]/20 border border-[var(--mm-600)]/20 text-[10px] sm:text-xs font-semibold text-[var(--mm-800)] mb-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Reset Mandiri &amp; Langsung Masuk</span>
                    </div>
                    <h3 class="font-display text-xl sm:text-2xl lg:text-2xl xl:text-3xl font-extrabold text-[var(--mm-900)] mb-0.5">Reset Kata Sandi</h3>
                    <p class="text-[11px] sm:text-xs text-[var(--mm-600)] font-medium leading-relaxed">
                        Masukkan username/email dan sandi baru. Setelah tersimpan, Anda otomatis langsung masuk ke sistem.
                    </p>
                </div>

                <!-- Notifikasi Status & Error Sesi -->
                @if (session('status'))
                    <div class="p-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-2.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold flex items-start gap-2">
                        <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-2.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs space-y-1">
                        <p class="font-bold flex items-center gap-1.5 text-rose-900">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span>Periksa kembali data Anda:</span>
                        </p>
                        <ul class="list-disc list-inside text-rose-700 pl-1 text-[11px]">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulir Input -->
                <form method="POST" action="{{ route('password.email') }}" @submit="if ($el.checkValidity()) { formLoading = true; } else { formLoading = false; }" class="space-y-2.5 sm:space-y-3">
                    @csrf

                    <!-- 1. Username / Nama Pengguna / Gmail -->
                    <div>
                        <label for="username" class="block text-xs sm:text-xs lg:text-sm font-bold text-[var(--mm-900)] mb-1">
                            Username / Gmail
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--mm-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus
                                   placeholder="Contoh: pelapor atau pelapor@gmail.com"
                                   class="input-field w-full rounded-xl pl-10 pr-4 py-2 sm:py-2.5 text-xs sm:text-sm bg-slate-50/70 border border-slate-200 text-slate-800 font-medium placeholder:text-slate-400 focus:bg-white focus:border-[var(--mm-600)] focus:ring-4 focus:ring-[var(--mm-600)]/15 transition-all">
                        </div>
                        @error('username')
                            <p class="mt-1 text-xs font-semibold text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- 2. Kata Sandi Baru -->
                    <div>
                        <label for="password" class="block text-xs sm:text-xs lg:text-sm font-bold text-[var(--mm-900)] mb-1">
                            Kata Sandi Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--mm-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                   placeholder="Masukkan kata sandi baru (min. 4 karakter)"
                                   class="input-field w-full rounded-xl pl-10 pr-11 py-2 sm:py-2.5 text-xs sm:text-sm bg-slate-50/70 border border-slate-200 text-slate-800 font-medium placeholder:text-slate-400 focus:bg-white focus:border-[var(--mm-600)] focus:ring-4 focus:ring-[var(--mm-600)]/15 transition-all">
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 cursor-pointer">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPassword" x-cloak style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411l0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs font-semibold text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- 3. Konfirmasi Kata Sandi Baru -->
                    <div>
                        <label for="password_confirmation" class="block text-xs sm:text-xs lg:text-sm font-bold text-[var(--mm-900)] mb-1">
                            Konfirmasi Kata Sandi Baru
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-[var(--mm-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <input type="password" :type="showPasswordConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                                   placeholder="Ketik ulang kata sandi baru Anda"
                                   class="input-field w-full rounded-xl pl-10 pr-11 py-2 sm:py-2.5 text-xs sm:text-sm bg-slate-50/70 border border-slate-200 text-slate-800 font-medium placeholder:text-slate-400 focus:bg-white focus:border-[var(--mm-600)] focus:ring-4 focus:ring-[var(--mm-600)]/15 transition-all">
                            <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 cursor-pointer">
                                <svg x-show="!showPasswordConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPasswordConfirm" x-cloak style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411l0 0L21 21"/></svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1 text-xs font-semibold text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-1.5 space-y-2">
                        <button type="submit" :disabled="formLoading"
                                class="btn-touch w-full rounded-xl py-2.5 sm:py-3 font-display font-bold text-white text-xs sm:text-sm tracking-wide uppercase
                                       bg-gradient-to-r from-[var(--mm-900)] via-[var(--mm-800)] to-[var(--mm-600)]
                                       hover:from-[var(--mm-900)] hover:to-[var(--mm-800)]
                                       active:scale-[0.98]
                                       shadow-xl shadow-[var(--mm-900)]/20 smooth-all
                                       border border-[var(--keraton-gold)]/50 flex items-center justify-center gap-2 cursor-pointer">
                            <span x-show="!formLoading" class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[var(--keraton-gold-soft)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Simpan Sandi &amp; Masuk Otomatis</span>
                            </span>
                            <span x-show="formLoading" x-cloak style="display: none;" class="flex items-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                <span>Menyimpan &amp; Membuka Dashboard...</span>
                            </span>
                        </button>

                        <a href="{{ route('pelapor.login') }}"
                           class="btn-touch flex justify-center w-full rounded-xl py-2 sm:py-2.5 text-[11px] sm:text-xs font-bold text-[var(--mm-800)]
                                  bg-white border border-[var(--mm-600)]/30 hover:bg-[var(--mm-200)]/30 active:scale-[0.97] smooth-all shadow-sm
                                  items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali ke Halaman Masuk
                        </a>
                    </div>
                </form>

                <div class="gold-divider my-1.5 opacity-25"></div>
            </div>

            <!-- Bagian Footer Informasi Hak Cipta (Hanya tampil di Mobile) -->
            <div class="pt-2 text-center block lg:hidden pb-2 safe-bottom">
                <p class="text-[10px] sm:text-[11px] text-[var(--mm-600)] font-medium">
                    © {{ date('Y') }} Pemerintah Kab. Cirebon
                </p>
            </div>
        </div>
    </div>

    <!-- Pustaka JavaScript Alpine.js untuk Integritas Interaktif Form -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>