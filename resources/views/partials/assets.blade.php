@php
    $manifestPath = public_path('build/manifest.json');
    $manifest = file_exists($manifestPath) ? json_decode(@file_get_contents($manifestPath), true) : [];
    $cssFile = $manifest['resources/css/app.css']['file'] ?? 'assets/app-aytb61jI.css';
    $jsFile = $manifest['resources/js/app.js']['file'] ?? 'assets/app-B9qO1Jfl.js';
@endphp

<!-- Memuat Berkas CSS & JS Terkompilasi Produksi (Aman untuk HP / Jaringan Lokal) -->
<link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
<script type="module" src="{{ asset('build/' . $jsFile) }}"></script>

@if (file_exists(public_path('hot')) && in_array(request()->getHost(), ['127.0.0.1', 'localhost']))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
