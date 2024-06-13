@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="title" label="Title" :readonly="$readonly"
                        value="{{ old('title', $movie->name) }}"/>
                        <x-field.input name="synopsis" label="Synopsis" :readonly="$readonly"
                        value="{{ old('title', $movie->synopsis) }}"/>

    </div>
    <div class="pb-6">
        <x-field.image
            name="photo_file"
            label="Photo"
            width="md"
            :readonly="$readonly"
            deleteTitle="Delete Photo"
            :deleteAllow="($mode == 'edit') && ($movie->photo_url)"
            deleteForm="form_to_delete_photo"
            imageUrl="$movie->photoFullUrl"/>
    </div>
</div>

