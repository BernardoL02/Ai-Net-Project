@extends('layouts.admin')

@section('main')

<div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
        <div class="flex justify-center">
            <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
                <h3 class="text-2xl mb-6 font-semibold"> Administrators and Employees </h3>

                <div>
                    <form  action="{{ route('users.index') }}" method="GET" class="pb-6 flex space-x-4 grid-cols-2 ">
                        @csrf

                        <div class="space-y-2">
                            <x-field.input label="Name" name="name" :width="'lg'" value="{{ request('name') }}"  />
                            <x-field.input label="Email" name="email" :width="'lg'" value="{{ request('email') }}" />
                        </div>

                        <div>
                            <x-field.select
                                name="type"
                                :options="['' => ' - ', 'A' => 'Admin', 'E' => 'Employee']"
                                label="User Type"
                                value="{{ request('type') }}"
                                :required="False"
                                :width="'full'"
                            />

                            <x-button element="submit" text="Search" type="submit" class="mt-[2.15rem]"/>

                        </div>
                    </form>
                </div>

                <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                    <table class="table-auto border-collapse ml-4">
                        <thead>
                        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                            <th class="px-2 py-2 text-left hidden lg:table-cell">Id</th>
                            <th class="px-2 py-2 text-left">Name</th>
                            <th class="px-2 py-2 text-left">Email</th>
                            <th class="px-2 py-2 text-center hidden sm:table-cell">Type</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($adminAndEmployees as $user)
                            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                                <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $user->id }}</td>
                                <td class="px-2 py-2 text-left">{{ $user->name }}</td>
                                <td class="px-2 py-2 text-left">{{ $user->email }}</td>
                                <td class="px-2 py-2 text-center">{{ $user->type }}</td>

                                <div class="ml-10">
                                    <td>
                                        <a href="{{ route('users.show', ['user' => $user]) }}">
                                            <svg class="hover:text-gray-900 hover:stroke-2 w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('users.edit', ['user' => $user]) }}">
                                            <svg class="hover:text-blue-600 hover:stroke-2 w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" >
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('users.destroy', ['user' => $user]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" name="delete" class="mt-2 hover:text-red-600">
                                                <svg class="hover:stroke-2 w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </div>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $adminAndEmployees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
