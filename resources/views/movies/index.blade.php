@extends('layouts.main')

@section('header-title', 'Introduction')

@section('main')
<main>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <h1 class="pb-3 text-4xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                Filmes Disponíveis
            </h1>
            <div> <!-- Terminar-->
                <form  action="{{ route('movies.index') }}" method="GET" class="pb-6 flex space-x-4">
                    <x-field.input label="Title" name="title" :width="'lg'" value="{{ request('title') }}"  />
                    <x-field.input label="Synopsis" name="synopsis" :width="'lg'" value="{{ request('synopsis') }}" />
                    <x-field.select
                        name="genre"
                        :options="$arrayGenresCode"
                        label="Genre"
                        :width="'md'"
                    />

                    <x-field.select
                        name="date"
                        :options="$screeningByDates"
                        label="Date"
                        :width="'md'"
                    />
                    <x-button element="submit" text="Search" type="primary" class="mt-7"/>
                </form>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($moviesByScreening as $movie)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:rotate-2 hover:scale-105">
                        <img src="{{ $movie->poster_full_url }}" alt="{{ $movie->title }}">
                        <div class="pb-0 p-2">
                            <h4 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $movie->title }}</h4>
                            <p class="text-gray-600 dark:text-gray-400">{{ $movie->genre->name }}</p>
                        </div>
                        @foreach($movie->screeningsDate($filterByDate) as $screening)
                            <div>
                                <a href="{{route('screenings.showcase',['screening'=>$screening])}}">
                                    {{$screening->date}} {{$screening->start_time}}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>
@endsection
