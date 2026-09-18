<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Kata Sandi | SIPERKOM Kabupaten Cirebon</title>
    
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

        /* Pengaturan Panel Responsif: Kartu dengan sudut melengkung di keempat sisi */
        .responsive-panel {
            width: 100%;
            margin: 0;
            border-radius: 0 0 32px 32px;
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

        .gold-divider {
            background: linear-gradient(90deg, transparent, var(--keraton-gold) 20%, var(--keraton-gold) 80%, transparent);
            height: 2px;
        }

        .input-field {
            border: 1.5px solid #E3DCC8;
            background-color: #ffffff !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .input-field:focus {
            outline: none;
            border-color: var(--mm-400);
            box-shadow: 0 0 0 4px rgba(46,158,196,0.15);
        }

        .smooth-all {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--batik-cream); }
        ::-webkit-scrollbar-thumb { background: var(--mm-600); border-radius: 4px; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- Pembungkus Utama Struktur Halaman Dua Kolom -->
    <div class="flex-1 flex flex-col lg:flex-row w-full min-h-screen items-center">

        <!-- =========================================================================
             PANEL KIRI: KARTU GAMBAR MOTIF MEGAMENDUNG
             ========================================================================= -->
        <div class="relative responsive-panel flex flex-col justify-between p-6 sm:p-10 lg:pl-14 lg:pr-14 text-white overflow-hidden bg-[var(--mm-900)] shrink-0">

            <!-- Gambar Latar Belakang Motif Batik Megamendung Khas Cirebon -->
            <img src="{{ asset('images/batik mega mendung.png') }}" class="absolute inset-0 w-full h-full object-cover object-center z-0 select-none pointer-events-none" alt="Batik Megamendung Cirebon" />

            <div class="video-overlay"></div>

            <!-- Header Logo & Instansi -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-11 h-11 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full bg-white/95 flex items-center justify-center shadow-xl ring-[2.5px] ring-[var(--keraton-gold)]/70 p-1.5 shrink-0 smooth-all hover:scale-105">
                    <img src="{{ asset('images/siperkom.png') }}" class="w-full h-full object-contain rounded-full" alt="Logo SIPERKOM" />
                </div>
                <div>
                    <h1 class="font-display font-extrabold text-xl sm:text-2xl tracking-tight leading-tight">SIPERKOM</h1>
                    <p class="text-[10px] sm:text-[11px] lg:text-xs font-medium text-[var(--mm-200)] tracking-wide">Pemerintah Kabupaten Cirebon</p>
                </div>
            </div>

            <!-- Konten Teks Tengah -->
            <div class="relative z-10 my-6 lg:my-auto py-2 space-y-3">
                <h2 class="font-display text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight tracking-tight">
                    Pemulihan Kata Sandi<br />Akun Terdaftar
                </h2>
                <p class="text-[var(--mm-200)] text-xs sm:text-sm max-w-md leading-relaxed font-medium">
                    Masukkan alamat email terdaftar Anda beserta kata sandi baru yang aman untuk memulihkan akses ke dashboard sistem pelaporan infrastruktur kecamatan.
                </p>

                <!-- Fitur Unggulan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <div class="bg-white/10 p-3 rounded-xl border border-white/20 backdrop-blur-md">
                        <span class="block font-display font-bold text-xs text-[var(--keraton-gold)] mb-0.5">Keamanan Akun</span>
                        <span class="text-[11px] text-[var(--mm-200)] leading-tight">Proses pemulihan terenkripsi dan terlindungi sistem.</span>
                    </div>
                    <div class="bg-white/10 p-3 rounded-xl border border-white/20 backdrop-blur-md">
                        <span class="block font-display font-bold text-xs text-[var(--keraton-gold)] mb-0.5">Akses Instan</span>
                        <span class="text-[11px] text-[var(--mm-200)] leading-tight">Dapat langsung masuk kembali setelah kata sandi diperbarui.</span>
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
             PANEL KANAN: FORMULIR RESET PASSWORD UTAMA
             ========================================================================= -->
        <div class="right-form-wrapper flex-1 flex flex-col justify-between p-6 sm:p-10 lg:px-12 lg:py-8 bg-[var(--batik-cream)] z-0">
            <div class="w-full max-w-md mx-auto bg-white rounded-3xl shadow-xl p-6 sm:p-8 border border-[#E3DCC8]/60 my-auto"
                 x-data="{ loading: false }">

                <!-- Header Sapaan -->
                <div class="mb-4 border-b border-gray-100 pb-2">
                    <h3 class="font-display text-base sm:text-lg font-extrabold text-[var(--mm-900)]">Atur Ulang Kata Sandi</h3>
                    <p class="text-xs text-[var(--mm-600)] mt-0.5 font-medium">Silakan tentukan kata sandi baru akun Anda</p>
                </div>

                <!-- Formulir Reset Password -->
                <form method="POST" action="{{ route('password.store') }}" @submit="loading = true" class="space-y-4 text-xs">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ request()->route('token') }}">

                    <!-- Alamat Email -->
                    <div>
                        <label for="email" class="block font-semibold text-[var(--ink)] mb-1">Alamat Email Terdaftar</label>
                        <input id="email" name="email" type="email" required autofocus value="{{ old('email', $request->email) }}" autocomplete="username"
                               class="input-field w-full rounded-xl px-3.5 py-2.5 text-xs font-medium text-[var(--ink)]" />
                        @if ($errors->has('email'))
                            <p class="mt-1 text-xs font-semibold text-[var(--keraton-maroon)]">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Kata Sandi Baru -->
                    <div>
                        <label for="password" class="block font-semibold text-[var(--ink)] mb-1">Kata Sandi Baru</label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" id="password" name="password" required autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="input-field w-full rounded-xl px-3.5 py-2.5 pr-10 text-xs font-medium text-[var(--ink)]" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-700 focus:outline-none">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411l0 0L21 21"/></svg>
                            </button>
                        </div>
                        @if ($errors->has('password'))
                            <p class="mt-1 text-xs font-semibold text-[var(--keraton-maroon)]">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Konfirmasi Kata Sandi Baru -->
                    <div>
                        <label for="password_confirmation" class="block font-semibold text-[var(--ink)] mb-1">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                   placeholder="••••••••"
                                   class="input-field w-full rounded-xl px-3.5 py-2.5 pr-10 text-xs font-medium text-[var(--ink)]" />
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-700 focus:outline-none">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411l0 0L21 21"/></svg>
                            </button>
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <p class="mt-1 text-xs font-semibold text-[var(--keraton-maroon)]">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                    </div>

                    <!-- Tombol Reset Password -->
                    <button type="submit" :disabled="loading"
                        class="w-full rounded-xl py-3 font-display font-bold text-white text-xs tracking-wide mt-2
                               bg-gradient-to-r from-[var(--mm-900)] via-[var(--mm-800)] to-[var(--mm-600)]
                               hover:from-[var(--mm-900)] hover:to-[var(--mm-900)]
                               shadow-lg shadow-[var(--mm-800)]/20 smooth-all
                               border border-[var(--keraton-gold)]/40 focus:outline-none focus:ring-4 focus:ring-[var(--mm-400)]/20
                               flex items-center justify-center gap-2">
                        
                        <span x-show="!loading">Reset Password</span>

                        <span x-show="loading" class="flex items-center gap-2" style="display: none;">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses Reset...
                        </span>
                    </button>
                </form>

                <div class="text-center text-[10px] text-gray-400 font-medium mt-4">
                    Sistem Pelaporan Perbaikan Komputer (SIPERKOM)
                </div>
            </div>

            <!-- Footer Bawah -->
            <div class="py-3 text-center block lg:hidden">
                <p class="text-[11px] text-[var(--mm-600)] font-medium">
                    © {{ date('Y') }} Pemerintah Kabupaten Cirebon — Hak Cipta Dilindungi Undang-Undang.
                </p>
            </div>
        </div>
    </div>

    <!-- Pustaka JavaScript Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>