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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
    </style>
</head>
<body class="antialiased bg-[var(--batik-cream)] text-[var(--ink)] min-h-screen selection:bg-[var(--keraton-gold)] selection:text-[var(--mm-900)] pb-10"
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
                <div class="text-right hidden xs:block">
                    <p class="text-[10px] font-bold text-[var(--mm-900)] leading-tight">{{ date('d M Y') }}</p>
                    <p class="text-[8.5px] text-gray-400 leading-tight">Kab. Cirebon</p>
                </div>
                <img src="{{ asset('images/siperkom.png') }}" alt="SIPERKOM" class="w-6 h-6 object-contain shrink-0">
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
                                <span>{{ strtoupper(substr($user->name ?? 'NI', 0, 2)) }}</span>
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
                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-white/10 border border-white/20 text-[9px] sm:text-[10px] text-[var(--keraton-gold-soft)] font-semibold backdrop-blur-xs">
                            <svg class="w-3 h-3 text-[var(--keraton-gold-soft)] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>Pelapor</span>
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

    </div>

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
                    <div class="w-48 h-48 rounded-2xl bg-[var(--mm-900)] text-white font-extrabold text-5xl flex items-center justify-center shadow-lg ring-4 ring-white/30">
                        <span>{{ strtoupper(substr($user->name ?? 'NI', 0, 2)) }}</span>
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

    <!-- Script AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function editProfileApp(existingAvatar) {
            return {
                avatarPreview: existingAvatar || null,
                savedAvatar: existingAvatar || null,
                deleteAvatar: false,
                avatarError: null,
                showAvatarModal: false,
                maxSizeMB: 2,
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
                        if (confirm('Apakah Anda yakin ingin menghapus foto profil ini dan kembali menggunakan inisial nama?')) {
                            const deleteForm = document.getElementById('delete-avatar-form');
                            if (deleteForm) {
                                deleteForm.submit();
                                return;
                            }
                        } else {
                            return;
                        }
                    }
                    // Jika baru pilih file secara lokal atau batal pasang
                    this.avatarPreview = null;
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

                    // Validasi tipe format gambar
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
                    if (!allowedTypes.includes(file.type.toLowerCase())) {
                        this.avatarError = 'Format file tidak didukung! Gunakan gambar JPG, PNG, atau WEBP.';
                        event.target.value = '';
                        return;
                    }

                    // Validasi ukuran maksimal (2 MB)
                    const maxSizeBytes = this.maxSizeMB * 1024 * 1024;
                    if (file.size > maxSizeBytes) {
                        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                        this.avatarError = `Ukuran foto (${fileSizeMB} MB) melebihi batas maksimal ${this.maxSizeMB} MB!`;
                        event.target.value = '';
                        return;
                    }

                    // Baca dan tampilkan pratinjau foto
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.avatarPreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    </script>
</body>
</html>