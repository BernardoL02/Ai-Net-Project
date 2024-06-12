@extends('layouts.admin')

@section('header-title', $genre->name)

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section class="grid grid-cols-2 p-4 gap-4">
                <div class="flex flex-col">
                    <div>
                        <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">
                            Genre "{{ $genre->name }}"
                        </h2>
                    </div>
                    <div class="mt-6 space-y-4">
                        @include('genres.shared.fields', ['mode' => 'show'])
                    </div>
                    @can('viewAny', App\Models\Movie::class)
                        <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                            Movies
                        </h3>
                    @endcan
                </div>

                <div class="flex justify-end items-start gap-4 mb-4">
                    @can('create', App\Models\genre::class)
                    <x-button
                        href="{{ route('genres.create') }}"
                        text="New"
                        type="success"/>
                    @endcan
                    @can('update', $genre)
                    <x-button
                        href="{{ route('genres.edit', ['genre' => $genre]) }}"
                        text="Edit"
                        type="info"/>
                    @endcan
                    @can('delete', $genre)
                    <form method="POST" action="{{ route('genres.destroy', ['genre' => $genre]) }}">
                        @csrf
                        @method('DELETE')
                        <x-button
                            element="submit"
                            text="Delete"
                            type="danger"/>
                    </form>
                    @endcan
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
