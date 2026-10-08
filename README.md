# SIPERKOM (Sistem Informasi Pelayanan Pengaduan Komunikasi)

<p align="center">
  <img src="public/images/logo-cirebon.png" alt="SIPERKOM Logo" width="120" onerror="this.style.display='none'"/>
</p>

<p align="center">
  <strong>Sistem Informasi Pelaporan & Penanganan Gangguan Infrastruktur Komunikasi</strong><br>
  Dinas Komunikasi dan Informatika (Diskominfo) Kabupaten Cirebon
</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-11%2B-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"></a>
  <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS"></a>
  <a href="https://vercel.com"><img src="https://img.shields.io/badge/Deploy-Vercel-000000?style=for-the-badge&logo=vercel&logoColor=white" alt="Vercel"></a>
</p>

---

## 🌐 Live Demo & Deployment

Aplikasi ini dapat diakses secara online:

- 🔗 **Live Demo URL:** `https://siperkom.vercel.app` *(atau URL Vercel/Hosting Anda)*
- 📦 **Repository GitHub:** [https://github.com/nifaryngoding-beep/SIPERKOM](https://github.com/nifaryngoding-beep/SIPERKOM)

---

## 📌 Ringkasan Sistem (UI / UX Showcase)

SIPERKOM dirancang dengan antarmuka modern, responsif (mendukung desktop & mobile), serta alur kerja yang intuitif untuk mempermudah pelaporan dan penanganan insiden infrastruktur komunikasi.

### 🌟 Fitur Utama

#### 1. 👥 Portal Publik & Pelapor (Desa / Instansi)
- **Landing Page Modern:** Pengenalan layanan, statistik laporan real-time, dan cek status tiket pengaduan secara instan tanpa login.
- **Login & Register Khusus Pelapor:** Autentikasi mandiri untuk perwakilan desa / instansi.
- **Dashboard Pelapor Mobile-Friendly:** Dilengkapi bottom navigation bar ala native mobile app untuk kemudahan akses dari smartphone.
- **Formulir Pengaduan Cepat:** Input judul, kategori masalah, deskripsi, lokasi, serta unggah bukti foto kendala.
- **Tracking & Riwayat Antrean:** Memantau progres penanganan secara transparan (*Menunggu*, *Diproses*, *Selesai*).
- **Profil & Manajemen Akun:** Pengelolaan data kontak dan instansi pelapor.

#### 2. 🛡️ Portal Administrator & Teknisi
- **Dashboard Analitik & Statistik:** Kartu metrik jumlah aduan, grafik persentase penyelesaian, dan notifikasi insiden mendesak.
- **Manajemen Antrean & Disposisi:** Penugasan teknisi lapangan dan pembaruan status pengerjaan secara real-time.
- **Rekap & Laporan:** Filter data berdasarkan bulan/tahun, ekspor laporan, dan dokumentasi penyelesaian.
- **Manajemen Pengguna & Hak Akses:** Pengaturan akun admin, teknisi, dan pelapor desa.

---

## 🚀 Cara Menghubungkan Live Demo ke GitHub

Jika Anda ingin mengaktifkan Live Demo via **Vercel** secara otomatis dari repository ini:

1. Kunjungi [Vercel](https://vercel.com) dan login menggunakan akun GitHub Anda.
2. Klik tombol **"Add New..."** ➔ **"Project"**.
3. Pilih repository **`nifaryngoding-beep/SIPERKOM`** lalu klik **Import**.
4. Pada bagian **Environment Variables**, tambahkan konfigurasi dasar:
   - `APP_NAME` : `SIPERKOM`
   - `APP_ENV` : `production`
   - `APP_KEY` : *(Salin dari file `.env` lokal Anda)*
   - `APP_URL` : *(URL Vercel yang dihasilkan)*
5. Klik **Deploy**.
6. Salin domain yang didapat (misal `https://siperkom.vercel.app`), lalu di halaman repository GitHub:
   - Klik ikon gerigi **⚙️ (Edit repository details)** di kanan atas.
   - Masukkan link tersebut ke kolom **Website**.
   - Simpan (*Save changes*). Link Live Demo akan otomatis muncul di bagian teratas repository GitHub!

---

## 💻 Panduan Instalasi Lokal

Jika ingin menjalankan aplikasi ini di komputer lokal:

### 1. Clone Repository
```bash
git clone https://github.com/nifaryngoding-beep/SIPERKOM.git
cd SIPERKOM
```

### 2. Instal Dependensi
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```
Sesuaikan konfigurasi database di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siperkom
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seeder Database
```bash
php artisan migrate --seed
```

### 5. Jalankan Server Lokal
```bash
# Terminal 1 (Laravel Server)
php artisan serve

# Terminal 2 (Vite Asset Bundler)
npm run dev
```
Akses aplikasi melalui browser di `http://127.0.0.1:8000`.

---

## 📄 Lisensi
Proyek ini dikembangkan untuk kebutuhan operasional layanan komunikasi dan informatika.
