<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-[#ff1a40] tracking-tight neodata-title">
                    Sécurité Système
                </h1>
                <p class="text-xs text-black mt-1 font-light">
                    Modification d'un accès existant — NEODATA.
                </p>
            </div>
            <a href="{{ route('users.index') }}"
                class="text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500 hover:text-[#ff1a40] flex items-center gap-1.5 transition-colors self-start sm:self-auto">
                ← Retour au registre
            </a>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;600;700;800&display=swap');

        :root {
            --red-bright: #ff1a40;
            --border: rgba(179, 179, 179, 0.73);
        }

        /* ── Ambient glow ── */
        .dash-wrap {
            position: relative;
            min-height: 100%;
        }

        .dash-wrap::before {
            content: '';
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(255, 26, 64, 0.08) 0%, transparent 65%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .dash-wrap::after {
            content: '';
            position: fixed;
            bottom: 10%;
            right: 5%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(255, 0, 43, 0.06) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .dash-content {
            position: relative;
            z-index: 1;
        }

        /* ── Panel ── */
        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
            backdrop-filter: blur(24px);
            transition: border-color 0.4s;
            animation: fadeSlideUp 0.5s ease both;
            position: relative;
            overflow: hidden;
        }

        .panel:hover {
            border-color: rgba(255, 26, 64, 0.2);
        }

        .panel::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: -1px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 26, 64, 0.4), transparent);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .panel:hover::after {
            opacity: 1;
        }

        .panel-title {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #000;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .panel-title::before {
            content: '';
            display: inline-block;
            width: 3px;
            height: 14px;
            background: var(--red-bright);
            border-radius: 2px;
            box-shadow: 0 0 8px var(--red-bright);
        }

        .panel-sub {
            font-size: 13px;
            color: rgba(49, 49, 49, 1);
            font-weight: 300;
            margin-top: 4px;
            padding-left: 13px;
        }

        /* ── Field label ── */
        .field-label {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(0, 0, 0, 0.45);
            margin-bottom: 7px;
        }

        /* ── Inputs ── */
        .neo-input,
        .neo-select {
            width: 100%;
            background: #fafafa;
            border: 1px solid rgba(179, 179, 179, 0.8);
            border-radius: 13px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #111;
            padding: 10px 16px;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }

        .neo-input::placeholder {
            color: rgba(0, 0, 0, 0.28);
            font-weight: 300;
        }

        .neo-input:focus,
        .neo-select:focus {
            border-color: var(--red-bright);
            box-shadow: 0 0 0 3px rgba(255, 26, 64, 0.08);
            background: #fff;
        }

        .neo-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23ff1a40' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-color: #fafafa;
            padding-right: 36px;
        }

        /* ── Password toggle wrapper ── */
        .input-wrapper {
            position: relative;
        }

        .input-wrapper .neo-input {
            padding-right: 42px;
        }

        .pw-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            color: rgba(0, 0, 0, 0.3);
            transition: color 0.2s;
            line-height: 1;
        }

        .pw-toggle:hover {
            color: var(--red-bright);
        }

        /* ── Field error ── */
        .field-error {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 600;
            color: #ff1a40;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* ── Divider ── */
        .form-divider {
            border: none;
            border-top: 1px solid rgba(179, 179, 179, 0.4);
            margin: 4px 0;
        }

        /* ── Buttons ── */
        .btn-cancel {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(0, 0, 0, 0.4);
            text-decoration: none;
            transition: color 0.2s;
        }

        .btn-cancel:hover {
            color: #000;
        }

        /* ── Submit + Spinner ── */
        .btn-submit {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            padding: 11px 28px;
            border-radius: 12px;
            background: linear-gradient(135deg, #ff1a40, #91041b);
            color: #fff;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 18px rgba(255, 26, 64, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover:not(:disabled) {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(255, 26, 64, 0.45);
        }

        .btn-submit:disabled {
            opacity: 0.72;
            cursor: not-allowed;
            transform: none;
        }

        /* Spinner SVG */
        .btn-spinner {
            width: 14px;
            height: 14px;
            display: none;
            animation: spin 0.75s linear infinite;
            flex-shrink: 0;
        }

        .btn-icon {
            display: flex;
            align-items: center;
        }

        .btn-submit.loading .btn-icon {
            display: none;
        }

        .btn-submit.loading .btn-spinner {
            display: block;
        }

        .btn-submit.loading .btn-label {
            opacity: 0.85;
        }

        .btn-submit.loading::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.12), transparent);
            animation: shimmer 1.2s ease infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="dash-wrap">
        <div class="dash-content max-w-2xl mx-auto">
            <div class="panel">

                {{-- Header --}}
                <div class="mb-7 pb-5 border-b border-[rgba(179,179,179,0.35)]">
                    <p class="panel-title">Modifier l'Accès Collaborateur</p>
                    <p class="panel-sub">Ajustez l'identité, le rôle et les informations de sécurité de <strong>{{ $user->name }}</strong>.</p>
                </div>

                <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-5" id="editUserForm">
                    @csrf
                    @method('PUT')

                    {{-- Nom --}}
                    <div>
                        <label class="field-label">
                            Nom Complet <span style="color:#ff1a40">*</span>
                        </label>
                        <input type="text" name="name" required value="{{ old('name', $user->name) }}" placeholder="Ex : Jean Dupont"
                            class="neo-input">
                        @error('name')
                            <p class="field-error"><span>▸</span> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="field-label">
                            Adresse Email <span style="color:#ff1a40">*</span>
                        </label>
                        <input type="email" name="email" required value="{{ old('email', $user->email) }}"
                            placeholder="collaborateur@entreprise.com" class="neo-input">
                        @error('email')
                            <p class="field-error"><span>▸</span> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Rôle --}}
                    <div>
                        <label class="field-label">
                            Rôle Système <span style="color:#ff1a40">*</span>
                        </label>
                        <select name="role" required class="neo-select">
                            <option value="employe" {{ old('role', $user->role) === 'employe' ? 'selected' : '' }}>👥 Employé</option>
                            <option value="direction" {{ old('role', $user->role) === 'direction' ? 'selected' : '' }}>💼 Direction</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>🛡 Administrateur</option>
                        </select>
                        @error('role')
                            <p class="field-error"><span>▸</span> {{ $message }}</p>
                        @enderror
                    </div>

                    <div style="padding-top: 10px; margin-bottom: 5px;">
                        <span class="field-label" style="margin-bottom: 2px;">Sécurité Mot de Passe</span>
                        <p style="font-size: 11px; color: rgba(0, 0, 0, 0.45); font-family: 'Outfit', sans-serif;">Laissez ces champs vides si vous ne souhaitez pas modifier le mot de passe actuel.</p>
                    </div>

                    {{-- Mot de passe --}}
                    <div>
                        <label class="field-label">
                            Nouveau mot de passe
                        </label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="passwordField"
                                placeholder="•••••••••••• (Laisser vide pour conserver)" class="neo-input">
                            <button type="button" class="pw-toggle"
                                onclick="toggleField('passwordField', 'eyeOpen', 'eyeOff')"
                                aria-label="Afficher / masquer le mot de passe">
                                <svg id="eyeOpen" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" style="display:block;">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg id="eyeOff" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" style="display:none;">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="field-error"><span>▸</span> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirmer le mot de passe --}}
                    <div>
                        <label class="field-label">
                            Confirmer le nouveau mot de passe
                        </label>
                        <div class="input-wrapper">
                            <input type="password" name="password_confirmation" id="passwordConfirmField"
                                placeholder="••••••••••••" class="neo-input">
                            <button type="button" class="pw-toggle"
                                onclick="toggleField('passwordConfirmField', 'eyeOpenConfirm', 'eyeOffConfirm')"
                                aria-label="Afficher / masquer le mot de passe">
                                <svg id="eyeOpenConfirm" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" style="display:block;">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg id="eyeOffConfirm" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" style="display:none;">
                                    <path
                                        d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24" />
                                    <line x1="1" y1="1" x2="23" y2="23" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="field-error"><span>▸</span> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div>
                        <hr class="form-divider">
                        <div class="flex items-center justify-end gap-6 pt-4">
                            <a href="{{ route('users.index') }}" class="btn-cancel">Annuler</a>
                            <button type="submit" class="btn-submit" id="editSubmitBtn">
                                <span class="btn-icon">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                                        <polyline points="17 21 17 13 7 13 7 21" />
                                        <polyline points="7 3 7 8 15 8" />
                                    </svg>
                                </span>
                                <svg class="btn-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10" stroke-opacity="0.25" />
                                    <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round" />
                                </svg>
                                <span class="btn-label" id="editBtnLabel">Enregistrer les modifications</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // ── Formulaire : Chargement à la soumission ──
        const form = document.getElementById('editUserForm');
        const btn = document.getElementById('editSubmitBtn');
        const label = document.getElementById('editBtnLabel');

        if (form) {
            form.addEventListener('submit', function () {
                if (!form.checkValidity()) return;
                btn.classList.add('loading');
                btn.disabled = true;
                label.textContent = 'Mise à jour en cours…';

                setTimeout(() => {
                    btn.classList.remove('loading');
                    btn.disabled = false;
                    label.textContent = 'Enregistrer les modifications';
                }, 15000);
            });
        }

        // ── Visibilité Mot de passe (Dynamique pour les deux champs) ──
        function toggleField(fieldId, eyeOpenId, eyeOffId) {
            const field = document.getElementById(fieldId);
            const eyeOpen = document.getElementById(eyeOpenId);
            const eyeOff = document.getElementById(eyeOffId);
            const isHidden = field.type === 'password';

            field.type = isHidden ? 'text' : 'password';
            eyeOpen.style.display = isHidden ? 'none' : 'block';
            eyeOff.style.display = isHidden ? 'block' : 'none';
        }
    </script>
</x-app-layout>
