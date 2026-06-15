@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gray-950 min-h-screen text-gray-100">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('documents.index') }}"
                    class="text-sm font-semibold text-gray-400 hover:text-emerald-400 transition flex items-center gap-2">
                    &larr; Retour aux archives
                </a>
            </div>

            <div class="bg-gray-900 border border-gray-800 shadow-2xl rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-800 bg-gray-900/50">
                    <h1 class="text-2xl font-bold text-white">Modifier les métadonnées</h1>
                    <p class="text-sm text-gray-400 mt-1">Auteur du dépôt original : <span
                            class="text-gray-200 font-medium">{{ $document->auteur }}</span> &bull; Archivé le : <span
                            class="font-mono text-xs text-gray-400">{{ $document->created_at->format('d/m/Y H:i') }}</span>
                    </p>
                </div>

                <form action="{{ route('documents.update', $document->id) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="titre" class="block text-sm font-semibold text-gray-300">Titre de l'archive *</label>
                        <input type="text" name="titre" id="titre" required value="{{ old('titre', $document->titre) }}"
                            class="mt-2 block w-full rounded-xl bg-gray-950 border-gray-800 text-gray-200 focus:border-emerald-500 focus:ring-emerald-500/20 text-sm py-3 px-4">
                        @error('titre') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-300">Dossier / Catégorie de
                            classement *</label>
                        <select name="category_id" id="category_id" required
                            class="mt-2 block w-full rounded-xl bg-gray-950 border-gray-800 text-gray-200 focus:border-emerald-500 focus:ring-emerald-500/20 text-sm py-3 px-4">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $document->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="mots_cles" class="block text-sm font-semibold text-gray-300">Mots-clés / Tags</label>
                        <input type="text" name="mots_cles" id="mots_cles"
                            value="{{ old('mots_cles', $document->mots_cles) }}"
                            class="mt-2 block w-full rounded-xl bg-gray-950 border-gray-800 text-gray-200 focus:border-emerald-500 focus:ring-emerald-500/20 text-sm py-3 px-4"
                            placeholder="Ex: compta, facture, 2026">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-300">Description / Résumé du
                            contenu</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-2 block w-full rounded-xl bg-gray-950 border-gray-800 text-gray-200 focus:border-emerald-500 focus:ring-emerald-500/20 text-sm p-4">{{ old('description', $document->description) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-4 pt-4 border-t border-gray-800">
                        <a href="{{ route('documents.index') }}"
                            class="px-5 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-xl text-sm font-medium transition">
                            Annuler
                        </a>
                        <button type="submit" id="editDocSubmitBtn"
                            class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:opacity-75 disabled:cursor-not-allowed text-white rounded-xl text-sm font-semibold shadow-lg transition inline-flex items-center gap-2">
                            <span class="btn-spinner hidden w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            <span class="btn-label">Enregistrer les modifications</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action*="documents.update"]');
            if (form) {
                form.addEventListener('submit', function() {
                    if (!form.checkValidity()) return;
                    const btn = document.getElementById('editDocSubmitBtn');
                    const label = btn.querySelector('.btn-label');
                    const spinner = btn.querySelector('.btn-spinner');
                    
                    btn.disabled = true;
                    spinner.classList.remove('hidden');
                    label.textContent = 'Enregistrement en cours…';
                });
            }
        });
    </script>
@endsection