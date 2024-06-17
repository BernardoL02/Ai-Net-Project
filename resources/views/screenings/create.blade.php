@extends('layouts.admin')

@section('header-title', 'New Screening')

@section('main')
@if (Session::has('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded flex mb-4" role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline">{{ Session::get('error') }}</span>
    </div>
@endif
<div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
        <h1 class="text-2xl font-bold mb-4 pl-4">Add New Screening</h1>
            <div class="flex">
                <form action="{{ route('screenings.storeMultiple') }}" method="POST">
                    @csrf
                    <div class="w-80">
                        <div class="mb-4 ">
                            <x-input-label for="movie_id" :value="__('Movie ID')"/>
                            <x-text-input id="movie_id" name="movie_id" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div class="mb-4">
                            <label for="theater_id">Theater ID:</label>
                            <input type="text" id="theater_id" name="theater_id" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="start_date">Start Date:</label>
                            <input type="date" id="start_date" name="start_date" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="end_date">End Date:</label>
                            <input type="date" id="end_date" name="end_date" class="w-full px-4 py-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="start_times">Start Times (comma-separated):</label>
                            <input type="text" id="start_times" name="start_times" class="w-full px-4 py-2 border rounded" placeholder="14:00, 16:00, 18:00" required>
                        </div>
                        <div class="grid grid-cols-2 gap-1">
                            <x-button element="submit" type="dark" text="Save" class="uppercase" />
                            <x-button element="a" type="primary" text="Cancel" class="uppercase" href="{{ url()->full() }}" />
                        </div>
                    </div>
                </form>
            </div>
    </div>
</div>
@endsection
