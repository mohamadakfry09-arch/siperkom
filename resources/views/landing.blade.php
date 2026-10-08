<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>SIPERKOM — Layanan Pelaporan Kerusakan TIK Disdukcapil Kab. Cirebon</title>
    <meta name="description" content="Portal resmi pengaduan dan perbaikan sarana komputer, printer, dan jaringan dinas di lingkungan Pemerintah Kabupaten Cirebon. Disdukcapil Kab. Cirebon.">

    <!-- Favicon Resmi Logo Kabupaten Cirebon -->
    <link rel="icon" href="{{ asset('images/cirebon.png') }}" type="image/png">

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Tema Kepemerintahan Modern Kompak: Royal Navy & Keraton Cirebon */
            --gov-navy-950: #04101e;
            --gov-navy-900: #07192f;
            --gov-navy-850: #0a2544;
            --gov-navy-800: #0d325c;
            --gov-navy-700: #12437b;
            --gov-blue-600: #195ea8;
            --gov-blue-500: #2579ce;
            --gov-cyan: #38bdf8;
            --gov-cyan-soft: #e0f2fe;

            /* Aksen Keraton Cirebon */
            --gold-500: #c9a227;
            --gold-400: #dfb943;
            --gold-300: #f2d572;
            --gold-100: #fbf5df;

            /* Status & Netral */
            --emerald-500: #10b981;
            --emerald-400: #34d399;
            --emerald-50: #ecfdf5;

            --bg-slate: #f8fafc;
            --surface-card: #ffffff;
            --border-slate: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 68px;
        }

        html, body {
            width: 100%;
            overflow-x: hidden;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-slate);
            color: var(--text-dark);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, .font-heading {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            letter-spacing: -0.015em;
        }

        /* Navbar Utama Kompak & Rapi */
        .gov-navbar {
            background-color: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-slate);
            position: sticky;
            top: 0;
            z-index: 80;
            box-shadow: 0 2px 10px -2px rgba(15, 23, 42, 0.05);
            transition: all 0.25s ease;
        }

        .gov-nav-inner {
            max-width: 1160px;
            margin: 0 auto;
            padding: 0.55rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
        }

        .gov-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }

        .brand-dukcapil-logo {
            height: 44px;
            width: auto;
            max-width: 170px;
            object-fit: contain;
            display: block;
            transition: transform 0.2s ease, filter 0.2s ease;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.05));
        }

        .brand-dukcapil-logo:hover {
            transform: scale(1.02);
        }

        .brand-divider {
            width: 1.5px;
            height: 32px;
            background-color: var(--border-slate);
            flex-shrink: 0;
        }

        .brand-text-block {
            display: flex;
            flex-direction: column;
        }

        .brand-region {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--gov-navy-850);
            line-height: 1.15;
        }

        .brand-system {
            font-size: 1rem;
            font-weight: 800;
            color: var(--gov-navy-900);
            line-height: 1.15;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .brand-system span.badge-gold {
            font-size: 0.6rem;
            font-weight: 700;
            padding: 0.1rem 0.35rem;
            border-radius: 4px;
            background-color: var(--gold-100);
            color: #855d08;
            border: 1px solid rgba(201, 162, 39, 0.35);
            letter-spacing: 0.02em;
        }

        .brand-desc {
            font-size: 0.675rem;
            color: var(--text-muted);
            line-height: 1.15;
        }

        /* Nav Links Desktop */
        .gov-nav-links {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            list-style: none;
        }

        .gov-nav-link {
            text-decoration: none;
            color: #334155;
            font-size: 0.8rem;
            font-weight: 600;
            transition: color 0.15s ease;
            position: relative;
            padding: 0.2rem 0;
        }

        .gov-nav-link:hover {
            color: var(--gov-blue-600);
        }

        .gov-nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--gold-500);
            transition: width 0.2s ease;
        }

        .gov-nav-link:hover::after {
            width: 100%;
        }

        /* Action Buttons Header */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-nav-pelapor {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.95rem;
            border-radius: 8px;
            font-size: 0.775rem;
            font-weight: 800;
            text-decoration: none;
            color: #ffffff;
            background: linear-gradient(135deg, var(--gov-navy-850) 0%, var(--gov-navy-950) 100%);
            border: 1px solid rgba(201, 162, 39, 0.4);
            box-shadow: 0 2px 8px rgba(10, 37, 68, 0.18);
            transition: all 0.2s ease;
        }

        .btn-nav-pelapor:hover {
            background: linear-gradient(135deg, var(--gov-navy-700) 0%, var(--gov-navy-900) 100%);
            box-shadow: 0 4px 12px rgba(10, 37, 68, 0.25);
            transform: translateY(-1px);
        }

        /* Tombol Mobile Toggle */
        .mobile-toggle-btn {
            display: none;
            background: none;
            border: 1px solid var(--border-slate);
            border-radius: 7px;
            padding: 0.35rem 0.45rem;
            cursor: pointer;
            color: var(--gov-navy-900);
        }

        /* HERO SECTION KOMPAK & BERSIH */
        .gov-hero {
            position: relative;
            background-color: var(--gov-navy-950);
            color: #ffffff;
            overflow: hidden;
            padding: 2.75rem 1rem 3rem;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(25, 94, 168, 0.32) 0%, transparent 45%),
                radial-gradient(circle at 90% 80%, rgba(201, 162, 39, 0.14) 0%, transparent 40%),
                linear-gradient(160deg, rgba(4, 16, 30, 0.95) 0%, rgba(10, 37, 68, 0.92) 50%, rgba(4, 16, 30, 0.98) 100%),
                url("{{ asset('images/batik mega mendung.png') }}");
            background-size: cover, cover, cover, cover;
            background-position: center;
            background-repeat: no-repeat;
            border-bottom: 2px solid var(--gold-500);
        }

        .hero-pattern-overlay {
            position: absolute;
            inset: 0;
            background: 
                radial-gradient(1px 1px at 20px 30px, rgba(255, 255, 255, 0.08), transparent),
                radial-gradient(1px 1px at 40px 70px, rgba(255, 255, 255, 0.05), transparent);
            background-size: 60px 60px;
            pointer-events: none;
        }

        .gov-hero-inner {
            max-width: 1140px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.15fr 0.95fr;
            gap: 2.25rem;
            align-items: center;
            position: relative;
            z-index: 10;
        }

        /* Sisi Kiri Hero */
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 0.25rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.675rem;
            font-weight: 700;
            color: var(--gold-300);
            letter-spacing: 0.03em;
            margin-bottom: 0.85rem;
            backdrop-filter: blur(8px);
        }

        .hero-tag svg {
            width: 13px;
            height: 13px;
            color: var(--gold-400);
        }

        .hero-title {
            font-size: 1.95rem;
            font-weight: 800;
            line-height: 1.25;
            color: #ffffff;
            margin-bottom: 0.85rem;
            letter-spacing: -0.02em;
        }

        .hero-title .gold-gradient {
            background: linear-gradient(135deg, #ffffff 0%, var(--gold-300) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtitle {
            font-size: 0.875rem;
            line-height: 1.55;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
            max-width: 500px;
        }

        .hero-cta-group {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, var(--gold-500) 0%, #b88f18 100%);
            color: var(--gov-navy-950);
            padding: 0.7rem 1.35rem;
            font-size: 0.825rem;
            font-weight: 800;
            border-radius: 9px;
            text-decoration: none;
            box-shadow: 0 4px 16px rgba(201, 162, 39, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.4);
            transition: all 0.2s ease;
        }

        .btn-hero-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(201, 162, 39, 0.45);
            background: linear-gradient(135deg, var(--gold-400) 0%, var(--gold-500) 100%);
        }

        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            padding: 0.7rem 1.15rem;
            font-size: 0.8rem;
            font-weight: 700;
            border-radius: 9px;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(8px);
            transition: all 0.2s ease;
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.16);
            border-color: rgba(255, 255, 255, 0.35);
            transform: translateY(-1px);
        }

        /* Sisi Kanan Hero: Portal Gateway Card Kompak */
        .portal-gateway-card {
            background: rgba(255, 255, 255, 0.98);
            border: 1.5px solid rgba(201, 162, 39, 0.35);
            border-radius: 16px;
            padding: 1.45rem 1.35rem;
            box-shadow: 
                0 14px 28px -6px rgba(4, 16, 30, 0.45),
                0 0 0 1px rgba(255, 255, 255, 0.15);
            color: var(--text-dark);
            position: relative;
        }

        .portal-gateway-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 0.85rem;
            border-bottom: 1px solid var(--border-slate);
            margin-bottom: 1rem;
        }

        .gateway-title-group h3 {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--gov-navy-950);
            line-height: 1.2;
        }

        .gateway-title-group p {
            font-size: 0.725rem;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .badge-live-spbe {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.65rem;
            font-weight: 700;
            color: #065f46;
            background-color: var(--emerald-50);
            border: 1px solid #a7f3d0;
            padding: 0.2rem 0.5rem;
            border-radius: 9999px;
            flex-shrink: 0;
        }

        .badge-live-spbe span.live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: var(--emerald-500);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.6);
            animation: pulseSpbeLive 2s infinite ease-in-out;
        }

        @keyframes pulseSpbeLive {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 5px rgba(16, 185, 129, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .portal-direct-action {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            margin-bottom: 1rem;
        }

        .btn-card-login {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, var(--gov-navy-900) 0%, var(--gov-navy-800) 100%);
            color: #ffffff;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 800;
            font-size: 0.85rem;
            border: 1px solid var(--gov-navy-700);
            box-shadow: 0 3px 10px rgba(7, 25, 47, 0.18);
            transition: all 0.2s ease;
        }

        .btn-card-login:hover {
            background: linear-gradient(135deg, var(--gov-navy-800) 0%, var(--gov-blue-600) 100%);
            box-shadow: 0 5px 14px rgba(7, 25, 47, 0.25);
            transform: translateY(-1px);
        }

        .btn-card-login .action-label {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .btn-card-register {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            padding: 0.65rem 1rem;
            background-color: #ffffff;
            color: var(--gov-navy-850);
            border: 1.5px solid var(--border-slate);
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.775rem;
            transition: all 0.15s ease;
        }

        .btn-card-register:hover {
            background-color: #f8fafc;
            border-color: var(--gov-navy-700);
            color: var(--gov-navy-950);
        }

        .portal-helpdesk-box {
            background-color: #f8fafc;
            border: 1px solid var(--border-slate);
            border-radius: 10px;
            padding: 0.65rem 0.85rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.65rem;
        }

        .helpdesk-text {
            font-size: 0.725rem;
            color: #475569;
            line-height: 1.35;
        }

        .helpdesk-text strong {
            color: var(--gov-navy-950);
            display: block;
        }

        .btn-wa-help {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.38rem 0.65rem;
            background-color: #25d366;
            color: #ffffff;
            font-size: 0.725rem;
            font-weight: 700;
            border-radius: 7px;
            text-decoration: none;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(37, 211, 102, 0.25);
            transition: all 0.15s ease;
        }

        .btn-wa-help:hover {
            background-color: #1eb857;
            transform: scale(1.02);
        }

        /* SECTION COMMON STYLES KOMPAK */
        .gov-section {
            padding: 2.75rem 1rem;
        }

        .gov-container {
            max-width: 1140px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            max-width: 640px;
            margin: 0 auto 2.25rem;
        }

        .section-eyebrow {
            display: inline-block;
            font-size: 0.675rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--gov-blue-600);
            background-color: var(--gov-cyan-soft);
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            margin-bottom: 0.5rem;
        }

        .section-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--gov-navy-950);
            line-height: 1.25;
            margin-bottom: 0.45rem;
        }

        .section-subtitle {
            font-size: 0.825rem;
            color: var(--text-muted);
            line-height: 1.5;
        }

        /* 4 PILAR LAYANAN TIK KOMPAK */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }

        .service-card {
            background: #ffffff;
            border: 1px solid var(--border-slate);
            border-radius: 14px;
            padding: 1.35rem 1.15rem;
            box-shadow: 0 2px 8px -2px rgba(15, 23, 42, 0.04);
            display: flex;
            flex-direction: column;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gov-blue-600) 0%, var(--gold-500) 100%);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .service-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px -4px rgba(10, 37, 68, 0.08);
            border-color: #cbd5e1;
        }

        .service-card:hover::before {
            opacity: 1;
        }

        .service-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gov-navy-900) 0%, var(--gov-navy-700) 100%);
            color: var(--gold-300);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.85rem;
            box-shadow: 0 4px 10px -2px rgba(7, 25, 47, 0.2);
            flex-shrink: 0;
        }

        .service-card h4 {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--gov-navy-950);
            margin-bottom: 0.4rem;
            line-height: 1.3;
        }

        .service-card p {
            font-size: 0.775rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 0.85rem;
            flex-grow: 1;
        }

        .service-checklist {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            padding-top: 0.75rem;
            border-top: 1px dashed var(--border-slate);
        }

        .service-checklist li {
            font-size: 0.725rem;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .service-checklist li svg {
            width: 13px;
            height: 13px;
            color: var(--emerald-500);
            flex-shrink: 0;
        }

        /* ALUR PENANGANAN (SOP SPBE) KOMPAK */
        .workflow-section {
            background-color: #ffffff;
            border-top: 1px solid var(--border-slate);
            border-bottom: 1px solid var(--border-slate);
        }

        .workflow-timeline {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            position: relative;
        }

        .workflow-step {
            background-color: var(--bg-slate);
            border: 1px solid var(--border-slate);
            border-radius: 12px;
            padding: 1.25rem 1rem;
            position: relative;
            transition: all 0.2s ease;
        }

        .workflow-step:hover {
            background-color: #ffffff;
            border-color: var(--gov-blue-500);
            box-shadow: 0 6px 16px -4px rgba(15, 23, 42, 0.06);
            transform: translateY(-2px);
        }

        .step-number {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--gov-navy-900) 0%, var(--gov-blue-600) 100%);
            color: #ffffff;
            font-size: 0.775rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
            box-shadow: 0 2px 6px rgba(10, 37, 68, 0.18);
        }

        .workflow-step h5 {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--gov-navy-950);
            margin-bottom: 0.3rem;
        }

        .workflow-step p {
            font-size: 0.75rem;
            color: var(--text-muted);
            line-height: 1.45;
        }

        /* BANNER EMERGENCY & HELPDESK (KOMPAK & RAPIH) */
        #keunggulan {
            padding: 1.75rem 1rem 2.5rem;
        }

        .emergency-banner {
            background: linear-gradient(135deg, var(--gov-navy-950) 0%, var(--gov-navy-850) 100%);
            border-radius: 14px;
            padding: 1.25rem 1.6rem;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.25rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(201, 162, 39, 0.3);
            box-shadow: 0 8px 20px -6px rgba(4, 16, 30, 0.22);
        }

        .emergency-banner::after {
            content: '';
            position: absolute;
            right: -5%;
            top: -30%;
            width: 220px;
            height: 220px;
            background: radial-gradient(circle, rgba(201, 162, 39, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .emergency-eyebrow {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 800;
            color: var(--gold-300);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.2rem;
        }

        .emergency-content h3 {
            font-size: 1.05rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }

        .emergency-content p {
            font-size: 0.775rem;
            color: #cbd5e1;
            max-width: 540px;
            line-height: 1.45;
        }

        .emergency-actions {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            flex-shrink: 0;
            position: relative;
            z-index: 2;
        }

        .btn-wa-emergency {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background-color: #25d366;
            color: #ffffff;
            padding: 0.55rem 1rem;
            font-size: 0.775rem;
            font-weight: 800;
            border-radius: 8px;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(37, 211, 102, 0.25);
            transition: all 0.2s ease;
        }

        .btn-wa-emergency:hover {
            background-color: #1eb857;
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(37, 211, 102, 0.35);
        }

        /* FOOTER RESMI KEPEMERINTAHAN KOMPAK */
        .gov-footer {
            background-color: var(--gov-navy-950);
            color: #94a3b8;
            border-top: 2px solid var(--gold-500);
            padding: 2.75rem 1rem 1.5rem;
            font-size: 0.8rem;
        }

        .gov-footer-inner {
            max-width: 1140px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.4fr 0.9fr 1.1fr 1fr;
            gap: 1.75rem;
            margin-bottom: 2rem;
        }

        .footer-brand {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .footer-brand-wrap {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .footer-dukcapil-pill {
            background: #ffffff;
            border-radius: 8px;
            padding: 4px 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
        }

        .footer-dukcapil-img {
            height: 38px;
            width: auto;
            max-width: 140px;
            object-fit: contain;
            display: block;
        }

        .footer-title-sub {
            font-size: 0.675rem;
            font-weight: 700;
            color: var(--gold-300);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .footer-title-main {
            font-size: 0.95rem;
            font-weight: 800;
            color: #ffffff;
        }

        .footer-col h5 {
            font-size: 0.85rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 0.85rem;
            letter-spacing: 0.02em;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.775rem;
        }

        .footer-links a:hover {
            color: var(--gold-300);
        }

        .footer-address {
            line-height: 1.5;
            color: #cbd5e1;
            font-size: 0.775rem;
        }

        /* SEKSI KONTAK & LOKASI RESMI (LUAS & RAPI) */
        /* SEKSI KONTAK & LOKASI RESMI (KOMPAK & JELAS) */
        .contact-location-section {
            background-color: #f8fafc;
            border-top: 1px solid var(--border-slate);
            padding: 2.25rem 1rem;
        }

        .location-section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 1.35rem;
        }

        .location-grid {
            display: grid;
            grid-template-columns: 1fr 1.25fr;
            background: #ffffff;
            border: 1px solid var(--border-slate);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.05);
        }

        .location-card-info {
            padding: 1.5rem 1.75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        }

        .location-header-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.675rem;
            font-weight: 700;
            color: var(--gov-blue-600);
            background-color: var(--gov-cyan-soft);
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            margin-bottom: 0.5rem;
            width: fit-content;
        }

        .location-office-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--gov-navy-950);
            line-height: 1.25;
            margin-bottom: 0.15rem;
        }

        .location-gov-subtitle {
            font-size: 0.725rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 1rem;
        }

        .location-meta-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            margin-bottom: 1.15rem;
        }

        .location-meta-item {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
        }

        .meta-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background-color: #ffffff;
            border: 1px solid var(--border-slate);
            color: var(--gov-blue-600);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .meta-detail {
            font-size: 0.75rem;
            line-height: 1.35;
            color: #334155;
        }

        .meta-detail strong {
            display: block;
            color: var(--gov-navy-950);
            font-weight: 700;
            font-size: 0.75rem;
            margin-bottom: 0.05rem;
        }

        .location-actions {
            margin-top: 0.25rem;
        }

        .btn-open-gmaps {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            width: 100%;
            padding: 0.55rem 1rem;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--gov-navy-850) 0%, var(--gov-navy-950) 100%);
            color: #ffffff;
            font-size: 0.775rem;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(10, 37, 68, 0.16);
            transition: all 0.2s ease;
        }

        .btn-open-gmaps:hover {
            background: linear-gradient(135deg, var(--gov-navy-700) 0%, var(--gov-navy-900) 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(10, 37, 68, 0.22);
            color: #ffffff;
        }

        .location-map-wrap {
            position: relative;
            min-height: 310px;
            background-color: #e2e8f0;
        }

        .location-map-wrap iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        @media (max-width: 900px) {
            .location-grid {
                grid-template-columns: 1fr;
            }
            .location-map-wrap {
                min-height: 250px;
            }
            .location-card-info {
                padding: 1.25rem;
            }
        }

        .footer-bottom-bar {
            max-width: 1140px;
            margin: 0 auto;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.725rem;
            color: #64748b;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .footer-bottom-bar a {
            color: #94a3b8;
            text-decoration: none;
        }

        .footer-bottom-bar a:hover {
            color: var(--gold-300);
        }

        /* MOBILE MENU DRAWER (DESKTOP HIDDEN) */
        .mobile-menu-drawer {
            display: none;
        }

        .mobile-nav-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            margin-bottom: 1rem;
        }

        .mobile-nav-links a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.35rem 0;
            display: block;
        }

        /* ========================================================
           RESPONSIF MOBILE KHUSUS (HP & TABLET) — SANGAT RAPI, INDAH & RINGKAS
           (Desain Desktop tetap 100% utuh tanpa diubah)
           ======================================================== */
        @media (max-width: 1024px) {
            .gov-hero-inner {
                grid-template-columns: 1fr;
                gap: 1.75rem;
            }

            .hero-title {
                font-size: 1.75rem;
            }

            .services-grid,
            .workflow-timeline {
                grid-template-columns: repeat(2, 1fr);
            }

            .gov-footer-inner {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            /* 0. Mobile Global Hygiene */
            html, body {
                overflow-x: hidden;
                -webkit-tap-highlight-color: transparent;
            }

            /* 1. Header & Identitas Navbar Mobile */
            .gov-navbar {
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            }

            .gov-nav-inner {
                padding: 0.55rem 0.85rem;
            }

            .gov-brand {
                gap: 0.55rem;
            }

            .brand-dukcapil-logo {
                height: 34px;
                max-width: 130px;
            }

            .brand-divider {
                height: 24px;
                margin: 0 0.1rem;
            }

            .brand-region {
                font-size: 0.55rem;
                line-height: 1.2;
            }

            .brand-system {
                font-size: 0.88rem;
                line-height: 1.15;
            }

            .brand-desc {
                font-size: 0.58rem;
                line-height: 1.2;
            }

            .gov-nav-links {
                display: none;
            }

            .mobile-toggle-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 38px;
                height: 38px;
                border-radius: 9px;
                background: #f1f5f9;
                border: 1px solid #cbd5e1;
                color: var(--gov-navy-950);
                cursor: pointer;
                transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1),
                            background-color 0.2s ease,
                            border-color 0.2s ease,
                            color 0.2s ease;
            }

            .mobile-toggle-btn:active {
                background: #e2e8f0;
                transform: scale(0.92);
            }

            .mobile-toggle-btn.active {
                background: #eff6ff;
                color: var(--gov-blue-600);
                border-color: var(--gov-blue-500);
                transform: rotate(90deg);
            }

            /* Drawer Mobile Menu — Silky Smooth Slide & Fade (Tidak Kaku) */
            .mobile-menu-drawer {
                display: block !important;
                max-height: 0;
                opacity: 0;
                overflow: hidden;
                pointer-events: none;
                transform: translateY(-8px);
                transition: max-height 0.35s cubic-bezier(0.16, 1, 0.3, 1), 
                            opacity 0.25s ease, 
                            transform 0.3s cubic-bezier(0.16, 1, 0.3, 1),
                            padding 0.3s ease;
                background: #ffffff;
                border-top: 1px solid transparent;
                border-bottom: 2px solid var(--gold-500);
                padding: 0 1rem;
                box-shadow: 0 16px 32px -4px rgba(10, 37, 68, 0.16);
            }

            .mobile-menu-drawer.open {
                max-height: 520px;
                opacity: 1;
                pointer-events: auto;
                transform: translateY(0);
                padding: 0.85rem 1rem 1.15rem;
                border-top-color: var(--border-slate);
            }

            .mobile-nav-links {
                display: flex;
                flex-direction: column;
                gap: 0.45rem;
                margin-bottom: 0.85rem;
            }

            .mobile-nav-links a {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.65rem 0.85rem;
                background: #f8fafc;
                border: 1px solid var(--border-slate);
                border-radius: 9px;
                color: var(--gov-navy-950);
                font-weight: 700;
                font-size: 0.825rem;
                text-decoration: none;
                transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), 
                            background-color 0.2s ease, 
                            border-color 0.2s ease, 
                            color 0.2s ease;
            }

            .mobile-nav-links a::after {
                content: '→';
                color: #94a3b8;
                font-size: 0.85rem;
                transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), color 0.2s ease;
            }

            .mobile-nav-links a:hover,
            .mobile-nav-links a:active {
                background: rgba(14, 165, 233, 0.08);
                border-color: rgba(14, 165, 233, 0.4);
                color: var(--gov-blue-600);
                transform: translateX(4px);
            }

            .mobile-nav-links a:hover::after,
            .mobile-nav-links a:active::after {
                color: var(--gov-blue-600);
                transform: translateX(3px);
            }

            /* 2. Hero Section Mobile */
            .gov-hero {
                padding: 1.5rem 0.85rem 2rem;
            }

            .gov-hero-inner {
                gap: 1.35rem;
            }

            .hero-tag {
                font-size: 0.62rem;
                padding: 0.22rem 0.65rem;
                margin-bottom: 0.75rem;
                line-height: 1.35;
                white-space: normal;
                display: inline-flex;
            }

            .hero-tag svg {
                width: 12px;
                height: 12px;
                flex-shrink: 0;
            }

            .hero-title {
                font-size: 1.45rem;
                line-height: 1.25;
                margin-bottom: 0.65rem;
                font-weight: 800;
            }

            .hero-subtitle {
                font-size: 0.8rem;
                line-height: 1.5;
                margin-bottom: 1.15rem;
                color: #cbd5e1;
            }

            .hero-cta-group {
                flex-direction: column;
                width: 100%;
                gap: 0.6rem;
                margin-bottom: 0;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                width: 100%;
                justify-content: center;
                padding: 0.75rem 1rem;
                font-size: 0.825rem;
                border-radius: 10px;
                min-height: 44px;
                transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                            box-shadow 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .btn-hero-primary:active,
            .btn-hero-secondary:active {
                transform: scale(0.975);
            }

            /* Card Portal Gateway Mobile */
            .portal-gateway-card {
                padding: 1.15rem 1rem;
                border-radius: 14px;
                margin-top: 0.35rem;
                box-shadow: 0 10px 24px -4px rgba(4, 16, 30, 0.35);
                transition: box-shadow 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .portal-gateway-header {
                margin-bottom: 0.75rem;
                padding-bottom: 0.65rem;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .gateway-title-group h3 {
                font-size: 0.95rem;
                font-weight: 800;
                line-height: 1.2;
            }

            .gateway-title-group p {
                font-size: 0.7rem;
                line-height: 1.3;
            }

            .badge-live-spbe {
                font-size: 0.6rem;
                padding: 0.15rem 0.45rem;
                gap: 0.3rem;
            }

            .portal-direct-action {
                gap: 0.55rem;
                margin-bottom: 0.75rem;
            }

            .btn-card-login {
                padding: 0.75rem 0.9rem;
                font-size: 0.825rem;
                border-radius: 9px;
                min-height: 44px;
                transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                            box-shadow 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            }

            .btn-card-login:active {
                transform: scale(0.975);
            }

            .btn-card-register {
                padding: 0.65rem 0.85rem;
                font-size: 0.775rem;
                border-radius: 9px;
                min-height: 42px;
                transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                            background-color 0.2s ease;
            }

            .btn-card-register:active {
                transform: scale(0.975);
            }

            .portal-helpdesk-box {
                padding: 0.65rem 0.75rem;
                margin-top: 0;
                border-radius: 9px;
                gap: 0.5rem;
                align-items: center;
            }

            .helpdesk-text {
                font-size: 0.675rem;
                line-height: 1.35;
            }

            .btn-wa-help {
                padding: 0.4rem 0.65rem;
                font-size: 0.685rem;
                border-radius: 7px;
                min-height: 34px;
                transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                            box-shadow 0.2s ease;
            }

            .btn-wa-help:active {
                transform: scale(0.95);
            }

            /* 3. Common Section & 4 Pilar Layanan Mobile */
            .gov-section {
                padding: 2.25rem 0.85rem;
            }

            .section-header {
                margin-bottom: 1.35rem;
                text-align: center;
            }

            .section-eyebrow {
                font-size: 0.625rem;
                padding: 0.18rem 0.55rem;
            }

            .section-title {
                font-size: 1.25rem;
                line-height: 1.28;
                margin-bottom: 0.35rem;
            }

            .section-subtitle {
                font-size: 0.775rem;
                line-height: 1.48;
            }

            .services-grid {
                grid-template-columns: 1fr;
                gap: 0.85rem;
            }

            .service-card {
                padding: 1.15rem 1rem;
                border-radius: 12px;
                transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                            box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                            border-color 0.2s ease;
            }

            .service-card:active {
                transform: scale(0.985);
                box-shadow: 0 4px 12px -2px rgba(10, 37, 68, 0.08);
                border-color: #cbd5e1;
            }

            .service-icon-box {
                width: 38px;
                height: 38px;
                border-radius: 9px;
                margin-bottom: 0.65rem;
            }

            .service-icon-box svg {
                width: 18px;
                height: 18px;
            }

            .service-card h4 {
                font-size: 0.95rem;
                margin-bottom: 0.3rem;
            }

            .service-card p {
                font-size: 0.75rem;
                line-height: 1.45;
                margin-bottom: 0.75rem;
            }

            .service-checklist {
                gap: 0.35rem;
                padding-top: 0.65rem;
            }

            .service-checklist li {
                font-size: 0.725rem;
            }

            /* 4. Alur Penanganan Timeline Mobile */
            .workflow-timeline {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .workflow-step {
                padding: 1.05rem 1rem;
                border-radius: 12px;
                display: grid;
                grid-template-columns: 36px 1fr;
                column-gap: 0.85rem;
                row-gap: 0.2rem;
                align-items: start;
                transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                            box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                            border-color 0.2s ease,
                            background-color 0.2s ease;
            }

            .workflow-step:active {
                transform: scale(0.985);
                background-color: #ffffff;
                border-color: var(--gov-blue-500);
                box-shadow: 0 4px 14px -2px rgba(15, 23, 42, 0.08);
            }

            .workflow-step .step-number {
                grid-row: 1 / 3;
                width: 36px;
                height: 36px;
                border-radius: 9px;
                font-size: 0.8rem;
                margin-bottom: 0;
            }

            .workflow-step h5 {
                font-size: 0.9rem;
                margin-bottom: 0.15rem;
                line-height: 1.3;
            }

            .workflow-step p {
                font-size: 0.75rem;
                line-height: 1.45;
            }

            /* 5. Emergency Banner Mobile */
            #keunggulan {
                padding: 1.5rem 0.85rem 2rem;
            }

            .emergency-banner {
                flex-direction: column;
                align-items: flex-start;
                padding: 1.25rem 1rem;
                gap: 1rem;
                border-radius: 12px;
            }

            .emergency-eyebrow {
                font-size: 0.625rem;
            }

            .emergency-content h3 {
                font-size: 1.05rem;
                line-height: 1.3;
                margin-bottom: 0.35rem;
            }

            .emergency-content p {
                font-size: 0.775rem;
                line-height: 1.45;
            }

            .emergency-actions {
                width: 100%;
            }

            .btn-wa-emergency {
                width: 100%;
                justify-content: center;
                padding: 0.75rem 1rem;
                font-size: 0.8rem;
                border-radius: 9px;
                min-height: 44px;
                transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                            box-shadow 0.2s ease;
            }

            .btn-wa-emergency:active {
                transform: scale(0.975);
            }

            /* 6. Kontak & Lokasi Mobile */
            .contact-location-section {
                padding: 2rem 0.85rem;
            }

            .location-grid {
                grid-template-columns: 1fr;
                border-radius: 12px;
                box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.06);
                transition: box-shadow 0.25s ease;
            }

            .location-card-info {
                padding: 1.25rem 1rem;
            }

            .location-header-badge {
                font-size: 0.65rem;
                padding: 0.2rem 0.55rem;
                margin-bottom: 0.45rem;
            }

            .location-office-title {
                font-size: 1.05rem;
                line-height: 1.25;
                margin-bottom: 0.15rem;
            }

            .location-gov-subtitle {
                font-size: 0.675rem;
                margin-bottom: 0.85rem;
            }

            .location-meta-list {
                gap: 0.6rem;
                margin-bottom: 1rem;
            }

            .meta-icon {
                width: 26px;
                height: 26px;
                border-radius: 6px;
            }

            .meta-detail {
                font-size: 0.725rem;
                line-height: 1.35;
            }

            .meta-detail strong {
                font-size: 0.725rem;
                margin-bottom: 0.05rem;
            }

            .btn-open-gmaps {
                padding: 0.7rem 1rem;
                font-size: 0.775rem;
                width: 100%;
                justify-content: center;
                border-radius: 9px;
                min-height: 42px;
                transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1),
                            box-shadow 0.2s ease;
            }

            .btn-open-gmaps:active {
                transform: scale(0.975);
            }

            .location-map-wrap {
                min-height: 230px;
                height: 230px;
                border-top: 1px solid var(--border-slate);
            }

            /* 7. Footer Mobile */
            .gov-footer {
                padding: 2rem 0.85rem 1.25rem;
            }

            .gov-footer-inner {
                grid-template-columns: 1fr;
                gap: 1.35rem;
                margin-bottom: 1.5rem;
            }

            .footer-brand {
                gap: 0.65rem;
            }

            .footer-brand-wrap {
                gap: 0.65rem;
                align-items: center;
            }

            .footer-dukcapil-pill {
                padding: 3px 8px;
                border-radius: 7px;
            }

            .footer-dukcapil-img {
                height: 32px;
                max-width: 115px;
            }

            .footer-title-sub {
                font-size: 0.625rem;
            }

            .footer-title-main {
                font-size: 0.875rem;
            }

            .footer-col h5 {
                font-size: 0.825rem;
                margin-bottom: 0.55rem;
                color: #ffffff;
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
                padding-bottom: 0.35rem;
            }

            .footer-links {
                gap: 0.45rem;
            }

            .footer-links a,
            .footer-address {
                font-size: 0.75rem;
                line-height: 1.45;
            }

            .footer-bottom-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.55rem;
                font-size: 0.685rem;
                padding-top: 1rem;
            }
        }

        /* 8. Ekstra Penyesuaian untuk Layar Smartphone Kecil (<= 480px) */
        @media (max-width: 480px) {
            .brand-desc {
                display: none;
            }

            .brand-dukcapil-logo {
                height: 29px;
                max-width: 105px;
            }

            .brand-system {
                font-size: 0.825rem;
            }

            .brand-region {
                font-size: 0.52rem;
            }

            .hero-title {
                font-size: 1.3rem;
            }

            .location-map-wrap {
                min-height: 200px;
                height: 200px;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER / NAVIGATION RESMI -->
    <header class="gov-navbar">
        <div class="gov-nav-inner">
            <!-- Brand Identitas Resmi -->
            <a href="{{ url('/') }}" class="gov-brand" title="SIPERKOM Disdukcapil Kabupaten Cirebon">

                <div class="brand-text-block">
                    <span class="brand-region">Pemerintah Kabupaten Cirebon</span>
                    <div class="brand-system">
                        SIPERKOM
                    </div>
                    <span class="brand-desc">Dinas Kependudukan dan Pencatatan Sipil</span>
                </div>
            </a>

            <!-- Navigasi Menu Desktop -->
            <nav aria-label="Navigasi Utama">
                <ul class="gov-nav-links">
                    <li><a href="#beranda" class="gov-nav-link">Beranda</a></li>
                    <li><a href="#layanan" class="gov-nav-link">Lingkup Layanan</a></li>
                    <li><a href="#alur" class="gov-nav-link">Alur Penanganan</a></li>
                    <li><a href="#keunggulan" class="gov-nav-link">Standar Layanan</a></li>
                    <li><a href="#kontak" class="gov-nav-link">Kontak & Lokasi</a></li>
                </ul>
            </nav>

            <!-- Aksi Masuk Header -->
            <div class="nav-actions">
                <!-- Toggle Mobile Menu Button -->
                <button class="mobile-toggle-btn" id="mobileMenuBtn" aria-label="Buka Menu Navigasi" onclick="toggleMobileMenu()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
            </div>
        </div>

        <!-- Menu Drawer Mobile -->
        <div class="mobile-menu-drawer" id="mobileMenuDrawer">
            <ul class="mobile-nav-links">
                <li><a href="#beranda" onclick="toggleMobileMenu()">Beranda</a></li>
                <li><a href="#layanan" onclick="toggleMobileMenu()">Lingkup Layanan</a></li>
                <li><a href="#alur" onclick="toggleMobileMenu()">Alur Penanganan</a></li>
                <li><a href="#keunggulan" onclick="toggleMobileMenu()">Standar Layanan</a></li>
                <li><a href="#kontak" onclick="toggleMobileMenu()">Kontak & Lokasi</a></li>
            </ul>
            <div style="display: flex; flex-direction: column; gap: 0.5rem; padding-top: 0.75rem; border-top: 1px solid var(--border-slate);">
                <a href="{{ route('pelapor.login') }}" class="btn-nav-pelapor" style="justify-content: center; padding: 0.65rem;">
                    Masuk Sebagai Pelapor
                </a>
                <a href="{{ route('register') }}" class="btn-card-register" style="justify-content: center; padding: 0.6rem;">
                    Pendaftaran Akun Baru
                </a>
            </div>
        </div>
    </header>

    <!-- HERO SECTION: MAJESTIC GOVTECH & MEGA MENDUNG ACCENT (KOMPAK) -->
    <section class="gov-hero" id="beranda">
        <div class="hero-pattern-overlay"></div>
        <div class="gov-hero-inner">

            <!-- Kolom Kiri: Informasi Resmi & Narasi -->
            <div>
                <div class="hero-tag">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span>LAYANAN RESMI SPBE DISDUKCAPIL KABUPATEN CIREBON</span>
                </div>

                <h1 class="hero-title">
                    Layanan Cepat Tanggap <span class="gold-gradient">Perbaikan TIK</span> Pemkab Cirebon
                </h1>

                <p class="hero-subtitle">
                    Portal terpadu bagi seluruh Dinas (OPD), Kecamatan, dan Kantor Desa se-Kabupaten Cirebon untuk menyampaikan laporan kerusakan perangkat komputer, jaringan internet, printer, dan perangkat biometrik kependudukan secara transparan.
                </p>

                <!-- Tombol Aksi Cepat Hero -->
                <div class="hero-cta-group">
                    <a href="{{ route('pelapor.login') }}" class="btn-hero-primary" title="Masuk ke Portal Laporan Pelapor">
                        <span>MASUK SEBAGAI PELAPOR</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                    </a>
                    <a href="{{ route('register') }}" class="btn-hero-secondary" title="Daftar Akun Baru Instansi">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                        <span>Daftar Akun Baru</span>
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Gateway Akses Portal Instansi (Executive Card Kompak) -->
            <div>
                <div class="portal-gateway-card">
                    <div class="portal-gateway-header">
                        <div class="gateway-title-group">
                            <h3>Portal Masuk Pelapor</h3>
                            <p>Khusus Perangkat Dinas, Kecamatan, dan Kantor Desa</p>
                        </div>
                        <div class="badge-live-spbe">
                            <span class="live-dot"></span>
                            <span>SPBE Aktif</span>
                        </div>
                    </div>

                    <!-- Pilihan Aksi Langsung -->
                    <div class="portal-direct-action">
                        <a href="{{ route('pelapor.login') }}" class="btn-card-login">
                            <div class="action-label">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                <span>MASUK SEBAGAI PELAPOR</span>
                            </div>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                        </a>

                        <a href="{{ route('register') }}" class="btn-card-register">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
                            <span>Pendaftaran Akun Baru Instansi</span>
                        </a>
                    </div>

                    <!-- Bantuan Helpdesk WhatsApp -->
                    <div class="portal-helpdesk-box">
                        <div class="helpdesk-text">
                            <strong>Butuh Bantuan Akun / Teknis?</strong>
                            Helpdesk Disdukcapil siap membantu.
                        </div>
                        <a href="https://wa.me/6285321812643?text=Halo%20Admin%20SIPERKOM,%20saya%20pelapor%20butuh%20bantuan%20layanan." target="_blank" rel="noopener noreferrer" class="btn-wa-help">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- RUANG LINGKUP LAYANAN TIK (4 PILAR UTAMA KOMPAK) -->
    <section class="gov-section" id="layanan">
        <div class="gov-container">
            <div class="section-header">
                <span class="section-eyebrow">LINGKUP FASILITASI RESMI</span>
                <h2 class="section-title">4 Pilar Layanan Pemeliharaan TIK</h2>
                <p class="section-subtitle">
                    Dinas Kependudukan dan Pencatatan Sipil memfasilitasi perbaikan dan dukungan teknis mencakup seluruh aset teknologi informasi penunjang administrasi pelayanan kependudukan dan pencatatan sipil.
                </p>
            </div>

            <div class="services-grid">
                <!-- 1. Komputer & Laptop -->
                <div class="service-card">
                    <div class="service-icon-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    </div>
                    <h4>Komputer &amp; Laptop Dinas</h4>
                    <p>Penanganan kendala hardware PC Desktop instansi, laptop operasional dinas, mati total, lambat, atau penggantian komponen suku cadang resmi.</p>
                    <ul class="service-checklist">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Instalasi OS &amp; Software Legal</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Perbaikan Harddisk/SSD &amp; RAM</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Pembersihan Malware/Virus Dinas</span>
                        </li>
                    </ul>
                </div>

                <!-- 2. Jaringan & Internet -->
                <div class="service-card">
                    <div class="service-icon-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                    </div>
                    <h4>Jaringan &amp; Internet Daerah</h4>
                    <p>Pemeliharaan koneksi Fiber Optik (FO) Intra-Pemkab, troubleshooting Wi-Fi kantor desa/kecamatan, dan manajemen bandwidth pelayanan.</p>
                    <ul class="service-checklist">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Koneksi Jaringan Intra Pemerintah</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Konfigurasi Router &amp; Access Point</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Penyelesaian Putus Koneksi / Lemot</span>
                        </li>
                    </ul>
                </div>

                <!-- 3. Printer & Scanner -->
                <div class="service-card">
                    <div class="service-icon-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                    </div>
                    <h4>Printer &amp; Scanner</h4>
                    <p>Perbaikan perangkat cetak surat dinas, printer laserjet, dot-matrix surat pengantar, dan scanner dokumen arsip kependudukan.</p>
                    <ul class="service-checklist">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Kendala Paper Jam &amp; Mekanikal</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Pengaturan Sharing Printer Lokal</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Instalasi Driver Perangkat Cetak</span>
                        </li>
                    </ul>
                </div>

                <!-- 4. Perangkat Rekam & Biometrik KTP-el -->
                <div class="service-card">
                    <div class="service-icon-box">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><line x1="15" y1="8" x2="17" y2="8"/><line x1="15" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="17" y2="16"/></svg>
                    </div>
                    <h4>Perangkat Rekam &amp; Biometrik</h4>
                    <p>Pemeliharaan alat perekaman KTP-el, pemindai sidik jari (fingerprint), pemindai iris mata, card reader smart card, dan signature pad kependudukan.</p>
                    <ul class="service-checklist">
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Pemindai Sidik Jari &amp; Iris Mata</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Card Reader &amp; Smart Card KTP-el</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Kamera &amp; Signature Pad Digital</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ALUR PENANGANAN LAPORAN (SOP STANDAR SPBE KOMPAK) -->
    <section class="gov-section workflow-section" id="alur">
        <div class="gov-container">
            <div class="section-header">
                <span class="section-eyebrow">STANDAR OPERASIONAL PROSEDUR (SOP)</span>
                <h2 class="section-title">Alur Penanganan Laporan Standar SPBE</h2>
                <p class="section-subtitle">
                    Setiap laporan kerusakan diproses secara terstruktur dengan sistem tiket agar tertib administrasi, terukur durasinya, dan berorientasi hasil optimal.
                </p>
            </div>

            <div class="workflow-timeline">
                <div class="workflow-step">
                    <div class="step-number">01</div>
                    <h5>Laporan Mandiri</h5>
                    <p>Perangkat daerah/desa mengisi formulir deskripsi kerusakan serta melampirkan foto bukti kondisi perangkat secara online di SIPERKOM.</p>
                </div>

                <div class="workflow-step">
                    <div class="step-number">02</div>
                    <h5>Verifikasi &amp; Disposisi</h5>
                    <p>Helpdesk Disdukcapil memvalidasi kelayakan laporan dan menugaskan teknisi spesialis sesuai kategori masalah dalam kurun waktu &lt; 24 jam.</p>
                </div>

                <div class="workflow-step">
                    <div class="step-number">03</div>
                    <h5>Tindakan Perbaikan</h5>
                    <p>Teknisi melakukan penanganan onsite di kantor dinas/pelapor atau di ruang teknis TIK Disdukcapil dengan tanda terima resmi.</p>
                </div>

                <div class="workflow-step">
                    <div class="step-number">04</div>
                    <h5>Uji Fungsi &amp; BAST Sah</h5>
                    <p>Perangkat diuji bersama pelapor dan diterbitkan Berita Acara Serah Terima (BAST) digital sebagai bukti penyelesaian sah.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- KEUNGGULAN TATA KELOLA SPBE & BANTUAN CEPAT (KOMPAK) -->
    <section class="gov-section" id="keunggulan">
        <div class="gov-container">
            <div class="emergency-banner">
                <div class="emergency-content">
                    <span class="emergency-eyebrow">
                        PUSAT BANTUAN TERPADU
                    </span>
                    <h3>Mengalami Kendala Jaringan Menjelang Pelayanan Masyarakat?</h3>
                    <p>
                        Tim Helpdesk Bidang TIK Disdukcapil Kabupaten Cirebon siap membantu koordinasi darurat dan pendampingan teknis pelayanan administrasi kependudukan.
                    </p>
                </div>
                <div class="emergency-actions">
                    <a href="https://wa.me/6285321812643?text=Halo%20Admin%20Disdukcapil,%20kantor%20kami%20membutuhkan%20bantuan%20darurat%20TIK." target="_blank" rel="noopener noreferrer" class="btn-wa-emergency">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>Chat WhatsApp Helpdesk</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SEKSI KONTAK & LOKASI RESMI (KOMPAK & JELAS) -->
    <section class="gov-section contact-location-section" id="kontak">
        <div class="gov-container">
            <div class="location-section-header">
                <span class="section-eyebrow">KONTAK &amp; LOKASI RESMI</span>
                <h2 class="section-title" style="font-size: 1.35rem; margin-bottom: 0.25rem;">Pusat Layanan &amp; Kantor Disdukcapil</h2>
                <p class="section-subtitle" style="font-size: 0.8rem; color: var(--text-muted);">
                    Kunjungi kantor dinas kami untuk koordinasi fisik, konsultasi teknis, atau penyerahan unit sarana prasarana TIK.
                </p>
            </div>

            <div class="location-grid">
                <!-- Sisi Kiri: Informasi Alamat & Pelayanan -->
                <div class="location-card-info">
                    <div class="location-header-badge">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        <span>Kantor Pelayanan Administrasi Kependudukan</span>
                    </div>

                    <h3 class="location-office-title">Dinas Kependudukan dan Pencatatan Sipil</h3>
                    <div class="location-gov-subtitle">Pemerintah Kabupaten Cirebon</div>

                    <div class="location-meta-list">
                        <div class="location-meta-item">
                            <div class="meta-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            </div>
                            <div class="meta-detail">
                                <strong>Alamat Kantor:</strong>
                                <span>Jl. Sunan Muria No. 4, Sumber, Kec. Sumber, Kabupaten Cirebon, Jawa Barat 45611</span>
                            </div>
                        </div>

                        <div class="location-meta-item">
                            <div class="meta-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            </div>
                            <div class="meta-detail">
                                <strong>Jam Pelayanan Kerja:</strong>
                                <span>Senin – Kamis (08.00 – 15.30 WIB) &bull; Jumat (08.00 – 16.00 WIB)</span>
                            </div>
                        </div>

                        <div class="location-meta-item">
                            <div class="meta-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            </div>
                            <div class="meta-detail">
                                <strong>Saluran Bantuan:</strong>
                                <span>Helpdesk WhatsApp: 0853-2181-2643 &bull; Portal: cirebonkab.go.id</span>
                            </div>
                        </div>
                    </div>

                    <div class="location-actions">
                        <a href="https://maps.google.com/?q=Dinas+Kependudukan+dan+Pencatatan+Sipil+Kabupaten+Cirebon" target="_blank" rel="noopener noreferrer" class="btn-open-gmaps">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            <span>Buka Petunjuk Arah di Google Maps &rarr;</span>
                        </a>
                    </div>
                </div>

                <!-- Sisi Kanan: Peta Google Maps Luas & Jernih -->
                <div class="location-map-wrap">
                    <iframe 
                        title="Peta Interaktif Lokasi Kantor Disdukcapil Kabupaten Cirebon" 
                        src="https://maps.google.com/maps?q=Dinas+Kependudukan+dan+Pencatatan+Sipil+Kabupaten+Cirebon&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER RESMI KEPEMERINTAHAN (KOMPAK & RAPI) -->
    <footer class="gov-footer">
        <div class="gov-footer-inner">
            <!-- Kolom 1: Identitas Resmi -->
            <div class="footer-brand">
                <div class="footer-brand-wrap">
                    <div>
                        <div class="footer-title-sub">Pemerintah Kabupaten Cirebon</div>
                        <div class="footer-title-main">SIPERKOM Disdukcapil</div>
                    </div>
                </div>
                <p style="line-height: 1.5; color: #94a3b8; font-size: 0.775rem;">
                    Sistem Informasi Pelaporan Kerusakan Komputer dan Infrastruktur TIK Resmi Disdukcapil Kabupaten Cirebon. Mengakselerasi tata kelola pelayanan administrasi kependudukan yang responsif, transparan, dan terpercaya.
                </p>
            </div>

            <!-- Kolom 2: Tautan Layanan -->
            <div class="footer-col">
                <h5>Layanan Utama</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('pelapor.login') }}">&rarr; Portal Masuk Pelapor</a></li>
                    <li><a href="{{ route('register') }}">&rarr; Pendaftaran Akun Baru</a></li>
                    <li><a href="#layanan">&rarr; 4 Pilar Layanan TIK</a></li>
                    <li><a href="#alur">&rarr; Alur &amp; SOP Pelaporan</a></li>
                </ul>
            </div>

            <!-- Kolom 3: Jam Layanan & Kebijakan -->
            <div class="footer-col">
                <h5>Jam Pelayanan Kerja</h5>
                <p class="footer-address" style="margin-bottom: 0.65rem;">
                    <strong>Senin &ndash; Kamis:</strong><br>
                    08.00 &ndash; 15.30 WIB<br><br>
                    <strong>Jumat:</strong><br>
                    08.00 &ndash; 16.00 WIB<br>
                    <em>(Sabtu, Minggu &amp; Libur Tutup)</em>
                </p>
            </div>

            <!-- Kolom 4: Alamat & Kontak Resmi -->
            <div class="footer-col">
                <h5>Alamat Kantor</h5>
                <p class="footer-address">
                    <strong>Dinas Kependudukan dan Pencatatan Sipil</strong><br>
                    Pemerintah Kabupaten Cirebon<br>
                    Jl. Sunan Muria No. 4, Sumber<br>
                    Kec. Sumber, Kabupaten Cirebon<br>
                    Jawa Barat 45611
                </p>
                <div style="margin-top: 0.85rem; display: flex; flex-direction: column; gap: 0.4rem;">
                    <a href="https://cirebonkab.go.id" target="_blank" rel="noopener noreferrer" style="color: #94a3b8; text-decoration: none; font-weight: 600; font-size: 0.725rem;">
                        Portal cirebonkab.go.id &rarr;
                    </a>
                </div>
            </div>
        </div>

        <!-- Copyright Bar -->
        <div class="footer-bottom-bar">
            <div>
                &copy; {{ date('Y') }} Dinas Kependudukan dan Pencatatan Sipil &mdash; Pemerintah Kabupaten Cirebon. Hak Cipta Dilindungi.
            </div>
            <div style="display: flex; gap: 1rem;">
                <a href="#beranda">Kembali ke Atas</a>
                <a href="https://disdukcapil.cirebonkab.go.id" target="_blank" rel="noopener noreferrer">Portal Disdukcapil</a>
            </div>
        </div>
    </footer>

    <!-- Script Sederhana untuk Mobile Menu & Smooth Scroll -->
    <script>
        function toggleMobileMenu() {
            var drawer = document.getElementById('mobileMenuDrawer');
            var btn = document.getElementById('mobileMenuBtn');
            if (drawer) {
                var isOpen = drawer.classList.toggle('open');
                if (btn) {
                    btn.classList.toggle('active', isOpen);
                }
            }
        }
    </script>
</body>
</html>
