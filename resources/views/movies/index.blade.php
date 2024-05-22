@extends('layouts.main')

@section('header-title', 'Introduction')

@section('main')
<main>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">

            <h1 class="pb-3 text-4xl font-semibold  text-gray-800 dark:text-gray-200 leading-tight">
                Filmes Disponíveis
            </h1>
            <form action="{{ route('movies.search') }}" method="GET" class="pb-6 flex space-x-4">
                <input type="text" name="query" placeholder="Search movies..." class="px-4 py-2 border rounded-md w-full bg-gray-100 dark:bg-gray-700 dark:text-white" value="{{ request('query') }}">
                <select name="genre" class="px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 dark:text-white">
                    <option value="">All Genres</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->code }}" {{ request('genre') == $genre->code ? 'selected' : '' }}>{{ $genre->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Search</button>
            </form>


            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6  ">
                @foreach ($moviesByScreening as $movie)

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:rotate-2 hover:scale-105">
                        <img src="{{ $movie->poster_full_url }}" alt="{{ $movie->title }}">
                        <div class="pb-0 p-2">
                            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200 ">{{ $movie->title }}</h4>
                            <p class="text-gray-600 dark:text-gray-400">{{ $movie->genre->name }}</p>
                        </div>
                    </div>

                @endforeach
            </div>

        </div>
    </div>
</main>
@endsection

