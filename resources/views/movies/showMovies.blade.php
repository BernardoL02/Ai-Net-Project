@extends('layouts.admin')

@section('header-title', 'List of Movies')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
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
