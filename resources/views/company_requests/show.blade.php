@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-x-hidden overflow-y-auto">
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-10">

        {{-- En-tête --}}
        <div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold">Demande d'accès</h1>
                <p class="text-sm text-slate-500">
                    {{ $companyAccessRequest->company_name }} — {{ $companyAccessRequest->contact_name }}
                </p>
            </div>
            <a href="{{ route('company-requests.index') }}"
               class="text-[#ff1a40] hover:underline text-sm sm:mt-1 self-start">
                ← Retour
            </a>
        </div>

        {{-- Grille infos --}}
        <div class="grid gap-4 sm:gap-6 grid-cols-1 md:grid-cols-2 mb-4 sm:mb-6">

            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
                <h2 class="mb-4 text-lg sm:text-xl font-semibold">Informations entreprise</h2>
                <dl class="space-y-2 text-sm">
                    <div class="flex flex-wrap gap-x-2">
                        <dt class="font-semibold text-slate-600">Nom :</dt>
                        <dd class="text-slate-800">{{ $companyAccessRequest->company_name }}</dd>
                    </div>
                    <div class="flex flex-wrap gap-x-2">
                        <dt class="font-semibold text-slate-600">Email :</dt>
                        <dd class="text-slate-800 break-all">{{ $companyAccessRequest->company_email }}</dd>
                    </div>
                    <div class="flex flex-wrap gap-x-2">
                        <dt class="font-semibold text-slate-600">Téléphone :</dt>
                        <dd class="text-slate-800">{{ $companyAccessRequest->telephone ?? '—' }}</dd>
                    </div>
                    <div class="flex flex-wrap gap-x-2">
                        <dt class="font-semibold text-slate-600">Adresse :</dt>
                        <dd class="text-slate-800">{{ $companyAccessRequest->adresse ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
                <h2 class="mb-4 text-lg sm:text-xl font-semibold">Contact</h2>
                <dl class="space-y-2 text-sm mb-4">
                    <div class="flex flex-wrap gap-x-2">
                        <dt class="font-semibold text-slate-600">Nom :</dt>
                        <dd class="text-slate-800">{{ $companyAccessRequest->contact_name }}</dd>
                    </div>
                    <div class="flex flex-wrap gap-x-2">
                        <dt class="font-semibold text-slate-600">Email :</dt>
                        <dd class="text-slate-800 break-all">{{ $companyAccessRequest->contact_email }}</dd>
                    </div>
                </dl>
                <p class="text-sm font-semibold text-slate-600 mb-1">Motif :</p>
                <p class="whitespace-pre-line text-sm text-slate-700 leading-relaxed">
                    {{ $companyAccessRequest->reason ?? 'Aucun motif renseigné.' }}
                </p>
            </div>
        </div>

        {{-- Statut & actions --}}
        <div class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Statut actuel</p>
            <span class="inline-block mb-4 px-2.5 py-0.5 rounded text-xs font-semibold uppercase tracking-wide
                {{ $companyAccessRequest->status === 'approved' ? 'bg-green-100 text-green-700' :
                   ($companyAccessRequest->status === 'rejected'  ? 'bg-red-100 text-red-700'   : 'bg-amber-100 text-amber-700') }}">
                {{ $companyAccessRequest->status }}
            </span>

            @if($companyAccessRequest->status === 'pending')
                <div class="flex flex-col gap-3 sm:flex-row">
                    <form method="POST"
                          action="{{ route('company-requests.approve', $companyAccessRequest) }}"
                          class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-xl bg-green-600 px-5 py-3 text-white font-medium text-sm hover:bg-green-700 transition-colors cursor-pointer">
                            Approuver
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('company-requests.reject', $companyAccessRequest) }}"
                          class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-xl bg-red-600 px-5 py-3 text-white font-medium text-sm hover:bg-red-700 transition-colors cursor-pointer">
                            Rejeter
                        </button>
                    </form>
                </div>
            @else
                <p class="text-sm text-slate-500">
                    Traité le {{ $companyAccessRequest->approved_at?->format('d/m/Y H:i') }}
                    par {{ $companyAccessRequest->approver?->name ?? 'Administrateur' }}.
                </p>
            @endif
        </div>

    </div>
</main>
@endsection