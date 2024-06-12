@extends('layouts.main')

@section('header-title', 'List of Tickets bought')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <x-tickets.table :tickets="$tickets"
                    :showDepartment="true"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true"
                    />
            </div>
        </div>
    </div>
@endsection
