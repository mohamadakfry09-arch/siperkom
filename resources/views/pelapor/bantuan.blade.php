<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Pusat Bantuan Pelapor | SIPERKOM</title>
    <link rel="icon" href="{{ asset('images/cirebon.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --mm-900: #072544;
            --mm-800: #0B3D6E;
            --mm-200: #A9D8E6;
            --keraton-gold: #C9A227;
            --keraton-gold-soft: #E9CE7D;
            --batik-cream: #FBF6EA;
            --ink: #1E2A32;
        }

        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        .mega-mendung-bg {
            background-color: var(--mm-900);
            background-image: linear-gradient(120deg, rgba(7, 37, 68, .9), rgba(11, 61, 110, .82)), url("{{ asset('images/batik mega mendung.png') }}");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="min-h-screen bg-[var(--batik-cream)] text-[var(--ink)] antialiased">
    <main class="mx-auto w-full max-w-5xl px-4 py-5 sm:px-6 sm:py-10 lg:px-8">
        <header class="mega-mendung-bg rounded-3xl border border-white/10 p-5 text-white shadow-xl sm:p-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div class="space-y-3">
                    <a href="{{ route('pelapor.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[var(--mm-200)] transition hover:text-white"><span aria-hidden="true">←</span> Kembali ke dashboard</a>
                    <h1 class="font-display text-2xl font-extrabold tracking-tight sm:text-4xl">Pusat Bantuan Pelapor</h1>
                    <p class="max-w-2xl text-sm leading-relaxed text-[var(--mm-200)]">Panduan singkat untuk memastikan laporan kerusakan perangkat kantor diproses lebih cepat.</p>
                </div>
                <a href="https://wa.me/6285321812643?text=Halo%20Admin%20Teknisi%20SIPERKOM,%20saya%20ingin%20konsultasi." target="_blank" rel="noopener noreferrer" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-xs font-bold text-white shadow-lg transition hover:bg-emerald-700 sm:w-auto">Hubungi Admin Teknisi</a>
            </div>
        </header>

        <section class="mt-5 grid gap-4 sm:mt-6 sm:grid-cols-2 lg:grid-cols-3">
            <article class="rounded-2xl border border-[#E3DCC8] bg-white p-5 shadow-sm"><div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--mm-800)] text-lg font-bold text-white">1</div><h2 class="font-display text-sm font-bold text-[var(--mm-900)]">Cek sambungan</h2><p class="mt-2 text-xs leading-relaxed text-gray-600">Pastikan kabel listrik, data, dan koneksi jaringan terpasang dengan baik.</p></article>
            <article class="rounded-2xl border border-[#E3DCC8] bg-white p-5 shadow-sm"><div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--keraton-gold)] text-lg font-bold text-[var(--mm-900)]">2</div><h2 class="font-display text-sm font-bold text-[var(--mm-900)]">Mulai ulang</h2><p class="mt-2 text-xs leading-relaxed text-gray-600">Simpan pekerjaan, lalu restart perangkat dan periksa apakah kendala masih terjadi.</p></article>
            <article class="rounded-2xl border border-[#E3DCC8] bg-white p-5 shadow-sm sm:col-span-2 lg:col-span-1"><div class="mb-4 flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-lg font-bold text-white">3</div><h2 class="font-display text-sm font-bold text-[var(--mm-900)]">Kirim detail lengkap</h2><p class="mt-2 text-xs leading-relaxed text-gray-600">Tuliskan gejala, lokasi perangkat, dan lampirkan foto atau dokumen PDF pendukung.</p></article>
        </section>

        <section class="mt-5 grid gap-5 sm:mt-6 lg:grid-cols-[1.2fr_.8fr]">
            <div class="rounded-2xl border border-[#E3DCC8] bg-white p-5 shadow-sm sm:p-6"><h2 class="font-display text-base font-bold text-[var(--mm-900)]">Sebelum membuat laporan</h2><ul class="mt-4 space-y-3 text-xs text-gray-700"><li class="flex gap-3"><span class="text-emerald-600">✓</span><span>Siapkan nama perangkat dan nomor inventaris jika tersedia.</span></li><li class="flex gap-3"><span class="text-emerald-600">✓</span><span>Catat pesan error atau waktu mulai masalah terjadi.</span></li><li class="flex gap-3"><span class="text-emerald-600">✓</span><span>Gunakan foto yang jelas dan dokumen pengantar dalam format PDF.</span></li><li class="flex gap-3"><span class="text-emerald-600">✓</span><span>Pastikan nomor WhatsApp yang aktif dapat dihubungi teknisi.</span></li></ul></div>
            <div class="rounded-2xl border border-[#E3DCC8] bg-[var(--mm-900)] p-5 text-white shadow-sm sm:p-6"><h2 class="font-display text-base font-bold">Butuh bantuan langsung?</h2><p class="mt-2 text-xs leading-relaxed text-[var(--mm-200)]">Tim teknisi dapat membantu mengarahkan pemeriksaan awal sebelum laporan dikirim.</p><a href="{{ route('pelapor.dashboard') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-[var(--keraton-gold)] px-4 py-3 text-xs font-bold text-[var(--mm-900)] transition hover:bg-[var(--keraton-gold-soft)]">Buat Laporan Kerusakan</a></div>
        </section>
    </main>
</body>
</html>