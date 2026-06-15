<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-[#ff1a40] tracking-tight neodata-title">
                    Consultation des Archives
                </h1>
                <p class="text-xs text-black mt-1 font-light">
                    Espace de recherche globale et d'accès sécurisé aux documents de l'organisation.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('documents.export') }}" class="btn-ghost">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('documents.create') }}" class="btn-submit-sm">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    Déposer un document
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;600;700;800&display=swap');

        :root {
            --red: #91041bff;
            --red-bright: #ff1a40;
            --red-dim: rgba(255, 26, 64, 0.08);
            --red-border: rgba(255, 26, 64, 0.35);
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
            padding: 24px;
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
            font-size: 13px;
            color: rgba(49, 49, 49, 1);
            font-weight: 300;
            margin-top: 4px;
            padding-left: 13px;
        }

        /* ── Header buttons ── */
        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 12px;
            background: #fff;
            border: 1px solid rgba(179, 179, 179, 0.8);
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(0, 0, 0, 0.6);
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
        }

        .btn-ghost:hover {
            border-color: rgba(0, 0, 0, 0.3);
            color: #000;
            background: #fafafa;
        }

        .btn-submit-sm {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 12px;
            background: linear-gradient(135deg, #ff1a40, #91041b);
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #fff;
            text-decoration: none;
            box-shadow: 0 4px 18px rgba(255, 26, 64, 0.3);
            transition: opacity 0.2s, transform 0.2s;
        }

        .btn-submit-sm:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        /* ── Alert ── */
        .alert-success {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 14px;
            background: rgba(16, 185, 129, 0.07);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #059669;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 500;
            animation: fadeSlideUp 0.4s ease both;
        }

        /* ── Search form inputs ── */
        .field-label {
            display: block;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(0, 0, 0, 0.45);
            margin-bottom: 7px;
        }

        .neo-input,
        .neo-select {
            width: 100%;
            background: #fafafa;
            border: 1px solid rgba(179, 179, 179, 0.8);
            border-radius: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            color: #111;
            padding: 10px 16px;
            transition: border-color 0.25s, box-shadow 0.25s;
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

        .neo-input[type="date"] {
            font-size: 12px;
            color: rgba(0, 0, 0, 0.6);
        }

        .btn-search {
            width: 100%;
            height: 100%;
            min-height: 42px;
            border-radius: 12px;
            background: #000;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-search:hover {
            background: #222;
            transform: translateY(-1px);
        }

        .reset-link {
            font-family: 'Outfit', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(255, 26, 64, 0.7);
            text-decoration: none;
            transition: color 0.2s;
            white-space: nowrap;
        }

        .reset-link:hover {
            color: #ff1a40;
        }

        /* ── TABLE ── */
        .neo-table {
            width: 100%;
            border-collapse: collapse;
        }

        .neo-table thead tr {
            border-bottom: 1px solid var(--border);
        }

        .neo-table thead th {
            font-family: 'Outfit', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(0, 0, 0, 0.4);
            padding: 12px 16px;
            text-align: left;
        }

        .neo-table thead th:last-child {
            text-align: right;
        }

        .neo-table tbody tr {
            border-bottom: 1px solid rgba(179, 179, 179, 0.22);
            transition: background 0.2s;
        }

        .neo-table tbody tr:last-child {
            border-bottom: none;
        }

        .neo-table tbody tr:hover {
            background: rgba(255, 26, 64, 0.025);
        }

        .neo-table td {
            padding: 14px 16px;
            vertical-align: middle;
        }

        .doc-title {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #000;
            display: flex;
            align-items: center;
            gap: 6px;
            max-width: 220px;
        }

        .doc-title span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .cat-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 6px;
            background: rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.08);
            font-family: monospace;
            font-size: 9px;
            color: rgba(0, 0, 0, 0.55);
            white-space: nowrap;
        }

        .author-cell {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 500;
            color: rgba(0, 0, 0, 0.5);
        }

        .keywords-cell {
            font-family: monospace;
            font-size: 10px;
            color: rgba(0, 0, 0, 0.35);
            max-width: 130px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }

        .date-cell {
            font-family: monospace;
            font-size: 10px;
            color: rgba(0, 0, 0, 0.4);
            white-space: nowrap;
        }

        .action-dl {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #059669;
            text-decoration: none;
            padding: 4px 10px;
            border-radius: 7px;
            border: 1px solid rgba(5, 150, 105, 0.25);
            background: rgba(5, 150, 105, 0.06);
            transition: background 0.2s, border-color 0.2s;
            white-space: nowrap;
        }

        .action-dl:hover {
            background: rgba(5, 150, 105, 0.12);
            border-color: rgba(5, 150, 105, 0.45);
        }

        .action-del {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #ff1a40;
            background: none;
            border: 1px solid rgba(255, 26, 64, 0.2);
            padding: 4px 10px;
            border-radius: 7px;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
            white-space: nowrap;
        }

        .action-del:hover {
            background: rgba(255, 26, 64, 0.08);
            border-color: rgba(255, 26, 64, 0.45);
        }

        /* ── MOBILE CARDS ── */
        .doc-card {
            background: #fff;
            border: 1px solid rgba(179, 179, 179, 0.5);
            border-radius: 14px;
            padding: 16px;
            transition: border-color 0.2s, box-shadow 0.2s;
            animation: fadeSlideUp 0.4s ease both;
        }

        .doc-card:hover {
            border-color: rgba(255, 26, 64, 0.25);
            box-shadow: 0 4px 20px rgba(255, 26, 64, 0.06);
        }

        .doc-card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #000;
            display: flex;
            align-items: flex-start;
            gap: 7px;
            line-height: 1.4;
        }

        .doc-card-arrow {
            color: #000000ff;
            font-size: 13px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .doc-card-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }

        .meta-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 7px;
            background: rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.07);
            font-family: monospace;
            font-size: 11px;
            color: rgba(0, 0, 0, 0.5);
        }

        .doc-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid rgba(179, 179, 179, 0.3);
        }

        .doc-card-date {
            font-family: monospace;
            font-size: 9px;
            color: rgba(0, 0, 0, 0.35);
        }

        .doc-card-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Empty state */
        .empty-state {
            padding: 48px 24px;
            text-align: center;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: rgba(0, 0, 0, 0.3);
            font-style: italic;
        }

        /* ── Pagination ── */
        .pagination-wrap {
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        /* ── Animations ── */
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(14px);
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

            {{-- ── Alert ── --}}
            <x-flash-messages />

            {{-- ── Panel Recherche ── --}}
            <div class="panel">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-[rgba(179,179,179,0.35)]">
                    <div>
                        <p class="panel-title">Moteur de Recherche</p>
                        <p class="panel-sub">Affinez l'indexation de la base documentaire en temps réel.</p>
                    </div>
                    @if(request()->hasAny(['search', 'category_id', 'date_debut', 'date_fin']))
                        <a href="{{ route('documents.index') }}" class="reset-link">↺ Réinitialiser</a>
                    @endif
                </div>

                <form action="{{ route('documents.index') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="sm:col-span-2">
                            <label for="search" class="field-label">Recherche globale</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Nom du document, mot-clé, auteur…" class="neo-input">
                        </div>
                        <div>
                            <label for="category_id" class="field-label">Dossier de classement</label>
                            <select name="category_id" id="category_id" class="neo-select">
                                <option value="">Tous les dossiers</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->parent ? $cat->parent->nom . ' › ' : '' }}{{ $cat->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="btn-search">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.35-4.35" />
                                </svg>
                                Rechercher
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-[rgba(179,179,179,0.3)]">
                        <div>
                            <label for="date_debut" class="field-label">Archivé après le</label>
                            <input type="date" name="date_debut" id="date_debut" value="{{ request('date_debut') }}"
                                class="neo-input">
                        </div>
                        <div>
                            <label for="date_fin" class="field-label">Avant le</label>
                            <input type="date" name="date_fin" id="date_fin" value="{{ request('date_fin') }}"
                                class="neo-input">
                        </div>
                    </div>
                </form>
            </div>

            {{-- ── Panel Registre ── --}}
            <div class="panel space-y-4">
                <div>
                    <p class="panel-title">Index Global Documentaire</p>
                    <p class="panel-sub">Données structurées sous protocoles de chiffrement actifs AES-256.</p>
                </div>

                {{-- ══ VIEW — PC & Tablettes (Tableau scrollable proprement) ══ --}}
                <div class="hidden md:block overflow-x-auto w-full rounded-xl">
                    <table class="neo-table min-w-full">
                        <thead>
                            <tr>
                                <th>Nom du document</th>
                                <th>Taille</th>
                                <th>Emplacement</th>
                                <th>Auteur</th>
                                <th>Mots-clés</th>
                                <th>Date d'archivage</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documents as $doc)
                                <tr>
                                    <td>
                                        <div class="doc-title">
                                            <span style="color:#ff1a40; flex-shrink:0;">▸</span>
                                            <span title="{{ $doc->titre }}">{{ $doc->titre }}</span>
                                        </div>
                                    </td>
                                    <td><span class="cat-badge">{{ $doc->taille_mo }} Mo</span></td>
                                    <td><span class="cat-badge">{{ $doc->category->nom ?? 'Racine' }}</span></td>
                                    <td><span class="author-cell">{{ $doc->auteur }}</span></td>
                                    <td><span class="keywords-cell"
                                            title="{{ $doc->mots_cles }}">{{ $doc->mots_cles ?? '—' }}</span></td>
                                    <td><span class="date-cell">{{ $doc->created_at->format('d/m/Y H:i') }}</span></td>
                                    <td>
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('documents.download', $doc->id) }}"
                                                class="action-dl">Télécharger</a>
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
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-state">Aucun document archivé ne correspond aux critères
                                        actuels.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ══ VIEW — Smartphones (Grille de cartes adaptatives) ══ --}}
                <div class="md:hidden space-y-4">
                    @forelse($documents as $doc)
                        <div class="doc-card">
                            <div class="doc-card-title">
                                <span class="doc-card-arrow">▸</span>
                                {{ $doc->titre }}
                            </div>

                            <div class="doc-card-meta">
                                <span class="meta-chip">
                                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z" />
                                    </svg>
                                    {{ $doc->category->nom ?? 'Racine' }}
                                </span>
                                <span class="meta-chip">
                                    <i class='bx bx-hdd' style="font-size: 10px;"></i>
                                    {{ $doc->taille_mo }} Mo
                                </span>
                                @if($doc->auteur)
                                    <span class="meta-chip">
                                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <circle cx="12" cy="8" r="4" />
                                            <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                                        </svg>
                                        {{ $doc->auteur }}
                                    </span>
                                @endif
                                @if($doc->mots_cles)
                                    <span
                                        class="meta-chip max-w-[180px] overflow-hidden text-overflow-ellipsis white-space-nowrap">
                                        # {{ $doc->mots_cles }}
                                    </span>
                                @endif
                            </div>

                            <div class="doc-card-footer">
                                <span class="doc-card-date">{{ $doc->created_at->format('d/m/Y H:i') }}</span>
                                <div class="doc-card-actions">
                                    <a href="{{ route('documents.download', $doc->id) }}" class="action-dl">Télécharger</a>
                                    @if(auth()->user()->isAdmin())
                                        <form action="{{ route('documents.destroy', $doc->id) }}" method="POST"
                                            onsubmit="return confirm('Confirmer la destruction permanente de cette archive ?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-del">Supprimer</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">Aucun document archivé ne correspond aux critères actuels.</div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($documents->hasPages())
                    <div class="pagination-wrap">
                        {{ $documents->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>