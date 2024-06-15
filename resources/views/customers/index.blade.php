@extends('layouts.admin')

@section('main')

<div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
        <div class="flex justify-center">
            <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
                <h3 class="text-2xl font-semibold"> Customers </h3>
                <p class="mt-1 mb-6 text-sm text-gray-600 dark:text-gray-300">
                    Management of customers.
                </p>

                <div>
                    <form  action="{{ route('customers.index') }}" method="GET" class="pb-6 flex space-x-4 grid-cols-2 items-center ">
                        @csrf

                        <div class="space-y-2">
                            <x-field.input label="Name" name="name" :width="'lg'" value="{{ request('name') }}"  />
                            <x-field.input label="Email" name="email" :width="'lg'" value="{{ request('email') }}" />
                        </div>

                        <div>
                            <x-field.input label="NIF" name="nif" :width="'lg'" value="{{ request('nif') }}" />

                            <div class="flex flex-row space-x-2">

                                <x-field.select class="translate-y-2"
                                name="state"
                                :options="['' => ' - ', '0' => 'Unblocked', '1' => 'Blocked']"
                                label="User State"
                                value="{{ request('state') }}"
                                :required="False"
                                :width="'full'"
                                />

                                <x-button element="submit" text="Search" type="submit" class="mt-[2.15rem]"/>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                    <table class="table-auto border-collapse ml-4 w-full">
                        <thead>
                        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                            <th class="px-2 py-2 text-left hidden lg:table-cell">Id</th>
                            <th class="px-2 py-2 text-left">Name</th>
                            <th class="px-2 py-2 text-left">Email</th>
                            <th class="px-2 py-2 text-left hidden sm:table-cell">NIF</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($allCustomers as $user)

                            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                                <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $user->id }}</td>
                                <td class="px-2 py-2 text-left">{{ $user->name }}</td>
                                <td class="px-2 py-2 text-left">{{ $user->email }}</td>
                                <td class="px-2 py-2 text-left ">{{ $user->customer?->nif}}</td>

                                <div class="ml-10">
                                    <td>
                                        <a href="{{ route('customers.show', ['customer' => $user->customer]) }}">
                                            <svg class="hover:text-gray-900 hover:stroke-2 w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('customers.block', ['customer' => $user->customer]) }}">
                                            @csrf
                                            @method('PUT')
                                            <button>
                                                @if($user->blocked == 0)
                                                <svg height="24px" width="24" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                    viewBox="0 0 512 512" xml:space="preserve">
                                                    <path style="fill:#FFB655;" d="M333.576,155.152c-24.764,0-48.362,5.075-69.818,14.234c-17.033,7.27-32.711,17.113-46.545,29.043
                                                        c-37.978,32.745-62.061,81.186-62.061,135.148c0,98.383,80.041,178.424,178.424,178.424S512,431.959,512,333.576
                                                        S431.959,155.152,333.576,155.152z"/>
                                                    <path style="fill:#A9A8AE;" d="M23.273,248.242c12.853,0,23.273-10.42,23.273-23.273v-93.092c0-47.051,38.281-85.332,85.333-85.332
                                                        s85.333,38.281,85.333,85.332v66.551c13.835-11.928,29.513-21.772,46.545-29.043v-37.508C263.758,59.161,204.597,0,131.879,0
                                                        S0,59.161,0,131.877v93.092C0,237.822,10.42,248.242,23.273,248.242z"/>
                                                    <path style="fill:#EE8700;" d="M155.152,333.576c0,98.383,80.041,178.424,178.424,178.424V155.152
                                                        c-24.764,0-48.362,5.075-69.818,14.234c-17.033,7.27-32.711,17.113-46.545,29.043C179.234,231.174,155.152,279.614,155.152,333.576z
                                                        "/>
                                                </svg>
                                                @else
                                                    <svg height="24" width="24" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        viewBox="0 0 512 512" xml:space="preserve">
                                                        <path style="fill:#FFB655;" d="M387.879,213.521c-13.329-14.629-29.054-27.037-46.545-36.603
                                                            c-25.364-13.872-54.444-21.766-85.333-21.766c-17.375,0-34.178,2.498-50.069,7.152c-10.594,3.103-20.783,7.165-30.466,12.083
                                                            c-1.614,0.819-3.213,1.663-4.799,2.531c-17.492,9.565-33.216,21.974-46.545,36.603C95.22,245.24,77.576,287.386,77.576,333.576
                                                            c0,24.596,5.002,48.046,14.044,69.384c1.131,2.667,2.324,5.302,3.578,7.902c1.882,3.899,3.901,7.72,6.052,11.456
                                                            c1.434,2.49,2.926,4.943,4.475,7.357c1.548,2.414,3.154,4.788,4.814,7.12c3.32,4.665,6.858,9.163,10.6,13.483
                                                            c3.742,4.318,7.688,8.456,11.821,12.397c6.2,5.913,12.825,11.383,19.822,16.364c3.499,2.49,7.09,4.856,10.769,7.095
                                                            c12.263,7.46,25.491,13.481,39.45,17.833c2.791,0.87,5.612,1.674,8.46,2.408c2.849,0.734,5.724,1.401,8.625,1.995
                                                            C231.691,510.749,243.703,512,256,512c98.383,0,178.424-80.041,178.424-178.424C434.424,287.386,416.78,245.24,387.879,213.521z"/>
                                                        <path style="fill:#C3C3C7;" d="M170.667,176.918v-45.04c0-47.051,38.281-85.332,85.333-85.332s85.333,38.281,85.333,85.332v45.039
                                                            c17.492,9.565,33.216,21.974,46.545,36.603v-81.642C387.879,59.161,328.718,0,256,0S124.121,59.161,124.121,131.877v81.642
                                                            C137.45,198.892,153.175,186.483,170.667,176.918z"/>
                                                        <path style="fill:#B4002B;" d="M288.912,333.576l16.514-16.514c9.089-9.087,9.089-23.824,0-32.912
                                                            c-9.087-9.089-23.824-9.089-32.912,0L256,300.663l-16.514-16.514c-9.087-9.089-23.824-9.089-32.912,0
                                                            c-9.089,9.087-9.089,23.824,0,32.912l16.514,16.514l-16.514,16.514c-9.089,9.087-9.089,23.824,0,32.912
                                                            c4.544,4.544,10.501,6.817,16.455,6.817s11.913-2.271,16.455-6.817L256,366.488l16.514,16.513
                                                            c4.544,4.544,10.501,6.817,16.455,6.817s11.913-2.271,16.455-6.817c9.089-9.087,9.089-23.824,0-32.912L288.912,333.576z"/>
                                                        <path style="fill:#EE8700;" d="M256,512V366.488l-16.514,16.513c-4.544,4.544-10.501,6.817-16.455,6.817
                                                            c-5.955,0-11.913-2.271-16.455-6.817c-9.089-9.087-9.089-23.824,0-32.912l16.513-16.513l-16.513-16.514
                                                            c-9.089-9.087-9.089-23.824,0-32.912c9.087-9.089,23.824-9.089,32.912,0L256,300.663V155.152c-30.889,0-59.969,7.894-85.333,21.766
                                                            c-17.492,9.565-33.216,21.974-46.545,36.603c-28.902,31.718-46.545,73.865-46.545,120.055C77.576,431.959,157.617,512,256,512z"/>
                                                        <path style="fill:#A9A8AE;" d="M124.121,131.879v81.642c13.329-14.629,29.054-27.037,46.545-36.603v-45.039
                                                            c0-47.053,38.281-85.333,85.333-85.333V0C183.282,0,124.121,59.161,124.121,131.879z"/>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('customers.destroy', ['customer' => $user->customer]) }}">
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
                        {{ $allCustomers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
