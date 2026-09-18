<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar | SIPERKOM Kabupaten Cirebon</title>
    
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

        .video-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(7, 37, 68, 0.88) 0%, rgba(11, 61, 110, 0.78) 100%);
            z-index: 1;
        }

        /* ===== PANEL RESPONSIF MOBILE-FIRST ===== */
        /* Mobile: panel mengikuti ukuran konten secara natural, tidak dipaksa max-height */
        .responsive-panel {
            width: 100%;
            margin: 0;
            border-radius: 0;
            box-shadow: 0 10px 25px rgba(7, 37, 68, 0.1);
            z-index: 10;
        }

        /* Desktop: layout side-by-side penuh */
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

        .input-field {
            border: 1.5px solid #E3DCC8;
            background-color: #ffffff !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 44px;
        }
        
        .input-field:focus {
            outline: none;
            border-color: var(--mm-400);
            box-shadow: 0 0 0 4px rgba(46,158,196,0.15);
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

    <!-- Pembungkus Utama Struktur Halaman Dua Kolom -->
    <div class="flex-1 flex flex-col lg:flex-row w-full min-h-screen lg:items-center">

        <!-- =========================================================================
             PANEL KIRI: KARTU GAMBAR MOTIF BATIK MEGAMENDUNG
             ========================================================================= -->
        <div class="relative responsive-panel flex flex-col justify-center p-5 pb-10 sm:p-8 sm:pb-12 lg:p-10 lg:pl-14 lg:pr-14 lg:pb-10 lg:justify-between text-white overflow-hidden bg-[var(--mm-900)] shrink-0">

            <!-- Gambar Latar Belakang Motif Batik Megamendung Khas Cirebon -->
            <img src="{{ asset('images/batik mega mendung.png') }}" class="absolute inset-0 w-full h-full object-cover object-center z-0 select-none pointer-events-none" alt="Batik Megamendung Cirebon" />

            <div class="video-overlay"></div>

            <!-- Header Logo & Instansi -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-11 h-11 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full bg-white/95 flex items-center justify-center shadow-xl ring-[2.5px] ring-[var(--keraton-gold)]/70 p-1.5 shrink-0 smooth-all hover:scale-105">
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

            <!-- Konten Teks Tengah -->
            <div class="relative z-10 mt-4 sm:mt-6 lg:my-auto lg:py-2 space-y-2 sm:space-y-3">
                <h2 class="font-display text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-snug sm:leading-tight tracking-tight">
                    Pendaftaran Akun<br />Pelapor Pelayanan
                </h2>
                <p class="text-[var(--mm-200)] text-xs sm:text-sm max-w-md leading-relaxed font-medium">
                    Daftarkan akun pelapor desa Anda untuk mulai mengirimkan laporan kendala perangkat komputer ke tim teknisi Kabupaten Cirebon.
                </p>

                <!-- Fitur Unggulan (hidden di mobile kecil, tampil di sm+) -->
                <div class="hidden sm:grid grid-cols-2 gap-3 pt-2">
                    <div class="bg-white/10 p-3 rounded-xl border border-white/20 backdrop-blur-md smooth-all hover:bg-white/20 hover:scale-[1.02]">
                        <span class="block font-display font-bold text-xs text-[var(--keraton-gold)] mb-0.5">Akses Terpadu</span>
                        <span class="text-[11px] text-[var(--mm-200)] leading-tight">Pengelolaan registrasi khusus instansi wilayah resmi.</span>
                    </div>
                    <div class="bg-white/10 p-3 rounded-xl border border-white/20 backdrop-blur-md smooth-all hover:bg-white/20 hover:scale-[1.02]">
                        <span class="block font-display font-bold text-xs text-[var(--keraton-gold)] mb-0.5">Pendaftaran Aman</span>
                        <span class="text-[11px] text-[var(--mm-200)] leading-tight">Perlindindungan data akun terenkripsi secara aman.</span>
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

        <!-- =========================================================================
             PANEL KANAN: FORMULIR REGISTRASI
             ========================================================================= -->
        <div class="right-form-wrapper flex-1 flex flex-col justify-between px-5 pt-3.5 sm:pt-6 pb-6 sm:p-8 lg:px-12 lg:py-8 bg-white lg:bg-transparent z-10 w-full">
            <!-- Handle Bar Mobile (Memberi kesan bottom sheet native app modern tepat di atas lekukan) -->
            <div class="w-12 h-1.5 bg-slate-300/80 rounded-full mx-auto mb-3 sm:hidden"></div>

            <div class="w-full max-w-md mx-auto my-auto py-1 sm:py-4 lg:py-6"
                 x-data="{ loading: false }">

                <!-- Header Sapaan -->
                <div class="mb-5 sm:mb-6 lg:mb-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[var(--mm-800)]/5 border border-[var(--mm-800)]/10 text-[11px] font-semibold text-[var(--mm-800)] mb-2 sm:hidden">
                        <svg class="w-3.5 h-3.5 text-[var(--keraton-gold)]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/>
                        </svg>
                        <span>Registrasi Akun Pelapor</span>
                    </div>
                    <h3 class="font-display text-2xl sm:text-2xl lg:text-3xl font-extrabold text-[var(--mm-900)] tracking-tight mb-1 sm:mb-2">Buat Akun Pelapor</h3>
                    <p class="text-xs sm:text-xs lg:text-sm text-slate-500 font-medium">Lengkapi data Anda di bawah ini untuk mulai melapor</p>
                </div>

                <!-- Notifikasi Error -->
                @if ($errors->any())
                    <div class="mb-4 px-3.5 py-3 rounded-xl bg-[var(--keraton-maroon)]/10 border border-[var(--keraton-maroon)]/30 text-[var(--keraton-maroon)] text-[11px] sm:text-xs shadow-sm space-y-1">
                        <p class="font-bold">Mohon periksa kembali data Anda:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Registrasi -->
                <form method="POST" action="{{ route('register') }}" @submit="loading = true" class="space-y-3.5 sm:space-y-4 text-xs">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[var(--ink)] mb-1 sm:mb-1.5">Nama Lengkap</label>
                        <input id="name" name="name" type="text" required autofocus value="{{ old('name') }}" autocomplete="off"
                               placeholder="Nama Lengkap Anda"
                               class="input-field w-full rounded-xl px-3.5 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-[var(--ink)]" />
                    </div>

                    <!-- Email Aktif -->
                    <div>
                        <label for="email" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[var(--ink)] mb-1 sm:mb-1.5">Alamat Email Pelapor</label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" autocomplete="off"
                               placeholder="namakamu@gmail.com"
                               class="input-field w-full rounded-xl px-3.5 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-[var(--ink)]" />
                    </div>

                    <!-- Alamat (Ketik Manual) -->
                    <div>
                        <label for="alamat" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[var(--ink)] mb-1 sm:mb-1.5">Alamat</label>
                        <input id="alamat" name="alamat" type="text" required value="{{ old('alamat') }}" autocomplete="off"
                               placeholder="Masukkan alamat lengkap Anda"
                               class="input-field w-full rounded-xl px-3.5 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-[var(--ink)]" />
                    </div>

                    <!-- Kata Sandi -->
                    <div>
                        <label for="password" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[var(--ink)] mb-1 sm:mb-1.5">Kata Sandi</label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="new-password"
                                placeholder="••••••••"
                                class="input-field w-full rounded-xl px-3.5 py-2.5 sm:py-3 pr-10 text-xs sm:text-sm font-medium text-[var(--ink)]" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-700 focus:outline-none">
                                <svg x-show="!show" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411l0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Konfirmasi Kata Sandi -->
                    <div>
                        <label for="password_confirmation" class="block text-[11px] sm:text-xs lg:text-sm font-semibold text-[var(--ink)] mb-1 sm:mb-1.5">Konfirmasi Kata Sandi</label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                                class="input-field w-full rounded-xl px-3.5 py-2.5 sm:py-3 pr-10 text-xs sm:text-sm font-medium text-[var(--ink)]" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-700 focus:outline-none">
                                <svg x-show="!show" class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411l0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Daftar -->
                    <button type="submit" :disabled="loading"
                        class="btn-touch w-full rounded-xl py-3 sm:py-3.5 font-display font-bold text-white text-xs sm:text-sm tracking-wide uppercase mt-2
                            bg-gradient-to-r from-[var(--mm-900)] via-[var(--mm-800)] to-[var(--mm-600)]
                            hover:from-[var(--mm-900)] hover:to-[var(--mm-900)]
                            active:scale-[0.98]
                            shadow-lg shadow-[var(--mm-800)]/20 smooth-all
                            border border-[var(--keraton-gold)]/40 focus:outline-none focus:ring-4 focus:ring-[var(--mm-400)]/20
                            flex items-center justify-center gap-2">
                        
                        <span x-show="!loading" class="flex items-center gap-2">
                            <span>DAFTAR</span>
                            <svg class="w-4 h-4 text-[var(--keraton-gold-soft)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </span>

                        <span x-show="loading" class="flex items-center gap-2" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses Pendaftaran...
                        </span>
                    </button>
                </form>

                <!-- Tautan Kembali -->
                <div class="mt-5 sm:mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-[11px] sm:text-xs font-bold text-[var(--mm-800)] hover:text-[var(--mm-900)] transition-colors">
                        Sudah punya akun pelapor? <span class="text-[var(--mm-600)]">Masuk di sini</span>
                    </a>
                </div>

                <div class="gold-divider my-4 sm:my-6 opacity-25"></div>
            </div>

            <!-- Footer Bawah -->
            <div class="pt-2 text-center block lg:hidden pb-3 safe-bottom">
                <p class="text-[10px] sm:text-[11px] text-[var(--mm-600)] font-medium">
                    © {{ date('Y') }} Pemerintah Kab. Cirebon
                </p>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>