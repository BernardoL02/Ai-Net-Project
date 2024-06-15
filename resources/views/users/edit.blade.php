@extends('layouts.admin')

@php
    if($mode == 'show'){
        $readonly = true;
    }
    else{
        $readonly = false;
    }
@endphp

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <header>
                    @if ($readonly)
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            View user "{{ $user?->name }}"
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300  mb-6">
                            See his information below.
                        </p>
                    @else
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Edit user "{{ $user?->name }}"
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300  mb-6">
                            Click on "Save" button to store the information.
                        </p>
                    @endif
                </header>

                <div class="grid grid-cols-2 items-left justify-start">
                    <div>
                        <form method="POST" action="{{ route('users.update', ['user' => $user]) }}">
                            @csrf
                            @method('PUT')

                            <div>
                                <div>
                                    <div class="space-y-6 mb-10">
                                        <x-field.input label="Name" name="name" :width="'lg'" value="{{ $user?->name }}" :readonly="$readonly" />
                                        <x-field.input label="Email" name="email" :width="'lg'" value="{{ $user?->email }}" :readonly="$readonly" />
                                        <x-field.input label="New Password" name="password" :width="'lg'" value=""  class="{{ $readonly ? 'hidden' : '' }}"/>

                                        <x-field.radiogroup
                                            name="type"
                                            :value="(string)$user?->type"
                                            label="Type"
                                            :options="['A' => 'Admin', 'E' => 'Employee']"
                                            :readonly="$readonly"
                                        />
                                    </div>

                                    <div class="flex items-center pt-9">
                                        <x-button element="a" type="light" text="Cancel" class="mr-5 {{ $readonly ? 'hidden' : '' }}" href="{{ url()->full() }}"/>

                                        <x-primary-button class="{{ $readonly ? 'hidden' : '' }}">{{ __('Save') }}</x-primary-button>

                                        @if (session('status') === 'profile-updated')
                                            <p
                                                x-data="{ show: true }"
                                                x-show="show"
                                                x-transition
                                                x-init="setTimeout(() => show = false, 2000)"
                                                class="text-sm text-green-600 dark:text-green-400 ml-4"
                                            >{{ __('Saved.') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="">
                        <div>
                            <x-field.image class="-translate-y-6"
                                name="photo_file"
                                label="Photo"
                                width="md"
                                deleteTitle="Delete Photo"
                                deleteAllow="true"
                                submitForm="form_to_update_photo"
                                deleteForm="form_to_delete_photo"
                                :imageUrl="$user->photoFullUrl"
                                :readonly="$readonly"/>
                        </div>

                        <form id="form_to_delete_photo"
                            method="POST" action="{{ route('profile.photo.destroy', ['user' => $user]) }}">
                            @csrf
                            @method('DELETE')
                        </form>

                        <form id="form_to_update_photo" method="POST" action="{{ route('profile.photo.update', ['user' => $user]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <x-button element="submit" text="Update Photo" type="success" class="-translate-y-1 {{ $readonly ? 'hidden' : '' }}" />
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>


@endsection
