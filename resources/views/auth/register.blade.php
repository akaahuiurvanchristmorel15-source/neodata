<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        *, *::before, *::after {
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
            position: relative;
            padding: 120px 24px 48px;
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

        /* ── Orbit rings ── */
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

        @keyframes spin-cw  { to { transform: rotate(360deg);  } }
        @keyframes spin-ccw { to { transform: rotate(-360deg); } }

        /* ── Header ── */
        header {
            position: fixed;
            top: 0;
            left: 50%;
            background: rgb(126, 0, 25);
            transform: translateX(-50%);
            width: 100%;
            max-width: 1800px;
            padding: 24px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
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

        .btn-menu i {
            font-size: 15px;
            transition: transform 0.25s;
        }

        .btn-menu:hover i {
            transform: translateX(-3px);
        }

        /* ── Card ── */
        .card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 500px;
            background: rgba(255, 255, 255, 1);
            border: 1px solid rgba(255, 255, 255, .10);
            padding: 40px 36px;
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
            0%, 100% { opacity: 1  }
            50%       { opacity: .3 }
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
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

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: rgba(255, 255, 255, .03);
            border: 1px solid rgba(100, 36, 36, 0.5);
            border-radius: 12px;
            color: #000000ff;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 400;
            padding: 13px 16px;
            outline: none;
            transition: border-color .25s, box-shadow .25s, background .25s;
        }

        textarea {
            width: 100%;
            background: rgba(255, 255, 255, .03);
            border: 1px solid rgba(100, 36, 36, 0.5);
            border-radius: 12px;
            color: #000000ff;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 400;
            padding: 13px 16px;
            outline: none;
            resize: vertical;
            min-height: 80px;
            transition: border-color .25s, box-shadow .25s, background .25s;
        }

        input:focus, textarea:focus {
            border-color: rgba(255, 26, 64, .55);
            background: rgba(255, 26, 64, .04);
            box-shadow: 0 0 0 3px rgba(255, 26, 64, .12);
        }

        input::placeholder, textarea::placeholder {
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

        /* ── Submit ── */
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
            margin-top: 8px;
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

        /* ── Footer note ── */
        .card-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 11px;
            color: rgba(107, 114, 128, .8);
            letter-spacing: .04em;
        }

        .card-footer a {
            color: #ff1a40;
            font-weight: 700;
            text-decoration: none;
        }

        .card-footer a:hover {
            text-decoration: underline;
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

        /* ── Responsive ── */
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

            .form-grid {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 32px 22px;
            }
        }

        /* ── Success Modal ── */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(83, 0, 14, 0.65);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px 30px;
            max-width: 460px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 26, 64, 0.2);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        .modal-icon-container {
            width: 80px;
            height: 80px;
            background: rgba(255, 26, 64, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .modal-icon {
            font-size: 48px;
            color: #ff1a40;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes popIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-title {
            font-size: 22px;
            font-weight: 700;
            color: #970019;
            margin-bottom: 12px;
        }

        .modal-text {
            font-size: 15px;
            color: rgba(0, 0, 0, 0.7);
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .modal-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            background: linear-gradient(135deg, #ff002b 0%, #ff1a40 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(255, 0, 43, 0.3);
            transition: all 0.2s;
        }

        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 0, 43, 0.4);
        }

        .modal-btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            color: #374151;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .modal-btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
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

    {{-- Header --}}
    <header>
        <h2 style="color:#fff; font-size:1.25rem; font-weight:800; letter-spacing:0.05em;">NEODATA</h2>
        <div>
            <a href="{{ route('login') }}" class="btn-menu">
                <i class="bx bx-left-arrow-alt"></i>
                <span class="btn-menu-text">Retour à la connexion</span>
            </a>
        </div>
    </header>

    {{-- Register card --}}
    <div class="card">

        {{-- Live indicator --}}
        <div class="card-live" aria-hidden="true">
            <span class="live-dot"></span>
            <span class="off-dot"></span>
        </div>

        {{-- Brand --}}
        <div class="brand">
            <span class="brand-title">INSCRIPTION</span>
            <p class="brand-sub">Demande d'accès au système — YA Consulting</p>
            <div class="brand-divider"></div>
        </div>

        {{-- Form --}}
        <form action="{{ route('register') }}" method="POST" novalidate>
            @csrf

            {{-- Nom complet --}}
            <div class="form-group">
                <label for="name">Nom complet / Contact</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    required autofocus autocomplete="name" placeholder="Ex: Jean Dupont">
                @error('name')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nom entreprise --}}
            <div class="form-group">
                <label for="company_name">Nom de l'entreprise</label>
                <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                    required placeholder="Ex: Entreprise ABC">
                @error('company_name')
                    <p class="error-msg">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email entreprise + Email professionnel --}}
            <div class="form-grid">
                <div>
                    <label for="company_email">Email de l'entreprise</label>
                    <input type="email" name="company_email" id="company_email" value="{{ old('company_email') }}"
                        required placeholder="contact@entreprise.com">
                    @error('company_email')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email">Email professionnel</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        required placeholder="nom@entreprise.com">
                    @error('email')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Téléphone + Adresse --}}
            <div class="form-grid">
                <div>
                    <label for="telephone">Téléphone</label>
                    <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}"
                        placeholder="0601020304">
                    @error('telephone')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="adresse">Adresse</label>
                    <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}"
                        placeholder="123 rue de l'exemple">
                    @error('adresse')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Mot de passe + Confirmation --}}
            <div class="form-grid">
                <div>
                    <label for="password">Clé d'accès</label>
                    <input type="password" name="password" id="password"
                        required autocomplete="new-password" placeholder="••••••••">
                </div>
                <div>
                    <label for="password_confirmation">Confirmation</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        required autocomplete="new-password" placeholder="••••••••">
                </div>
                @error('password')
                    <p class="error-msg" style="grid-column: span 2;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Motif --}}
            <div class="form-group">
                <label for="reason">Motif de la demande d'accès</label>
                <textarea name="reason" id="reason" rows="2"
                    placeholder="Décrivez votre besoin d'accéder aux archives...">{{ old('reason') }}</textarea>
            </div>

            <button type="submit" class="btn-submit" id="registerSubmitBtn">
                <span class="btn-spinner"></span>
                <span class="btn-label">Envoyer la demande d'accès</span>
                <span class="btn-arrow">→</span>
            </button>
        </form>

        <div class="card-footer">
            Déjà autorisé ? <a href="{{ route('login') }}">Connectez-vous</a>
            <br><br>
            Système sécurisé<span></span>YA Consulting
        </div>

    </div>

    {{-- Success Modal --}}
    @if (session('success'))
        <div class="modal-overlay active" id="successModal">
            <div class="modal-content">
                <div class="modal-icon-container">
                    <i class="bx bx-check-circle modal-icon"></i>
                </div>
                <h3 class="modal-title">Demande d'accès envoyée</h3>
                <p class="modal-text">{{ session('success') }}</p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <a href="{{ route('login') }}" class="modal-btn">
                        <i class="bx bx-log-in"></i> Retour à la connexion
                    </a>
                    <button type="button" class="modal-btn-secondary" onclick="closeModal()">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script>
        function closeModal() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.classList.remove('active');
            }
        }

        // Close modal when clicking outside the content area
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal();
                    }
                });
            }

            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    if (!form.checkValidity()) return;
                    const btn = document.getElementById('registerSubmitBtn');
                    const label = btn.querySelector('.btn-label');
                    btn.classList.add('loading');
                    btn.disabled = true;
                    label.textContent = 'Envoi en cours…';
                });
            }
        });
    </script>

</body>

</html>