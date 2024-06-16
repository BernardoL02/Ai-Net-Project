<!-- resources/views/movies/show.blade.php -->

@extends('layouts.admin')

@section('header-title', isset($screening) ? 'Screening "' . $screening->id . '"' : 'Screening not found')

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">
                    @can('create', App\Models\Screening::class)
                    <x-button
                        href="{{ route('screenings.create') }}"
                        text="New"
                        type="success"/>
                    @endcan
                    @can('update', $screening)
                    <x-button
                        href="{{ route('screenings.edit', ['screening' => $screening]) }}"
                        text="Edit"
                        type="primary"/>
                    @endcan
                    @can('delete', $screening)
                    <form method="POST" action="{{ route('screenings.destroy', ['screening' => $screening]) }}">
                        @csrf
                        @method('DELETE')
                        <x-button
                            element="submit"
                            text="Delete"
                            type="danger"/>
                    </form>
                    @endcan
                </div>
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Screening "{{ $screening->id }}"
                    </h2>
                </header>
                @include('screenings.shared.fields', ['mode' => 'show'])
            </section>
        </div>
    </div>
</div>
@endsection
