@auth
<script>
    /**
     * SIPERKOM Tab-Based Session Guard
     * Memastikan sesi hanya aktif di tab browser yang melakukan login.
     * Jika tab tertutup atau link dipindahkan ke tab/jendela lain, otomatis kembali ke login.
     */
    (function() {
        try {
            const currentUserId = '{{ Auth::id() }}';
            const activeTabUser = sessionStorage.getItem('siperkom_tab_session');
            const isAuthInit = {{ (session('just_logged_in') || request()->has('auth_init')) ? 'true' : 'false' }};

            if (isAuthInit) {
                // Sesi tab aktif baru setelah login berhasil
                sessionStorage.setItem('siperkom_tab_session', currentUserId);
                if (window.history.replaceState && window.location.search.includes('auth_init')) {
                    const cleanUrl = window.location.href.replace(/([?&])auth_init=[^&]+(&|$)/, '$1').replace(/[?&]$/, '');
                    window.history.replaceState({}, document.title, cleanUrl || window.location.pathname);
                }
            } else if (!activeTabUser || String(activeTabUser) !== String(currentUserId)) {
                // Sembunyikan tampilan instan agar tidak terjadi kedipan konten
                if (document.documentElement) {
                    document.documentElement.style.display = 'none';
                }

                // Tab sempat ditutup, link dipindah/dicopy ke tab baru, atau belum diautentikasi di tab ini
                try {
                    if (navigator.sendBeacon) {
                        navigator.sendBeacon('{{ route("logout") }}', new URLSearchParams({ '_token': '{{ csrf_token() }}' }));
                    } else {
                        fetch('{{ route("logout") }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            keepalive: true
                        });
                    }
                } catch (e) {}

                sessionStorage.removeItem('siperkom_tab_session');
                window.location.replace('{{ route("login") }}');
            }
        } catch (e) {}
    })();
</script>
@endauth
