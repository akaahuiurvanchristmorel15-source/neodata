<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-[#ff1a40] tracking-tight neodata-title">
                    Tableau de bord
                </h1>
                <p class="text-xs text-black mt-1 font-light">
                    Indicateurs clés de performance et d'activité — NEODATA.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex h-2 w-2 rounded-full bg-[#ff1a40] animate-pulse"></span>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Outfit:wght@300;400;600;700;800&display=swap');

        /* ── Root tokens ── */
        :root {
            --red: #91041bff;
            --red-dim: rgba(255, 26, 64, 0.18);
            --red-border: rgba(255, 26, 64, 0.35);
            --bg-card: rgba(255, 255, 255, 1);
            --bg-card2: rgba(255, 255, 255, 1);
            --border: rgba(179, 179, 179, 0.73);
        }

        /* ── Ambient glow background ── */
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

        /* ── KPI cards ── */
        .kpi-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 24px;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            transition: border-color 0.4s, box-shadow 0.4s, transform 0.3s;
            animation: fadeSlideUp 0.5s ease both;
        }

        .kpi-card:hover {
            border-color: var(--red-border);
            transform: translateY(-2px);
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255, 26, 64, 0.04) 0%, transparent 60%);
            pointer-events: none;
        }

        /* Corner glow dot */
        .kpi-card .corner-glow {
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.15;
            pointer-events: none;
            transition: opacity 0.4s;
        }

        .kpi-card:hover .corner-glow {
            opacity: 0.3;
        }

        .kpi-label {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
        }

        .kpi-value {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 56px;
            line-height: 1;
            color: #000000ff;
            letter-spacing: 0.02em;
            margin: 8px 0 4px;
        }

        .kpi-sub {
            font-size: 14px;
            color: rgb(22, 22, 22);
            font-weight: 300;
            letter-spacing: 0.05em;
        }

        /* Animated separator line */
        .kpi-line {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 2px;
            width: 0%;
            border-radius: 0 0 20px 20px;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .kpi-card:hover .kpi-line {
            width: 100%;
        }

        /* ── Panel cards ── */
        .panel {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 24px;
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            transition: border-color 0.4s;
            animation: fadeSlideUp 0.6s ease both;
            position: relative;
        }

        .panel:hover {
            border-color: rgba(255, 26, 64, 0.2);
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
            background: var(--red);
            border-radius: 2px;
            box-shadow: 0 0 8px var(--red);
        }

        .panel-sub {
            font-size: 12px;
            color: rgba(49, 49, 49, 1);
            font-weight: 300;
            margin-top: 4px;
            padding-left: 13px;
        }

        /* ── Doc rows ── */
        .doc-row {
            padding: 12px 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            transition: background 0.2s;
            border-radius: 8px;
            margin: 0 -8px;
        }

        .doc-row:hover {
            background: rgba(255, 26, 64, 0.04);
        }

        .doc-row:last-child {
            border-bottom: none;
        }

        .doc-name {
            font-size: 14px;
            font-weight: 600;
            color: #000000ff;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .doc-cat {
            font-size: 14px;
            color: rgba(0, 0, 0, 1);
            font-family: monospace;
        }

        .doc-date {
            font-size: 14px;
            font-family: monospace;
            color: rgba(0, 0, 0, 1);
            white-space: nowrap;
        }

        /* ── Top consultations badge ── */
        .rank-badge {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18px;
            color: var(--red);
            min-width: 28px;
            letter-spacing: 0.05em;
        }

        .consult-badge {
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(255, 26, 64, 0.1);
            border: 1px solid rgba(255, 26, 64, 0.25);
            color: var(--red);
            font-family: monospace;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* ── Stats bar ── */
        .stat-card {
            background: var(--bg-card2);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 14px;
            padding: 14px 16px;
            transition: border-color 0.3s, transform 0.3s;
        }

        .stat-card:hover {
            border-color: rgba(255, 26, 64, 0.3);
            transform: translateY(-1px);
        }

        .stat-bar-track {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 999px;
            height: 4px;
            overflow: hidden;
            margin-top: 10px;
        }

        .stat-bar-fill {
            height: 4px;
            border-radius: 999px;
            background: linear-gradient(90deg, #ff1a40, #ff4d6d);
            box-shadow: 0 0 8px rgba(255, 26, 64, 0.5);
            transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1);
            animation: barGrow 1.4s cubic-bezier(0.4, 0, 0.2, 1) both;
        }

        @keyframes barGrow {
            from {
                width: 0 !important;
            }
        }

        /* ── Animations ── */
        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .kpi-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .kpi-card:nth-child(2) {
            animation-delay: 0.12s;
        }

        .kpi-card:nth-child(3) {
            animation-delay: 0.19s;
        }

        @keyframes pulse-red {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(255, 26, 64, 0.4);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(255, 26, 64, 0);
            }
        }

        .pulse-red {
            animation: pulse-red 2s ease-in-out infinite;
        }

        /* Scan line effect on hover for panels */
        .panel::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 26, 64, 0.4), transparent);
            top: -1px;
            opacity: 0;
            transition: opacity 0.4s;
        }

        .panel:hover::after {
            opacity: 1;
        }
    </style>

    <div class="dash-wrap">
        <div class="dash-content space-y-6">

            {{-- ── KPI Row ── --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                {{-- Volume Global --}}
                <div class="kpi-card">
                    <div class="corner-glow" style="background: radial-gradient(circle, #10b981, transparent);"></div>
                    <p class="kpi-label text-emerald-400">DOCUMENT TOTAL</p>
                    <p class="kpi-value">{{ $totalDocuments }}</p>
                    <p class="kpi-sub">Fichiers archivés au total</p>
                    <div class="kpi-line" style="background: linear-gradient(90deg, #10b981, #34d399);"></div>
                </div>

                {{-- Structure de classement --}}
                <div class="kpi-card">
                    <div class="corner-glow" style="background: radial-gradient(circle, #a855f7, transparent);"></div>
                    <p class="kpi-label text-purple-400">DOSSIERS ACTIFS</p>
                    <p class="kpi-value">{{ $totalCategories }}</p>
                    <p class="kpi-sub">Dossiers et sous-dossiers actifs</p>
                    <div class="kpi-line" style="background: linear-gradient(90deg, #a855f7, #c084fc);"></div>
                </div>

                {{-- État du chiffrement --}}
                <div class="kpi-card">
                    <div class="corner-glow" style="background: radial-gradient(circle, #ff1a40, transparent);"></div>
                    <p class="kpi-label text-[#ff1a40]">État du chiffrement</p>
                    <div class="flex items-center gap-3 mt-3 mb-1">
                        <span class="flex h-3 w-3 rounded-full bg-[#ff1a40] pulse-red shrink-0"></span>
                        <span
                            style="font-family:'Bebas Neue',sans-serif; font-size:28px; letter-spacing:0.05em; color:#ff1a40;">
                            Matériel Actif
                        </span>
                    </div>
                    <p class="kpi-sub">Clés AES-256 opérationnelles</p>
                    <div class="kpi-line" style="background: linear-gradient(90deg, #ff1a40, #ff4d6d);"></div>
                </div>

            </div>

            {{-- ── Panels ── --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                {{-- Récents dépôts chiffrés --}}
                <div class="panel space-y-4">
                    <div>
                        <p class="panel-title">Récents dépôts chiffrés</p>
                        <p class="panel-sub">Les 5 dernières archives enregistrées dans le système.</p>
                    </div>
                    <div class="space-y-1">
                        @forelse($derniersDocuments as $doc)
                            <div class="doc-row">
                                <div class="space-y-0.5 truncate max-w-[68%]">
                                    <p class="doc-name truncate">
                                        <span class="text-[#ff1a40] mr-2 text-xs">▸</span>
                                        {{ $doc->titre }}
                                    </p>
                                    <p class="doc-cat">{{ $doc->category->nom ?? 'Racine' }} • <span class="text-slate-400 font-mono text-[9px]">{{ $doc->taille_mo }} Mo</span></p>
                                </div>
                                <span class="doc-date">{{ $doc->created_at->format('d/m/Y') }}</span>
                            </div>
                        @empty
                            <p class="text-xs italic text-gray-600 py-4">Aucun document déposé.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Documents les plus consultés --}}
                <div class="panel space-y-4">
                    <div>
                        <p class="panel-title">Documents les plus consultés</p>
                        <p class="panel-sub">Classement basé sur le volume de téléchargements et décryptages.</p>
                    </div>
                    <div class="space-y-1">
                        @forelse($topConsultations as $index => $item)
                            <div class="doc-row">
                                <div class="flex items-center gap-3 truncate max-w-[75%]">
                                    <span class="rank-badge">#{{ $index + 1 }}</span>
                                    <p class="text-xs font-semibold text-gray-200 truncate">{{ $item['titre'] }}</p>
                                </div>
                                <span class="consult-badge">{{ $item['total'] }} clics</span>
                            </div>
                        @empty
                            <p class="text-xs italic text-gray-600 py-4">Aucune consultation enregistrée.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- ── Archive stats ── --}}
            <div class="panel space-y-5">
                <div>
                    <p class="panel-title">Répartition des Archives par Dossier</p>
                    <p class="panel-sub">Densité documentaire par secteur de classement.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse($statsCategories as $stat)
                        @php
                            $pourcentage = $totalDocuments > 0 ? ($stat->documents_count / $totalDocuments) * 100 : 0;
                        @endphp
                        <div class="stat-card">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-[15px] font-bold text-black truncate max-w-[70%] flex items-center gap-1.5">
                                    <span class="text-black text-[15px]">▸</span>
                                    {{ $stat->nom }}
                                </span>
                                <span class="font-mono font-bold text-[14px] text-black">{{ $stat->documents_count }}</span>
                            </div>
                            <div class="stat-bar-track">
                                <div class="stat-bar-fill" style="width: {{ $pourcentage }}%"></div>
                            </div>
                            <p class="text-[12px] text-gray-600 mt-1.5 font-mono">
                                {{ number_format($pourcentage, 1) }}% du volume
                            </p>
                        </div>
                    @empty
                        <p class="text-xs italic text-gray-600 col-span-4 py-2">Aucune statistique disponible.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>