@php
    $user = Auth::user();

    $canChangeName = true;
    $nextChangeDate = null;
    $remainingTimeStr = null;
    if (!empty($user->name_changed_at)) {
        $cooldown = \Carbon\Carbon::parse($user->name_changed_at)->addDays(7);
        if (now()->lt($cooldown)) {
            $canChangeName = false;
            $nextChangeDate = $cooldown->translatedFormat('d F Y, H:i') . ' WIB';
            $diffInMinutes = (int) max(0, now()->diffInMinutes($cooldown));
            $daysLeft = intdiv($diffInMinutes, 1440);
            $hoursLeft = intdiv($diffInMinutes % 1440, 60);
            $minutesLeft = $diffInMinutes % 60;

            if ($daysLeft > 0) {
                $remainingTimeStr = $daysLeft . ' hari' . ($hoursLeft > 0 ? ' ' . $hoursLeft . ' jam' : '');
            } elseif ($hoursLeft > 0) {
                $remainingTimeStr = $hoursLeft . ' jam' . ($minutesLeft > 0 ? ' ' . $minutesLeft . ' menit' : '');
            } else {
                $remainingTimeStr = max(1, $minutesLeft) . ' menit';
            }
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Edit Profil Pelapor | SIPERKOM Kabupaten Cirebon</title>
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

        /* Optimasi Tampilan Layar HP / Mobile */
        html, body {
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
        }

        @supports (padding-bottom: env(safe-area-inset-bottom)) {
            body {
                padding-bottom: calc(2.5rem + env(safe-area-inset-bottom));
            }
        }

        /* Latar Belakang Gambar Batik Mega Mendung Pelapor */
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
            background: linear-gradient(90deg, transparent, var(--keraton-gold) 20%, var(--keraton-gold) 80%, transparent);
            height: 1.5px;
        }

        [x-cloak] { display: none !important; }

        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #F1ECE1; }
        ::-webkit-scrollbar-thumb { background: var(--mm-600); border-radius: 10px; }

        input:focus-visible, button:focus-visible {
            outline: 2px solid var(--keraton-gold);
            outline-offset: 2px;
        }

        button, a, [role="button"], .btn-touch {
            will-change: transform;
            transition: transform 0.16s cubic-bezier(0.34, 1.56, 0.64, 1),
                        background-color 0.15s ease,
                        border-color 0.15s ease,
                        box-shadow 0.2s cubic-bezier(0.34, 1.56, 0.64, 1),
                        opacity 0.15s ease;
        }

        button:active:not(:disabled),
        a:active,
        [role="button"]:active,
        .btn-touch:active:not(:disabled) {
            transform: scale(0.96) translateZ(0);
            transition: transform 0.08s ease-out;
        }
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)] min-h-screen selection:bg-[var(--keraton-gold)] selection:text-[var(--mm-900)] pb-6 lg:pb-12"
      x-data="editProfileApp('{{ $user->avatar ? asset('storage/'.$user->avatar) : '' }}')">

    <div class="max-w-2xl mx-auto px-3.5 sm:px-6 py-3.5 sm:py-8 space-y-3.5 sm:space-y-5">

        <!-- ================= TOP BAR HEADER: MOBILE & DESKTOP ================= -->
        <header class="bg-white rounded-2xl border border-[#E3DCC8] px-3.5 sm:px-5 py-2.5 sm:py-3 shadow-xs flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5 min-w-0">
                <a href="{{ route('pelapor.dashboard') }}" 
                   class="inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-[var(--batik-cream)] border border-[#E3DCC8] text-[var(--mm-900)] hover:bg-[#F3EBD8] active:scale-95 transition shadow-2xs shrink-0"
                   title="Kembali ke Dashboard">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="min-w-0">
                    <h1 class="font-display font-bold text-xs sm:text-sm text-[var(--mm-900)] truncate leading-tight">Pengaturan Profil</h1>
                    <p class="text-[9.5px] sm:text-[10px] text-gray-500 truncate leading-tight mt-0.5">Identitas Akun Pelapor</p>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0 border-l border-[#E3DCC8] pl-3">
                <button type="button" 
                        @click="openLogoutModal()"
                        class="px-2.5 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 active:scale-95 text-[#7A2331] border border-rose-200/80 text-[11px] font-bold transition flex items-center gap-1.5 shadow-2xs cursor-pointer"
                        title="Keluar dari Akun">
                    <svg class="w-3.5 h-3.5 text-[#7A2331]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span>Keluar</span>
                </button>
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] font-bold text-[var(--mm-900)] leading-tight">{{ date('d M Y') }}</p>
                    <p class="text-[8.5px] text-gray-400 leading-tight">Kab. Cirebon</p>
                </div>
                <img src="{{ asset('images/siperkom.png') }}" alt="SIPERKOM" class="w-6 h-6 object-contain shrink-0 hidden sm:block">
            </div>
        </header>

        <!-- ================= NOTIFIKASI SUKSES / STATUS ================= -->
        @if (session('status') === 'profile-updated' || session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-3.5 py-2.5 rounded-2xl text-xs font-semibold shadow-xs flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-600">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="min-w-0 text-[11px]">
                    <span class="font-bold block text-emerald-950">Berhasil Disimpan!</span>
                    <span class="text-emerald-800">{{ session('success') ?? 'Profil Anda berhasil diperbarui dengan sukses.' }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-900 px-3.5 py-2.5 rounded-2xl text-xs font-medium shadow-xs space-y-1">
                <div class="font-bold text-red-950 flex items-center gap-1.5 text-xs">
                    <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Mohon periksa kembali data Anda:</span>
                </div>
                <ul class="list-disc list-inside text-[11px] text-red-800 pl-1 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ================= FORM UTAMA PROFIL ================= -->
        <form action="{{ route('pelapor.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="space-y-3.5 sm:space-y-5">
                <!-- 1. KARTU HERO / AVATAR DENGAN BACKGROUND BATIK MEGA MENDUNG (PERSIS DASHBOARD, REKAP, RIWAYAT) -->
                <div class="relative overflow-hidden rounded-2xl shadow-lg text-white bg-[#0b2f56] border border-white/10 p-4 sm:p-6">
                    <!-- Layer Motif Mega Mendung dengan Masking Gradasi Atas-Bawah (100% Rata & Mulus) -->
                    <div class="absolute inset-0 pointer-events-none mega-mendung-bg"
                         style="-webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 14px, black calc(100% - 14px), transparent 100%); mask-image: linear-gradient(to bottom, transparent 0%, black 14px, black calc(100% - 14px), transparent 100%);"></div>

                <div class="relative z-10 flex items-center gap-3.5 sm:gap-5">
                    <!-- Foto / Avatar Bulat Halus dengan Kamera Overlay (Klik untuk Lihat & Ganti) -->
                    <div class="relative shrink-0">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-[var(--mm-800)] text-white font-bold font-display flex items-center justify-center text-lg sm:text-2xl overflow-hidden shadow-lg ring-3 ring-white/30 cursor-pointer hover:ring-[var(--keraton-gold)]/60 transition active:scale-95"
                             @click="openAvatarModal()"
                             title="Klik untuk melihat atau mengganti foto profil">
                            <template x-if="avatarPreview">
                                <img :src="avatarPreview" class="w-full h-full object-cover" alt="Avatar">
                            </template>
                            <template x-if="!avatarPreview">
                                <img src="{{ asset('images/pelapor-avatar.svg') }}" class="w-full h-full object-cover" alt="Avatar Pelapor">
                            </template>
                        </div>
                        
                        <!-- Tombol Mini Kamera -->
                        <button type="button" 
                                @click.stop="openAvatarModal()"
                                class="absolute -bottom-1 -right-1 w-6 h-6 sm:w-7 sm:h-7 rounded-xl bg-[var(--keraton-gold)] text-[var(--mm-900)] flex items-center justify-center shadow-md border-2 border-white hover:scale-105 active:scale-95 transition cursor-pointer"
                                title="Lihat atau ganti foto">
                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Detail Pengguna & Tombol Upload -->
                    <div class="flex-1 min-w-0 space-y-1">
                        <div class="flex items-center justify-between gap-2">
                            <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-white/10 border border-white/20 text-[9px] sm:text-[10px] text-[var(--keraton-gold-soft)] font-semibold backdrop-blur-xs">
                                <svg class="w-3 h-3 text-[var(--keraton-gold-soft)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span>Pelapor</span>
                            </div>
                        </div>
                        <h2 class="font-display font-extrabold text-sm sm:text-lg text-white truncate leading-tight">
                            {{ $user->name ?? 'Pelapor' }}
                        </h2>
                        <p class="text-[10.5px] sm:text-xs text-[var(--mm-200)] truncate leading-tight">
                            {{ $user->alamat ?? 'Kabupaten Cirebon' }}
                        </p>
                        
                        <div class="pt-1.5 space-y-1.5">
                            <div class="flex flex-wrap items-center gap-2">
                                <label for="avatar" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[var(--keraton-gold)] text-[var(--mm-900)] text-[10.5px] font-bold shadow-xs hover:bg-[var(--keraton-gold-soft)] active:scale-95 transition cursor-pointer">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Pilih Foto Baru</span>
                                </label>

                                <template x-if="avatarPreview">
                                    <button type="button" 
                                            @click="confirmDeleteAvatar()"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-500/25 hover:bg-rose-500/40 text-rose-100 border border-rose-400/40 text-[10.5px] font-bold shadow-xs active:scale-95 transition cursor-pointer"
                                            title="Hapus foto profil saat ini">
                                        <svg class="w-3 h-3 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus Foto</span>
                                    </button>
                                </template>

                                <span class="text-[9.5px] text-white/80 font-medium bg-black/20 backdrop-blur-xs px-2 py-0.5 rounded-md border border-white/10">
                                    Maks. <strong class="text-[var(--keraton-gold-soft)] font-bold">2 MB</strong> • JPG, PNG, WEBP
                                </span>
                            </div>
                            <input type="hidden" name="delete_avatar" :value="deleteAvatar ? '1' : '0'">
                            <input type="hidden" name="avatar_base64" :value="avatarBase64 || ''">
                            <input type="file" name="avatar" id="avatar" x-ref="avatarInput" accept="image/png, image/jpeg, image/jpg, image/webp" @change="handleAvatarUpload($event)" class="hidden">
                            
                            <!-- Notifikasi Error Validasi Avatar (Client-side) -->
                            <div x-show="avatarError" x-cloak class="p-2 rounded-lg bg-red-500/90 text-white text-[10px] font-semibold flex items-center gap-1.5 shadow-sm border border-red-400">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <span x-text="avatarError"></span>
                            </div>

                            @error('avatar')
                                <p class="text-red-300 text-[10px] font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. KARTU INPUT FORM IDENTITAS -->
            <div class="bg-white rounded-2xl border border-[#E3DCC8] shadow-xs p-4 sm:p-5 space-y-3.5">
                <div class="flex items-center justify-between border-b border-[#E3DCC8]/70 pb-2.5">
                    <div>
                        <h3 class="font-display font-bold text-xs sm:text-sm text-[var(--mm-900)]">Informasi Akun Pelapor</h3>
                        <p class="text-[10px] text-gray-500 mt-0.5">Pastikan data nama dan akun sesuai dengan penugasan di kantor.</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-md bg-[var(--batik-cream)] border border-[#E3DCC8] text-[9.5px] font-bold text-[var(--mm-800)]">
                        Terverifikasi
                    </span>
                </div>

                <div class="space-y-3 text-xs">
                    <!-- Nama Lengkap (Editable dengan batasan 1 minggu sekali) -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label for="name" class="font-bold text-[11px] text-gray-700 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Nama Lengkap Pelapor</span>
                                <span class="text-red-500">*</span>
                            </label>
                            @if(!$canChangeName)
                                <span class="text-[9px] text-amber-800 bg-amber-100/90 border border-amber-300 px-1.5 py-0.5 rounded-md font-bold inline-flex items-center gap-1 shadow-2xs">
                                    <svg class="w-2.5 h-2.5 text-amber-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    <span>Terkunci (1 Minggu Sekali)</span>
                                </span>
                            @else
                                <span class="text-[9px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded-md font-semibold inline-flex items-center gap-0.5">
                                    ✓ Dapat Diubah
                                </span>
                            @endif
                        </div>

                        @if($canChangeName)
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required 
                                   placeholder="Nama lengkap Anda"
                                   class="w-full bg-[var(--batik-cream)]/40 border border-[#E3DCC8] focus:border-[var(--mm-600)] focus:bg-white rounded-xl px-3 py-2 text-xs font-semibold text-[var(--mm-900)] transition shadow-2xs capitalize placeholder:font-normal placeholder:text-gray-400">
                            <p class="text-[9.5px] text-gray-500 flex items-center gap-1 mt-1">
                                <svg class="w-3 h-3 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Catatan: Nama hanya dapat diubah 1 kali setiap 7 hari (1 minggu).</span>
                            </p>
                        @else
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ $user->name }}" 
                                   readonly 
                                   class="w-full bg-gray-50/90 border border-[#E3DCC8]/80 text-gray-700 font-bold rounded-xl px-3 py-2 text-xs cursor-not-allowed shadow-2xs"
                                   title="Nama terkunci sementara karena batas perubahan 1 minggu sekali">
                            
                            <!-- Box Notifikasi Cooldown 1 Minggu yang Elegan & Rapi -->
                            <div class="mt-2 p-3 rounded-2xl bg-gradient-to-r from-amber-50/95 to-amber-100/60 border border-amber-300/80 shadow-2xs space-y-2">
                                <div class="flex items-center justify-between gap-2 flex-wrap">
                                    <div class="flex items-center gap-1.5 text-amber-950 font-bold text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-amber-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Batas Waktu Perubahan Nama</span>
                                    </div>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[9.5px] font-extrabold bg-amber-200/90 text-amber-950 shadow-2xs">
                                        <span>⏳ Tersisa {{ $remainingTimeStr }}</span>
                                    </span>
                                </div>

                                <p class="text-[10.5px] text-amber-900 leading-snug">
                                    Anda baru dapat mengganti nama profil kembali pada <strong class="font-bold text-amber-950">{{ $nextChangeDate }}</strong>.
                                </p>

                                <div class="pt-1 flex items-center gap-1.5 text-[9.5px] text-amber-800">
                                    <svg class="w-3 h-3 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Jeda 7 hari diterapkan agar data nama pelapor tetap sinkron dan konsisten.</span>
                                </div>
                            </div>
                        @endif

                        @error('name')
                            <p class="text-red-600 text-[10.5px] font-semibold mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Aktif (Readonly) -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label class="font-bold text-[11px] text-gray-700 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>Alamat Email Akun</span>
                            </label>
                            <span class="text-[9px] text-gray-500 bg-gray-100 border border-gray-200 px-1.5 py-0.2 rounded font-semibold inline-flex items-center gap-0.5">
                                🔒 Terkunci
                            </span>
                        </div>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               readonly 
                               class="w-full bg-gray-50/80 border border-[#E3DCC8]/70 text-gray-600 rounded-xl px-3 py-2 text-xs font-medium cursor-not-allowed shadow-2xs" 
                               title="Email terdaftar dan tidak dapat diubah">
                        <p class="text-[9.5px] text-gray-400">Email akun bersifat permanen sebagai kredensial login Anda.</p>
                    </div>

                    <!-- Alamat (Dapat Diubah) -->
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label for="alamat" class="font-bold text-[11px] text-gray-700 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Alamat</span>
                            </label>
                            <span class="text-[9px] text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded-md font-semibold inline-flex items-center gap-0.5">
                                ✓ Dapat Diubah
                            </span>
                        </div>
                        <input type="text" 
                               name="alamat" 
                               id="alamat" 
                               value="{{ old('alamat', $user->alamat ?: ($user->kecamatan ?? '')) }}" 
                               placeholder="Contoh: Jl. Sunan Kalijaga No. 7, Sumber, Cirebon" 
                               class="w-full bg-[var(--batik-cream)]/40 border border-[#E3DCC8] focus:border-[var(--mm-600)] focus:bg-white rounded-xl px-3 py-2 text-xs font-semibold text-[var(--mm-900)] transition shadow-2xs placeholder:font-normal placeholder:text-gray-400">
                        <p class="text-[9.5px] text-gray-500 flex items-center gap-1 mt-1">
                            <svg class="w-3 h-3 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Alamat domisili atau instansi penugasan pelapor di wilayah Kabupaten Cirebon.</span>
                        </p>
                        @error('alamat')
                            <p class="text-red-600 text-[10.5px] font-semibold mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tombol Simpan Profil -->
                <div class="pt-2">
                    <button type="submit" 
                            name="action" 
                            value="save_only"
                            class="w-full py-2.5 sm:py-3 px-4 bg-[var(--mm-800)] hover:bg-[var(--mm-900)] active:scale-[0.98] text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Profil</span>
                    </button>
                </div>
            </div>
        </div>
        </form>

        <!-- ================= 3. ZONA BERBAHAYA (HAPUS AKUN) ================= -->
        <div class="bg-red-50/60 rounded-2xl border border-red-200/80 p-3.5 sm:p-4 space-y-2.5 shadow-2xs">
            <div class="flex items-start justify-between gap-2">
                <div class="space-y-0.5">
                    <h4 class="font-display font-bold text-xs text-red-950 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span>Zona Berbahaya</span>
                    </h4>
                    <p class="text-[10px] text-red-700 leading-tight">
                        Tindakan ini permanen. Seluruh riwayat laporan pengaduan Anda akan terhapus dari sistem.
                    </p>
                </div>
            </div>

            <form action="{{ route('pelapor.profile.destroy') }}" 
                  method="POST" 
                  onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus akun ini secara permanen? Seluruh data laporan akan hilang.');" 
                  class="pt-1">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full sm:w-auto px-3.5 py-2 bg-white hover:bg-red-50 active:scale-95 text-red-700 border border-red-300 rounded-xl text-[11px] font-bold transition shadow-2xs flex items-center justify-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <span>Hapus Akun Saya</span>
                </button>
            </form>
        </div>

        <!-- Spacer Khusus Mobile dengan jarak ideal (persis sama seperti dashboard) -->
        <div class="block md:hidden w-full pointer-events-none select-none" style="height: 2.25rem;" aria-hidden="true"></div>

    </div>

    <!-- KOMPONEN NAVIGASI BAWAH MOBILE -->
    @include('pelapor.partials.bottom-nav', ['active' => 'profil'])

    <!-- Form Tersembunyi untuk Hapus Avatar -->
    <form id="delete-avatar-form" action="{{ route('pelapor.profile.avatar.destroy') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- ================= MODAL LIHAT & GANTI FOTO PROFIL ================= -->
    <div x-show="showAvatarModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
         @keydown.escape.window="closeAvatarModal()">
        
        <!-- Backdrop Blur Gelap -->
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity"
             @click="closeAvatarModal()"
             x-show="showAvatarModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Kartu Modal Tampilan Foto -->
        <div class="relative z-10 w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden border border-[#E3DCC8] transform transition-all"
             x-show="showAvatarModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            
            <!-- Header Modal -->
            <div class="px-4 py-3 bg-[var(--batik-cream)] border-b border-[#E3DCC8] flex items-center justify-between">
                <div>
                    <h3 class="font-display font-bold text-xs text-[var(--mm-900)]">Foto Profil Pelapor</h3>
                </div>
                <button type="button" 
                        @click="closeAvatarModal()" 
                        class="w-7 h-7 rounded-xl bg-white border border-[#E3DCC8] text-gray-500 hover:text-gray-800 hover:bg-gray-100 flex items-center justify-center transition cursor-pointer"
                        title="Tutup preview">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Tampilan Foto Ukuran Penuh -->
            <div class="p-4 bg-slate-900/5 flex flex-col items-center justify-center">
                <template x-if="avatarPreview">
                    <div class="relative w-64 h-64 sm:w-72 sm:h-72 rounded-2xl overflow-hidden shadow-lg ring-4 ring-[var(--keraton-gold)]/30 bg-black flex items-center justify-center">
                        <img :src="avatarPreview" class="w-full h-full object-cover" alt="Foto Profil Pelapor">
                    </div>
                </template>
                <template x-if="!avatarPreview">
                    <div class="relative w-48 h-48 sm:w-56 sm:h-56 rounded-2xl overflow-hidden shadow-lg ring-4 ring-[var(--keraton-gold)]/30 bg-[#072544] flex items-center justify-center p-2">
                        <img src="{{ asset('images/pelapor-avatar.svg') }}" class="w-full h-full object-contain" alt="Foto Profil Pelapor">
                    </div>
                </template>
                <p class="text-[11px] text-gray-600 font-semibold mt-3 text-center">
                    {{ $user->name ?? 'Pelapor' }}
                </p>
                <p class="text-[10px] text-gray-400 text-center">
                    {{ $user->alamat ?? 'Kabupaten Cirebon' }}
                </p>

                <!-- Informasi Ketentuan Upload Foto Profil -->
                <div class="mt-3.5 w-full max-w-xs bg-amber-50/90 border border-amber-200/90 rounded-xl p-2.5 text-center shadow-2xs">
                    <p class="text-[10.5px] text-amber-900 font-bold flex items-center justify-center gap-1">
                        <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Ketentuan Upload Foto Profil (PP)
                    </p>
                    <p class="text-[9.5px] text-amber-800 mt-1 leading-relaxed">
                        Maksimal ukuran file: <strong class="font-bold text-amber-950">2 Megabita (2 MB)</strong><br>
                        Format yang didukung: <strong class="font-bold text-amber-950">JPG, JPEG, PNG, WEBP</strong>
                    </p>
                </div>
            </div>

            <!-- Tombol Aksi: Ganti Foto Baru, Hapus Foto & Tutup -->
            <div class="p-3.5 bg-white border-t border-[#E3DCC8] flex flex-wrap sm:flex-nowrap items-center gap-2">
                <button type="button" 
                        @click="triggerAvatarInput()"
                        class="flex-1 py-2.5 px-3 bg-[var(--mm-800)] hover:bg-[var(--mm-900)] active:scale-95 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-[var(--keraton-gold-soft)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Ganti Foto Baru</span>
                </button>

                <template x-if="avatarPreview">
                    <button type="button" 
                            @click="confirmDeleteAvatar()"
                            class="py-2.5 px-3.5 bg-rose-50 hover:bg-rose-100 active:scale-95 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer"
                            title="Hapus foto profil">
                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Hapus Foto</span>
                    </button>
                </template>

                <button type="button" 
                        @click="closeAvatarModal()"
                        class="py-2.5 px-4 bg-gray-100 hover:bg-gray-200 active:scale-95 text-gray-700 rounded-xl text-xs font-semibold transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL CROP / SETTING PAS-KAN FOTO PROFIL ================= -->
    <div x-show="showCropModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overscroll-contain"
         style="touch-action: none;"
         @keydown.escape.window="cancelCrop()">
        
        <!-- Backdrop Blur Gelap -->
        <div class="fixed inset-0 bg-black/75 backdrop-blur-xs transition-opacity"
             @click="cancelCrop()"></div>

        <!-- Kartu Modal Crop -->
        <div class="relative z-10 w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden border border-[#E3DCC8] flex flex-col max-h-[94vh]"
             x-show="showCropModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">
            
            <!-- Header Modal -->
            <div class="px-4 py-3 bg-[var(--mm-900)] text-white flex items-center justify-between border-b border-white/10 shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-[var(--keraton-gold)]/20 border border-[var(--keraton-gold)] flex items-center justify-center text-[var(--keraton-gold-soft)]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-xs sm:text-sm text-white leading-tight">Sesuaikan Foto Profil</h3>
                        <p class="text-[9.5px] text-[var(--mm-200)]">Geser dan perbesar agar pas di lingkaran</p>
                    </div>
                </div>
                <button type="button" @click="cancelCrop()" class="w-7 h-7 rounded-lg hover:bg-white/10 text-white/80 hover:text-white flex items-center justify-center text-sm font-bold transition cursor-pointer">✕</button>
            </div>

            <!-- Body Area Crop -->
            <div class="p-3.5 sm:p-4 flex-1 overflow-y-auto space-y-3 bg-slate-50 flex flex-col items-center">
                
                <!-- Viewport Canvas Interaktif (Dimensi Presisi 280x280) -->
                <div class="relative mx-auto rounded-2xl overflow-hidden bg-slate-900 shadow-inner select-none cursor-grab active:cursor-grabbing border border-slate-300 shrink-0"
                     style="width: 280px; height: 280px; min-width: 280px; min-height: 280px; touch-action: none;"
                     @pointerdown.prevent.stop="startDrag($event)"
                     @pointermove.prevent.stop="onDrag($event)"
                     @pointerup.prevent.stop="stopDrag($event)"
                     @pointercancel.prevent.stop="stopDrag($event)"
                     @wheel.prevent="onWheel($event)"
                     @touchstart.prevent.stop="onTouchStart($event)"
                     @touchmove.prevent.stop="onTouchMove($event)"
                     @touchend.prevent.stop="onTouchEnd($event)">
                    
                    <canvas x-ref="cropCanvas" width="280" height="280" style="width: 280px; height: 280px; display: block;"></canvas>
                    
                    <!-- SVG Masking Lingkaran Emas Transparan -->
                    <svg class="absolute inset-0 pointer-events-none" style="width: 280px; height: 280px;" viewBox="0 0 280 280">
                        <defs>
                            <mask id="cropHolePelapor">
                                <rect width="280" height="280" fill="white" />
                                <circle cx="140" cy="140" r="125" fill="black" />
                            </mask>
                        </defs>
                        <rect width="280" height="280" fill="rgba(7, 37, 68, 0.75)" mask="url(#cropHolePelapor)" />
                        <circle cx="140" cy="140" r="125" fill="none" stroke="#C9A227" stroke-width="2.5" stroke-dasharray="8 5" />
                    </svg>

                    <!-- Hint Tooltip -->
                    <div class="absolute bottom-2 left-1/2 -translate-x-1/2 px-2.5 py-1 rounded-full bg-black/60 backdrop-blur-xs text-[9.5px] text-white/90 font-medium pointer-events-none flex items-center gap-1 shadow-sm whitespace-nowrap">
                        <span>👆 Geser foto untuk posisi pas</span>
                    </div>
                </div>

                <!-- Slider Zoom & Kontrol -->
                <div class="w-full max-w-[280px] space-y-2">
                    <div class="flex items-center justify-between text-[10px] font-bold text-slate-600">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                            Perbesar / Perkecil:
                        </span>
                        <span class="text-[var(--mm-800)] font-mono font-bold" x-text="Math.round(zoomLevel * 100) + '%'"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="zoomOut()" class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 flex items-center justify-center font-bold text-sm shadow-2xs active:scale-95 transition cursor-pointer">−</button>
                        <input type="range" min="1" max="3" step="0.02" x-model="zoomLevel" @input="onZoomChange()" class="flex-1 accent-[var(--mm-600)] cursor-pointer h-1.5 bg-slate-200 rounded-lg">
                        <button type="button" @click="zoomIn()" class="w-7 h-7 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 flex items-center justify-center font-bold text-sm shadow-2xs active:scale-95 transition cursor-pointer">+</button>
                    </div>

                    <!-- Tombol Aksi Putar & Pusatkan + Live Mini Preview -->
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="rotate90()" class="px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-[10px] font-bold flex items-center gap-1 shadow-2xs active:scale-95 transition cursor-pointer" title="Putar 90 derajat">
                                <svg class="w-3 h-3 text-[var(--mm-800)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span>Putar 90°</span>
                            </button>
                            <button type="button" @click="resetCrop()" class="px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 text-[10px] font-bold flex items-center gap-1 shadow-2xs active:scale-95 transition cursor-pointer" title="Kembalikan ke tengah">
                                <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                <span>Pusatkan</span>
                            </button>
                        </div>

                        <!-- Mini Preview -->
                        <div class="flex items-center gap-1.5 bg-white border border-slate-200 px-2 py-1 rounded-xl shadow-2xs">
                            <canvas x-ref="miniPreviewCanvas" width="28" height="28" class="w-6 h-6 sm:w-7 sm:h-7 rounded-full shadow-inner border border-[var(--keraton-gold)] block"></canvas>
                            <span class="text-[9px] font-bold text-slate-500">Pratinjau</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Tombol -->
            <div class="px-4 py-3 bg-white border-t border-slate-200 flex items-center gap-2 shrink-0">
                <button type="button" @click="cancelCrop()" class="flex-1 py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition active:scale-95 cursor-pointer">
                    Batal
                </button>
                <button type="button" @click="applyCrop()" class="flex-1 py-2 px-3 bg-[var(--mm-800)] hover:bg-[var(--mm-900)] text-white rounded-xl text-xs font-bold transition shadow-sm active:scale-95 flex items-center justify-center gap-1.5 cursor-pointer border border-white/10">
                    <svg class="w-3.5 h-3.5 text-[var(--keraton-gold-soft)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Gunakan Foto Ini</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL KONFIRMASI KELUAR AKUN (INTERAKTIF & MODERN) ================= -->
    <div x-show="showLogoutModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6"
         @keydown.escape.window="closeLogoutModal()">
        
        <!-- Backdrop Blur Gelap -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity"
             @click="closeLogoutModal()"
             x-show="showLogoutModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Kartu Modal Konfirmasi -->
        <div class="relative z-10 w-full max-w-xs sm:max-w-sm bg-white rounded-3xl shadow-2xl p-5 sm:p-6 border border-[#E3DCC8] text-center transform transition-all"
             x-show="showLogoutModal"
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

    <!-- Script AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function editProfileApp(existingAvatar) {
            return {
                avatarPreview: existingAvatar || null,
                savedAvatar: existingAvatar || null,
                avatarBase64: null,
                deleteAvatar: false,
                avatarError: null,
                showAvatarModal: false,
                showLogoutModal: false,
                showCropModal: false,
                maxSizeMB: 5,

                // Properti Canvas Cropper (Tersimpan sebagai nilai primitif)
                cropBoxSize: 280,
                cropImgWidth: 0,
                cropImgHeight: 0,
                minScale: 1.0,
                cropScale: 1.0,
                zoomLevel: 1.0,
                cropOffsetX: 0,
                cropOffsetY: 0,
                cropRotation: 0,
                isDragging: false,
                dragStartX: 0,
                dragStartY: 0,
                touchStartDist: null,
                initialTouchScale: 1.0,

                init() {
                    this.$watch('showCropModal', (value) => {
                        if (value) {
                            document.documentElement.classList.add('overflow-hidden', 'touch-none');
                            document.body.classList.add('overflow-hidden', 'touch-none');
                        } else if (!this.showAvatarModal && !this.showLogoutModal) {
                            document.documentElement.classList.remove('overflow-hidden', 'touch-none');
                            document.body.classList.remove('overflow-hidden', 'touch-none');
                        }
                    });
                    this.$watch('showAvatarModal', (value) => {
                        if (value) {
                            document.documentElement.classList.add('overflow-hidden');
                            document.body.classList.add('overflow-hidden');
                        } else if (!this.showCropModal && !this.showLogoutModal) {
                            document.documentElement.classList.remove('overflow-hidden');
                            document.body.classList.remove('overflow-hidden');
                        }
                    });
                    this.$watch('showLogoutModal', (value) => {
                        if (value) {
                            document.documentElement.classList.add('overflow-hidden');
                            document.body.classList.add('overflow-hidden');
                        } else if (!this.showCropModal && !this.showAvatarModal) {
                            document.documentElement.classList.remove('overflow-hidden');
                            document.body.classList.remove('overflow-hidden');
                        }
                    });
                },

                openLogoutModal() {
                    this.showLogoutModal = true;
                },
                closeLogoutModal() {
                    this.showLogoutModal = false;
                },
                openAvatarModal() {
                    this.showAvatarModal = true;
                },
                closeAvatarModal() {
                    this.showAvatarModal = false;
                },
                triggerAvatarInput() {
                    this.closeAvatarModal();
                    this.$refs.avatarInput.click();
                },
                confirmDeleteAvatar() {
                    if (this.savedAvatar) {
                        if (confirm('Apakah Anda yakin ingin menghapus foto profil ini dan kembali menggunakan avatar bawaan?')) {
                            const deleteForm = document.getElementById('delete-avatar-form');
                            if (deleteForm) {
                                deleteForm.submit();
                                return;
                            }
                        } else {
                            return;
                        }
                    }
                    this.avatarPreview = null;
                    this.avatarBase64 = null;
                    this.deleteAvatar = true;
                    if (this.$refs.avatarInput) {
                        this.$refs.avatarInput.value = '';
                    }
                    this.avatarError = null;
                    this.closeAvatarModal();
                },
                handleAvatarUpload(event) {
                    this.avatarError = null;
                    this.deleteAvatar = false;
                    const file = event.target.files[0];
                    if (!file) return;

                    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
                    if (!allowedTypes.includes(file.type.toLowerCase())) {
                        this.avatarError = 'Format file tidak didukung! Gunakan gambar JPG, PNG, atau WEBP.';
                        event.target.value = '';
                        return;
                    }

                    const maxSizeBytes = this.maxSizeMB * 1024 * 1024;
                    if (file.size > maxSizeBytes) {
                        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                        this.avatarError = `Ukuran foto (${fileSizeMB} MB) terlalu besar, maksimal ${this.maxSizeMB} MB!`;
                        event.target.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        const img = new Image();
                        img.onload = () => {
                            this.initCropper(img);
                        };
                        img.onerror = () => {
                            this.avatarError = 'Gagal membaca berkas gambar.';
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },

                initCropper(img) {
                    window._siperkomCropImg = img;
                    const nw = img.naturalWidth || img.width || 512;
                    const nh = img.naturalHeight || img.height || 512;
                    this.cropImgWidth = nw;
                    this.cropImgHeight = nh;
                    this.cropBoxSize = 280;

                    // Skala dasar: Sisi terkecil minimal 300px agar penuh dan leluasa digeser tanpa celah
                    this.minScale = Math.max(300 / nw, 300 / nh);
                    this.cropScale = this.minScale;
                    this.zoomLevel = 1.0;
                    this.cropOffsetX = 0;
                    this.cropOffsetY = 0;
                    this.cropRotation = 0;
                    this.isDragging = false;
                    this.touchStartDist = null;
                    this.clampOffsets();
                    this.showCropModal = true;

                    this.$nextTick(() => {
                        this.drawCropCanvas();
                    });
                },

                cancelCrop() {
                    this.showCropModal = false;
                    this.isDragging = false;
                    window._siperkomCropImg = null;
                    if (this.$refs.avatarInput && !this.avatarBase64) {
                        this.$refs.avatarInput.value = '';
                    }
                },

                clampOffsets() {
                    const img = window._siperkomCropImg;
                    const nw = this.cropImgWidth || (img ? (img.naturalWidth || img.width) : 512) || 512;
                    const nh = this.cropImgHeight || (img ? (img.naturalHeight || img.height) : 512) || 512;
                    const isSideways = this.cropRotation === 90 || this.cropRotation === 270;
                    const curW = isSideways ? nh : nw;
                    const curH = isSideways ? nw : nh;

                    // Pastikan skala tidak pernah di bawah ukuran lingkaran diameter 250px
                    const minCoverScale = Math.max(250 / curW, 250 / curH);
                    if (!this.cropScale || this.cropScale < minCoverScale) {
                        this.cropScale = minCoverScale;
                    }

                    const halfW = (curW * this.cropScale) / 2;
                    const halfH = (curH * this.cropScale) / 2;
                    const radius = 125; // radius lingkaran emas di tengah (cx=140, cy=140)

                    // Batas geser maksimum agar lingkaran r=125 tidak pernah tembus/bocor
                    const maxOffsetX = Math.max(0, halfW - radius);
                    const maxOffsetY = Math.max(0, halfH - radius);
                    this.cropOffsetX = Math.max(-maxOffsetX, Math.min(maxOffsetX, this.cropOffsetX || 0));
                    this.cropOffsetY = Math.max(-maxOffsetY, Math.min(maxOffsetY, this.cropOffsetY || 0));
                },

                drawCropCanvas() {
                    const canvas = this.$refs.cropCanvas;
                    const img = window._siperkomCropImg;
                    if (!canvas || !img) return;

                    this.clampOffsets();

                    const ctx = canvas.getContext('2d');
                    const size = 280;

                    if (canvas.width !== size || canvas.height !== size) {
                        canvas.width = size;
                        canvas.height = size;
                    }

                    ctx.save();
                    ctx.clearRect(0, 0, size, size);

                    // Latar kanvas
                    ctx.fillStyle = '#072544';
                    ctx.fillRect(0, 0, size, size);

                    // Posisikan gambar di tengah kanvas (140, 140) + offset
                    ctx.translate(size / 2 + this.cropOffsetX, size / 2 + this.cropOffsetY);
                    ctx.rotate((this.cropRotation * Math.PI) / 180);
                    ctx.scale(this.cropScale, this.cropScale);

                    const nw = this.cropImgWidth || img.naturalWidth || img.width || 512;
                    const nh = this.cropImgHeight || img.naturalHeight || img.height || 512;
                    ctx.drawImage(img, -nw / 2, -nh / 2, nw, nh);

                    ctx.restore();

                    this.updateMiniPreview();
                },

                updateMiniPreview() {
                    const mini = this.$refs.miniPreviewCanvas;
                    const img = window._siperkomCropImg;
                    if (!mini || !img) return;

                    const ctx = mini.getContext('2d');
                    const size = 56;
                    if (mini.width !== size || mini.height !== size) {
                        mini.width = size;
                        mini.height = size;
                    }

                    ctx.save();
                    ctx.clearRect(0, 0, size, size);

                    // Masking bentuk lingkaran
                    ctx.beginPath();
                    ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2);
                    ctx.clip();

                    const factor = size / 280;
                    ctx.translate(size / 2 + this.cropOffsetX * factor, size / 2 + this.cropOffsetY * factor);
                    ctx.rotate((this.cropRotation * Math.PI) / 180);
                    ctx.scale(this.cropScale * factor, this.cropScale * factor);

                    const nw = this.cropImgWidth || img.naturalWidth || img.width || 512;
                    const nh = this.cropImgHeight || img.naturalHeight || img.height || 512;
                    ctx.drawImage(img, -nw / 2, -nh / 2, nw, nh);

                    ctx.restore();
                },

                startDrag(e) {
                    if (e.pointerType === 'touch' && !e.isPrimary) return;
                    if (e.cancelable) e.preventDefault();
                    if (e.stopPropagation) e.stopPropagation();
                    this.isDragging = true;
                    if (e.target && e.target.setPointerCapture && e.pointerId) {
                        try { e.target.setPointerCapture(e.pointerId); } catch(err) {}
                    }
                    this.dragStartX = e.clientX - this.cropOffsetX;
                    this.dragStartY = e.clientY - this.cropOffsetY;
                },

                onDrag(e) {
                    if (!this.isDragging) return;
                    if (e.cancelable) e.preventDefault();
                    if (e.stopPropagation) e.stopPropagation();
                    this.cropOffsetX = e.clientX - this.dragStartX;
                    this.cropOffsetY = e.clientY - this.dragStartY;
                    this.clampOffsets();
                    this.drawCropCanvas();
                },

                stopDrag(e) {
                    this.isDragging = false;
                    if (e && e.target && e.target.releasePointerCapture && e.pointerId) {
                        try { e.target.releasePointerCapture(e.pointerId); } catch(err) {}
                    }
                },

                onWheel(e) {
                    if (e.deltaY < 0) {
                        this.zoomIn();
                    } else {
                        this.zoomOut();
                    }
                },

                onTouchStart(e) {
                    if (e.touches.length === 2) {
                        this.isDragging = false;
                        this.touchStartDist = Math.hypot(
                            e.touches[0].clientX - e.touches[1].clientX,
                            e.touches[0].clientY - e.touches[1].clientY
                        );
                        this.initialTouchScale = this.cropScale;
                    }
                },

                onTouchMove(e) {
                    if (e.touches.length === 2 && this.touchStartDist) {
                        const currentDist = Math.hypot(
                            e.touches[0].clientX - e.touches[1].clientX,
                            e.touches[0].clientY - e.touches[1].clientY
                        );
                        const ratio = currentDist / this.touchStartDist;
                        const newScale = Math.max(this.minScale, Math.min(this.minScale * 3.5, this.initialTouchScale * ratio));
                        this.cropScale = newScale;
                        this.zoomLevel = Math.max(1.0, Math.min(3.0, (newScale / this.minScale))).toFixed(2);
                        this.clampOffsets();
                        this.drawCropCanvas();
                    }
                },

                onTouchEnd(e) {
                    if (e.touches && e.touches.length < 2) {
                        this.touchStartDist = null;
                    }
                },

                onZoomChange() {
                    this.cropScale = this.minScale * parseFloat(this.zoomLevel);
                    this.clampOffsets();
                    this.drawCropCanvas();
                },

                zoomIn() {
                    this.zoomLevel = Math.min(3.0, (parseFloat(this.zoomLevel) + 0.1).toFixed(2));
                    this.onZoomChange();
                },

                zoomOut() {
                    this.zoomLevel = Math.max(1.0, (parseFloat(this.zoomLevel) - 0.1).toFixed(2));
                    this.onZoomChange();
                },

                rotate90() {
                    this.cropRotation = (this.cropRotation + 90) % 360;
                    const img = window._siperkomCropImg;
                    const nw = this.cropImgWidth || (img ? (img.naturalWidth || img.width) : 512) || 512;
                    const nh = this.cropImgHeight || (img ? (img.naturalHeight || img.height) : 512) || 512;
                    const isSideways = this.cropRotation === 90 || this.cropRotation === 270;
                    const curW = isSideways ? nh : nw;
                    const curH = isSideways ? nw : nh;
                    this.minScale = Math.max(300 / curW, 300 / curH);
                    this.cropScale = this.minScale * parseFloat(this.zoomLevel);
                    this.clampOffsets();
                    this.drawCropCanvas();
                },

                resetCrop() {
                    this.cropOffsetX = 0;
                    this.cropOffsetY = 0;
                    this.cropRotation = 0;
                    this.zoomLevel = 1.0;
                    const img = window._siperkomCropImg;
                    const nw = this.cropImgWidth || (img ? (img.naturalWidth || img.width) : 512) || 512;
                    const nh = this.cropImgHeight || (img ? (img.naturalHeight || img.height) : 512) || 512;
                    this.minScale = Math.max(300 / nw, 300 / nh);
                    this.cropScale = this.minScale;
                    this.clampOffsets();
                    this.drawCropCanvas();
                },

                applyCrop() {
                    const img = window._siperkomCropImg;
                    if (!img) return;

                    this.clampOffsets();

                    const outCanvas = document.createElement('canvas');
                    const outSize = 512;
                    outCanvas.width = outSize;
                    outCanvas.height = outSize;
                    const outCtx = outCanvas.getContext('2d');

                    const factor = outSize / 280;
                    outCtx.save();
                    outCtx.translate(outSize / 2 + this.cropOffsetX * factor, outSize / 2 + this.cropOffsetY * factor);
                    outCtx.rotate((this.cropRotation * Math.PI) / 180);
                    outCtx.scale(this.cropScale * factor, this.cropScale * factor);

                    const nw = this.cropImgWidth || img.naturalWidth || img.width || 512;
                    const nh = this.cropImgHeight || img.naturalHeight || img.height || 512;
                    outCtx.drawImage(img, -nw / 2, -nh / 2, nw, nh);
                    outCtx.restore();

                    const dataUrl = outCanvas.toDataURL('image/jpeg', 0.92);
                    this.avatarPreview = dataUrl;
                    this.avatarBase64 = dataUrl;
                    this.deleteAvatar = false;

                    // Buat file Blob dan pasang ke input file via DataTransfer
                    outCanvas.toBlob((blob) => {
                        if (blob) {
                            try {
                                const file = new File([blob], 'avatar_' + Date.now() + '.jpg', { type: 'image/jpeg' });
                                const dt = new DataTransfer();
                                dt.items.add(file);
                                if (this.$refs.avatarInput) {
                                    this.$refs.avatarInput.files = dt.files;
                                }
                            } catch (e) {
                                console.warn('DataTransfer fallback base64', e);
                            }
                        }
                    }, 'image/jpeg', 0.92);

                    this.showCropModal = false;
                }
            }
        }
    </script>
</body>
</html>