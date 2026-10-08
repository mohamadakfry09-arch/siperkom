<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pendaftaran Akun Admin & Teknisi | SIPERKOM Kab. Cirebon</title>
    
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
            --batik-cream: #FBF6EA;
            --ink: #1E2A32;
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
                overflow-y: auto;
            }
        }

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

        .input-field {
            border: 1.5px solid #E3DCC8;
            background-color: #ffffff !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 44px;
        }
        
        .input-field:focus {
            outline: none;
            border-color: var(--mm-600);
            box-shadow: 0 0 0 4px rgba(20,88,138,0.15);
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

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--batik-cream); }
        ::-webkit-scrollbar-thumb { background: var(--mm-600); border-radius: 4px; }

        @media (max-width: 1023px) {
            html {
                scroll-behavior: smooth;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- Pembungkus Utama Dua Kolom -->
    <div class="flex-1 flex flex-col lg:flex-row w-full min-h-screen lg:items-center">

        <!-- PANEL KIRI: GAMBAR MOTIF BATIK MEGAMENDUNG KHUSUS ADMIN -->
        <div class="relative responsive-panel flex flex-col justify-center p-5 pb-10 sm:p-8 sm:pb-12 lg:p-10 lg:pl-14 lg:pr-14 lg:pb-10 lg:justify-between text-white overflow-hidden bg-[var(--mm-900)] shrink-0">

            <img src="{{ asset('images/batik mega mendung.png') }}" class="absolute inset-0 w-full h-full object-cover object-center z-0 select-none pointer-events-none" alt="Batik Megamendung Cirebon" />

            <div class="video-overlay"></div>

            <!-- Header Logo & Instansi -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-11 h-11 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full bg-white/95 flex items-center justify-center shadow-xl ring-[2.5px] ring-[var(--keraton-gold)]/80 p-1.5 shrink-0 smooth-all hover:scale-105">
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

            <!-- Konten Teks Narasi Admin -->
            <div class="relative z-10 mt-4 sm:mt-6 lg:my-auto lg:py-2 space-y-2 sm:space-y-3">
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-500/15 backdrop-blur-md border border-amber-400/30 text-[10px] sm:text-xs text-amber-200 mb-1 font-medium">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>Registrasi Petugas Resmi</span>
                </div>
                <h2 class="font-display text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-snug sm:leading-tight tracking-tight">
                    Pendaftaran Akun<br />Administrator & Teknisi
                </h2>
                <p class="text-[var(--mm-200)] text-xs sm:text-sm max-w-md leading-relaxed font-medium">
                    Registrasikan akun petugas baru untuk mengelola antrean verifikasi laporan kerusakan dan penanganan teknisi di seluruh wilayah Kabupaten Cirebon.
                </p>

                <!-- Fitur Unggulan -->
                <div class="hidden sm:grid grid-cols-2 gap-3 pt-2">
                    <div class="bg-white/10 p-3 rounded-xl border border-white/20 backdrop-blur-md smooth-all hover:bg-white/20 hover:scale-[1.02]">
                        <span class="block font-display font-bold text-xs text-amber-300 mb-0.5">Otoritas Pengelola</span>
                        <span class="text-[11px] text-[var(--mm-200)] leading-tight">Akses penuh terhadap manajemen tiket & laporan.</span>
                    </div>
                    <div class="bg-white/10 p-3 rounded-xl border border-white/20 backdrop-blur-md smooth-all hover:bg-white/20 hover:scale-[1.02]">
                        <span class="block font-display font-bold text-xs text-amber-300 mb-0.5">Penugasan Lapangan</span>
                        <span class="text-[11px] text-[var(--mm-200)] leading-tight">Dukungan disposisi tugas teknisi secara terstruktur.</span>
                    </div>
                </div>
            </div>

            <!-- Footer Kiri -->
            <div class="relative z-10 pt-3 border-t border-white/10 hidden lg:block">
                <p class="text-xs text-[var(--mm-200)]/70">
                    © {{ date('Y') }} Pemerintah Kabupaten Cirebon — Hak Cipta Dilindungi Undang-Undang.
                </p>
            </div>
        </div>

        <!-- PANEL KANAN: FORMULIR REGISTRASI ADMIN -->
        <div class="right-form-wrapper flex-1 flex flex-col justify-between px-5 pt-3.5 sm:pt-6 pb-6 sm:p-8 lg:px-12 lg:py-8 bg-white lg:bg-transparent z-10 w-full">
            <!-- Handle Bar Mobile -->
            <div class="w-12 h-1.5 bg-slate-300/80 rounded-full mx-auto mb-3 sm:hidden"></div>

            <div class="w-full max-w-md mx-auto my-auto py-1 sm:py-4 lg:py-6"
                 x-data="{ loading: false, revealPass: false, pass: '' }">

                <!-- Header Sapaan -->
                <div class="mb-5 sm:mb-6 lg:mb-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-[11px] font-semibold text-amber-800 mb-2">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
                        </svg>
                        <span>Portal Pengelola Sistem</span>
                    </div>
                    <h3 class="font-display text-2xl sm:text-2xl lg:text-3xl font-extrabold text-[var(--mm-900)] tracking-tight mb-1 sm:mb-2">Buat Akun Administrator</h3>
                    <p class="text-xs sm:text-xs lg:text-sm text-slate-500 font-medium">Lengkapi data petugas untuk mendapatkan akses kendali sistem</p>
                </div>

                <!-- Notifikasi Error -->
                @if ($errors->any())
                    <div class="mb-4 px-3.5 py-3 rounded-xl bg-[var(--keraton-maroon)]/10 border border-[var(--keraton-maroon)]/30 text-[var(--keraton-maroon)] text-[11px] sm:text-xs shadow-sm space-y-1">
                        <div class="font-bold flex items-center gap-1.5">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span>Terjadi kesalahan pendaftaran:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-slate-700 ml-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulir Pendaftaran Admin -->
                <form method="POST" action="{{ route('admin.register.store') }}" @submit="loading = true" class="space-y-3.5 sm:space-y-4">
                    @csrf


                    <!-- Input Nama Petugas -->
                    <div>
                        <label for="name" class="block text-xs lg:text-sm font-bold text-slate-700 mb-1">Nama Lengkap Petugas</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4 text-[var(--mm-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input id="name" name="name" type="text" required autofocus value="{{ old('name') }}"
                                placeholder="Contoh: Budi Santoso / Tim Lapangan 1"
                                class="input-field w-full rounded-xl pl-10 pr-4 py-2 sm:py-2.5 text-xs sm:text-sm font-medium placeholder:text-slate-400" />
                        </div>
                    </div>

                    <!-- Input Email Dinas -->
                    <div>
                        <label for="email" class="block text-xs lg:text-sm font-bold text-slate-700 mb-1">Alamat Email Resmi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4 text-[var(--mm-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input id="email" name="email" type="email" required value="{{ old('email') }}"
                                placeholder="nama@siperkom.id atau email dinas"
                                class="input-field w-full rounded-xl pl-10 pr-4 py-2 sm:py-2.5 text-xs sm:text-sm font-medium placeholder:text-slate-400" />
                        </div>
                    </div>

                    <!-- Input Kata Sandi -->
                    <div>
                        <label for="password" class="block text-xs lg:text-sm font-bold text-slate-700 mb-1">Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                                <svg class="w-4 h-4 text-[var(--mm-600)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input :type="revealPass ? 'text' : 'password'" id="password" name="password" required x-model="pass"
                                placeholder="Minimal 6 karakter"
                                class="input-field w-full rounded-xl pl-10 pr-11 py-2 sm:py-2.5 text-xs sm:text-sm font-medium placeholder:text-slate-400" />
                            <button type="button" @click="revealPass = !revealPass" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 focus:outline-none">
                                <svg x-show="!revealPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="revealPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Submit Registrasi Admin -->
                    <div class="pt-2">
                        <button type="submit" :disabled="loading"
                            class="btn-touch w-full rounded-xl py-3 font-display font-bold text-white text-xs sm:text-sm tracking-wider uppercase
                                bg-gradient-to-r from-[var(--mm-900)] via-[var(--mm-800)] to-[#0c4a6e]
                                hover:from-[var(--mm-900)] hover:to-[var(--mm-800)]
                                active:scale-[0.98] shadow-lg shadow-[var(--mm-900)]/20 smooth-all
                                border border-amber-400/40 focus:outline-none focus:ring-4 focus:ring-[var(--mm-600)]/20
                                flex items-center justify-center gap-2">
                            
                            <span x-show="!loading" class="flex items-center gap-2">
                                <span>DAFTARKAN AKUN ADMIN</span>
                                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </span>

                            <span x-show="loading" class="flex items-center gap-2" style="display: none;">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mendaftarkan Petugas...
                            </span>
                        </button>
                    </div>
                </form>

                <!-- Tautan Kembali ke Login Admin -->
                <div class="mt-5 sm:mt-6 text-center">
                    <a href="{{ route('admin.login') }}" class="text-[11px] sm:text-xs font-bold text-[var(--mm-800)] hover:text-[var(--mm-900)] transition-colors block">
                        Sudah punya akun admin? <span class="text-amber-600 underline">Masuk di Portal Admin</span>
                    </a>
                </div>

                <div class="gold-divider my-4 sm:my-6 opacity-25"></div>
            </div>

            <!-- Footer Bawah Mobile -->
            <div class="pt-2 text-center block lg:hidden pb-3 safe-bottom">
                <p class="text-[10px] sm:text-[11px] text-[var(--mm-600)] font-medium">
                    © {{ date('Y') }} Pemerintah Kab. Cirebon • Hak Cipta Dilindungi
                </p>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
