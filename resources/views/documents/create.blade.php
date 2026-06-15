<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-[#ff1a40] tracking-tight neodata-title">
                    Archiver un nouveau document
                </h1>
                <p class="text-xs text-black mt-1 font-light">
                    Le fichier sera automatiquement fragmenté et chiffré (AES-256) dès son écriture sur le serveur.
                </p>
            </div>
            <a href="{{ route('documents.index') }}"
                class="text-[10px] font-bold uppercase tracking-[0.15em] text-gray-500 hover:text-[#ff1a40] flex items-center gap-1.5 transition-colors">
                <span>←</span> Retour au registre
            </a>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;600;700;800&display=swap');

        :root {
            --red: #91041bff;
            --red-bright: #ff1a40;
            --red-dim: rgba(255, 26, 64, 0.12);
            --red-border: rgba(255, 26, 64, 0.35);
            --bg-card: rgba(255, 255, 255, 1);
            --border: rgba(179, 179, 179, 0.73);
        }

        /* ── Ambient glow ── */
        .dash-wrap { position: relative; min-height: 100%; }

        .dash-wrap::before {
            content: '';
            position: fixed;
            top: 30%; left: 50%;
            transform: translate(-50%, -50%);
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(255, 26, 64, 0.08) 0%, transparent 65%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .dash-wrap::after {
            content: '';
            position: fixed;
            bottom: 10%; right: 5%;
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(255, 0, 43, 0.06) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        .dash-content { position: relative; z-index: 1; }

        /* ── Panel ── */
        .panel {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            transition: border-color 0.4s;
            animation: fadeSlideUp 0.5s ease both;
            position: relative;
            overflow: hidden;
        }

        .panel::after {
            content: '';
            position: absolute;
            left: 0; right: 0; top: -1px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 26, 64, 0.4), transparent);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .panel:hover::after { opacity: 1; }
        .panel:hover { border-color: rgba(255, 26, 64, 0.2); }

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
            width: 3px; height: 14px;
            background: var(--red-bright);
            border-radius: 2px;
            box-shadow: 0 0 8px var(--red-bright);
        }

        .panel-sub {
            font-size: 12px;
            color: rgba(49, 49, 49, 1);
            font-weight: 300;
            margin-top: 4px;
            padding-left: 13px;
        }

        /* ── Form field label ── */
        .field-label {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(0,0,0,0.5);
            margin-bottom: 7px;
        }

        /* ── Inputs ── */
        .neo-input,
        .neo-select,
        .neo-textarea {
            width: 100%;
            background: #fff;
            border: 1px solid rgba(179, 179, 179, 0.8);
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #111;
            padding: 10px 16px;
            transition: border-color 0.25s, box-shadow 0.25s;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }

        .neo-input::placeholder,
        .neo-textarea::placeholder {
            color: rgba(0,0,0,0.3);
            font-weight: 300;
        }

        .neo-input:focus,
        .neo-select:focus,
        .neo-textarea:focus {
            border-color: var(--red-bright);
            box-shadow: 0 0 0 3px rgba(255, 26, 64, 0.08);
        }

        .neo-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23ff1a40' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }

        .neo-textarea {
            resize: none;
            line-height: 1.6;
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

        /* ── Drop zone ── */
        .drop-zone {
            border: 1.5px dashed rgba(179, 179, 179, 0.8);
            border-radius: 16px;
            padding: 36px 24px;
            text-align: center;
            background: rgba(250,250,250,0.6);
            cursor: pointer;
            transition: border-color 0.3s, background 0.3s;
            position: relative;
        }

        .drop-zone:hover,
        .drop-zone.drag-over {
            border-color: var(--red-bright);
            background: rgba(255, 26, 64, 0.03);
        }

        .drop-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .drop-icon {
            width: 40px; height: 40px;
            margin: 0 auto 12px;
            color: rgba(0,0,0,0.2);
            transition: color 0.3s;
        }

        .drop-zone:hover .drop-icon { color: var(--red-bright); }

        .drop-text {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: rgba(0,0,0,0.6);
        }

        .drop-text .accent { color: var(--red-bright); text-decoration: underline; }

        .drop-formats {
            font-family: monospace;
            font-size: 10px;
            color: rgba(0,0,0,0.3);
            margin-top: 6px;
        }

        .drop-filename {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            color: var(--red-bright);
            margin-top: 10px;
            display: none;
        }

        /* ── Security notice ── */
        .security-notice {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            background: rgba(255, 26, 64, 0.04);
            border: 1px solid rgba(255, 26, 64, 0.15);
            border-radius: 14px;
        }

        .security-notice p {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 300;
            color: rgba(0,0,0,0.6);
            line-height: 1.6;
        }

        .security-notice strong { font-weight: 700; color: rgba(0,0,0,0.75); }

        /* ── Divider ── */
        .form-divider {
            border: none;
            border-top: 1px solid rgba(179,179,179,0.4);
            margin: 8px 0;
        }

        /* ── Actions ── */
        .btn-cancel {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(0,0,0,0.4);
            transition: color 0.2s;
            text-decoration: none;
        }

        .btn-cancel:hover { color: #000; }

        .btn-submit {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            padding: 11px 24px;
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
        }

        .btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(255, 26, 64, 0.45);
        }

        /* ── Step counter (decorative) ── */
        .field-group {
            display: grid;
            gap: 20px;
        }

        /* ── Animation ── */
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulse-red {
            0%,100% { box-shadow: 0 0 0 0 rgba(255,26,64,0.4); }
            50%      { box-shadow: 0 0 0 6px rgba(255,26,64,0); }
        }

        .pulse-red { animation: pulse-red 2s ease-in-out infinite; }

        /* ── Submit + Spinner ── */
        .btn-submit {
            position: relative;
            overflow: hidden;
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
    </style>

    <div class="dash-wrap">
        <div class="dash-content max-w-3xl mx-auto">

            <div class="panel">

                {{-- Header --}}
                <div class="mb-7 pb-5 border-b border-[rgba(179,179,179,0.35)]">
                    <p class="panel-title">Métadonnées de l'archive</p>
                    <p class="panel-sub">Renseignez scrupuleusement les index requis pour le moteur de recherche.</p>
                </div>

                <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data"
                    class="field-group" id="archiveForm">
                    @csrf

                    {{-- Titre --}}
                    <div>
                        <label for="titre" class="field-label">Nom / Titre officiel du document <span style="color:#ff1a40">*</span></label>
                        <input type="text" name="titre" id="titre"
                            value="{{ old('titre') }}" required
                            placeholder="Ex : Bilan Comptable Q1 2026"
                            class="neo-input">
                        @error('titre')
                            <p class="field-error"><span>▸</span> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dossier --}}
                    <div>
                        <label for="category_id" class="field-label">Dossier d'affectation <span style="color:#ff1a40">*</span></label>
                        <select name="category_id" id="category_id" required class="neo-select">
                            <option value="">— Sélectionnez un dossier ou sous-dossier —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->parent ? $cat->parent->nom . ' › ' : '' }}{{ $cat->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="field-error"><span>▸</span> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mots-clés --}}
                    <div>
                        <label for="mots_cles" class="field-label">Mots-clés d'indexation <span style="color:rgba(0,0,0,0.25); font-weight:400; text-transform:none; letter-spacing:0; font-size:9px;">(séparés par des virgules)</span></label>
                        <input type="text" name="mots_cles" id="mots_cles"
                            value="{{ old('mots_cles') }}"
                            placeholder="facture, consulting, fiscal, 2026"
                            class="neo-input">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="field-label">Description / Sommaire succinct</label>
                        <textarea name="description" id="description" rows="3"
                            placeholder="Description libre pour aider l'indexation algorithmique du moteur de recherche…"
                            class="neo-textarea">{{ old('description') }}</textarea>
                    </div>

                    {{-- Drop zone fichier --}}
                    <div>
                        <label class="field-label">Fichier source à sécuriser <span style="color:#ff1a40">*</span></label>
                        <div class="drop-zone" id="dropZone">
                            <input type="file" name="fichier" id="fichier" required>
                            <svg class="drop-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="drop-text">
                                Glissez votre document ici, ou <span class="accent">parcourez vos fichiers</span>
                            </p>
                            <p class="drop-formats">PDF · DOCX · XLSX · PNG · JPG — Max 10 Mo</p>
                            <p class="drop-filename" id="dropFilename"></p>
                        </div>
                        @error('fichier')
                            <p class="field-error mt-2"><span>▸</span> {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Security notice --}}
                    <div class="security-notice">
                        <span class="flex h-2 w-2 rounded-full bg-[#ff1a40] mt-1.5 shrink-0 pulse-red"></span>
                        <p>
                            <strong>Note de sécurité :</strong> Une fois validé, la clé cryptographique liée à votre
                            compte sera requise pour initier toute future demande de décryptage ou téléchargement.
                        </p>
                    </div>

                    {{-- Actions --}}
                    <div>
                        <hr class="form-divider">
                        <div class="flex items-center justify-end gap-6 pt-4">
                            <a href="{{ route('documents.index') }}" class="btn-cancel">Annuler</a>
                            <button type="submit" class="btn-submit" id="archiveSubmitBtn">
                                <span class="btn-icon">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                                    </svg>
                                </span>
                                <svg class="btn-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10" stroke-opacity="0.25" />
                                    <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round" />
                                </svg>
                                <span class="btn-label" id="archiveBtnLabel">Chiffrer et stocker</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // Drop zone feedback
        const dz = document.getElementById('dropZone');
        const inp = document.getElementById('fichier');
        const fn  = document.getElementById('dropFilename');

        inp.addEventListener('change', () => {
            if (inp.files.length) {
                fn.textContent = '▸ ' + inp.files[0].name;
                fn.style.display = 'block';
            }
        });

        dz.addEventListener('dragover', e => { e.preventDefault(); dz.classList.add('drag-over'); });
        dz.addEventListener('dragleave', () => dz.classList.remove('drag-over'));
        dz.addEventListener('drop', e => {
            e.preventDefault();
            dz.classList.remove('drag-over');
            if (e.dataTransfer.files.length) {
                inp.files = e.dataTransfer.files;
                fn.textContent = '▸ ' + e.dataTransfer.files[0].name;
                fn.style.display = 'block';
            }
        });

        const form = document.getElementById('archiveForm');
        const btn = document.getElementById('archiveSubmitBtn');
        const label = document.getElementById('archiveBtnLabel');

        if (form) {
            form.addEventListener('submit', function () {
                if (!form.checkValidity()) return;
                btn.classList.add('loading');
                btn.disabled = true;
                label.textContent = 'Chiffrement en cours…';
            });
        }
    </script>
</x-app-layout>