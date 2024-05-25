{{--
    NOTE: we've used the match to define multiple versions of width class,
    to ensure that all specific width related classes are defined statically
    on the source code - this guarantees that the Tailwind builder
    detects the corresponding class.
    If we had used dynamically generated classes (e.g. "w-{{ $width }}") then
    the builder would not detect concrete values.
    Check documentation about dynamic classes:
    https://tailwindcss.com/docs/content-configuration#dynamic-class-names
--}}
@php
    $widthClass = match($width) {
        'full' => 'w-full',
        'xs' => 'w-20',
        'sm' => 'w-32',
        'md' => 'w-64',
        'lg' => 'w-96',
        'xl' => 'w-[48rem]',
        '1/3' => 'w-1/3',
        '2/3' => 'w-2/3',
        '1/4' => 'w-1/4',
        '2/4' => 'w-2/4',
        '3/4' => 'w-3/4',
        '1/5' => 'w-1/5',
        '2/5' => 'w-2/5',
        '3/5' => 'w-3/5',
        '4/5' => 'w-4/5',
    };

    $maxHeightClass = match($width) {
        'full' => 'max-h-full',
        'xs' => 'max-h-32',
        'sm' => 'max-h-48',
        'md' => 'max-h-96',
        'lg' => 'max-h-[36rem]',
        'xl' => 'max-h-[72rem]',
        '1/3', '2/3', '1/4', '2/4', '3/4', '1/5', '2/5', '3/5', '4/5'  => 'max-h-full',
    };
@endphp
<div {{ $attributes }}>
    <div class="flex-col">
        <div class="block font-medium text-sm text-gray-700 dark:text-gray-300 mt-6">
            {{ $label }}
        </div>
        <img class="{{$widthClass}} {{$maxHeightClass}} aspect-auto border-4 border-white shadow-2xl "
             src="{{ $imageUrl }}">
        @if(!$readonly)
        <div class="{{$widthClass}} flex-col space-y-4 items-stretch mt-4">
            <div>
                <div class="flex flex-row items-center">
                    <input id="id_{{ $name }}" name="{{ $name }}" type="file" form="{{ $submitForm }}"
                        accept="image/png, image/jpeg"
                        onchange="document.getElementById('id_{{ $name }}_selected_file').innerHTML= document.getElementById('id_{{ $name }}').files[0].name ?? ''"
                        class="hidden"/>
                        <label for="id_{{ $name }}"
                            class="
                            inline-flex items-leeft px-4 py-2 bg-gray-800 dark:bg-gray-200 border
                            border-transparent rounded-md font-semibold text-sm text-white
                            dark:text-gray-800 tracking-widest hover:bg-gray-700 dark:hover:bg-white
                            focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300
                            focus:outline-none focus:ring-2 focus:ring-indigo-500
                            focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                        >Choose File</label>
                        <label id="id_{{ $name }}_selected_file"
                            class="text-xs pl-4 text-gray-500 truncate max-w-32"></label>
                </div>
                @error( $name )
                    <div class="text-sm text-red-500">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @if($deleteAllow)
            <div>
                <x-button
                    element="submit"
                    :text="$deleteTitle"
                    type="primary"
                    form="{{ $deleteForm }}"
                    onclick="document.getElementById('{{ $deleteForm }}').submit();"
                    />
            </div>
            @endif
        </div>
        @endif
    </div>
</div>

