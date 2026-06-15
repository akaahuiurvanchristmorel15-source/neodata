<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #7e0019ff;
            color: #e5e7eb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            padding: 24px;
        }

        /* ── Glows ── */
        .glow {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
        }

        .glow-c {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(255, 26, 64, .17) 0%, transparent 70%);
        }

        .glow-tr {
            top: -80px;
            right: -80px;
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(255, 77, 109, .13) 0%, transparent 70%);
        }

        .glow-bl {
            bottom: -80px;
            left: -80px;
            width: 360px;
            height: 360px;
            background: radial-gradient(circle, rgba(255, 0, 43, .10) 0%, transparent 70%);
        }

        /* ── Orbit rings (decorative, behind card) ── */
        .orbits {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 640px;
            height: 640px;
            pointer-events: none;
        }

        .orbit {
            position: absolute;
            border-radius: 50%;
            top: 50%;
            left: 50%;
        }

        .orbit-1 {
            width: 88%;
            height: 88%;
            margin-left: -44%;
            margin-top: -44%;
            border: 1px solid rgba(255, 26, 64, .22);
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
            box-shadow: 0 0 14px #ff1a40, 0 0 28px rgba(255, 26, 64, .5);
        }

        .orbit-2 {
            width: 105%;
            height: 105%;
            margin-left: -52.5%;
            margin-top: -52.5%;
            border: 1px dashed rgba(255, 77, 109, .13);
            animation: spin-ccw 65s linear infinite;
        }

        @keyframes spin-cw {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes spin-ccw {
            to {
                transform: rotate(-360deg);
            }
        }

        header {
            position: fixed;
            top: 10px;
            margin: 12px auto;
            width: 98%;
            max-width: 1800px;
            border-radius: 50px;
            padding: 24px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        /* ── Card ── */
        .card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 1);
            border: 1px solid rgba(255, 255, 255, .10);
            padding: 40px 36px;
            -webkit-backdrop-filter: blur(22px);
            box-shadow: 0 28px 80px rgba(255, 0, 43, .18), 0 0 0 1px rgba(255, 26, 64, .06);
            transition: border-color .4s;
        }

        .card:hover {
            border-color: rgba(255, 255, 255, 1);
        }

        /* live indicator */
        .card-live {
            position: absolute;
            top: 16px;
            right: 18px;
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ff1a40;
            animation: blink 2s ease-in-out infinite;
        }

        .off-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #374151;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .3
            }
        }

        /* ── Brand ── */
        .brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .brand-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            letter-spacing: 0.1em;
            font-size: 25px;
            line-height: 1;
            color: #970019ff;
            display: block;
            margin-bottom: 6px;
        }

        .brand-sub {
            font-size: 12px;
            font-weight: 300;
            letter-spacing: .06em;
            color: rgba(0, 0, 0, 0.75);
        }

        .brand-divider {
            width: 40px;
            height: 1px;
            background: linear-gradient(90deg, transparent, #ff1a40, transparent);
            margin: 14px auto 0;
        }

        /* ── Form ── */
        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #880000ff;
            margin-bottom: 8px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: rgba(255, 255, 255, .03);
            border: 1px solid rgba(100, 36, 36, 0.5);
            border-radius: 12px;
            color: #000000ff;
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 400;
            padding: 13px 16px;
            outline: none;
            transition: border-color .25s, box-shadow .25s, background .25s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: rgba(255, 26, 64, .55);
            background: rgba(255, 26, 64, .04);
            box-shadow: 0 0 0 3px rgba(255, 26, 64, .12);
        }

        input::placeholder {
            color: rgba(107, 114, 128, .6);
        }

        .error-msg {
            font-size: 11px;
            color: #ff4d6d;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .error-msg::before {
            content: '⚠';
            font-size: 10px;
        }

        /* Remember row */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 26px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 0;
            text-transform: none;
            color: #9ca3af;
            cursor: pointer;
        }

        input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: #ff1a40;
            cursor: pointer;
            border-radius: 4px;
        }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #ff002b 0%, #ff1a40 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 6px 28px rgba(255, 0, 43, .42), 0 2px 8px rgba(255, 0, 43, .25);
            transition: opacity .2s, transform .15s, box-shadow .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            opacity: .9;
            transform: translateY(-1px);
            box-shadow: 0 10px 36px rgba(255, 0, 43, .52);
        }

        .btn-submit:active {
            transform: translateY(0);
            opacity: 1;
        }

        .btn-arrow {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }

        .btn-submit.loading {
            opacity: 0.85;
            cursor: not-allowed;
            pointer-events: none;
        }

        .btn-submit.loading .btn-arrow {
            display: none;
        }

        .btn-submit.loading .btn-spinner {
            display: block;
        }

        .btn-spinner {
            width: 14px;
            height: 14px;
            display: none;
            animation: spin-cw 0.75s linear infinite;
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
        }

        /* ── Support Technique IA Link ── */
        .forgot-password {
            text-decoration: none;
            color: rgba(255, 26, 64, .9);
            font-size: 12px;
            font-weight: 600;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 5px;
            opacity: .85;
        }

        .forgot-password:hover {
            opacity: 1;
            color: #ff1a40;
        }

        /* ── Footer note ── */
        .card-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 11px;
            color: rgba(107, 114, 128, .65);
            letter-spacing: .04em;
        }

        .card-footer span {
            display: inline-block;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #ff1a40;
            vertical-align: middle;
            margin: 0 6px;
            box-shadow: 0 0 6px #ff1a40;
        }

        ::selection {
            background: #ff1a40;
            color: #fff;
        }

        /* Responsive return to home button */
        .btn-menu i {
            font-size: 15px;
            transition: transform 0.25s;
        }

        .btn-menu:hover i {
            transform: translateX(-3px);
        }

        @media (max-width: 767px) {
            header {
                padding: 16px 24px;
            }
            .btn-menu {
                padding: 0;
                width: 38px;
                height: 38px;
                justify-content: center;
                border-radius: 50%;
            }
            .btn-menu-text {
                display: none;
            }
            .btn-menu i {
                font-size: 18px;
            }
            .btn-menu:hover i {
                transform: translateX(-2px);
            }
        }
    </style>
</head>

<body>

    {{-- Glows --}}
    <div class="glow glow-c" aria-hidden="true"></div>
    <div class="glow glow-tr" aria-hidden="true"></div>
    <div class="glow glow-bl" aria-hidden="true"></div>

    {{-- Orbiting rings --}}
    <div class="orbits" aria-hidden="true">
        <div class="orbit orbit-1"></div>
        <div class="orbit orbit-2"></div>
    </div>

    <header>
        <h2 class="text-white-500 text-2xl font-bold">NEODATA</h2>
        <div class="header-actions">
            <a href="{{ route('welcome') }}" class="btn-menu">
                <i class="bx bx-left-arrow-alt"></i>
                <span class="btn-menu-text">Retour à l'accueil</span>
            </a>
        </div>
    </header>

    {{-- Login card --}}
    <div class="card">

        {{-- Live indicator --}}
        <div class="card-live" aria-hidden="true">
            <span class="live-dot"></span>
            <span class="off-dot"></span>
        </div>

        {{-- Brand --}}
        <div class="brand">
            <span class="brand-title">CONNEXION</span>
            <p class="brand-sub">Authentification sécurisée — YA Consulting</p>
            <div class="brand-divider"></div>
        </div>

        {{-- Form --}}
        <form action="{{ route('login') }}" method="POST" novalidate>
            @csrf

            <div class="form-group">
                <label for="email">Adresse Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                    placeholder="vous@exemple.com">
                @error('email')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                    placeholder="••••••••••••">
                @error('password')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Se souvenir de moi
                </label>
                <a href="{{ route('password-reset.email') }}" class="forgot-password">
                    <i class='bx bx-support'></i> Support Technique IA
                </a>
            </div>

            <button type="submit" class="btn-submit" id="loginSubmitBtn">
                <span class="btn-spinner"></span>
                <span class="btn-label">Ouvrir une session</span>
                <span class="btn-arrow">→</span>
            </button>
        </form>

        <div class="card-footer">
            Système sécurisé<span></span>YA Consulting
        </div>

    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const form = this;
            if (!form.checkValidity()) return;
            const btn = document.getElementById('loginSubmitBtn');
            const label = btn.querySelector('.btn-label');
            btn.classList.add('loading');
            btn.disabled = true;
            label.textContent = 'Connexion en cours…';
        });
    </script>
</body>

</html>