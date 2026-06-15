<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-[#ff1a40] tracking-tight neodata-title">
                    Gestion des Comptes
                </h1>
                <p class="text-xs text-black mt-1 font-light">
                    Droits d'accès et sécurité système — NEODATA.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex h-2 w-2 rounded-full bg-[#ff1a40] animate-pulse"></span>
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

        /* ── Buttons ── */
        .btn-new {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 9px 18px;
            border-radius: 12px;
            background: linear-gradient(135deg, #ff1a40, #91041b);
            color: #fff;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: opacity 0.2s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 18px rgba(255, 26, 64, 0.3);
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-new:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 6px 24px rgba(255, 26, 64, 0.45);
        }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px;
            border-radius: 14px;
            font-size: 12px;
            font-family: 'Outfit', sans-serif;
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

        /* ── Avatar ── */
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #ff1a40 0%, #91041b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 15px;
            color: #fff;
            letter-spacing: 0.05em;
            flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(255, 26, 64, 0.25);
        }

        /* ── Role badges ── */
        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 999px;
            font-family: 'Outfit', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .role-admin {
            background: rgba(255, 26, 64, 0.08);
            border: 1px solid rgba(255, 26, 64, 0.3);
            color: #ff1a40;
        }

        .role-direction {
            background: rgba(168, 85, 247, 0.08);
            border: 1px solid rgba(168, 85, 247, 0.3);
            color: #a855f7;
        }

        .role-employe {
            background: rgba(59, 130, 246, 0.08);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #3b82f6;
        }

        /* ── Revoke / session ── */
        .btn-revoke {
            font-family: 'Outfit', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(255, 26, 64, 0.06);
            border: 1px solid rgba(255, 26, 64, 0.25);
            color: #ff1a40;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s, transform 0.2s;
            white-space: nowrap;
        }

        .btn-revoke:hover {
            background: rgba(255, 26, 64, 0.14);
            border-color: rgba(255, 26, 64, 0.5);
            transform: translateY(-1px);
        }

        .btn-edit {
            font-family: 'Outfit', sans-serif;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 6px 12px;
            border-radius: 8px;
            background: rgba(59, 130, 246, 0.06);
            border: 1px solid rgba(59, 130, 246, 0.25);
            color: #3b82f6;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s, border-color 0.2s, transform 0.2s;
            white-space: nowrap;
            display: inline-block;
            margin-right: 6px;
        }

        .btn-edit:hover {
            background: rgba(59, 130, 246, 0.14);
            border-color: rgba(59, 130, 246, 0.5);
            transform: translateY(-1px);
        }

        .session-label {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: rgba(0, 0, 0, 0.3);
            letter-spacing: 0.08em;
            font-style: italic;
        }

        /* ══════════════ TABLE — desktop ══════════════ */
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

        .neo-table thead th:last-child {
            text-align: right;
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

        /* ══════════════ CARDS — mobile ══════════════ */
        .user-card {
            background: #fff;
            border: 1px solid rgba(179, 179, 179, 0.55);
            border-radius: 16px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            animation: fadeSlideUp 0.4s ease both;
            transition: border-color 0.25s, box-shadow 0.25s;
        }

        .user-card:hover {
            border-color: rgba(255, 26, 64, 0.25);
            box-shadow: 0 4px 20px rgba(255, 26, 64, 0.06);
        }

        .user-card-top {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-card-info {
            flex: 1;
            min-width: 0;
        }

        .user-card-name {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: #000;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .user-card-email {
            font-family: monospace;
            font-size: 10px;
            color: rgba(0, 0, 0, 0.45);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-top: 2px;
        }

        .user-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 10px;
            border-top: 1px solid rgba(179, 179, 179, 0.3);
            flex-wrap: wrap;
            gap: 8px;
        }

        .user-card-date {
            font-family: monospace;
            font-size: 12px;
            color: rgba(0, 0, 0, 0.35);
        }

        /* ── Pagination ── */
        .pagination-wrap {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: flex-end;
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

            .btn-new {
                font-size: 9px;
                padding: 8px 12px;
            }

            .header-bar {
                flex-wrap: wrap;
                gap: 10px;
            }
        }

        /* ── Animation ── */
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
        <div class="dash-content space-y-5">

            {{-- ── Header bar ── --}}
            <div class="header-bar flex items-center justify-between">
                <div>
                    <p class="panel-title" style="font-size:13px;">Comptes &amp; Droits d'Accès</p>
                </div>
                <a href="{{ route('users.create') }}" class="btn-new">
                    <span style="font-size:14px; line-height:1;">+</span>
                    Ajouter un utilisateur
                </a>
            </div>

            {{-- ── Alerts ── --}}
            <x-flash-messages />

            {{-- ── Panel ── --}}
            <div class="panel">
                <div class="px-6 pt-5 pb-4 border-b border-[rgba(179,179,179,0.35)]">
                    <p class="panel-title">Registre des Utilisateurs</p>
                    <p class="panel-sub">Liste des comptes actifs et leurs niveaux de privilèges.</p>
                </div>

                {{-- ══ TABLE desktop ══ --}}
                <div class="overflow-x-auto desktop-only">
                    <table class="neo-table">
                        <thead>
                            <tr>
                                <th>Nom Complet</th>
                                <th>Adresse Email</th>
                                <th>Rôle Système</th>
                                <th>Création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                            <span
                                                style="font-family:'Outfit',sans-serif; font-size:13px; font-weight:600; color:#000;">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            style="font-family:monospace; font-size:11px; color:rgba(0,0,0,0.55);">{{ $user->email }}</span>
                                    </td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="role-badge role-admin"><i class="bx bx-shield"></i>Administrateur</span>
                                        @elseif($user->role === 'direction')
                                            <span class="role-badge role-direction">💼 Direction</span>
                                        @else
                                            <span class="role-badge role-employe">👥 Employé</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            style="font-family:monospace; font-size:11px; color:rgba(0,0,0,0.4);">{{ $user->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td style="text-align:right; white-space: nowrap;">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">Modifier</a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                onsubmit="return confirm('Voulez-vous vraiment révoquer l\'accès de ce compte ?');"
                                                style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-revoke">Révoquer l'accès</button>
                                            </form>
                                        @else
                                            <span class="session-label">Session active</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ══ CARDS mobile ══ --}}
                <div class="mobile-only">
                    @foreach($users as $user)
                        <div class="user-card">
                            {{-- Top : avatar + nom + email --}}
                            <div class="user-card-top">
                                <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                                <div class="user-card-info">
                                    <div class="user-card-name">{{ $user->name }}</div>
                                    <div class="user-card-email">{{ $user->email }}</div>
                                </div>
                                {{-- Badge rôle aligné à droite --}}
                                @if($user->role === 'admin')
                                    <span class="role-badge role-admin"><i class="bx bx-shield"></i>Administrateur</span>
                                @elseif($user->role === 'direction')
                                    <span class="role-badge role-direction"><i class="bx bx-briefcase"></i> Direction</span>
                                @else
                                    <span class="role-badge role-employe"><i class="bx bx-user"></i> Employé</span>
                                @endif
                            </div>

                            <div class="user-card-footer">
                                <span class="user-card-date">Créé le {{ $user->created_at->format('d/m/Y') }}</span>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn-edit">Modifier</a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment révoquer l\'accès de ce compte ?');"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-revoke">Révoquer l'accès</button>
                                        </form>
                                    @else
                                        <span class="session-label">Session active</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pagination-wrap">
                    {{ $users->links() }}
                </div>
            </div>

            @isset($create)
                <div class="panel p-6 mt-4">{{ $create }}</div>
            @endisset

        </div>
    </div>
</x-app-layout>