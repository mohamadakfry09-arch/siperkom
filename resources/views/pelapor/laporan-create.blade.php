<!-- Footer Sidebar / Logout -->
            <div class="space-y-3">
                <a href="https://wa.me/" target="_blank" class="flex items-center justify-center space-x-2 w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl transition">
                    <span>WhatsApp Admin Teknisi</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center space-x-2 w-full py-2.5 bg-rose-900/60 hover:bg-rose-900 text-rose-200 text-xs font-semibold rounded-xl transition">
                        <span>Keluar</span>
                    </button>
                </form>
                <p class="text-[10px] text-center text-slate-500 pt-2">&copy; 2026 SIPERKOM v1.2</p>
            </div>