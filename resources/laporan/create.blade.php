<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-black text-slate-800">
            Buat Laporan Kerusakan Komputer
        </h2>
        <p class="text-sm text-slate-500">
            Sistem Informasi Pelaporan Kerusakan Komputer (SIPERKOM)
        </p>
    </x-slot>

    <div class="py-8 bg-slate-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-white rounded-3xl shadow p-8 border border-slate-100">
                
                <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Judul / Kendala Singkat</label>
                        <input type="text" name="judul" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" placeholder="Contoh: Layar Laptop Mati / Tidak Bisa Nyala" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nomor Perangkat / Ruangan</label>
                        <input type="text" name="ruangan" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" placeholder="Contoh: Ruang Lab Komputer 1 / PC-05" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Kerusakan Secara Detail</label>
                        <textarea name="deskripsi" rows="4" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" placeholder="Jelaskan kronologi atau gejala kerusakan secara lengkap..." required></textarea>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow">
                            Kirim Laporan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>