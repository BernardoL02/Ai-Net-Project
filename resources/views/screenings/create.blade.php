@extends('layouts.admin')

@section('header-title', 'New Screening')

@section('main')
<h1 class="text-2xl font-bold mb-4 pl-10">Add New Screening</h1>
    <div class="flex pl-10">
        <form action="{{ route('screenings.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="movie_id" class="block text-gray-700">Movie ID:</label>
                <input type="text" id="movie_id" name="movie_id" class="w-full px-4 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="theater_id" class="block text-gray-700">Theater ID:</label>
                <input type="text" id="theater_id" name="theater_id" class="w-full px-4 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-gray-700">Date:</label>
                <input type="date" id="date" name="date" class="w-full px-4 py-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label for="start_time" class="block text-gray-700">Start Time:</label>
                <input type="time" id="start_time" name="start_time" class="w-full px-4 py-2 border rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
@endsection
