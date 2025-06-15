@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="bg-gradient-to-r from-gray-900 via-black to-gray-900 relative rounded-lg mb-6 p-6">
        <div class="hex-pattern absolute inset-0 opacity-5 rounded-lg"></div>
        <div class="flex items-center justify-between relative">
            <h1 class="text-2xl font-bold text-white">Détails de l'Article</h1>
            <div class="flex gap-2">
                <a href="{{ route('articles.edit', $article) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span>Modifier</span>
                </a>
                <a href="{{ route('articles.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Retour</span>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-gray-800 rounded-lg p-6 border border-purple-500/10 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-white">{{ $article->titre }}</h2>
                <div class="flex items-center gap-4 mt-2">
                    <span class="text-gray-400 text-sm">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $article->created_at->format('d/m/Y') }}
                    </span>
                    @if($article->categorie)
                    <span class="text-gray-400 text-sm">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        {{ $article->categorie }}
                    </span>
                    @endif
                    <span class="px-2 py-1 text-xs font-medium rounded
                        @if($article->statut === 'publié') bg-green-500/20 text-green-400
                        @else bg-yellow-500/20 text-yellow-400
                        @endif">
                        {{ ucfirst($article->statut) }}
                    </span>
                </div>
            </div>
            <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Supprimer</span>
                </button>
            </form>
        </div>

        <div class="border-t border-gray-700 pt-6">
            <div class="prose prose-invert max-w-none">
                {!! nl2br(e($article->contenu)) !!}
            </div>
        </div>
        
        @if($article->tags)
        <div class="mt-6 pt-6 border-t border-gray-700">
            <h3 class="text-lg font-medium text-white mb-2">Tags</h3>
            <div class="flex flex-wrap gap-2">
                @foreach(explode(',', $article->tags) as $tag)
                <span class="bg-gray-700 text-gray-300 px-3 py-1 rounded-full text-sm">{{ trim($tag) }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
