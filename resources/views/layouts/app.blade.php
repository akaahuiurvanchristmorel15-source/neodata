<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NEODATA') }}</title>

    <!-- PWA Meta & Manifest -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#7e0019">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="NEODATA">
    <link rel="apple-touch-icon" href="{{ asset('logo-favicon.png') }}">

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:300,400,600,800&display=swap" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: rgb(253, 241, 241);
            color: #ff1a40;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* ─── GLOWS AMBIANTS ─── */
        .glow-tr {
            position: fixed;
            top: -10%;
            right: -5%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(255, 77, 109, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* ─── GLASSMORPHISM ─── */
        .glass-sidebar {
            background: rgba(255, 255, 255, 1);
            border-right: 1px solid rgba(255, 26, 64, 0.08);
        }

        .glass-header {
            background: rgba(255, 255, 255, 1);
            border-bottom: 1px solid rgba(255, 26, 64, 0.08);
        }

        .glass-dropdown {
            background: rgba(119, 0, 24, 1);
        }

        /* ─── NAV ITEMS ─── */
        .nav-item-active {
            background: linear-gradient(135deg, #ff002b, #ff1a40);
            color: #ffffff !important;
            box-shadow: 0 4px 20px rgba(255, 0, 43, 0.2);
        }

        .nav-item-hover:hover {
            background: rgba(255, 26, 64, 0.05);
            color: #ff002b !important;
            border-color: rgba(255, 26, 64, 0.2);
        }

        .title-premium {
            font-family: 'Bebas Neue', sans-serif;
            letter-spacing: 0.05em;
        }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 26, 64, 0.05);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 26, 64, 0.3);
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 26, 64, 0.5);
        }

        /* ─── SIDEBAR desktop ─── */
        .sidebar {
            width: 264px;
            flex-shrink: 0;
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 30;
        }

        /* ─── MOBILE TOPBAR ─── */
        .mobile-topbar {
            display: none;
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(255, 255, 255, 1);
            border-bottom: 1px solid rgba(255, 26, 64, 0.08);
            padding: 14px 20px;
            align-items: center;
            justify-content: space-between;
        }

        /* ─── BURGER BUTTON ─── */
        .burger-btn {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: transparent;
            border: 1px solid rgba(255, 26, 64, 0.18);
            display: flex;
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
            background: #ff1a40;
            border-radius: 99px;
            transition: transform 0.3s, opacity 0.3s, width 0.3s;
            transform-origin: center;
        }

        /* X state */
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

        /* ─── MOBILE DRAWER ─── */
        .drawer-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(4px);
            z-index: 20;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .drawer-overlay.visible {
            display: block;
        }

        .drawer-overlay.active {
            opacity: 1;
        }

        /* ─── RESPONSIVE ─── */
        @media (max-width: 1023px) {
            .mobile-topbar {
                display: flex;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                transform: translateX(-100%);
                box-shadow: 8px 0 40px rgba(255, 26, 64, 0.08);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-area {
                /* full width on mobile */
            }
        }

        @media (min-width: 1024px) {
            .mobile-topbar {
                display: none !important;
            }

            .sidebar {
                transform: translateX(0) !important;
                position: sticky;
            }

            .drawer-overlay {
                display: none !important;
            }
        }

        /* ─── NOTIFICATION DROPDOWN ─── */
        .notif-dropdown {
            position: fixed;
            bottom: calc(100% + 10px);
            right: -50px;
            bottom: 120px;
            width: 310px;
            background: rgba(119, 0, 24, 1);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            display: none;
            z-index: 60;
        }

        .notif-trigger:hover .notif-dropdown,
        .notif-trigger:focus-within .notif-dropdown {
            display: block;
        }
    </style>
</head>

<body class="antialiased">

    <div class="glow-tr" aria-hidden="true"></div>

    {{-- ══ MOBILE TOPBAR ══ --}}
    <header class="mobile-topbar">
        <span class="text-2xl font-bold title-premium text-[#ff1a40]">
            NEO<span class="text-slate-800">DATA</span>
            <span
                class="ml-1.5 px-1.5 py-0.5 text-[9px] uppercase font-mono font-bold bg-[#ff1a40]/10 border border-[#ff1a40]/30 text-[#ff1a40] rounded-md tracking-widest align-middle">PRO</span>
        </span>
        <button class="burger-btn" id="burgerBtn" aria-label="Menu" aria-expanded="false">
            <span class="burger-line"></span>
            <span class="burger-line"></span>
            <span class="burger-line"></span>
        </button>
    </header>

    {{-- ══ OVERLAY ══ --}}
    <div class="drawer-overlay" id="drawerOverlay"></div>

    <div class="flex min-h-screen relative ">

        {{-- ══════════════ SIDEBAR ══════════════ --}}
        <aside class="sidebar glass-sidebar" id="sidebar">

            <div class="p-6 space-y-7 overflow-y-auto flex-1">
                {{-- Logo — masqué sur mobile (topbar le montre) --}}
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100 lg:flex hidden">
                    <span class="text-4xl font-bold title-premium text-[#ff1a40]">
                        NEO<span class="text-slate-800">DATA</span>
                    </span>
                    <span
                        class="px-1.5 py-0.5 text-[9px] uppercase font-mono font-bold bg-[#ff1a40]/10 border border-[#ff1a40]/30 text-[#ff1a40] rounded-md tracking-widest">
                        PRO
                    </span>
                </div>

                {{-- Logo visible sidebar mobile --}}
                <div class="flex items-center gap-2.5 pb-4 border-b border-gray-100 lg:hidden">
                    <span class="text-3xl font-bold title-premium text-[#ff1a40]">
                        NEO<span class="text-slate-800">DATA</span>
                    </span>
                    <span
                        class="px-1.5 py-0.5 text-[9px] uppercase font-mono font-bold bg-[#ff1a40]/10 border border-[#ff1a40]/30 text-[#ff1a40] rounded-md tracking-widest">PRO</span>
                </div>

                <nav class="space-y-1.5">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 px-3 mb-3 font-sans">
                        Menu principal
                    </p>

                    <a href="{{ route('dashboard') }}" onclick="closeSidebar()"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition duration-200 border border-transparent {{ request()->routeIs('dashboard') ? 'nav-item-active' : 'text-slate-600 nav-item-hover' }}">
                        <i
                            class='bx bxs-dashboard text-lg {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}'></i>
                        <span>Tableau de bord</span>
                    </a>

                    @if(auth()->check() && !auth()->user()->isSuperAdmin())
                        <a href="{{ route('documents.index', ['search' => 1]) }}" onclick="closeSidebar()"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition duration-200 border border-transparent {{ request()->input('search') ? 'nav-item-active' : 'text-slate-600 nav-item-hover' }}">
                            <i
                                class='bx bx-search-alt text-lg {{ request()->input('search') ? 'text-white' : 'text-slate-400' }}'></i>
                            <span>Recherche</span>
                        </a>
                    @endif

                    @if(auth()->check() && auth()->user()->isSuperAdmin())
                        <a href="{{ route('company-requests.index') }}" onclick="closeSidebar()"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition duration-200 border border-transparent {{ request()->routeIs('company-requests.*') ? 'nav-item-active' : 'text-slate-600 nav-item-hover' }}">
                            <i
                                class='bx bxs-building text-lg {{ request()->routeIs('company-requests.*') ? 'text-white' : 'text-slate-400' }}'></i>
                            <span>Demandes d'accès</span>
                            
                        </a>
                    @endif

                    @if(auth()->check() && auth()->user()->isAdmin() && !auth()->user()->isSuperAdmin())
                        <a href="{{ route('users.index') }}" onclick="closeSidebar()"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition duration-200 border border-transparent {{ request()->routeIs('users.*') ? 'nav-item-active' : 'text-slate-600 nav-item-hover' }}">
                            <i
                                class='bx bxs-user-detail text-lg {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-400' }}'></i>
                            <span>Gestion des utilisateurs</span>
                        </a>
                    @endif

                    @if(auth()->check() && !auth()->user()->isSuperAdmin() && (auth()->user()->isAdmin() || auth()->user()->isDirection()))
                        <div class="pt-5 mt-5 border-t border-gray-100 space-y-1.5">
                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 px-3 mb-3">
                                Infrastructures
                            </p>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('categories.index') }}" onclick="closeSidebar()"
                                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition duration-200 border border-transparent {{ request()->routeIs('categories.*') ? 'nav-item-active' : 'text-slate-600 nav-item-hover' }}">
                                    <i
                                        class='bx bxs-category text-lg {{ request()->routeIs('categories.*') ? 'text-white' : 'text-slate-400' }}'></i>
                                    <span>Dossiers & Fichiers</span>
                                </a>
                            @endif

                            <a href="{{ route('audit.index') }}" onclick="closeSidebar()"
                                class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition duration-200 border border-transparent {{ request()->routeIs('audit.*') ? 'nav-item-active' : 'text-slate-600 nav-item-hover' }}">
                                <i
                                    class='bx bxs-shield-quarter text-lg {{ request()->routeIs('audit.*') ? 'text-white' : 'text-slate-400' }}'></i>
                                <span>Activités récentes</span>
                            </a>
                        </div>
                    @endif
                </nav>
            </div>

            {{-- Footer sidebar --}}
            <div class="p-4 border-t border-gray-100 bg-slate-50 space-y-3">
                <div class="flex items-center justify-between px-2 py-1">
                    <div class="truncate max-w-[130px]">
                        <p class="text-xs font-bold text-slate-800 truncate">
                            {{ auth()->user()->name ?? 'Utilisateur' }}
                        </p>
                        <p class="text-[10px] text-slate-400 capitalize font-mono tracking-wider mt-0.5">
                            {{ auth()->user()->role ?? 'Membre' }}
                        </p>
                    </div>

                    {{-- Notifications --}}
                    <div class="relative notif-trigger">
                        <button
                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-200 hover:border-[#ff1a40]/40 rounded-xl text-slate-500 hover:text-slate-800 transition relative">
                            <i class='bx bx-bell text-lg'></i>
                            @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute top-1 right-1 flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#ff1a40] opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#ff1a40]"></span>
                                </span>
                            @endif
                        </button>

                        <div class="notif-dropdown">
                            <div class="p-3 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                                <span class="text-xs font-bold tracking-wide text-white uppercase">Alertes
                                    Système</span>
                                @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                                    <span class="text-[9px] bg-[#ff1a40]/20 text-[#ff4d6d] px-1.5 py-0.5 rounded font-bold">
                                        {{ auth()->user()->unreadNotifications->count() }} NEW
                                    </span>
                                @endif
                            </div>
                            <div class="divide-y divide-white/5 max-h-60 overflow-y-auto">
                                @if(auth()->check())
                                    @forelse(auth()->user()->notifications->take(15) as $notif)
                                        <a href="{{ route('notifications.read', $notif->id) }}"
                                            class="p-3 flex gap-3 hover:bg-white/[0.06] transition block {{ is_null($notif->read_at) ? 'bg-white/[0.04]' : '' }}">
                                            <div class="relative shrink-0">
                                                <span class="text-sm">{{ $notif->data['icone'] ?? '⚙️' }}</span>
                                                @if(is_null($notif->read_at))
                                                    <span class="absolute -top-0.5 -right-0.5 flex h-2 w-2 rounded-full bg-[#ff1a40]"></span>
                                                @endif
                                            </div>
                                            <div class="space-y-0.5 flex-1 min-w-0">
                                                <p class="text-xs leading-snug {{ is_null($notif->read_at) ? 'text-white font-medium' : 'text-gray-400' }}">
                                                    {{ $notif->data['message'] ?? '' }}
                                                </p>
                                                <p class="text-[9px] font-mono {{ is_null($notif->read_at) ? 'text-white/40' : 'text-white/20' }}">
                                                    {{ $notif->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="p-5 text-center text-xs text-white/30 italic">
                                            <i class='bx bx-badge-check text-xl mb-1 text-white/20 block'></i>
                                            Aucune notification.
                                        </div>
                                    @endforelse
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-xl text-xs font-bold uppercase tracking-wider text-red-500 bg-red-50 hover:bg-red-100 border border-red-200 transition duration-200 cursor-pointer">
                        <i class='bx bx-log-out-circle text-sm'></i>
                        <span>Fermer la session</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ══════════════ MAIN AREA ══════════════ --}}
        <div class="main-area flex-1 flex flex-col min-w-0">

            {{-- NOTIFICATION POP-UP DEMANDES EN ATTENTE --}}
            @if(auth()->check() && auth()->user()->isAdmin())
                @php
                    $pendingCount = \App\Models\CompanyAccessRequest::where('status', 'pending')->count();
                @endphp
                @if($pendingCount > 0)
                    <div id="notifPending" class="fixed top-20 left-8 z-50 bg-white border-2 border-amber-400 rounded-xl shadow-2xl p-6 max-w-md animate-pulse">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <span class="text-3xl"><i class='bx bx-bell'></i></span>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-amber-900">Demandes d'accès en attente</h3>
                                <p class="text-black/70 text-800 mt-2">
                                    <strong>{{ $pendingCount }}</strong> demande(s) à examiner
                                </p>
                                <a href="{{ route('company-requests.index') }}" class="inline-block mt-3 px-4 py-2 bg-red text-red rounded-lg hover:bg-[#ff1a40] hover:text-white font-semibold text-sm transition ">
                                    Voir demandes →
                                </a>
                            </div>
                            <button onclick="closeNotif()" class="text-amber-500 hover:text-amber-700 text-2xl font-bold leading-none">✕</button>
                        </div>
                    </div>
                @endif
            @endif

            @isset($header)
                <header class="glass-header py-5 px-8 shadow-sm relative z-20">
                    <div class="max-w-7xl mx-auto">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1 p-4 sm:p-8 relative z-10">
                <div class="max-w-7xl mx-auto">
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset

                    @isset($utilisateurs)
                        <div class="mt-8">{{ $utilisateurs }}</div>
                    @endisset
                </div>
            </main>
        </div>

    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const burgerBtn = document.getElementById('burgerBtn');
        const drawerOverlay = document.getElementById('drawerOverlay');

        function openSidebar() {
            sidebar.classList.add('open');
            burgerBtn.classList.add('open');
            burgerBtn.setAttribute('aria-expanded', 'true');
            drawerOverlay.classList.add('visible');
            requestAnimationFrame(() => drawerOverlay.classList.add('active'));
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            burgerBtn.classList.remove('open');
            burgerBtn.setAttribute('aria-expanded', 'false');
            drawerOverlay.classList.remove('active');
            setTimeout(() => drawerOverlay.classList.remove('visible'), 300);
            document.body.style.overflow = '';
        }

        burgerBtn.addEventListener('click', () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });

        drawerOverlay.addEventListener('click', closeSidebar);

        // Fermer avec Échap
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeSidebar();
        });

        // Auto-fermeture notification demandes en attente
        function closeNotif() {
            const notif = document.getElementById('notifPending');
            if (notif) {
                notif.style.opacity = '0';
                notif.style.transform = 'translateX(400px)';
                notif.style.transition = 'all 0.3s ease-out';
                setTimeout(() => notif.remove(), 300);
            }
        }

        // Auto-fermer après 20 secondes
        if (document.getElementById('notifPending')) {
            setTimeout(() => closeNotif(), 20000);
        }

        // ═══════════════════════════════════════════════════════════
        // AUTO-REFRESH PAGE AFTER ACTIONS
        // ═══════════════════════════════════════════════════════════
        
        // Configuration du rafraîchissement automatique
        const AUTO_REFRESH_CONFIG = {
            ENABLED: true,                    // Activer/désactiver le rafraîchissement
            SUCCESS_DELAY: 2000,              // Délai avant rafraîchissement (ms)
            CHECK_INTERVAL: 100,              // Intervalle de vérification (ms)
            EXCLUDE_ROUTES: ['logout'],       // Routes à exclure
        };

        // Fonction pour détecter et rafraîchir après succès
        function setupAutoRefresh() {
            if (!AUTO_REFRESH_CONFIG.ENABLED) return;

            // Attendre que le DOM soit complètement chargé
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initAutoRefresh);
            } else {
                initAutoRefresh();
            }
        }

        function initAutoRefresh() {
            // Chercher les éléments d'alerte de succès
            const observer = new MutationObserver(() => {
                const successElements = document.querySelectorAll(
                    '[data-flash-type="success"], .alert-success[data-flash-type="success"]'
                );
                
                if (successElements.length > 0) {
                    // Rafraîchir après le délai configuré
                    setTimeout(() => {
                        if (window.location.href.includes(AUTO_REFRESH_CONFIG.EXCLUDE_ROUTES[0])) {
                            return; // Ne pas rafraîchir pour les routes exclues
                        }
                        location.reload();
                    }, AUTO_REFRESH_CONFIG.SUCCESS_DELAY);
                    
                    // Arrêter d'observer
                    observer.disconnect();
                }
            });

            // Observer les changements dans le DOM
            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: false,
            });

            // Vérification immédiate au cas où le message serait déjà présent
            const existingSuccess = document.querySelectorAll(
                '[data-flash-type="success"], .alert-success[data-flash-type="success"]'
            );
            
            if (existingSuccess.length > 0) {
                setTimeout(() => {
                    location.reload();
                }, AUTO_REFRESH_CONFIG.SUCCESS_DELAY);
                observer.disconnect();
            }
        }

        // Lancer la configuration
        setupAutoRefresh();

        // Alternative : Intercepte les soumissions de formulaire pour AJAX (optionnel)
        // Décommentez pour utiliser AJAX à la place des redirections standard
        /*
        document.addEventListener('DOMContentLoaded', () => {
            const forms = document.querySelectorAll('form[method="POST"], form[method="post"]');
            
            forms.forEach(form => {
                // Exclure les formulaires de logout et certains autres
                if (form.action.includes('logout') || form.closest('[data-no-ajax]')) {
                    return;
                }

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const actionUrl = this.action;
                    const method = this.method.toUpperCase();
                    const submitBtn = this.querySelector('button[type="submit"]');
                    
                    // Désactiver le bouton pendant la soumission
                    if (submitBtn) submitBtn.disabled = true;

                    fetch(actionUrl, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: formData
                    })
                    .then(response => {
                        if (response.ok || response.status === 302 || response.status === 301) {
                            // Rafraîchir après un succès
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            // Afficher une erreur
                            if (submitBtn) submitBtn.disabled = false;
                            alert('Une erreur s\'est produite. Veuillez réessayer.');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur AJAX:', error);
                        if (submitBtn) submitBtn.disabled = false;
                        alert('Erreur de connexion. Veuillez réessayer.');
                    });
                });
            });
        });
        */
    </script>

</body>

</html>