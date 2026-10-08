<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Portal Masuk Administrator & Teknisi | SIPERKOM Kab. Cirebon</title>
    
    <!-- Favicon Resmi Logo Kabupaten Cirebon -->
    <link rel="icon" href="{{ asset('images/cirebon.png') }}" type="image/png">

    <!-- Memuat Berkas Kompilasi Aset CSS & JavaScript Utama via Vite & Produksi -->
    @include('partials.assets')

    <!-- Style Kustomisasi Tema Megamendung Keraton Cirebon -->
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
            --batik-cream: #F8FAFC;
            --ink: #1E2A32;
        }

        html, body {
            min-height: 100%;
            width: 100%;
        }

        body { 
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            background-color: var(--batik-cream);
            color: var(--ink);
            opacity: 0;
            animation: smoothLoad 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
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

        .video-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(7, 37, 68, 0.92) 0%, rgba(11, 61, 110, 0.82) 100%);
            z-index: 1;
        }

        .responsive-panel {
            width: 100%;
            margin: 0;
            border-radius: 0;
            box-shadow: 0 10px 25px rgba(7, 37, 68, 0.1);
            z-index: 10;
        }

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

        .btn-touch {
            min-height: 48px;
        }

        @supports (padding: env(safe-area-inset-bottom)) {
            .safe-bottom {
                padding-bottom: calc(12px + env(safe-area-inset-bottom));
            }
        }

        @media (max-width: 1023px) {
            html {
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</head>
<body class="antialiased min-h-screen lg:h-screen lg:overflow-hidden flex flex-col bg-[var(--batik-cream)]" x-data="adminLoginSystem()">

    <!-- Pembungkus Utama Struktur Dua Kolom -->
    <div class="flex-1 flex flex-col lg:flex-row w-full min-h-screen lg:h-screen lg:max-h-screen lg:overflow-hidden lg:items-center">

        {{-- =========================================================================
             PANEL KIRI: Kartu Gambar Motif Batik Megamendung Khusus Administrator
             ========================================================================= --}}
        <div class="relative responsive-panel flex flex-col justify-center p-5 pb-9 sm:p-8 sm:pb-12 lg:p-8 lg:px-12 lg:justify-between text-white overflow-hidden bg-[var(--mm-900)] shrink-0">

            <!-- Background Batik Megamendung -->
            <img src="{{ asset('images/batik mega mendung.png') }}" class="absolute inset-0 w-full h-full object-cover object-center z-0 select-none pointer-events-none" alt="Batik Megamendung Cirebon" />

            <!-- Lapisan Filter Transparan Tema Navy Keraton -->
            <div class="video-overlay"></div>

            <!-- Header Logo dan Identitas Instansi -->
            <div class="relative z-10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 lg:w-13 lg:h-13 rounded-full bg-white/95 flex items-center justify-center shadow-xl ring-[2.5px] ring-[var(--keraton-gold)]/80 p-1.5 shrink-0 smooth-all hover:scale-105">
                        <img src="{{ asset('images/siperkom.png') }}" class="w-full h-full object-contain rounded-full" alt="Logo SIPERKOM" />
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="font-display font-extrabold text-lg sm:text-xl lg:text-2xl tracking-tight leading-tight text-white">SIPERKOM</h1>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-400/20 text-amber-300 border border-amber-400/40 tracking-wider uppercase">Pengelola</span>
                        </div>
                        <p class="text-[10px] sm:text-[11px] lg:text-xs font-medium text-[var(--mm-200)] tracking-wide">Pemerintah Kabupaten Cirebon</p>
                    </div>
                </div>
            </div>

            <!-- Konten Narasi Deskripsi Administrator -->
            <div class="relative z-10 mt-4 sm:mt-6 lg:my-auto lg:py-2">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-500/15 backdrop-blur-md border border-amber-400/30 text-[10px] sm:text-xs text-amber-200 mb-2 font-medium">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>Portal Khusus Administrator & Teknisi</span>
                </div>
                <h2 class="font-display text-xl sm:text-3xl lg:text-3xl xl:text-4xl font-extrabold leading-snug sm:leading-tight mb-2 sm:mb-3 text-white">
                    Panel Kendali Operasional<br />& Penugasan Teknisi
                </h2>

                <!-- Teks Deskripsi di HP / Layar Kecil (Tampil Langsung Tanpa Ketik Otomatis, Rapi & Tidak Loncat ke Bawah) -->
                <p class="block lg:hidden text-[var(--mm-200)] text-xs leading-relaxed mb-3.5">
                    Panel operasional resmi bagi administrator dan tim teknisi lapangan dalam mengelola, memverifikasi, serta menuntaskan seluruh tiket aduan infrastruktur di Kabupaten Cirebon.
                </p>

                <!-- Teks Interaktif Animasi Ketik Khusus Layar Laptop / Desktop -->
                <p class="hidden lg:block text-[var(--mm-200)] text-sm max-w-lg leading-relaxed mb-4 min-h-[44px]" x-text="dynamicTypedDescription">
                    Panel operasional resmi bagi administrator dan tim teknisi lapangan dalam mengelola, memverifikasi, serta menuntaskan seluruh tiket aduan infrastruktur di Kabupaten Cirebon.
                </p>

                <!-- Fitur Utama Pengelola (Tampil di Mobile Maupun Laptop/Desktop) -->
                <div class="grid grid-cols-2 gap-2.5 sm:gap-3 pt-3 border-t border-white/15">
                    <div class="bg-white/10 p-2.5 sm:p-3 rounded-xl border border-white/20 backdrop-blur-md smooth-all hover:bg-white/20 hover:scale-[1.02]">
                        <span class="block font-display font-bold text-[11px] sm:text-sm text-[var(--keraton-gold)] mb-0.5">Disposisi & Antrean</span>
                        <span class="text-[10px] sm:text-[11px] text-[var(--mm-200)] leading-tight sm:leading-relaxed">Verifikasi berkas laporan masyarakat dan delegasikan tugas langsung ke teknisi lapangan.</span>
                    </div>
                    <div class="bg-white/10 p-2.5 sm:p-3 rounded-xl border border-white/20 backdrop-blur-md smooth-all hover:bg-white/20 hover:scale-[1.02]">
                        <span class="block font-display font-bold text-[11px] sm:text-sm text-[var(--keraton-gold)] mb-0.5">Rekapitulasi & Log</span>
                        <span class="text-[10px] sm:text-[11px] text-[var(--mm-200)] leading-tight sm:leading-relaxed">Ekspor berkas PDF/Excel resmi dan pantau seluruh audit trail penanganan infrastruktur.</span>
                    </div>
                </div>
            </div>

            <!-- Footer Kiri -->
            <div class="relative z-10 pt-3 border-t border-white/10 hidden lg:block">
                <p class="text-[11px] text-[var(--mm-200)]/70">
                    © {{ date('Y') }} Pemerintah Kabupaten Cirebon — Portal Administrator & Teknisi
                </p>
            </div>
        </div>

        {{-- =========================================================================
             PANEL KANAN: FORMULIR AUTENTIKASI MASUK ADMIN & TEKNISI
             ========================================================================= --}}
        <div class="relative right-form-panel flex-1 flex flex-col justify-between lg:justify-center px-5 pt-3.5 sm:pt-6 pb-6 sm:p-8 lg:px-8 xl:px-14 lg:py-4 bg-white lg:bg-transparent z-10 w-full lg:h-screen lg:overflow-hidden">
            <!-- Handle Bar Mobile -->
            <div class="w-12 h-1.5 bg-slate-300/80 rounded-full mx-auto mb-3 sm:hidden"></div>

            <div class="w-full max-w-md mx-auto my-auto lg:my-0 py-1 sm:py-2">

                <!-- Judul Sapaan Portal Admin -->
                <div class="mb-3.5 sm:mb-4 lg:mb-5 text-center lg:text-left">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-[11px] font-semibold text-slate-700 mb-2">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
                        </svg>
                        <span>Akses Khusus Pengelola & Teknisi</span>
                    </div>
                    <h3 class="font-display text-xl sm:text-2xl lg:text-2xl xl:text-3xl font-extrabold text-[var(--mm-900)] tracking-tight mb-1">Masuk Administrator</h3>
                    <p class="text-xs sm:text-xs text-slate-500 font-medium">Gunakan kredensial resmi operator dinas atau teknisi lapangan yang terdaftar</p>
                </div>

                <!-- Notifikasi Pesan Error Sesi -->
                @if (session('error'))
                    <div x-show="displayErrorAlert" class="mb-3.5 px-4 py-3 rounded-xl bg-[var(--keraton-maroon)]/10 border border-[var(--keraton-maroon)]/30 text-[var(--keraton-maroon)] text-xs sm:text-sm shadow-sm flex items-start justify-between gap-3 transition-all duration-300">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <span class="font-bold block mb-0.5">Akses Ditolak:</span>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                        <button @click="displayErrorAlert = false" class="text-[var(--keraton-maroon)] hover:opacity-75 font-bold">&times;</button>
                    </div>
                @endif

                <!-- Notifikasi Peringatan Validasi -->
                @if ($errors->any())
                    <div class="mb-3.5 px-4 py-3 rounded-xl bg-[var(--keraton-maroon)]/10 border border-[var(--keraton-maroon)]/30 text-[var(--keraton-maroon)] text-xs sm:text-sm shadow-sm flex items-start gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <span class="font-bold block mb-0.5">Perhatian Akses Admin:</span>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    </div>
                @endif

                <!-- Notifikasi Pesan Berhasil -->
                @if (session('status'))
                    <div class="mb-3.5 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm shadow-sm flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- Formulir Login Khusus Administrator -->
                <form method="POST" action="{{ route('admin.login.store') }}" @submit="formSubmissionLoading = true" class="space-y-3 sm:space-y-3.5">
                    @csrf

                    <!-- Input Username / Email Admin -->
                    <div>
                        <label for="email" class="block text-xs lg:text-sm font-bold text-slate-700 mb-1">Username / Email Administrator</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <svg class="w-5 h-5 text-[var(--mm-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                            <input id="email" name="email" type="text" required autofocus value="{{ old('email') }}"
                                placeholder="Contoh: admin atau admin@siperkom.id"
                                class="input-field w-full rounded-xl pl-11 pr-4 py-2 sm:py-2.5 lg:py-2.5 text-xs sm:text-sm bg-slate-50/70 border border-slate-200 text-slate-800 font-medium placeholder:text-slate-400 focus:bg-white focus:border-[var(--mm-600)] focus:ring-4 focus:ring-[var(--mm-600)]/15 transition-all" />
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs font-semibold text-[var(--keraton-maroon)] flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Input Kata Sandi -->
                    <div>
                        <label for="password" class="block text-xs lg:text-sm font-bold text-slate-700 mb-1">Kata Sandi</label>
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

                    <!-- Row Info Keamanan -->
                    <div class="flex items-center justify-between pt-0.5">
                        <span class="text-[11px] text-slate-500 font-medium flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Otoritas Keamanan Level Tinggi</span>
                        </span>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-[var(--mm-600)] hover:text-[var(--mm-900)] transition-colors">Lupa sandi?</a>
                        @endif
                    </div>

                    <!-- Tombol Masuk Admin -->
                    <button type="submit" :disabled="formSubmissionLoading"
                        class="btn-touch w-full rounded-xl py-3 sm:py-3.5 font-display font-bold text-white text-xs sm:text-sm tracking-wider uppercase
                            bg-gradient-to-r from-[var(--mm-900)] via-[var(--mm-800)] to-[#0c4a6e]
                            hover:from-[var(--mm-900)] hover:to-[var(--mm-800)]
                            active:scale-[0.98]
                            shadow-lg shadow-[var(--mm-900)]/25 smooth-all
                            border border-amber-400/40 focus:outline-none focus:ring-4 focus:ring-[var(--mm-600)]/20
                            flex items-center justify-center gap-2">
                        
                        <span x-show="!formSubmissionLoading" class="flex items-center gap-2">
                            <span>MASUK ADMINISTRATOR</span>
                            <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </span>

                        <span x-show="formSubmissionLoading" class="flex items-center gap-2" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memvalidasi Otoritas...
                        </span>
                    </button>
                </form>

                <!-- Divider Pemisah Bersih Rata Tanpa Kotak Belang -->
                <div class="flex items-center my-3 sm:my-3.5 gap-3">
                    <div class="flex-1 h-px bg-slate-200"></div>
                    <span class="text-xs text-slate-400 font-medium px-1 select-none">atau</span>
                    <div class="flex-1 h-px bg-slate-200"></div>
                </div>

                <!-- Tautan Registrasi Akun Admin -->
                <div>
                    <a href="{{ route('admin.register') }}"
                       class="btn-touch w-full rounded-xl py-2.5 px-4 text-xs sm:text-sm font-bold text-[var(--mm-900)]
                              bg-white hover:bg-amber-50/50 border-2 border-slate-200 hover:border-amber-400/80
                              active:scale-[0.98] smooth-all shadow-xs flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        <span>Belum punya akun admin? <strong class="text-amber-700 underline">Daftar Akun Admin Baru</strong></span>
                    </a>
                </div>
            </div>

            <!-- Footer Hak Cipta Mobile (Sama Persis dengan Desktop) -->
            <div class="pt-6 pb-2 text-center block lg:hidden safe-bottom">
                <p class="text-[11px] text-slate-500 font-medium">
                    © {{ date('Y') }} Pemerintah Kabupaten Cirebon — Portal Administrator & Teknisi
                </p>
            </div>
        </div>
    </div>

    <!-- Pustaka JavaScript Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function adminLoginSystem() {
            return {
                formSubmissionLoading: false,
                displayErrorAlert: true,
                dynamicTypedDescription: '',
                fullDescriptionText: 'Panel operasional resmi bagi administrator dan tim teknisi lapangan dalam mengelola, memverifikasi, serta menuntaskan seluruh tiket aduan infrastruktur di Kabupaten Cirebon.',
                currentCharIndex: 0,
                
                init() {
                    try {
                        sessionStorage.removeItem('siperkom_tab_session');
                    } catch(e) {}

                    // Hanya jalankan efek ketik di layar besar (laptop/desktop) agar di HP tidak meloncat ke bawah
                    if (window.innerWidth >= 1024) {
                        this.initializeTypingEffect();
                    } else {
                        this.dynamicTypedDescription = this.fullDescriptionText;
                    }
                },

                initializeTypingEffect() {
                    let typingTimer = setInterval(() => {
                        if (this.currentCharIndex < this.fullDescriptionText.length) {
                            this.dynamicTypedDescription += this.fullDescriptionText.charAt(this.currentCharIndex);
                            this.currentCharIndex++;
                        } else {
                            clearInterval(typingTimer);
                        }
                    }, 16);
                }
            }
        }
    </script>
</body>
</html>
