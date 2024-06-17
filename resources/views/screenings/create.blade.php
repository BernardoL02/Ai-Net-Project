@extends('layouts.admin')

@section('header-title', 'New Screening')

@section('main')
<h1 class="text-2xl font-bold mb-4 pl-10">Add New Screening</h1>
    <div class="flex pl-10">
        <form action="{{ route('screenings.storeMultiple') }}" method="POST">
            @csrf
            @if (Session::has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ Session::get('error') }}</span>
                </div>
            @endif
            <div class="mb-4">
                <label for="movie_id" class="block text-gray-700">Movie ID:</label>
                <input type="text" id="movie_id" name="movie_id" class="w-full px-4 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="theater_id" class="block text-gray-700">Theater ID:</label>
                <input type="text" id="theater_id" name="theater_id" class="w-full px-4 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="start_date" class="block text-gray-700">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="w-full px-4 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="end_date" class="block text-gray-700">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="w-full px-4 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="start_times" class="block text-gray-700">Start Times (comma-separated):</label>
                <input type="text" id="start_times" name="start_times" class="w-full px-4 py-2 border rounded" placeholder="14:00, 16:00, 18:00" required>
            </div>
            <div class="grid grid-cols-2 gap-1">
                <x-button element="submit" type="dark" text="Save" class="uppercase" />
                <x-button element="a" type="primary" text="Cancel" class="uppercase" href="{{ url()->full() }}" />
            </div>
        </form>
    </div>
@endsection
