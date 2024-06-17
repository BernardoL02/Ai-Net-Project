@extends('layouts.admin')

@section('header-title', 'List of Theaters')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <form action="{{ route('theaters.index') }}" method="GET" class="pb-6 flex space-x-4">
                <x-field.input label="Name" name="name" :width="'lg'" value="{{ request('name') }}" />
                <x-button element="submit" text="Search" type="dark" class="mt-7"/>
                <x-button element="a" type="light" text="All Theaters" :href="url()->current()" class="mt-7"/>
            </form>

            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <x-theaters.table :theaters="$theaters"
                    :showView="true"
                    :showEdit="true"
                    :showDelete="true"
                />
                <div class="mt-4">
                    {{ $theaters->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
