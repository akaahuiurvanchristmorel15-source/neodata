@extends('layouts.app')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Sora:wght@300;400;600;700&display=swap');

.ai-page {
    min-height: 100vh; display: flex; align-items: center;
    justify-content: center; padding: 2rem 1rem;
    background: #ffdcdc18; position: relative; overflow: hidden;
}
.ai-grid-bg {
    position: fixed; inset: 0;
    background-image: linear-gradient(rgba(255,26,64,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,26,64,0.06) 1px, transparent 1px);
    background-size: 32px 32px; pointer-events: none; z-index: 0;
}
.ai-orb { position: fixed; border-radius: 50%; pointer-events: none; z-index: 0; }
.ai-orb-1 { width: 400px; height: 400px; top: -100px; right: -80px; background: radial-gradient(circle, rgba(255,26,64,0.14) 0%, transparent 65%); }
.ai-orb-2 { width: 280px; height: 280px; bottom: -60px; left: -50px; background: radial-gradient(circle, rgba(255,26,64,0.09) 0%, transparent 65%); }

.ai-card {
    position: relative; z-index: 1; width: 100%; max-width: 440px;
    background: #fff; border: 0.5px solid rgba(255,26,64,0.22);
    border-radius: 20px; padding: 36px 32px 32px; overflow: hidden;
}
.ai-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,26,64,0.6), transparent);
}

/* Logo */
.ai-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; }
.ai-logo-icon { width: 38px; height: 38px; border-radius: 10px; border: 0.5px solid rgba(255,26,64,0.4); background: rgba(255,26,64,0.08); display: flex; align-items: center; justify-content: center; }
.ai-logo-icon svg { width: 18px; height: 18px; }
.ai-logo-text { font-family: 'Space Mono', monospace; font-size: 11px; color: rgba(0,0,0,0.55); letter-spacing: .08em; text-transform: uppercase; }

/* Scan */
.ai-scan { width: 100%; height: 2px; background: linear-gradient(90deg, transparent, rgba(255,26,64,0.4), transparent); margin-bottom: 24px; position: relative; overflow: hidden; }
.ai-scan::after { content: ''; position: absolute; top: 0; left: -100%; width: 60%; height: 100%; background: linear-gradient(90deg, transparent, #ff1a40, transparent); animation: ai-scan 2.5s linear infinite; }
@keyframes ai-scan { to { left: 160%; } }

/* Typography */
.ai-heading { font-family: 'Sora', sans-serif; font-size: 22px; font-weight: 700; color: #000; line-height: 1.2; margin: 0 0 6px; }
.ai-heading span { color: #ff1a40; }
.ai-subtitle { font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 300; color: rgba(0,0,0,0.5); margin: 0 0 22px; line-height: 1.5; }

/* Progress */
.ai-progress-wrap { margin-bottom: 22px; }
.ai-progress-label { font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,26,64,0.8); margin-bottom: 8px; }
.ai-progress-bar { height: 4px; background: rgba(255,26,64,0.12); border-radius: 99px; overflow: hidden; }
.ai-progress-fill { height: 100%; width: 100%; background: #ff1a40; border-radius: 99px; position: relative; overflow: hidden; }
.ai-progress-fill::after { content: ''; position: absolute; top: 0; left: -100%; width: 60%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent); animation: ai-shimmer 2s infinite; }
@keyframes ai-shimmer { to { left: 160%; } }

/* Email box */
.ai-email-box { border: 0.5px solid rgba(255,26,64,0.2); border-radius: 10px; padding: 12px 16px; background: rgba(255,26,64,0.025); margin-bottom: 18px; display: flex; align-items: center; gap: 10px; }
.ai-email-icon { color: #ff1a40; font-size: 16px; flex-shrink: 0; }
.ai-email-label { font-family: 'Space Mono', monospace; font-size: 9px; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,26,64,0.7); margin-bottom: 3px; }
.ai-email-val { font-family: 'Space Mono', monospace; font-size: 13px; color: rgba(0,0,0,0.8); }

/* Fields */
.ai-field-group { margin-bottom: 16px; }
.ai-label { font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,26,64,0.8); display: block; margin-bottom: 8px; }
.ai-field-wrap { position: relative; }
.ai-field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: rgba(255,26,64,0.5); font-size: 16px; pointer-events: none; }
.ai-input { width: 100%; padding: 12px 42px; background: rgba(255,255,255,0.8); border: 0.5px solid rgba(255,50,50,0.35); border-radius: 10px; font-family: 'Sora', sans-serif; font-size: 14px; color: rgba(0,0,0,0.9); outline: none; box-sizing: border-box; transition: border-color .2s; caret-color: #ff1a40; }
.ai-input::placeholder { color: rgba(255,31,31,0.35); font-size: 13px; }
.ai-input:focus { border-color: rgba(255,26,64,0.6); background: #fff; }
.ai-input.error { border-color: rgba(255,26,64,0.7); }
.ai-error { color: rgba(255,26,64,0.9); font-size: 12px; margin-top: 4px; font-family: 'Sora', sans-serif; }
.ai-toggle { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: rgba(0,0,0,0.3); font-size: 16px; padding: 0; display: flex; align-items: center; }
.ai-toggle:hover { color: rgba(255,26,64,0.7); }
.ai-field-hint { font-family: 'Sora', sans-serif; font-size: 11px; color: rgba(0,0,0,0.4); margin-top: 6px; display: flex; align-items: center; gap: 5px; }
.ai-field-hint i { color: #ff1a40; font-size: 12px; }

/* Security box */
.ai-security-box { border: 0.5px solid rgba(255,26,64,0.2); border-radius: 10px; padding: 14px 16px; background: rgba(255,26,64,0.03); margin-bottom: 20px; }
.ai-security-title { font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,26,64,0.8); margin-bottom: 10px; display: flex; align-items: center; gap: 6px; }
.ai-security-list { display: flex; flex-direction: column; gap: 6px; }
.ai-security-item { display: flex; align-items: center; gap: 8px; font-family: 'Sora', sans-serif; font-size: 12px; color: rgba(0,0,0,0.5); }
.ai-security-item i { color: #ff1a40; font-size: 13px; flex-shrink: 0; }

/* Buttons */
.ai-btn-primary { width: 100%; padding: 14px; background: #ff1a40; border: none; border-radius: 10px; font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 600; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 10px; position: relative; overflow: hidden; transition: opacity .2s; letter-spacing: .02em; }
.ai-btn-primary::after { content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.14), transparent); animation: ai-shimmer 2.5s infinite; }
.ai-btn-primary:hover { opacity: .88; }
.ai-btn-secondary { width: 100%; padding: 13px; background: transparent; border: 0.5px solid rgba(0,0,0,0.2); border-radius: 10px; font-family: 'Sora', sans-serif; font-size: 13px; color: rgba(0,0,0,0.45); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: border-color .2s, color .2s; text-decoration: none; }
.ai-btn-secondary:hover { border-color: rgba(255,26,64,0.7); color: rgba(255,26,64,0.8); }

.ai-divider { height: 0.5px; background: rgba(0,0,0,0.1); margin: 22px 0 14px; }
.ai-final-note { font-family: 'Sora', sans-serif; font-size: 12px; color: rgba(0,0,0,0.4); text-align: center; display: flex; align-items: center; justify-content: center; gap: 6px; }
.outer-footer { font-family: 'Space Mono', monospace; font-size: 10px; color: rgba(0,0,0,0.25); text-align: center; margin-top: 16px; letter-spacing: .06em; text-transform: uppercase; position: relative; z-index: 1; }

@media(max-width: 480px) {
    .ai-card { padding: 24px 16px; }
    .ai-heading { font-size: 19px; }
}
</style>

<div class="ai-page">
    <div class="ai-grid-bg"></div>
    <div class="ai-orb ai-orb-1"></div>
    <div class="ai-orb ai-orb-2"></div>

    <div style="position:relative;z-index:1;width:100%;max-width:440px">
        <div class="ai-card">
            <div class="ai-logo">
                <div class="ai-logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#ff1a40" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                        <path d="M2 17l10 5 10-5"/>
                        <path d="M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <span class="ai-logo-text">NEODATA — Support IA</span>
            </div>

            <div class="ai-scan"></div>

            <h1 class="ai-heading">Nouveau<br><span>mot de passe</span></h1>
            <p class="ai-subtitle">Créez un mot de passe sécurisé pour votre compte</p>

            <div class="ai-progress-wrap">
                <div class="ai-progress-label">Étape 3 sur 3 — Dernière étape</div>
                <div class="ai-progress-bar"><div class="ai-progress-fill"></div></div>
            </div>

            <x-flash-messages />

            <div class="ai-email-box">
                <i class="bx bx-envelope ai-email-icon" aria-hidden="true"></i>
                <div>
                    <div class="ai-email-label">Compte</div>
                    <div class="ai-email-val">{{ $email }}</div>
                </div>
            </div>

            <form action="{{ route('password-reset.update-password') }}" method="POST">
                @csrf

                <div class="ai-field-group">
                    <label class="ai-label" for="password">Nouveau mot de passe</label>
                    <div class="ai-field-wrap">
                        <i class="bx bx-lock ai-field-icon" aria-hidden="true"></i>
                        <input type="password" name="password" id="password"
                            placeholder="Créez un mot de passe fort"
                            class="ai-input @error('password') error @enderror"
                            required>
                        <button type="button" class="ai-toggle" onclick="togglePwd('password', this)" aria-label="Afficher/masquer le mot de passe">
                            <i class="bx bx-show" id="ico-password"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="ai-error">{{ $message }}</p>
                    @enderror
                    <p class="ai-field-hint">
                        <i class="bx bx-check-circle" aria-hidden="true"></i>
                        Minimum 8 caractères
                    </p>
                </div>

                <div class="ai-field-group">
                    <label class="ai-label" for="password_confirmation">Confirmer le mot de passe</label>
                    <div class="ai-field-wrap">
                        <i class="bx bx-lock-open ai-field-icon" aria-hidden="true"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="Confirmez votre mot de passe"
                            class="ai-input @error('password_confirmation') error @enderror"
                            required>
                        <button type="button" class="ai-toggle" onclick="togglePwd('password_confirmation', this)" aria-label="Afficher/masquer la confirmation">
                            <i class="bx bx-show" id="ico-password_confirmation"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="ai-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="ai-security-box">
                    <div class="ai-security-title">
                        <i class="bx bx-shield" aria-hidden="true"></i>
                        Conseils de sécurité
                    </div>
                    <div class="ai-security-list">
                        <div class="ai-security-item"><i class="bx bx-check" aria-hidden="true"></i>Au moins 8 caractères</div>
                        <div class="ai-security-item"><i class="bx bx-check" aria-hidden="true"></i>Majuscules, minuscules et chiffres</div>
                        <div class="ai-security-item"><i class="bx bx-check" aria-hidden="true"></i>Caractères spéciaux (!@#$%)</div>
                        <div class="ai-security-item"><i class="bx bx-check" aria-hidden="true"></i>Évitez les informations personnelles</div>
                    </div>
                </div>

                <button type="submit" class="ai-btn-primary">
                    <i class="bx bx-lock-alt" aria-hidden="true"></i>
                    Réinitialiser le mot de passe
                </button>
            </form>

            <a href="{{ route('password-reset.cancel') }}" class="ai-btn-secondary">
                <i class="bx bx-x" aria-hidden="true"></i>
                Annuler
            </a>

            <div class="ai-divider"></div>
            <p class="ai-final-note">
                <i class="bx bx-check-circle" style="color:#ff1a40;font-size:14px" aria-hidden="true"></i>
                Après réinitialisation, vous serez redirigé vers la connexion
            </p>
        </div>
        <p class="outer-footer">Données chiffrées et sécurisées — NEODATA © {{ date('Y') }}</p>
    </div>
</div>

<script>
function togglePwd(id, btn) {
    const inp = document.getElementById(id);
    const ico = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.className = 'bx bx-hide';
    } else {
        inp.type = 'password';
        ico.className = 'bx bx-show';
    }
}
</script>
@endsection