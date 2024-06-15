@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="title" label="Title" :readonly="$readonly" value="{{ old('title', $movie->title) }}"/>
            <x-field.select
                name="genre"
                :options="$arrayGenresCode"
                label="Genre"
                :readonly="$readonly"
                :width="'md'"
                value="{{ old('genre', $movie->genre) }}"
            />
        <x-field.input name="year" label="Year" :readonly="$readonly" value="{{ old('year', $movie->year) }}"/>
        <x-field.input name="trailer_url" label="Trailer Url" :readonly="$readonly" value="{{ old('trailer_url', $movie->trailer_url) }}"/>
        <x-field.text-area name="synopsis" label="Synopsis" width="full" height="lg" :readonly="$readonly" value="{{ old('synopsis', $movie->synopsis) }}"/>
    </div>

    <div class="mb-4">
        <label for="poster_filename" class="block text-gray-700 font-bold mb-2 text-center">Poster</label>
        @php
            $defaultPosterUrl = asset('img/default_poster.png');
            $posterUrl = $movie->poster_filename ? asset('storage/posters/' . $movie->poster_filename) : $defaultPosterUrl;
        @endphp
        <img src="{{ $posterUrl }}" alt="Poster" class="mb-2 w-64 h-96 object-cover mx-auto">
        @if (!$readonly)
            <input type="file" name="poster_filename" id="poster_filename" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-800 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300"/>
            @if($movie->poster_filename)
                <button type="button" class="px-4 py-2 mt-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:bg-red-800" onclick="document.getElementById('form_to_delete_poster').submit();">Delete Poster</button>
            @endif
        @endif
    </div>
</div>

