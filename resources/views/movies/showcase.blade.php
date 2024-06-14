@extends('layouts.main')

@section('header-title', 'Movie')

@section('main')
    <main>
        <div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
                <div class="mx-auto py-12">
                    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-row">
                            <img class="w-[280px] h-[370px] mr-10 shadow-2xl border-8 border-white"
                                 src="{{ $movie->poster_full_url }}" alt="{{ $movie->title }}">

                            <div class="flex flex-col">
                                <div>
                                    <h3 class="text-3xl font-bold">{{ $movie->title }}</h3>
                                    <p class="text-base">{{ $movie->genre->name }}</p>
                                    <div class="pt-6 space-y-4">
                                        <div>
                                            <h3 class="text-lg font-semibold">Year</h3>
                                            <p>{{ $movie->year }}</p>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold">Synopsis</h3>
                                            <p>{{ $movie->synopsis }}</p>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold">Trailer</h3>
                                            @if($movie?->getTrailerEmbedUrlAttribute())
                                                <div class="relative inline-block">
                                                    <iframe class="rounded-xl w-96 h-64" src="{{ $movie->getTrailerEmbedUrlAttribute() }}" frameborder="0" allowfullscreen></iframe>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" pt-64">
                                <div>
                                    <h3 class="text-lg font-semibold">Pick a date and time</h3>

                                </div>
                                <form id="form_selectDate" method="GET" action="{{ route('movies.showcase', $movie) }}" class="mt-6">
                                    <x-field.select
                                        name="date"
                                        :options="['-' => 'Select Date'] + $screeningByDates"
                                        label="Date"
                                        :width="'md'"
                                        onchange="document.getElementById('form_selectDate').submit();"
                                        value="{{ request('date') }}"
                                    />
                                </form>
                                <form id="form_selectTime" method="GET" action="{{ route('movies.screeningId', $movie) }}" class="mt-6">
                                    <x-field.select
                                        name="time"
                                        :options="['-' => 'Select Time'] + $startTimes"
                                        label="Time"
                                        :width="'md'"
                                        onchange="document.getElementById('form_selectTime').submit();"
                                        value="{{ request('time') }}"
                                    />
                                    <input type="hidden" name="date" value="{{ $filterByDate }}" />
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
