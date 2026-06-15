<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#ff1a40] tracking-tight neodata-title">
                    Arborescence des Dossiers
                </h1>
                <p class="text-xs text-black mt-1 font-light">
                    Gérez la structure de classement des dossiers et sous-dossiers — NEODATA.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex h-2 w-2 rounded-full bg-[#ff1a40] animate-pulse"></span>            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;600;700;800&display=swap');

        :root {
            --red: #91041bff;
            --red-bright: #ff1a40;
            --bg-card: rgba(255, 255, 255, 1);
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
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
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
            font-size: 14px;
            color: rgba(49, 49, 49, 1);
            font-weight: 300;
            margin-top: 4px;
            padding-left: 13px;
        }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px;
            border-radius: 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeSlideUp 0.4s ease both;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.07);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #059669;
        }

        .alert-error {
            background: rgba(255, 26, 64, 0.07);
            border: 1px solid rgba(255, 26, 64, 0.3);
            color: #ff1a40;
        }

        /* ── Form ── */
        .field-label {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(0, 0, 0, 0.45);
            margin-bottom: 7px;
        }

        .neo-input,
        .neo-select,
        .neo-textarea {
            width: 100%;
            background: #fafafa;
            border: 1px solid rgba(179, 179, 179, 0.8);
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #111;
            padding: 10px 16px;
            transition: border-color 0.25s, box-shadow 0.25s;
            outline: none;
            -webkit-appearance: none;
            appearance: none;
        }

        .neo-input::placeholder,
        .neo-textarea::placeholder {
            color: rgba(0, 0, 0, 0.28);
            font-weight: 300;
        }

        .neo-input:focus,
        .neo-select:focus,
        .neo-textarea:focus {
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

        .neo-textarea {
            resize: none;
            line-height: 1.6;
        }

        .field-hint {
            font-family: 'Outfit', sans-serif;
            font-size: 9px;
            color: rgba(0, 0, 0, 0.3);
            margin-top: 5px;
        }

        /* ── Bouton + Spinner ── */
        .btn-submit {
            width: 100%;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            padding: 12px 24px;
            border-radius: 12px;
            background: linear-gradient(135deg, #ff1a40, #91041b);
            color: #fff;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 18px rgba(255, 26, 64, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
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
            opacity: 0.75;
            cursor: not-allowed;
            transform: none;
        }

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
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.12) 50%, transparent 100%);
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

        /* ══ TABLE desktop ══ */
        .neo-table {
            width: 100%;
            border-collapse: collapse;
        }

        .neo-table thead tr {
            border-bottom: 1px solid var(--border);
        }

        .neo-table thead th {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(0, 0, 0, 0.4);
            padding: 14px 20px;
            text-align: left;
        }

        .neo-table thead th:last-child {
            text-align: right;
        }

        .neo-table thead th.center {
            text-align: center;
        }

        .neo-table tbody tr {
            border-bottom: 1px solid rgba(179, 179, 179, 0.25);
            transition: background 0.2s;
        }

        .neo-table tbody tr:last-child {
            border-bottom: none;
        }

        .neo-table tbody tr:hover {
            background: rgba(255, 26, 64, 0.03);
        }

        .neo-table td {
            padding: 15px 20px;
            vertical-align: middle;
        }

        .folder-path-parent {
            font-family: monospace;
            font-size: 14px;
            color: rgba(0, 0, 0, 0.35);
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .folder-name-root {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #000;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .folder-name-sub {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #000;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .folder-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .folder-icon-root {
            background: rgba(255, 26, 64, 0.08);
            border: 1px solid rgba(255, 26, 64, 0.2);
        }

        .folder-icon-sub {
            background: rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        .level-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 999px;
            font-family: 'Outfit', sans-serif;
            font-size: 2px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .level-root {
            background: rgba(255, 26, 64, 0.07);
            border: 1px solid rgba(255, 26, 64, 0.25);
            color: #ff1a40;
        }

        .level-sub {
            background: rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.1);
            color: rgba(0, 0, 0, 0.5);
        }

        .doc-count {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            color: #000;
            letter-spacing: 0.05em;
            line-height: 1;
        }

        .doc-count-label {
            font-family: monospace;
            font-size: 9px;
            color: rgba(0, 0, 0, 0.3);
        }

        .btn-delete {
            font-family: 'Outfit', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 8px;
            background: transparent;
            border: 1px solid rgba(255, 26, 64, 0.2);
            color: rgba(255, 26, 64, 0.6);
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s, color 0.2s;
            white-space: nowrap;
        }

        .btn-delete:hover {
            background: rgba(255, 26, 64, 0.08);
            border-color: rgba(255, 26, 64, 0.45);
            color: #ff1a40;
        }

        .empty-state {
            padding: 48px 24px;
            text-align: center;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: rgba(0, 0, 0, 0.3);
            font-style: italic;
        }

        /* ── Breadcrumb ── */
        .explorer-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            background: rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(179, 179, 179, 0.4);
            padding: 8px 16px;
            border-radius: 12px;
        }
        .breadcrumb-item {
            color: rgba(0, 0, 0, 0.4);
            text-decoration: none;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .breadcrumb-item:hover {
            color: var(--red-bright);
        }
        .breadcrumb-item.active {
            color: #000;
            pointer-events: none;
        }
        .breadcrumb-separator {
            color: rgba(0, 0, 0, 0.25);
            font-size: 12px;
            display: flex;
            align-items: center;
        }

        /* ── Folder explorer ── */
        .folders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
        }
        .folder-card {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(179, 179, 179, 0.48);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            text-decoration: none;
            transition: border-color 0.3s, background 0.3s, transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }
        .folder-card:hover {
            border-color: rgba(255, 26, 64, 0.4);
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 26, 64, 0.06);
        }
        .folder-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #ff1a40, #91041b);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .folder-card:hover::before {
            opacity: 1;
        }
        .folder-icon-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .folder-icon {
            font-size: 38px;
            color: #ff1a40;
            transition: transform 0.3s, color 0.3s;
        }
        .folder-card:hover .folder-icon {
            transform: scale(1.08);
        }
        .folder-arrow {
            color: rgba(0, 0, 0, 0.25);
            transition: transform 0.2s, color 0.2s;
            font-size: 16px;
        }
        .folder-card:hover .folder-arrow {
            transform: translateX(3px);
            color: var(--red-bright);
        }
        .folder-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .folder-name {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #000;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .folder-stats {
            font-family: monospace;
            font-size: 9px;
            color: rgba(0, 0, 0, 0.4);
        }

        .empty-folder-state {
            background: rgba(255, 255, 255, 0.4);
            border: 1px dashed rgba(179, 179, 179, 0.6);
            border-radius: 16px;
            padding: 40px 20px;
            text-align: center;
            font-family: 'Outfit', sans-serif;
        }
        .empty-folder-title {
            font-size: 14px;
            font-weight: 700;
            color: #000;
            margin-top: 8px;
        }
        .empty-folder-text {
            font-size: 12px;
            color: rgba(0, 0, 0, 0.45);
            margin-top: 4px;
            font-weight: 300;
        }

        /* ── Documents styling ── */
        .cat-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 600;
            background: rgba(255, 26, 64, 0.05);
            border: 1px solid rgba(255, 26, 64, 0.25);
            color: var(--red-bright);
        }
        .doc-title {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #000;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .author-cell {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 500;
            color: rgba(0, 0, 0, 0.6);
        }
        .date-cell {
            font-family: monospace;
            font-size: 10px;
            color: rgba(0, 0, 0, 0.45);
        }
        .action-dl {
            font-family: 'Outfit', sans-serif;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 6px;
            background: rgba(16, 185, 129, 0.08);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: #059669;
            transition: all 0.2s;
            text-decoration: none;
        }
        .action-dl:hover {
            background: #059669;
            color: #fff;
        }
        .action-del {
            font-family: 'Outfit', sans-serif;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 6px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #dc2626;
            transition: all 0.2s;
            cursor: pointer;
        }
        .action-del:hover {
            background: #dc2626;
            color: #fff;
        }

        /* ── Animations ── */
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

        .panel:nth-child(1) {
            animation-delay: 0.05s;
        }

        .panel:nth-child(2) {
            animation-delay: 0.12s;
        }
    </style>

    <div class="dash-wrap">
        <div class="dash-content space-y-5">

            {{-- Alerts --}}
            <x-flash-messages />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">

                {{-- ── Formulaire ── --}}
                <div class="panel">
                    <div class="px-6 pt-5 pb-4 border-b border-[rgba(179,179,179,0.35)]">
                        <p class="panel-title">Nouveau dossier</p>
                        <p class="panel-sub">Créez un dossier racine ou un sous-dossier de classement.</p>
                    </div>

                    <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-5" id="folderForm">
                        @csrf

                        <div>
                            <label for="nom" class="field-label">
                                Nom du dossier <span style="color:#ff1a40">*</span>
                            </label>
                            <input type="text" name="nom" id="nom" required
                                placeholder="Ex : Rapports, Factures, YA Consulting" class="neo-input">
                        </div>

                        <div>
                            <label for="parent_id" class="field-label">Dossier parent</label>
                            <select name="parent_id" id="parent_id" class="neo-select">
                                <option value="">📁 Aucun — Dossier Racine</option>
                                @foreach($allCategories as $allCat)
                                    <option value="{{ $allCat->id }}" {{ $currentCategory && $currentCategory->id == $allCat->id ? 'selected' : '' }}>
                                        {{ $allCat->parent ? $allCat->parent->nom . ' › ' : '' }}{{ $allCat->nom }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="field-hint">Laissez vide pour créer un dossier racine.</p>
                        </div>

                        <div>
                            <label for="description" class="field-label">Description</label>
                            <textarea name="description" id="description" rows="2"
                                placeholder="Notes sur le contenu de ce dossier…" class="neo-textarea"></textarea>
                        </div>

                        <button type="submit" class="btn-submit" id="folderSubmitBtn">
                            <span class="btn-icon">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z" />
                                </svg>
                            </span>
                            <svg class="btn-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <circle cx="12" cy="12" r="10" stroke-opacity="0.25" />
                                <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round" />
                            </svg>
                            <span class="btn-label" id="folderBtnLabel">Enregistrer le dossier</span>
                        </button>
                    </form>
                </div>

                {{-- ── Explorateur de Classement (Dossiers & Contenu) ── --}}
                <div class="panel lg:col-span-2 space-y-6 p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-4 border-b border-[rgba(179,179,179,0.35)]">
                        <div>
                            <p class="panel-title">{{ $currentCategory ? $currentCategory->nom : 'Structure de classement' }}</p>
                            <p class="panel-sub">
                                @if($currentCategory)
                                    Dossier actif ({{ $currentCategory->taille_mo > 1024 ? round($currentCategory->taille_mo / 1024, 2) . ' Go' : $currentCategory->taille_mo . ' Mo' }}) — Gérer ses sous-dossiers et visualiser ses documents.
                                @else
                                    Dossiers principaux configurés pour l'archivage NEODATA.
                                @endif
                            </p>
                        </div>

                        {{-- Breadcrumbs interactifs --}}
                        <div class="explorer-breadcrumb">
                            <a href="{{ route('categories.index') }}" class="breadcrumb-item {{ !$currentCategory ? 'active' : '' }}">
                                <i class='bx bxs-home' style="font-size: 13px;"></i> Racine
                            </a>
                            @foreach($breadcrumbs as $crumb)
                                <span class="breadcrumb-separator"><i class='bx bx-chevron-right'></i></span>
                                <a href="{{ route('categories.index', ['category_id' => $crumb->id]) }}" 
                                   class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                                    {{ $crumb->nom }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- 📁 Sous-Dossiers Grid --}}
                    <div class="space-y-3">
                        <p class="field-label" style="margin-bottom: 0;">{{ $currentCategory ? 'Sous-dossiers' : 'Dossiers Principaux' }}</p>
                        @if($subCategories->isNotEmpty())
                            <div class="folders-grid">
                                @foreach($subCategories as $cat)
                                    <div class="relative group">
                                        <a href="{{ route('categories.index', ['category_id' => $cat->id]) }}" class="folder-card">
                                            <div class="folder-icon-wrap">
                                                <i class='bx bxs-folder folder-icon'></i>
                                                <i class='bx bx-chevron-right folder-arrow'></i>
                                            </div>
                                            <div class="folder-info">
                                                 <span class="folder-name" title="{{ $cat->nom }}">{{ $cat->nom }}</span>
                                                 <span class="folder-stats">
                                                     📁 {{ $cat->enfants()->count() }} • 📄 {{ $cat->documents_count ?? $cat->documents()->count() }} • 💾 {{ $cat->taille_mo > 1024 ? round($cat->taille_mo / 1024, 2) . ' Go' : $cat->taille_mo . ' Mo' }}
                                                 </span>
                                             </div>
                                        </a>
                                        {{-- Bouton Supprimer flottant ou discret sur le dossier --}}
                                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" 
                                              class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity z-10"
                                              onsubmit="return confirm('Attention : supprimer ce dossier supprimera également tous ses sous-dossiers !');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-delete" style="padding: 4px 8px; border-color: rgba(255,26,64,0.4); background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-black opacity-35 italic font-light pl-2">Aucun sous-dossier configuré ici.</p>
                        @endif
                    </div>

                    {{-- 📄 Documents dans ce dossier (uniquement si un dossier est ouvert) --}}
                    @if($currentCategory)
                        <div class="space-y-3 pt-2 border-t border-[rgba(179,179,179,0.25)]">
                            <p class="field-label" style="margin-bottom: 0;">Documents classés dans ce dossier</p>
                            @if($documents->isNotEmpty())
                                {{-- View Table --}}
                                <div class="overflow-x-auto w-full rounded-xl">
                                    <table class="neo-table min-w-full">
                                         <thead>
                                             <tr>
                                                 <th>Nom du document</th>
                                                 <th>Taille</th>
                                                 <th>Auteur</th>
                                                 <th>Date d'archivage</th>
                                                 <th class="text-right">Actions</th>
                                             </tr>
                                         </thead>
                                         <tbody>
                                             @foreach($documents as $doc)
                                                 <tr>
                                                     <td>
                                                         <div class="doc-title">
                                                             <span style="color:#ff1a40; flex-shrink:0;">▸</span>
                                                             <span title="{{ $doc->titre }}">{{ $doc->titre }}</span>
                                                         </div>
                                                     </td>
                                                     <td><span class="cat-badge">{{ $doc->taille_mo }} Mo</span></td>
                                                     <td><span class="author-cell">{{ $doc->auteur }}</span></td>
                                                     <td><span class="date-cell">{{ $doc->created_at->format('d/m/Y H:i') }}</span></td>
                                                    <td>
                                                        <div class="flex items-center justify-end gap-2">
                                                            <a href="{{ route('documents.download', $doc->id) }}" class="action-dl">Télécharger</a>
                                                            @if(auth()->user()->isAdmin())
                                                                <form action="{{ route('documents.destroy', $doc->id) }}" method="POST"
                                                                    style="display:inline;"
                                                                    onsubmit="return confirm('Confirmer la destruction permanente de cette archive ?');">
                                                                    @csrf @method('DELETE')
                                                                    <button type="submit" class="action-del">Supprimer</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="empty-folder-state">
                                    <i class='bx bx-folder-open animate-bounce' style="font-size: 38px; color: rgba(255, 26, 64, 0.4); display: inline-block;"></i>
                                    <p class="empty-folder-title">Ce dossier est vide</p>
                                    <p class="empty-folder-text">Aucun document n'est encore classé directement dans ce dossier.</p>
                                    <div class="pt-3">
                                        <a href="{{ route('documents.create', ['category_id' => $currentCategory->id]) }}" class="btn-submit" style="display: inline-flex; width: auto; padding: 8px 16px;">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                                                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                                                <polyline points="17 8 12 3 7 8" />
                                                <line x1="12" y1="3" x2="12" y2="15" />
                                            </svg>
                                            Y déposer un document
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('folderForm');
        const btn = document.getElementById('folderSubmitBtn');
        const label = document.getElementById('folderBtnLabel');

        form.addEventListener('submit', function () {
            if (!form.checkValidity()) return;
            btn.classList.add('loading');
            btn.disabled = true;
            label.textContent = 'Enregistrement…';
            setTimeout(() => {
                btn.classList.remove('loading');
                btn.disabled = false;
                label.textContent = 'Enregistrer le dossier';
            }, 15000);
        });
    </script>

</x-app-layout>