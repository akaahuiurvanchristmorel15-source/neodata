@extends('layouts.app')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Sora:wght@300;400;600;700&display=swap');

.ai-page {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
    background: #ffdcdc31;
    position: relative;
    overflow: hidden;
}

.ai-grid-bg {
    position: fixed;
    inset: 0;
    background-image:
        linear-gradient(rgba(255,26,64,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,26,64,0.06) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
    z-index: 0;
}

.ai-orb {
    position: fixed;
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
}
.ai-orb-1 { width: 400px; height: 400px; top: -100px; right: -80px; background: radial-gradient(circle, rgba(255,26,64,0.18) 0%, transparent 65%); }
.ai-orb-2 { width: 300px; height: 300px; bottom: -80px; left: -60px; background: radial-gradient(circle, rgba(255,26,64,0.12) 0%, transparent 65%); }

.ai-card {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 440px;
    background: rgb(255, 255, 255);
    border: 0.5px solid rgba(255,26,64,0.25);
    border-radius: 20px;
    padding: 36px 32px 32px;
    overflow: hidden;
}

.ai-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,26,64,0.6), transparent);
}

/* Logo */
.ai-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 28px; }
.ai-logo-icon {
    width: 40px; height: 40px;
    border-radius: 10px;
    border: 0.5px solid rgba(255,26,64,0.4);
    background: rgba(255,26,64,0.1);
    display: flex; align-items: center; justify-content: center;
}
.ai-logo-icon svg { width: 20px; height: 20px; }
.ai-logo-text {
    font-family: 'Space Mono', monospace;
    font-size: 12px;
    color: rgba(0, 0, 0, 0.68);
    letter-spacing: .08em;
    text-transform: uppercase;
}

/* Scan line */
.ai-scan {
    width: 100%; height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255,26,64,0.4), transparent);
    margin-bottom: 28px;
    position: relative; overflow: hidden;
}
.ai-scan::after {
    content: '';
    position: absolute; top: 0; left: -100%;
    width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, #ff1a40, transparent);
    animation: ai-scan 2.5s linear infinite;
}
@keyframes ai-scan { to { left: 160%; } }

/* Typography */
.ai-heading {
    font-family: 'Sora', sans-serif;
    font-size: 26px; font-weight: 700;
    color: #000000; line-height: 1.2;
    margin: 0 0 6px;
}
.ai-heading span { color: #ff1a40; }
.ai-subtitle {
    font-family: 'Sora', sans-serif;
    font-size: 13px; font-weight: 300;
    color: rgba(255,255,255,0.4);
    margin: 0 0 28px;
}
.ai-label {
    font-family: 'Space Mono', monospace;
    font-size: 10px; letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(255,26,64,0.8);
    display: block; margin-bottom: 8px;
}

/* Input */
.ai-field-wrap { position: relative; margin-bottom: 16px; }
.ai-field-icon {
    position: absolute; left: 14px; top: 50%;
    transform: translateY(-50%);
    color: rgba(255, 26, 64, 0.71);
    font-size: 16px; pointer-events: none;
}
.ai-input {
    width: 100%;
    padding: 13px 14px 13px 42px;
    background: rgba(255,255,255,0.05);
    border: 0.5px solid rgba(255, 50, 50, 0.87);
    border-radius: 10px;
    font-family: 'Sora', sans-serif;
    font-size: 14px;
    color: rgba(0, 0, 0, 0.9);
    outline: none;
    box-sizing: border-box;
    transition: border-color .2s;
    caret-color: #ff1a40;
}
.ai-input::placeholder { color: rgba(255, 31, 31, 0.48); font-size: 13px; }
.ai-input:focus { border-color: rgba(255,26,64,0.5); background: rgba(255,255,255,0.07); }
.ai-input.error { border-color: rgba(255,26,64,0.7); }
.ai-error { color: rgba(255,26,64,0.9); font-size: 12px; margin-top: 4px; font-family: 'Sora', sans-serif; }

/* Hint */
.ai-hint {
    font-family: 'Sora', sans-serif;
    font-size: 12px;
    color: rgba(0, 0, 0, 0.51);
    text-align: center;
    display: flex; align-items: center;
    justify-content: center; gap: 6px;
    margin-bottom: 24px;
}

/* Buttons */
.ai-btn-primary {
    width: 100%;
    padding: 14px;
    background: #ff1a40;
    border: none; border-radius: 10px;
    font-family: 'Sora', sans-serif;
    font-size: 14px; font-weight: 600;
    color: #fff; cursor: pointer;
    display: flex; align-items: center;
    justify-content: center; gap: 8px;
    margin-bottom: 10px;
    position: relative; overflow: hidden;
    transition: opacity .2s;
    letter-spacing: .02em;
}
.ai-btn-primary::after {
    content: '';
    position: absolute; top: 0; left: -100%;
    width: 50%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
    animation: ai-shimmer 2.5s infinite;
}
@keyframes ai-shimmer { to { left: 180%; } }
.ai-btn-primary:hover { opacity: .88; }

.ai-btn-secondary {
    width: 100%;
    padding: 13px;
    background: transparent;
    border: 0.5px solid rgba(0, 0, 0, 0.28);
    border-radius: 10px;
    font-family: 'Sora', sans-serif;
    font-size: 13px; font-weight: 400;
    color: rgba(0, 0, 0, 0.4);
    cursor: pointer;
    display: flex; align-items: center;
    justify-content: center; gap: 8px;
    transition: border-color .2s, color .2s;
    text-decoration: none;
}
.ai-btn-secondary:hover { border-color: rgba(255, 0, 0, 0.99); color: rgba(255, 0, 0, 0.7); }

/* Divider */
.ai-divider { height: 0.5px; background: rgba(0, 0, 0, 0.19); margin: 28px 0 20px; }

/* Steps */
.ai-steps-title {
    font-family: 'Space Mono', monospace;
    font-size: 10px; letter-spacing: .12em;
    text-transform: uppercase;
    color: rgba(0, 0, 0, 0.59);
    margin-bottom: 14px;
}
.ai-steps { display: flex; flex-direction: column; gap: 10px; }
.ai-step { display: flex; align-items: flex-start; gap: 12px; }
.ai-step-num {
    width: 22px; height: 22px;
    border-radius: 6px;
    background: rgba(255,26,64,0.1);
    border: 0.5px solid rgba(255,26,64,0.3);
    font-family: 'Space Mono', monospace;
    font-size: 10px; font-weight: 700;
    color: #ff1a40;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px;
}
.ai-step-text {
    font-family: 'Sora', sans-serif;
    font-size: 12px;
    color: rgb(0, 0, 0);
    line-height: 1.5; padding-top: 3px;
}

/* Footer */
.ai-footer {
    font-family: 'Space Mono', monospace;
    font-size: 10px;
    color: rgba(255,255,255,0.18);
    text-align: center;
    margin-top: 20px;
    letter-spacing: .06em;
    text-transform: uppercase;
    position: relative; z-index: 1;
}

/* Flash */
.ai-flash-success {
    background: rgba(22,163,74,0.12);
    border: 0.5px solid rgba(22,163,74,0.3);
    color: rgba(134,239,172,0.9);
    border-radius: 8px;
    padding: 10px 14px;
    font-family: 'Sora', sans-serif;
    font-size: 13px;
    margin-bottom: 16px;
}
.ai-flash-error {
    background: rgba(255,26,64,0.1);
    border: 0.5px solid rgba(255,26,64,0.3);
    color: rgba(255,150,165,0.9);
    border-radius: 8px;
    padding: 10px 14px;
    font-family: 'Sora', sans-serif;
    font-size: 13px;
    margin-bottom: 16px;
}

/* Mobile */
@media(max-width: 480px) {
    .ai-card { padding: 28px 20px 24px; }
    .ai-heading { font-size: 22px; }
}
</style>

<div class="ai-page">
    <div class="ai-grid-bg"></div>
    <div class="ai-orb ai-orb-1"></div>
    <div class="ai-orb ai-orb-2"></div>

    <div style="position:relative;z-index:1;width:100%;max-width:440px">
        <div class="ai-card">
            {{-- Logo --}}
            <div class="ai-logo">
                <div class="ai-logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#ff1a40" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <span class="ai-logo-text">NEODATA - Support IA</span>
            </div>

            <div class="ai-scan"></div>

            <h1 class="ai-heading">Réinitialisez<br>votre <span>mot de passe</span></h1>
            <p class="ai-subtitle">Vérification sécurisée via IA en 4 étapes</p>

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="ai-flash-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="ai-flash-error">{{ session('error') }}</div>
            @endif

            <form action="{{ route('password-reset.validate-email') }}" method="POST">
                @csrf

                <label class="ai-label" for="email">Adresse email</label>
                <div class="ai-field-wrap">
                    <i class="bx bx-envelope ai-field-icon" aria-hidden="true"></i>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        placeholder="votre.email@entreprise.com"
                        value="{{ old('email') }}"
                        class="ai-input @error('email') error @enderror"
                        required
                    >
                    @error('email')
                        <p class="ai-error">{{ $message }}</p>
                    @enderror
                </div>

                <p class="ai-hint">
                    <i class="bx bx-shield" style="font-size:14px;color:rgba(255,26,64,0.5)" aria-hidden="true"></i>
                    Email associé à votre compte NEODATA
                </p>

                <button type="submit" class="ai-btn-primary">
                    <i class="bx bx-right-arrow-alt" aria-hidden="true"></i>
                    Continuer
                </button>
            </form>

            <a href="{{ route('login') }}" class="ai-btn-secondary">
                <i class="bx bx-arrow-back" aria-hidden="true"></i>
                Retour à la connexion
            </a>

            <div class="ai-divider"></div>

            <p class="ai-steps-title">Comment ça fonctionne</p>
            <div class="ai-steps">
                <div class="ai-step">
                    <div class="ai-step-num">01</div>
                    <div class="ai-step-text">Entrez votre adresse email</div>
                </div>
                <div class="ai-step">
                    <div class="ai-step-num">02</div>
                    <div class="ai-step-text">Répondez à 3 questions de vérification</div>
                </div>
                <div class="ai-step">
                    <div class="ai-step-num">03</div>
                    <div class="ai-step-text">Créez un nouveau mot de passe sécurisé</div>
                </div>
                <div class="ai-step">
                    <div class="ai-step-num">04</div>
                    <div class="ai-step-text">Redirection automatique vers la connexion</div>
                </div>
            </div>
        </div>

        <p class="ai-footer">Données chiffrées et sécurisées — NEODATA © {{ date('Y') }}</p>
    </div>
</div>
@endsection