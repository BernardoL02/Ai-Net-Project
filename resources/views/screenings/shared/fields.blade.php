@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="movie_id" label="Movie Id" type="movie_id" :readonly="$readonly" value="{{ old('movie_id', $screening->movie_id) }}"/>
        <x-field.input name="theater_id" label="Theater Id" type="theater_id" :readonly="$readonly" value="{{ old('theater_id', $screening->theater_id) }}"/>
        <x-field.input name="date" label="Date" type="date" :readonly="$readonly" value="{{ old('date', $screening->date) }}"/>
        <x-field.input name="start_time" label="Start Time" type="time" :readonly="$readonly" value="{{ old('start_time', $screening->start_time) }}"/>
    </div>
    <div class="mb-4">
        <label for="poster_filename" class="block text-gray-700 font-bold mb-2 text-center">Poster</label>
        @php
        $defaultPosterUrl = asset('img/default_poster.png');
        $posterUrl = $screening->poster_filename ? asset('storage/posters/' . $screening->poster_filename) : $defaultPosterUrl;
        @endphp
        <img src="{{ $posterUrl }}" alt="Poster" class="mb-2 w-64 h-96 object-cover mx-auto">
    </div>
</div>
