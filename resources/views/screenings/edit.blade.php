@extends('layouts.admin')

@section('header-title', 'Screening "' . $screening->id . '"')

@section('main')
<div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
        <h1 class="text-2xl font-bold mb-4 pl-10">Edit Screening</h1>
            <div class="container flex pt-15 ">
                <form action="{{ route('screenings.update', $screening->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="movie_id" class="block text-gray-700">Movie ID:</label>
                        <input type="text" id="movie_id" name="movie_id" value="{{ $screening->movie_id }}" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="theater_id" class="block text-gray-700">Theater ID:</label>
                        <input type="text" id="theater_id" name="theater_id" value="{{ $screening->theater_id }}" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="date" class="block text-gray-700">Date:</label>
                        <input type="date" id="date" name="date" value="{{ $screening->date }}" class="w-full px-4 py-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="start_time" class="block text-gray-700">Start Time:</label>
                        <input type="time" id="start_time" name="start_time" value="{{ $screening->start_time }}" class="w-full px-4 py-2 border rounded" required>

                    </div>
                    <div class="grid grid-cols-2 gap-1">
                        <x-button element="submit" type="dark" text="Save" class="uppercase" />
                        <x-button element="a" type="primary" text="Cancel" class="uppercase" href="{{ url()->full() }}" />
                    </div>
                </form>
            </div>
    </div>
</div>

@endsection
