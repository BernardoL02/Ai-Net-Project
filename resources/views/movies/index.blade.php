@extends('layouts.main')

@section('header-title', 'Introduction')

@section('main')
<main>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <h3 class="pb-3 font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight">
                Filmes Disponíveis
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($movies as $movie)
                    @if($movie)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <img src="{{ $movie->poster_full_url }}" alt="{{ $movie->title }}" class="w-full h-auto">
                        <div class="p-4">
                            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $movie->title }}</h4>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</main>
@endsection
