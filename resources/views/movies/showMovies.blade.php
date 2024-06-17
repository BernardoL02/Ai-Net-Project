@extends('layouts.admin')

@section('header-title', 'List of Movies')

@section('main')

    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
                    <form action="{{ route('movies.showMovies') }}" method="GET" class="pb-6 flex space-x-4">
                        <x-field.input label="Title" name="title" :width="'lg'" value="{{ request('title') }}" />
                        <x-field.select
                            name="genre"
                            :options="$arrayGenresCode"
                            label="Genre"
                            :width="'md'"
                            value="{{ request('genre') }}"
                        />
                        <x-button element="submit" text="Search" type="dark" class="mt-7"/>
                        <x-button element="a" type="light" text="All Movies" :href="url()->current()" class="mt-7"/>
                    </form>
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <x-movies.table
                :movies="$movies"
                />
            </div>
            <div class="mt-4">
                {{ $movies->links() }}
            </div>
        </div>
    </div>
@endsection
