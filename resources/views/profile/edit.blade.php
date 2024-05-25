@extends('layouts.main')

@if ($editPassword)
    @section('header-title', 'Change Password')
@else
    @section('header-title', 'Profile')
@endif

@section('main')
<div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
        <div class="mx-auto py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div>
                        @if ($editPassword)
                            @include("profile.partials.update-password-form")
                        @else
                            @include("profile.partials.update-profile-information-form")
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
