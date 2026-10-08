<!-- ============================================================
     KOMPONEN NAVIGASI BAWAH MOBILE (NATIVE APP FEEL)
     Dirancang khusus untuk pengalaman mobile prima bagi pelapor SIPERKOM
============================================================ -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 w-full z-50 bg-white/95 backdrop-blur-md border-t border-[#E3DCC8] shadow-[0_-4px_24px_rgba(7,37,68,0.12)] px-2 py-1.5 select-none touch-manipulation"
     style="position: fixed !important; bottom: 0 !important; left: 0 !important; right: 0 !important; width: 100% !important; max-width: 100% !important; box-sizing: border-box !important; padding-bottom: max(0.5rem, env(safe-area-inset-bottom, 0.5rem)); -webkit-tap-highlight-color: transparent;"
     aria-label="Navigasi Bawah">
    
    <!-- Kontainer Ergonomis: Proporsional di Ponsel & Terpusat Rapi di Tablet (iPad / Tab) -->
    <div class="w-full max-w-lg md:max-w-xl mx-auto flex items-center justify-between">

        <!-- 1. Beranda -->
        <a href="{{ route('pelapor.dashboard') }}"
           class="flex-1 flex flex-col items-center justify-center py-1 transition-transform duration-150 ease-out group active:scale-90 text-center touch-manipulation cursor-pointer select-none">
            <div class="relative p-1.5 rounded-xl transition-all duration-200 {{ ($active ?? '') === 'dashboard' ? 'bg-[#FBF6EA] text-[#C9A227] ring-1 ring-[#C9A227]/40 shadow-xs scale-105' : 'text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }}">
                <svg class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ ($active ?? '') === 'dashboard' ? '2.5' : '2' }}" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <span class="text-[10px] tracking-tight mt-0.5 transition-colors duration-150 {{ ($active ?? '') === 'dashboard' ? 'text-[#072544] font-extrabold' : 'text-gray-500 font-semibold' }}">Beranda</span>
        </a>

        <!-- 2. Riwayat -->
        <a href="{{ route('pelapor.riwayat') }}"
           class="flex-1 flex flex-col items-center justify-center py-1 transition-transform duration-150 ease-out group active:scale-90 text-center relative touch-manipulation cursor-pointer select-none">
            <div class="relative p-1.5 rounded-xl transition-all duration-200 {{ ($active ?? '') === 'riwayat' ? 'bg-[#FBF6EA] text-[#C9A227] ring-1 ring-[#C9A227]/40 shadow-xs scale-105' : 'text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }}">
                <svg class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ ($active ?? '') === 'riwayat' ? '2.5' : '2' }}" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-[10px] tracking-tight mt-0.5 transition-colors duration-150 {{ ($active ?? '') === 'riwayat' ? 'text-[#072544] font-extrabold' : 'text-gray-500 font-semibold' }}">Riwayat</span>
        </a>

        <!-- 3. Lapor -->
        @if(($active ?? '') === 'dashboard')
            <button type="button" 
                    @click="openReportModal()"
                    class="flex-1 flex flex-col items-center justify-center py-1 transition-transform duration-150 ease-out group active:scale-90 text-center touch-manipulation cursor-pointer select-none">
                <div class="relative p-1.5 rounded-xl transition-all duration-200 bg-[var(--mm-900)] text-[var(--keraton-gold)] shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-[10px] tracking-tight mt-0.5 text-[#072544] font-extrabold">Lapor</span>
            </button>
        @else
            <a href="{{ route('pelapor.dashboard') }}?open_modal=1"
               class="flex-1 flex flex-col items-center justify-center py-1 transition-transform duration-150 ease-out group active:scale-90 text-center touch-manipulation cursor-pointer select-none">
                <div class="relative p-1.5 rounded-xl transition-all duration-200 bg-[var(--mm-900)] text-[var(--keraton-gold)] shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <span class="text-[10px] tracking-tight mt-0.5 text-[#072544] font-extrabold">Lapor</span>
            </a>
        @endif

        <!-- 4. Rekap -->
        <a href="{{ route('pelapor.rekap') }}"
           class="flex-1 flex flex-col items-center justify-center py-1 transition-transform duration-150 ease-out group active:scale-90 text-center touch-manipulation cursor-pointer select-none">
            <div class="relative p-1.5 rounded-xl transition-all duration-200 {{ ($active ?? '') === 'rekap' ? 'bg-[#FBF6EA] text-[#C9A227] ring-1 ring-[#C9A227]/40 shadow-xs scale-105' : 'text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }}">
                <svg class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ ($active ?? '') === 'rekap' ? '2.5' : '2' }}" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="text-[10px] tracking-tight mt-0.5 transition-colors duration-150 {{ ($active ?? '') === 'rekap' ? 'text-[#072544] font-extrabold' : 'text-gray-500 font-semibold' }}">Rekap</span>
        </a>

        <!-- 5. Profil / Akun -->
        <a href="{{ route('pelapor.profile.edit') }}"
           class="flex-1 flex flex-col items-center justify-center py-1 transition-transform duration-150 ease-out group active:scale-90 text-center touch-manipulation cursor-pointer select-none">
            <div class="relative p-1.5 rounded-xl transition-all duration-200 {{ ($active ?? '') === 'profil' ? 'bg-[#FBF6EA] text-[#C9A227] ring-1 ring-[#C9A227]/40 shadow-xs scale-105' : 'text-gray-400 group-hover:bg-gray-100 group-hover:text-gray-600' }}">
                <svg class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ ($active ?? '') === 'profil' ? '2.5' : '2' }}" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <span class="text-[10px] tracking-tight mt-0.5 transition-colors duration-150 {{ ($active ?? '') === 'profil' ? 'text-[#072544] font-extrabold' : 'text-gray-500 font-semibold' }}">Profil</span>
        </a>

    </div>
</nav>
