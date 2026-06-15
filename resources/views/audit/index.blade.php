<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h1 class="text-xl font-bold text-[#ff1a40] tracking-tight neodata-title">
                    Traçabilité & Audits
                </h1>
                <p class="text-xs text-black mt-1 font-light">
                    Historique exclusif des actions de sécurité système — NEODATA.
                </p>
            </div>
            <div class="flex items-center gap-2 self-start sm:self-auto">
                <span class="flex h-2 w-2 rounded-full bg-[#ff1a40] animate-pulse"></span>
                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#ff1a40]">Journal sécurisé</span>
            </div>
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
            font-size: 12px;
            color: rgba(49, 49, 49, 1);
            font-weight: 300;
            margin-top: 4px;
            padding-left: 13px;
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
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(0, 0, 0, 0.4);
            padding: 14px 20px;
            text-align: left;
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
            padding: 14px 20px;
            vertical-align: middle;
        }

        /* Cells */
        .cell-date {
            font-family: monospace;
            font-size: 10px;
            color: rgba(0, 0, 0, 0.4);
            white-space: nowrap;
        }

        .cell-user {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #000;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-dot {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: linear-gradient(135deg, #ff1a40, #91041b);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 11px;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(255, 26, 64, 0.2);
        }

        .user-dot-system {
            background: rgba(0, 0, 0, 0.08);
            color: rgba(0, 0, 0, 0.4);
            box-shadow: none;
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
        }

        .action-chip {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 7px;
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.05em;
            background: rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.08);
            color: rgba(0, 0, 0, 0.6);
            white-space: nowrap;
        }

        /* Colorise selon le type d'action */
        .action-chip.chip-upload {
            background: rgba(16, 185, 129, 0.07);
            border-color: rgba(16, 185, 129, 0.25);
            color: #059669;
        }

        .action-chip.chip-delete {
            background: rgba(255, 26, 64, 0.07);
            border-color: rgba(255, 26, 64, 0.25);
            color: #ff1a40;
        }

        .action-chip.chip-download {
            background: rgba(59, 130, 246, 0.07);
            border-color: rgba(59, 130, 246, 0.25);
            color: #3b82f6;
        }

        .action-chip.chip-login {
            background: rgba(168, 85, 247, 0.07);
            border-color: rgba(168, 85, 247, 0.25);
            color: #a855f7;
        }

        .action-chip.chip-logout {
            background: rgba(0, 0, 0, 0.04);
            border-color: rgba(0, 0, 0, 0.12);
            color: rgba(0, 0, 0, 0.5);
        }

        .cell-ip {
            font-family: monospace;
            font-size: 10px;
            color: rgba(0, 0, 0, 0.35);
            white-space: nowrap;
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

        /* ══ CARDS mobile ══ */
        .log-card {
            background: #fff;
            border: 1px solid rgba(179, 179, 179, 0.5);
            border-radius: 14px;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            animation: fadeSlideUp 0.4s ease both;
            transition: border-color 0.25s, box-shadow 0.25s;
        }

        .log-card:hover {
            border-color: rgba(255, 26, 64, 0.22);
            box-shadow: 0 4px 18px rgba(255, 26, 64, 0.05);
        }

        .log-card-top {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .log-card-meta {
            flex: 1;
            min-width: 0;
        }

        .log-card-user {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #000;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .log-card-date {
            font-family: monospace;
            font-size: 12px;
            color: rgba(0, 0, 0, 0.54);
            margin-top: 2px;
        }

        .log-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 10px;
            border-top: 1px solid rgba(179, 179, 179, 0.28);
            flex-wrap: wrap;
            gap: 6px;
        }

        .log-card-ip {
            font-family: monospace;
            font-size: 12px;
            color: rgba(0, 0, 0, 0.32);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ── Pagination ── */
        .pagination-wrap {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
        }

        /* ── Responsive toggles ── */
        .desktop-only {
            display: block;
        }

        .mobile-only {
            display: none;
        }

        @media (max-width: 767px) {
            .desktop-only {
                display: none !important;
            }

            .mobile-only {
                display: flex !important;
                flex-direction: column;
                gap: 10px;
                padding: 16px;
            }
        }

        /* ── Animation ── */
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
    </style>

    @php
        /**
         * Retourne la classe CSS de colorisation selon le contenu du message.
         */
        function chipClass(string $action): string
        {
            $a = strtolower($action);
            if (str_contains($a, 'upload') || str_contains($a, 'archiv') || str_contains($a, 'ajout') || str_contains($a, 'créé'))
                return 'chip-upload';
            if (str_contains($a, 'supprim') || str_contains($a, 'delet') || str_contains($a, 'révok'))
                return 'chip-delete';
            if (str_contains($a, 'télécharg') || str_contains($a, 'download') || str_contains($a, 'décrypt'))
                return 'chip-download';
            if (str_contains($a, 'login') || str_contains($a, 'connect') || str_contains($a, 'session'))
                return 'chip-login';
            if (str_contains($a, 'logout') || str_contains($a, 'déconnect'))
                return 'chip-logout';
            return '';
        }

        function userInitials(?string $name): string
        {
            if (!$name)
                return '?';
            return strtoupper(substr($name, 0, 2));
        }
    @endphp

    <div class="dash-wrap">
        <div class="dash-content">

            <div class="panel">
                {{-- Panel header --}}
                <div class="px-6 pt-5 pb-4 border-b border-[rgba(179,179,179,0.35)]">
                    <p class="panel-title">Registres d'activités</p>
                    <p class="panel-sub">Liste complète des événements et modifications système enregistrés.</p>
                </div>

                {{-- ══ TABLE desktop ══ --}}
                <div class="overflow-x-auto desktop-only">
                    <table class="neo-table">
                        <thead>
                            <tr>
                                <th>Date &amp; Heure</th>
                                <th>Utilisateur</th>
                                <th>Action</th>
                                <th>Adresse IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                @php
                                    $action = $log->message ?? $log->action ?? '';
                                    $name = $log->user->name ?? null;
                                    $isSystem = !$name;
                                @endphp
                                <tr>
                                    <td><span class="cell-date">{{ $log->created_at->format('d/m/Y H:i:s') }}</span></td>
                                    <td>
                                        <div class="cell-user">
                                            <div class="user-dot {{ $isSystem ? 'user-dot-system' : '' }}">
                                                {{ $isSystem ? '⚙' : userInitials($name) }}
                                            </div>
                                            {{ $name ?? 'Système / Visiteur' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="action-chip {{ chipClass($action) }}">{{ $action }}</span>
                                    </td>
                                    <td><span class="cell-ip">{{ $log->ip_address ?? '0.0.0.0' }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-state">Aucune action enregistrée pour le moment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- ══ CARDS mobile ══ --}}
                <div class="mobile-only">
                    @forelse($logs as $log)
                        @php
                            $action = $log->message ?? $log->action ?? '';
                            $name = $log->user->name ?? null;
                            $isSystem = !$name;
                        @endphp
                        <div class="log-card">
                            <div class="log-card-top">
                                <div class="user-dot {{ $isSystem ? 'user-dot-system' : '' }}">
                                    {{ $isSystem ? '⚙' : userInitials($name) }}
                                </div>
                                <div class="log-card-meta">
                                    <div class="log-card-user">{{ $name ?? 'Système / Visiteur' }}</div>
                                    <div class="log-card-date">{{ $log->created_at->format('d/m/Y H:i:s') }}</div>
                                </div>
                                <span class="action-chip {{ chipClass($action) }}">{{ $action }}</span>
                            </div>
                            <div class="log-card-footer">
                                <span class="log-card-ip">
                                    <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="2" y1="12" x2="22" y2="12" />
                                        <path d="M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20" />
                                    </svg>
                                    {{ $log->ip_address ?? '0.0.0.0' }}
                                </span>
                                <span style="font-family:monospace; font-size:9px; color:rgba(0,0,0,0.25);">
                                    {{ $log->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">Aucune action enregistrée pour le moment.</div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if(method_exists($logs, 'links') && $logs->hasPages())
                    <div class="pagination-wrap">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>