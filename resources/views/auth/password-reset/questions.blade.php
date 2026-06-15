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
    background: #ffdcdc18;
    position: relative;
    overflow: hidden;
}
.ai-grid-bg {
    position: fixed; inset: 0;
    background-image:
        linear-gradient(rgba(255,26,64,0.06) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,26,64,0.06) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none; z-index: 0;
}
.ai-orb { position: fixed; border-radius: 50%; pointer-events: none; z-index: 0; }
.ai-orb-1 { width: 400px; height: 400px; top: -100px; right: -80px; background: radial-gradient(circle, rgba(255,26,64,0.14) 0%, transparent 65%); }
.ai-orb-2 { width: 280px; height: 280px; bottom: -60px; left: -50px; background: radial-gradient(circle, rgba(255,26,64,0.09) 0%, transparent 65%); }

.ai-card {
    position: relative; z-index: 1;
    width: 100%; max-width: 560px;
    background: #fff;
    border: 0.5px solid rgba(255,26,64,0.22);
    border-radius: 20px;
    padding: 36px 32px 32px;
    overflow: hidden;
}
.ai-card::before {
    content: ''; position: absolute;
    top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,26,64,0.6), transparent);
}

/* Logo */
.ai-logo { display: flex; align-items: center; gap: 10px; margin-bottom: 24px; }
.ai-logo-icon {
    width: 38px; height: 38px; border-radius: 10px;
    border: 0.5px solid rgba(255,26,64,0.4);
    background: rgba(255,26,64,0.08);
    display: flex; align-items: center; justify-content: center;
}
.ai-logo-icon svg { width: 18px; height: 18px; }
.ai-logo-text {
    font-family: 'Space Mono', monospace; font-size: 11px;
    color: rgba(0,0,0,0.55); letter-spacing: .08em; text-transform: uppercase;
}

/* Scan line */
.ai-scan {
    width: 100%; height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255,26,64,0.4), transparent);
    margin-bottom: 24px; position: relative; overflow: hidden;
}
.ai-scan::after {
    content: ''; position: absolute; top: 0; left: -100%;
    width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, #ff1a40, transparent);
    animation: ai-scan 2.5s linear infinite;
}
@keyframes ai-scan { to { left: 160%; } }

/* Typography */
.ai-heading { font-family: 'Sora', sans-serif; font-size: 22px; font-weight: 700; color: #000; line-height: 1.2; margin: 0 0 6px; }
.ai-heading span { color: #ff1a40; }
.ai-subtitle { font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 300; color: rgba(0,0,0,0.5); margin: 0 0 22px; line-height: 1.5; }

/* Progress */
.ai-progress-wrap { margin-bottom: 24px; }
.ai-progress-label { font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: .1em; text-transform: uppercase; color: rgba(255,26,64,0.8); margin-bottom: 8px; }
.ai-progress-bar { height: 4px; background: rgba(255,26,64,0.12); border-radius: 99px; overflow: hidden; }
.ai-progress-fill { height: 100%; width: 66%; background: #ff1a40; border-radius: 99px; position: relative; overflow: hidden; }
.ai-progress-fill::after {
    content: ''; position: absolute; top: 0; left: -100%; width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    animation: ai-shimmer 2s infinite;
}
@keyframes ai-shimmer { to { left: 160%; } }

/* Question block */
.ai-question-block {
    border: 0.5px solid rgba(255,26,64,0.2); border-radius: 12px;
    padding: 18px 20px; margin-bottom: 14px;
    background: rgba(255,26,64,0.025);
    position: relative; overflow: hidden;
}
.ai-question-block::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0;
    width: 3px; background: #ff1a40; border-radius: 3px 0 0 3px;
}
.ai-q-header { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; }
.ai-q-num {
    width: 26px; height: 26px; border-radius: 8px;
    background: #ff1a40; font-family: 'Space Mono', monospace;
    font-size: 11px; font-weight: 700; color: #fff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; margin-top: 1px;
}
.ai-q-label { font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 600; color: #000; line-height: 1.4; }
.ai-label { font-family: 'Space Mono', monospace; font-size: 10px; letter-spacing: .12em; text-transform: uppercase; color: rgba(255,26,64,0.8); display: block; margin-bottom: 8px; }
.ai-input {
    width: 100%; padding: 12px 14px;
    background: rgba(255,255,255,0.8);
    border: 0.5px solid rgba(255,50,50,0.35); border-radius: 10px;
    font-family: 'Sora', sans-serif; font-size: 14px; color: rgba(0,0,0,0.9);
    outline: none; box-sizing: border-box; transition: border-color .2s; caret-color: #ff1a40;
}
.ai-input::placeholder { color: rgba(255,31,31,0.35); font-size: 13px; }
.ai-input:focus { border-color: rgba(255,26,64,0.6); background: #fff; }
.ai-input.error { border-color: rgba(255,26,64,0.7); }
.ai-error { color: rgba(255,26,64,0.9); font-size: 12px; margin-top: 4px; font-family: 'Sora', sans-serif; }
.ai-tip { font-family: 'Sora', sans-serif; font-size: 11px; color: rgba(0,0,0,0.4); margin-top: 8px; display: flex; align-items: center; gap: 5px; }

/* Info box */
.ai-info-box {
    border: 0.5px solid rgba(255,26,64,0.2); border-radius: 10px;
    padding: 12px 16px; background: rgba(255,26,64,0.04);
    margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px;
}
.ai-info-box-icon { color: #ff1a40; font-size: 16px; flex-shrink: 0; margin-top: 1px; }
.ai-info-box-text { font-family: 'Sora', sans-serif; font-size: 12px; color: rgba(0,0,0,0.6); line-height: 1.5; }
.ai-info-box-text strong { color: #ff1a40; font-weight: 600; }

/* Buttons */
.ai-btn-primary {
    width: 100%; padding: 14px; background: #ff1a40;
    border: none; border-radius: 10px;
    font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 600;
    color: #fff; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    margin-bottom: 10px; position: relative; overflow: hidden;
    transition: opacity .2s; letter-spacing: .02em;
}
.ai-btn-primary::after {
    content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.14), transparent);
    animation: ai-shimmer 2.5s infinite;
}
.ai-btn-primary:hover { opacity: .88; }
.ai-btn-secondary {
    width: 100%; padding: 13px; background: transparent;
    border: 0.5px solid rgba(0,0,0,0.2); border-radius: 10px;
    font-family: 'Sora', sans-serif; font-size: 13px; font-weight: 400;
    color: rgba(0,0,0,0.45); cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    transition: border-color .2s, color .2s; text-decoration: none;
}
.ai-btn-secondary:hover { border-color: rgba(255,26,64,0.7); color: rgba(255,26,64,0.8); }

.ai-divider { height: 0.5px; background: rgba(0,0,0,0.1); margin: 24px 0 16px; }
.ai-footer-note { font-family: 'Sora', sans-serif; font-size: 12px; color: rgba(0,0,0,0.4); text-align: center; line-height: 1.6; }
.ai-footer-note strong { color: #ff1a40; font-weight: 600; }
.outer-footer { font-family: 'Space Mono', monospace; font-size: 10px; color: rgba(0,0,0,0.25); text-align: center; margin-top: 16px; letter-spacing: .06em; text-transform: uppercase; position: relative; z-index: 1; }

@media(max-width: 480px) {
    .ai-card { padding: 24px 16px 24px; }
    .ai-heading { font-size: 19px; }
    .ai-q-label { font-size: 13px; }
}
</style>

<div class="ai-page">
    <div class="ai-grid-bg"></div>
    <div class="ai-orb ai-orb-1"></div>
    <div class="ai-orb ai-orb-2"></div>

    <div style="position:relative;z-index:1;width:100%;max-width:560px">
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

            <h1 class="ai-heading">Vérification de<br>votre <span>identité</span></h1>
            <p class="ai-subtitle">Répondez aux 3 questions suivantes pour confirmer votre association à votre entreprise</p>

            <div class="ai-progress-wrap">
                <div class="ai-progress-label">Étape 2 sur 3</div>
                <div class="ai-progress-bar"><div class="ai-progress-fill"></div></div>
            </div>

            <x-flash-messages />

            <form action="{{ route('password-reset.verify-answers') }}" method="POST">
                @csrf

                @foreach($questions as $question)
                    <div class="ai-question-block">
                        <div class="ai-q-header">
                            <div class="ai-q-num">{{ str_pad($question['id'], 2, '0', STR_PAD_LEFT) }}</div>
                            <label for="answer{{ $question['id'] }}" class="ai-q-label">
                                {{ $question['question'] }}
                            </label>
                        </div>
                        <span class="ai-label">Réponse</span>
                        <input
                            type="text"
                            name="answer{{ $question['id'] }}"
                            id="answer{{ $question['id'] }}"
                            placeholder="{{ $question['placeholder'] }}"
                            class="ai-input @error('answer'.$question['id']) error @enderror"
                            required
                        >
                        @error('answer'.$question['id'])
                            <p class="ai-error">{{ $message }}</p>
                        @enderror
                        <p class="ai-tip">
                            <i class="bx bx-bulb" style="color:#ff1a40;font-size:13px" aria-hidden="true"></i>
                            Les réponses ne sont pas sensibles à la casse
                        </p>
                    </div>
                @endforeach

                <div class="ai-info-box">
                    <i class="bx bx-info-circle ai-info-box-icon" aria-hidden="true"></i>
                    <p class="ai-info-box-text">
                        <strong>Important :</strong> Les réponses doivent correspondre exactement aux informations enregistrées de votre entreprise.
                    </p>
                </div>

                <button type="submit" class="ai-btn-primary">
                    <i class="bx bx-check" aria-hidden="true"></i>
                    Vérifier les réponses
                </button>
            </form>

            <a href="{{ route('password-reset.cancel') }}" class="ai-btn-secondary">
                <i class="bx bx-x" aria-hidden="true"></i>
                Annuler
            </a>

            <div class="ai-divider"></div>
            <div class="ai-footer-note">
                <p>Vous ne connaissez pas les réponses ?</p>
                <p style="margin-top:4px">Contactez votre <strong>administrateur système</strong> pour obtenir ces informations.</p>
            </div>
        </div>
        <p class="outer-footer">Données chiffrées et sécurisées — NEODATA © {{ date('Y') }}</p>
    </div>
</div>
@endsection