@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="title" label="Title" :readonly="$readonly" value="{{ old('title', $movie->title) }}"/>
        <x-field.input name="genre_code" label="Genre Code" :readonly="$readonly" value="{{ old('genre_code', $movie->genre_code) }}"/>
        <x-field.input name="year" label="Year" :readonly="$readonly" value="{{ old('year', $movie->year) }}"/>
        <x-field.input name="synopsis" label="Synopsis" :readonly="$readonly" value="{{ old('synopsis', $movie->synopsis) }}"/>
    </div>
    <div class="pb-6">
        <x-field.image
            name="Foto"
            width="md"
            :readonly="$readonly"
            deleteTitle="Delete Photo"
            :deleteAllow="($mode == 'edit') && ($movie->posterFullUrl)"
            deleteForm="form_to_delete_photo"
            :imageUrl="$movie->posterFullUrl"/>

    </div>
</div>

