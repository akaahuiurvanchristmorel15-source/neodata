<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>NEODATA</title>
    
    <link rel="icon" type="image/png" href="{{ asset('logo-favicon.png') }}">
    <meta name="theme-color" content="#7e0019">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="NEODATA">
    <link rel="apple-touch-icon" href="{{ asset('logo-favicon.png') }}">

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,600,800&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;600;700;800&display=swap"
        rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #7e0019ff;
            color: #e5e7eb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-x: hidden;
            position: relative;
        }

        /* ─── GLOWS ─── */
        .glow-center {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 650px;
            height: 650px;
            background: radial-gradient(circle, rgba(255, 26, 64, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .glow-tr {
            position: fixed;
            top: 10%;
            right: 5%;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(255, 77, 109, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .glow-bl {
            position: fixed;
            bottom: 5%;
            left: 5%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 0, 43, 0.09) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* ─── HEADER ─── */
        header {
            background-color: #7e001918;
            backdrop-filter: blur(40px);
            box-shadow: inset 0 0 20px #ffffff86;
            margin: 12px auto;
            width: 98%;
            max-width: 1800px;
            border-radius: 50px;
            padding: 24px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 10;
        }

        nav.main-nav {
            display: none;
        }

        @media (min-width: 768px) {
            nav.main-nav {
                display: flex;
                align-items: center;
                gap: 32px;
                font-size: 13px;
                font-weight: 500;
                color: #9ca3af;
            }
        }

        nav.main-nav a {
            font-size: 16px;
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.2s;
        }

        nav.main-nav a:first-child {
            color: #ffffff;
        }

        nav.main-nav a:hover {
            color: #ffa1b1ff;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-menu {
            padding: 9px 22px;
            border-radius: 999px;
            font-size: 11px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 10px #8d8d8dff;
            color: #53000eff;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.25s;
            cursor: pointer;
        }

        .btn-menu:hover {
            color: #ffffff;
            background: rgba(255, 0, 0, 1);
        }

        .btn-dashboard {
            padding: 9px 22px;
            border-radius: 999px;
            font-size: 11px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            background: linear-gradient(135deg, #ff002b, #ff1a40);
            color: #ffffff;
            border: none;
            box-shadow: 0 4px 24px rgba(255, 0, 43, 0.4);
            transition: opacity 0.2s;
            cursor: pointer;
        }

        .btn-dashboard:hover {
            opacity: 0.88;
        }

        .menu-dots {
            display: flex;
            gap: 4px;
        }

        .menu-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #6b7280;
        }
        .menu-dot2 {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #ffffff;
        }

        /* ─── BURGER BUTTON ─── */
        .burger-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.18);
            /* display: flex; retiré d'ici pour éviter le conflit avec le lg:hidden de Tailwind */
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
            padding: 0;
        }

        .burger-btn:hover {
            background: rgba(255, 26, 64, 0.05);
            border-color: rgba(255, 26, 64, 0.35);
        }

        .burger-line {
            display: block;
            width: 18px;
            height: 1.5px;
            background: #ffffff;
            border-radius: 99px;
            transition: transform 0.3s, opacity 0.3s, width 0.3s;
            transform-origin: center;
        }

        /* X state — corrigé : gap 5px + height 1.5px → déplacement = 5px + 1.5px = 6.5px ✓ */
        .burger-btn.open .burger-line:nth-child(1) {
            transform: translateY(6.5px) rotate(45deg);
        }

        .burger-btn.open .burger-line:nth-child(2) {
            opacity: 0;
            width: 0;
        }

        .burger-btn.open .burger-line:nth-child(3) {
            transform: translateY(-6.5px) rotate(-45deg);
        }

        /* ─── MOBILE MENU FULLSCREEN OVERLAY ─── */
        #mobile-menu {
            position: fixed;
            inset: 0;
            z-index: 50;
            height: 250px;
            background: rgb(126, 0, 25);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
            /* shadow direction bottom */
            box-shadow: 0 0 5px 50px rgb(126, 0, 25);
        }

        #mobile-menu.open {
            opacity: 1;
            pointer-events: auto;
        }

        #mobile-menu .close-btn {
            position: absolute;
            top: 24px;
            right: 24px;
            background: none;
            border: none;
            color: #ffffff;
            font-size: 28px;
            cursor: pointer;
            line-height: 1;
        }

        #mobile-menu .overlay-link {
            font-family: 'Outfit', sans-serif;
            font-size: 18px;
            font-weight: 600;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 32px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            width: 220px;
            text-align: center;
            transition: background 0.2s;
        }

        #mobile-menu .overlay-link:hover {
            background: rgba(255,255,255,0.16);
        }

        /* ─── MAIN GRID ─── */
        main.hero {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
            padding: 24px 48px 40px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 48px;
            align-items: center;
            position: relative;
            z-index: 0;
        }

        @media (min-width: 1024px) {
            main.hero {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* ─── LEFT COLUMN ─── */
        .hero-left {
            display: flex;
            flex-direction: column;
            gap: 0;
            text-align: justify;
        }

        h1.hero-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(60px, 8vw, 230px);
            line-height: 0.88;
            color: #ffffff;
            letter-spacing: 0.02em;
            margin-bottom: 20px;
        }

        .title-accent {
            display: block;
            background: linear-gradient(135deg, #ffffff 0%, #ff4d6d 45%, #ff1a40 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 32px rgba(255, 26, 64, 0.55));
        }

        .hero-desc {
            font-size: 14px;
            line-height: 1.75;
            color: rgba(255, 255, 255, 0.8);
            max-width: 400px;
            font-weight: 300;
            margin-bottom: 32px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 16px;
        }

        .btn-discover {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 13px 26px;
            border-radius: 999px;
            font-size: 11px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-discover:hover {
            background: rgba(255, 255, 255, 0.09);
            border-color: rgba(255, 26, 64, 0.5);
        }

        .btn-discover:hover .dot-main {
            background: #ff1a40;
            box-shadow: 0 0 10px #ff1a40;
            transform: scale(1.25);
        }

        .dot-main,
        .dot-sub {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
            transition: all 0.3s;
        }

        .dot-main {
            background: #ffffff;
        }

        .dot-sub {
            background: rgba(255, 255, 255, 0.3);
        }

        .btn-connect {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 13px 18px;
            border-radius: 999px;
            font-size: 11px;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            text-decoration: none;
            background: transparent;
            border: none;
            color: #9ca3af;
            transition: color 0.2s;
            cursor: pointer;
        }

        .btn-connect:hover {
            color: #ffffff;
        }

        .connect-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* ─── RIGHT COLUMN (CARD + ORBITS) ─── */
        .hero-right {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .orbit-wrap {
            position: relative;
            width: 360px;
            height: 360px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .orbit-ring {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .orbit-1 {
            width: 150%;
            height: 150%;
            border: 1px solid rgba(255, 26, 64, 0.3);
            animation: spin-cw 40s linear infinite;
        }

        .orbit-1::before {
            content: '';
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 10px;
            height: 10px;
            background: #ff1a40;
            border-radius: 50%;
            box-shadow: 0 0 14px #ff1a40, 0 0 30px rgba(255, 26, 64, 0.5);
        }

        .orbit-2 {
            width: 250%;
            height: 100%;
            border: 1px dashed rgba(255, 77, 109, 0.18);
            animation: spin-ccw 60s linear infinite;
        }

        .orbit-glow {
            position: absolute;
            inset: -20px;
            background: radial-gradient(circle, rgba(255, 0, 43, 0.18) 0%, rgba(255, 26, 64, 0.12) 40%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            transition: opacity 0.7s;
        }

        .orbit-wrap:hover .orbit-glow {
            opacity: 1.4;
        }

        @keyframes spin-cw {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        @keyframes spin-ccw {
            from { transform: rotate(0deg); }
            to   { transform: rotate(-360deg); }
        }

        .server-card {
            position: relative;
            z-index: 5;
            background: rgba(5, 0, 1, 0.65);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 16px;
            width: 290px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            box-shadow: 0 24px 64px rgba(255, 0, 43, 0.18);
            transition: border-color 0.5s;
        }

        .server-card:hover {
            border-color: rgba(255, 26, 64, 0.4);
        }

        .card-status {
            position: absolute;
            top: 12px;
            right: 14px;
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .status-dot-live {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ff1a40;
            animation: blink 2s ease-in-out infinite;
        }

        .status-dot-off {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #374151;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.35; }
        }

        .card-img {
            width: 100%;
            aspect-ratio: 4/3;
            border-radius: 14px;
            overflow: hidden;
            object-fit: cover;
            mix-blend-mode: screen;
            filter: drop-shadow(0 0 32px rgba(255, 26, 64, 0.42));
            transition: transform 0.5s;
            display: block;
            background: #1a0008;
        }

        .server-card:hover .card-img {
            transform: scale(1.02);
        }

        /* ─── FOOTER ─── */
        footer {
            width: 100%;
            background: rgba(255, 255, 255, 0.92);
            border-top: 1px solid rgba(255, 26, 64, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: relative;
            z-index: 10;
        }

        .footer-grid {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            font-size: 11px;
            color: #000000ff;
            font-weight: 300;
        }

        @media (min-width: 768px) {
            .footer-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .footer-cell {
            padding: 18px 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        @media (min-width: 768px) {
            .footer-cell {
                border-bottom: none;
                border-right: 1px solid rgba(255, 255, 255, 0.06);
            }
            .footer-cell:last-child {
                border-right: none;
            }
        }

        .footer-cell.center {
            align-items: center;
            justify-content: center;
        }

        .play-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.52);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000000ff;
            font-size: 13px;
            flex-shrink: 0;
            cursor: pointer;
            transition: all 0.25s;
        }

        .play-btn:hover {
            border-color: #ff1a40;
            color: #ff1a40;
            box-shadow: 0 0 14px rgba(255, 26, 64, 0.45);
        }

        .play-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #000000;
            align-self: center;
        }

        .footer-num {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.32);
            border: 1px solid rgba(0, 0, 0, 0.73);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-family: monospace;
            color: #000000ff;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .footer-text {
            font-size: 11px;
            line-height: 1.65;
            color: rgba(0, 0, 0, 0.85);
        }

        .socials {
            display: flex;
            align-items: center;
            gap: 22px;
            font-size: 16px;
            font-weight: 600;
        }

        .socials a {
            color: #6b7280;
            text-decoration: none;
            transition: color 0.2s;
        }

        .socials a:hover {
            color: #ff1a40;
        }

        /* ─── SELECTION ─── */
        ::selection {
            background: #ff1a40;
            color: #ffffff;
        }
    </style>
</head>

<body>

    {{-- Ambient glows --}}
    <div class="glow-center" aria-hidden="true"></div>
    <div class="glow-tr" aria-hidden="true"></div>
    <div class="glow-bl" aria-hidden="true"></div>

    {{-- ══════════════════════════════════════ HEADER ══════════════════════════════════════ --}}
    <header>
        <h2 class="text-white-500 text-2xl font-bold">NEODATA</h2>
        <div class="header-actions relative flex items-center justify-between">
            @if (Route::has('login'))
                @auth
                    {{-- Utilisateur connecté --}}
                    <a href="{{ url('/dashboard') }}" class="btn-dashboard text-sm md:text-base px-4 py-2">
                        Tableau de bord
                    </a>
                @else
                    {{-- Visiteur --}}

            {{-- ── BURGER : Visible en flex sur mobile/tablette, caché via display:none dès l'écran 'lg' ── --}}
                    <button class="flex md:hidden burger-btn" id="burgerBtn" aria-label="Ouvrir le menu" aria-expanded="false">
                        <span class="burger-line"></span>
                        <span class="burger-line"></span>
                        <span class="burger-line"></span>
                    </button> 

                    {{-- ── BOUTONS DESKTOP : cachés sur mobile, visibles sur md+ ── --}}
                    <div class="hidden md:flex flex-row items-center gap-4">
                        <a href="{{ route('login') }}" class="btn-menu text-base px-4 py-2">
                            Connexion
                            <span class="menu-dots" aria-hidden="true">
                                <i class="bx bx-caret-right"></i>
                            </span>
                        </a>
                        <a href="{{ route('register') }}" class="btn-menu text-base px-4 py-2">
                            Demande d'accès
                            <span class="menu-dots" aria-hidden="true">
                                <i class="bx bx-caret-right"></i>
                            </span>
                        </a>
                    </div>

                    {{-- ── OVERLAY FULLSCREEN MOBILE ── --}}
                    <div id="mobile-menu" role="dialog" aria-modal="true" aria-label="Menu navigation">

                        {{-- Bouton fermer --}}
                        <button class="close-btn" id="closeMenu" aria-label="Fermer le menu">
                            <i class="bx bx-x"></i>
                        </button>

                        {{-- Liens centrés --}}
                        <a href="{{ route('login') }}" class="overlay-link">Connexion</a>
                        <a href="{{ route('register') }}" class="overlay-link">Demande d'accès</a>
                    </div>

                @endauth
            @endif
        </div>
    </header>

    {{-- ══════════════════════════════════════ HERO ══════════════════════════════════════ --}}
    <main class="hero">

        {{-- Left column --}}
        <div class="hero-left">
            <h1 class="hero-title">
                ARCHIVAGE <br>
                <span class="title-accent">NEODATA</span>
            </h1>

            <p class="hero-desc">
                Bienvenue sur notre plateforme de gestion des archives, une solution moderne conçue pour faciliter
                l'organisation, le stockage et la consultation sécurisée de vos documents.
                Grâce à une interface intuitive et performante, gérez efficacement vos archives administratives,
                professionnelles ou personnelles en quelques clics.
            </p>

            <div class="hero-actions">
                <a href="{{ route('register') }}" class="btn-discover">
                    <span>Enregistrer votre Entreprise</span>
                    <span class="dot-main" aria-hidden="true"></span>
                    <span class="dot-sub" aria-hidden="true"></span>
                </a>

                <a href="{{ route('login') }}" class="btn-discover">
                    <span>Se connecter</span>
                    <span class="dot-main" aria-hidden="true"></span>
                    <span class="dot-sub" aria-hidden="true"></span>
                </a>

                <a href="https://ya-consulting.com" target="_blank" rel="noopener noreferrer" class="btn-connect">
                    <i class='bx bx-world connect-icon'></i>
                    <span>Site Officiel</span>
                </a>
            </div>
        </div>

        {{-- Right column — card with orbiting rings --}}
        <div class="hero-right">
            <div class="orbit-wrap">
                <div class="orbit-glow" aria-hidden="true"></div>
                <div class="orbit-ring orbit-1" aria-hidden="true"></div>
                <div class="orbit-ring orbit-2" aria-hidden="true"></div>

                <div class="w-full max-w-md mx-auto relative px-4">
                    <div class="flex items-center gap-2 mb-4 px-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#ff1a40] animate-pulse"></span>
                        <h2 class="text-[10px] uppercase font-bold tracking-[0.2em] text-gray-400">Performance & Fonctionnalités</h2>
                    </div>

                    <div class="relative h-[280px] overflow-hidden [mask-image:linear-gradient(to_bottom,transparent,black_10%,black_90%,transparent)]">
                        <div class="space-y-4 animate-[marquee_20s_linear_infinite] hover:[animation-play-state:paused] cursor-pointer">

                            <div class="bg-white/[0.01] border border-white/5 backdrop-blur-md p-4 rounded-2xl flex gap-4 items-center hover:border-[#ff1a40]/30 transition group">
                                <div class="w-10 h-10 rounded-xl bg-[#ff1a40]/5 border border-[#ff1a40]/10 flex items-center justify-center text-[#ff1a40] shrink-0 group-hover:shadow-[0_0_10px_#ff1a40] transition">
                                    <i class='bx bxs-archive-in text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wide text-white">Archivage Centralisé</h3>
                                    <p class="text-white-300 text-[11px] leading-tight mt-0.5">Centralisation et indexation automatique des données de YA Consulting.</p>
                                </div>
                            </div>

                            <div class="bg-white/[0.01] border border-white/5 backdrop-blur-md p-4 rounded-2xl flex gap-4 items-center hover:border-[#ff1a40]/30 transition group">
                                <div class="w-10 h-10 rounded-xl bg-[#ff1a40]/5 border border-[#ff1a40]/10 flex items-center justify-center text-[#ff1a40] shrink-0 group-hover:shadow-[0_0_10px_#ff1a40] transition">
                                    <i class='bx bxs-folder-open text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wide text-white">Indexation Sémantique</h3>
                                    <p class="text-white-300 text-[11px] leading-tight mt-0.5">Arborescence intelligente par catégories et sous-catégories dynamiques.</p>
                                </div>
                            </div>

                            <div class="bg-white/[0.01] border border-white/5 backdrop-blur-md p-4 rounded-2xl flex gap-4 items-center hover:border-[#ff1a40]/30 transition group">
                                <div class="w-10 h-10 rounded-xl bg-[#ff1a40]/5 border border-[#ff1a40]/10 flex items-center justify-center text-[#ff1a40] shrink-0 group-hover:shadow-[0_0_10px_#ff1a40] transition">
                                    <i class="bx bx-layers-down-right"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wide text-white">Audit & Traçabilité</h3>
                                    <p class="text-white-300 text-[11px] leading-tight mt-0.5">Journalisation complète des logs d'accès et des actions utilisateurs.</p>
                                </div>
                            </div>

                            <div class="bg-white/[0.01] border border-white/5 backdrop-blur-md p-4 rounded-2xl flex gap-4 items-center hover:border-[#ff1a40]/30 transition group">
                                <div class="w-10 h-10 rounded-xl bg-[#ff1a40]/5 border border-[#ff1a40]/10 flex items-center justify-center text-[#ff1a40] shrink-0 group-hover:shadow-[0_0_10px_#ff1a40] transition">
                                    <i class='bx bxs-lock-alt text-xl'></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold uppercase tracking-wide text-white">Rétention Sécurisée</h3>
                                    <p class="text-white-300 text-[11px] leading-tight mt-0.5">Verrouillage matériel des archives et isolation des fichiers sensibles.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    {{-- ══════════════════════════════════════ FOOTER ══════════════════════════════════════ --}}
    <footer>
        <div class="footer-grid">
            <div class="footer-cell" style="align-items:center;">
                <button class="play-btn" aria-label="Lire la bande-annonce">▶</button>
                <span class="play-label">Play Trailer</span>
            </div>

            <div class="footer-cell">
                <span class="footer-num" aria-hidden="true">1</span>
                <p class="footer-text">
                    Garantir la sécurité, l'intégrité et la centralisation de vos archives numériques,
                    en offrant une solution d'archivage moderne et chiffrée adaptée aux exigences de demain.
                </p>
            </div>

            <div class="footer-cell">
                <span class="footer-num" aria-hidden="true">2</span>
                <p class="footer-text">
                    Devenir la référence en matière de transition zéro papier, en transformant la gestion
                    documentaire en une expérience fluide, sécurisée et accessible instantanément.
                </p>
            </div>
                <div class="footer-cell">
                    <span class="footer-num" aria-hidden="true">3</span>
                    <p class="footer-text">
                        pour les mot de passe oublier contacter le support technique de YA Consulting : 
                        <a href="{{ route('password-reset.email') }}" class="text-[#ff1a40] hover:underline transition duration-200 ease-in-out">
                            <i class='bx bx-support'></i> Support Technique IA
                        </a>
                    </p>
                </div>

            <div class="footer-cell center">
                <nav class="socials" aria-label="Réseaux sociaux">
                    <a href="#" aria-label="Facebook">f</a>
                    <a href="#" aria-label="LinkedIn">in</a>
                    <a href="https://aka-designstudio.com/Portfolio/" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    aria-label="Créateurs">
                    Créateur
                    </a>                    
                    <a href="https://ya-consulting.com/" aria-label="ya-consulting.com" target="_blank" rel="noopener noreferrer">yac.com</a>
                </nav>
            </div>
        </div>
    </footer>

    {{-- ══════════════════════════════════════ SCRIPTS ══════════════════════════════════════ --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const burgerBtn = document.getElementById('burgerBtn');
            const mobileMenu = document.getElementById('mobile-menu');
            const closeBtn  = document.getElementById('closeMenu');

            function openMenu() {
                mobileMenu.classList.add('open');
                burgerBtn.classList.add('open');
                burgerBtn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden'; // bloque le scroll
            }

            function closeMenu() {
                mobileMenu.classList.remove('open');
                burgerBtn.classList.remove('open');
                burgerBtn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }

            if (burgerBtn && mobileMenu) {
                burgerBtn.addEventListener('click', openMenu);
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', closeMenu);
            }

            // Ferme aussi avec la touche Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeMenu();
            });
        });
    </script>

</body>

</html>