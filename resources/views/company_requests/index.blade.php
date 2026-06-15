@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-x-hidden overflow-y-auto">
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-10">

        <div class="mb-6">
            <h1 class="text-2xl sm:text-3xl font-bold">Demandes d'accès entreprises</h1>
        </div>

        <x-flash-messages />

        {{-- ── TABLEAU (desktop ≥ md) ── --}}
        <div class="hidden md:block overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Entreprise</th>
                        <th class="px-4 py-3">Contact</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3">Demandé le</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($requests as $request)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">
                                {{ $request->company_name }}
                                <br><span class="text-xs text-slate-500">{{ $request->company_email }}</span>
                            </td>
                            <td class="px-4 py-3">
                                {{ $request->contact_name }}
                                <br><span class="text-xs text-slate-500">{{ $request->contact_email }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wide
                                    {{ $request->status === 'approved' ? 'bg-green-100 text-green-700' :
                                       ($request->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ $request->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $request->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <a href="{{ route('company-requests.show', $request) }}"
                                       class="text-[#ff1a40] hover:underline font-medium">Voir</a>

                                    @if($request->status === 'approved' && $request->company)
                                        <form method="POST" action="{{ route('companies.toggle-status', $request->company) }}" class="inline">
                                            @csrf
                                            @if($request->company->is_active)
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 font-semibold hover:underline bg-transparent border-none p-0 cursor-pointer text-xs"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir suspendre cette entreprise ?')">
                                                    Suspendre
                                                </button>
                                            @else
                                                <button type="submit"
                                                    class="text-green-600 hover:text-green-800 font-semibold hover:underline bg-transparent border-none p-0 cursor-pointer text-xs">
                                                    Réactiver
                                                </button>
                                            @endif
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">Aucune demande en attente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── CARTES (mobile < md) ── --}}
        <div class="flex flex-col gap-3 md:hidden">
            @forelse($requests as $request)
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm p-4">
                    <div class="flex items-start justify-between gap-2 mb-3">
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">{{ $request->company_name }}</p>
                            <p class="text-xs text-slate-500">{{ $request->company_email }}</p>
                        </div>
                        <span class="shrink-0 inline-block px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wide
                            {{ $request->status === 'approved' ? 'bg-green-100 text-green-700' :
                               ($request->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ $request->status }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <p class="text-xs text-slate-400 uppercase tracking-wide mb-0.5">Contact</p>
                        <p class="text-sm text-slate-700">{{ $request->contact_name }}</p>
                        <p class="text-xs text-slate-500">{{ $request->contact_email }}</p>
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                        <span class="text-xs text-slate-400">{{ $request->created_at->format('d/m/Y H:i') }}</span>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('company-requests.show', $request) }}"
                               class="text-[#ff1a40] hover:underline font-medium text-sm">Voir</a>

                            @if($request->status === 'approved' && $request->company)
                                <form method="POST" action="{{ route('companies.toggle-status', $request->company) }}" class="inline">
                                    @csrf
                                    @if($request->company->is_active)
                                        <button type="submit"
                                            class="text-red-500 font-semibold text-xs bg-transparent border-none p-0 cursor-pointer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir suspendre cette entreprise ?')">
                                            Suspendre
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="text-green-600 font-semibold text-xs bg-transparent border-none p-0 cursor-pointer">
                                            Réactiver
                                        </button>
                                    @endif
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-slate-200 bg-white p-6 text-center text-slate-500 text-sm">
                    Aucune demande en attente.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    </div>
</main>
@endsection