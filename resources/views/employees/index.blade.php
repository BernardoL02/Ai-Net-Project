@extends('layouts.admin')

@section('main')

<div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
        <div class="flex justify-center">
            <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
                <h3 class="text-2xl font-semibold"> Screening Session Access Control </h3>
                <p class="mt-1 mb-6 text-sm text-gray-600 dark:text-gray-300">
                    Managing customer access to screening sessions.
                </p>

                <div>
                    <form  action="{{ route('employees.get_tickets_of_screening_session') }}" method="GET" class=" ">
                        @csrf

                        <div class="space-y-2">

                            <x-field.select
                                name="movie"
                                :options="$movies"
                                label="Movie"
                                :required="True"
                                :width="'full'"
                            />
                            <x-field.select
                                name="theater"
                                :options="$theaters"
                                label="Theater"
                                :required="True"
                                :width="'full'"
                            />

                            <div class="mb-4">
                                <label for="date" class="block text-gray-700 font-medium text-sm dark:text-gray-300">Date</label>
                                <input type="date" id="date" name="date" class="shadow-sm w-full px-4 py-2 border rounded appearance-none block mt-1 bg-white dark:bg-gray-900text-black dark:text-gray-50 border-gray-300 dark:border-gray-700" required>
                            </div>

                            <div class="mb-4">
                                <label for="start_time" class="block text-gray-700 font-medium text-sm dark:text-gray-300">Start Time</label>
                                <input type="time" id="start_time" name="start_time" class="shadow-sm w-full px-4 py-2 border rounded appearance-none block mt-1 bg-white dark:bg-gray-900text-black dark:text-gray-50 border-gray-300 dark:border-gray-700" required>
                            </div>

                            <x-button element="submit" text="Search" type="submit" class="mt-5 pt-6"/>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
