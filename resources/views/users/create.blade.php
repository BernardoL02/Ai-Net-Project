@extends('layouts.admin')

@section('main')
<div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
        <div class="flex justify-center">
            <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Create a new User
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300  mb-6">
                    Click on "Create" button to store the information.
                </p>

                <div>
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="space-y-6">
                            <x-field.input label="Name" name="name" :width="'lg'" value="{{ request('name') }}"  />
                            <x-field.input label="Email" name="email" :width="'lg'" value="{{ request('email') }}" />
                            <x-field.input label="Password" name="password" type="password" :width="'lg'" value="{{ request('password') }}" />

                            <x-field.radiogroup
                                name="type"
                                value="A"
                                label="Type"
                                :options="['A' => 'Admin', 'E' => 'Employee']"
                            />

                            <x-button element="submit" text="Create" type="submit" class="mt-[2.15rem]"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
