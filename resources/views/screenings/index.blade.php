@extends('layouts.admin')

@section('header-title', 'List of Movies')

@section('main')
<div class="flex justify-center">
    <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">

        <form action="{{ route('screenings.index') }}" method="GET" class="pb-6 flex space-x-4">
            <x-field.input label="Movie ID" name="movie_id" :width="'sm'" value="{{ request('movie_id') }}" />
            <x-field.select
                name="date"
                :options="$screeningByDates"
                label="Date"
                :width="'md'"
                value="{{ request('date', '-') }}"
            />

            <x-button element="submit" text="Search" type="dark" class="mt-7"/>
        </form>
        @if (Session::has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative " role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ Session::get('error') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path fill-rule="evenodd" d="M14.35 5.65a.5.5 0 0 1 0 .7L10.71 10l3.64 3.65a.5.5 0 0 1-.7.7L10 10.71l-3.65 3.64a.5.5 0 0 1-.7-.7L9.29 10 5.65 6.35a.5.5 0 0 1 .7-.7L10 9.29l3.65-3.64a.5.5 0 0 1 .7 0z"/>
                </svg>
            </span>
        </div>
        @endif
        <div class="font-base text-sm text-gray-700 dark:text-gray-300 pt-2">
            <x-screenings.table
                :screenings="$screenings"
            />
        </div>
        <div class="mt-4">
            {{ $screenings->links() }}
        </div>
    </div>
</div>
@endsection
