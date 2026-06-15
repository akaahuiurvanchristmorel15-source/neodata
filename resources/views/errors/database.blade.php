<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infrastructure Isolée — YA Consulting</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-full flex items-center justify-center p-4 antialiased selection:bg-rose-500/30">

    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-rose-500/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
    </div>

    <div
        class="relative max-w-md w-full bg-slate-900/60 backdrop-blur-xl border border-slate-800/80 p-8 rounded-3xl shadow-2xl text-center space-y-6">

        <div
            class="mx-auto h-16 w-16 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center animate-pulse">
            <svg class="h-8 w-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M4 7v10c0 2.21 3.58 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.58 4 8 4s8-1.79 8-4M4 7c0-2.21 3.58-4 8-4s8 1.79 8 4m0 5c0 2.21-3.58 4-8 4s-8-1.79-8-4" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12l12 4"
                    class="stroke-rose-600" />
            </svg>
        </div>

        <div class="space-y-2">
            <h1 class="text-[15px] font-bold text-slate-100 tracking-tight">
                Liaison Base de Données Interrompue
            </h1>
            <p class="text-[15px] text-slate-400 leading-relaxed max-w-xs mx-auto">
                Le serveur de stockage des données est inaccessible ou temporairement hors ligne pour maintenance.
            </p>
        </div>

        <div
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-950 border border-slate-800 font-mono text-[15px] uppercase tracking-wider text-slate-500">
            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
            Status: Database_Offline (500)
        </div>

        <hr class="border-slate-800/60">

        <div class="space-y-4">
            <p class="text-[15px] text-slate-500 italic">
                Le système a été isolé automatiquement pour préserver l'intégrité des structures d'archivage.
            </p>

            <button onclick="window.location.reload()"
                class="w-full py-2.5 px-4 rounded-xl text-[15px] font-bold text-slate-200 bg-slate-800 hover:bg-slate-750 border border-slate-700/60 transition active:scale-[0.98]">
                Relancer la tentative de connexion
            </button>
        </div>

    </div>

</body>

</html>