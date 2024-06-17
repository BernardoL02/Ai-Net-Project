@extends('layouts.admin')

@section('main')
<main>
    <div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
            <div class="flex justify-center">
                <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
                    <div>
                        <div class="flex flex-row mb-16">
                            <img class="w-[280px] h-[370px] mr-10 shadow-2xl border-8 border-white"
                            src="{{ $screening->movie->poster_full_url }}" alt="{{ $screening->movie->title }}">

                            <div >
                                <h3 class="text-3xl font-bold">{{ $screening->movie->title }}</h3>
                                <p class="text-base">{{ $screening->movie->genre->name}}</p>

                                <div class="pt-6 space-y-4">
                                    <div> <h3 class="text-lg font-semibold">Date </h3> <p> {{ $screening->date}}  </p> </div>
                                    <div> <h3 class="text-lg font-semibold">Start Time </h3> <p> {{ $screening->start_time}}  </p> </div>
                                    <div> <h3 class="text-lg font-semibold">Theater </h3> <p> {{ $screening->theater->name}}  </p> </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-2xl font-semibold"> Ticket Validation </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                Below is a form to search for tickets by ID or QR Code.
                            </p>

                            <p class="mt-1 mb-6 text-sm text-gray-600 dark:text-gray-300">
                                Number of tickets: {{ $tickets->count()}}
                            </p>
                        </div>

                        <div>
                            <form action="{{ route('employees.apply_additional_filters') }}" method="GET" class="pb-6 flex space-x-4">
                                <x-field.input label="Ticket ID" name="id" :width="'lg'" value="{{ request('id') }}" />
                                <x-field.input label="QR Code" name="qrcode" :width="'lg'" value="{{ request('qrcode') }}" />

                                <x-button element="submit" text="Search" type="dark" class="mt-7"/>
                                <x-button element="a" type="light" text="All Tickets" :href="url()->current()" class="mt-7"/>
                            </form>
                        </div>

                        <table class="table-auto border-collapse w-full border-2 border-gray-400 justify-center items-center">
                            <thead>
                            <tr class="border-2 border-gray-500 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                                <th class="px-4 py-2 text-left hidden lg:table-cell">ID</th>
                                <th class="px-4 py-2 text-left">Photo</th>
                                <th class="px-4 py-2 text-left">Customer Name</th>
                                <th class="px-4 py-2 text-left">Customer Email</th>
                                <th class="px-4 py-2 text-left">Seat</th>
                                <th class="px-4 py-2 text-left">Price</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Valid?</th>
                            </tr>
                            </thead>
                            <tbody class="border-2 border-gray-400 dark:border-gray-500">
                            @foreach ($tickets as $ticket)
                                <tr class="border border-gray-400 dark:border-gray-500">
                                    <td class="px-4 py-2 text-left border border-gray-300">{{ $ticket?->id }}</td>
                                    <td class="px-4 py-2 text-left border border-gray-300">
                                    @if ($ticket?->purchase?->customer?->user != null)
                                        <div class="flex items-center">
                                            <img src="{{ $ticket?->purchase?->customer?->user?->photo_full_url }}" alt="Foto do usuário" class="h-14 w-14 rounded-full object-cover">
                                        </div>
                                    @else
                                        <svg width="60px" height="60px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="pr-1">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM12 7.75C11.3787 7.75 10.875 8.25368 10.875 8.875C10.875 9.28921 10.5392 9.625 10.125 9.625C9.71079 9.625 9.375 9.28921 9.375 8.875C9.375 7.42525 10.5503 6.25 12 6.25C13.4497 6.25 14.625 7.42525 14.625 8.875C14.625 9.58584 14.3415 10.232 13.883 10.704C13.7907 10.7989 13.7027 10.8869 13.6187 10.9708C13.4029 11.1864 13.2138 11.3753 13.0479 11.5885C12.8289 11.8699 12.75 12.0768 12.75 12.25V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V12.25C11.25 11.5948 11.555 11.0644 11.8642 10.6672C12.0929 10.3733 12.3804 10.0863 12.6138 9.85346C12.6842 9.78321 12.7496 9.71789 12.807 9.65877C13.0046 9.45543 13.125 9.18004 13.125 8.875C13.125 8.25368 12.6213 7.75 12 7.75ZM12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17Z" fill="#4682B4"/>
                                        </svg>
                                    @endif
                                    </td>
                                    <td class="px-4 py-2 text-left border border-gray-300">{{ $ticket?->purchase?->customer_name }}</td>
                                    <td class="px-4 py-2 text-left border border-gray-300">{{ $ticket?->purchase?->customer_email }}</td>
                                    <td class="px-4 py-2 text-center border border-gray-300">{{ $ticket?->seat?->row }}{{ $ticket?->seat?->seat_number }}</td>
                                    <td class="px-4 py-2 text-center border border-gray-300">{{ $ticket?->price }}</td>

                                    <td class="px-8 py-2 border border-gray-300">
                                        @if ($ticket?->status == 'valid')
                                            <svg width="24" height="24" viewBox="0 0 117 117" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">

                                            <g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1">

                                            <g fill-rule="nonzero" id="correct">

                                            <path d="M34.5,55.1 C32.9,53.5 30.3,53.5 28.7,55.1 C27.1,56.7 27.1,59.3 28.7,60.9 L47.6,79.8 C48.4,80.6 49.4,81 50.5,81 C50.6,81 50.6,81 50.7,81 C51.8,80.9 52.9,80.4 53.7,79.5 L101,22.8 C102.4,21.1 102.2,18.5 100.5,17 C98.8,15.6 96.2,15.8 94.7,17.5 L50.2,70.8 L34.5,55.1 Z" fill="#17AB13" id="Shape"/>

                                            <path d="M89.1,9.3 C66.1,-5.1 36.6,-1.7 17.4,17.5 C-5.2,40.1 -5.2,77 17.4,99.6 C28.7,110.9 43.6,116.6 58.4,116.6 C73.2,116.6 88.1,110.9 99.4,99.6 C118.7,80.3 122,50.7 107.5,27.7 C106.3,25.8 103.8,25.2 101.9,26.4 C100,27.6 99.4,30.1 100.6,32 C113.1,51.8 110.2,77.2 93.6,93.8 C74.2,113.2 42.5,113.2 23.1,93.8 C3.7,74.4 3.7,42.7 23.1,23.3 C39.7,6.8 65,3.9 84.8,16.2 C86.7,17.4 89.2,16.8 90.4,14.9 C91.6,13 91,10.5 89.1,9.3 Z" fill="#4A4A4A" id="Shape"/>
                                            </g>
                                            </g>
                                            </svg>
                                        @else
                                            <svg width="24" height="24" viewBox="0 0 117 117" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1">
                                                <g fill-rule="nonzero" id="cancel">
                                                <path d="M58.5,116.6 C90.5,116.6 116.6,90.6 116.6,58.5 C116.6,26.4 90.5,0.4 58.5,0.4 C26.5,0.4 0.4,26.5 0.4,58.5 C0.4,90.5 26.5,116.6 58.5,116.6 Z M58.5,8.6 C86,8.6 108.4,31 108.4,58.5 C108.4,86 86,108.4 58.5,108.4 C31,108.4 8.6,86 8.6,58.5 C8.6,31 31,8.6 58.5,8.6 Z" fill="#4A4A4A" id="Shape"/>
                                                <path d="M36.7,79.7 C37.5,80.5 38.5,80.9 39.6,80.9 C40.7,80.9 41.7,80.5 42.5,79.7 L58.5,63.7 L74.5,79.7 C75.3,80.5 76.3,80.9 77.4,80.9 C78.5,80.9 79.5,80.5 80.3,79.7 C81.9,78.1 81.9,75.5 80.3,73.9 L64.3,57.9 L80.3,41.9 C81.9,40.3 81.9,37.7 80.3,36.1 C78.7,34.5 76.1,34.5 74.5,36.1 L58.5,52.1 L42.5,36.1 C40.9,34.5 38.3,34.5 36.7,36.1 C35.1,37.7 35.1,40.3 36.7,41.9 L52.7,57.9 L36.7,73.9 C35.1,75.5 35.1,78.1 36.7,79.7 Z" fill="#FF0000" id="Shape"/>
                                                </g>
                                                </g>
                                            </svg>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="flex flex-row space-x-2 justify-center">
                                            <form method="POST" action="{{ route('employees.validateTicket', ['ticket' => $ticket]) }}">
                                                @csrf
                                                @method('PUT')
                                                <button>
                                                    <svg width="25" height="25" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="25" height="25" rx="5" ry="5" style="fill:green;stroke:black;stroke-width:2" />
                                                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="10" fill="white" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">YES</text>
                                                    </svg>
                                                </button>
                                            </form>

                                            <div class="flex flex-row space-x-2 justify-center">
                                                <form method="POST" action="{{ route('employees.invalidateTicket', ['ticket' => $ticket]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                <button>
                                                    <svg width="25" height="25" xmlns="http://www.w3.org/2000/svg">
                                                        <rect width="25" height="25" rx="5" ry="5" style="fill:red;stroke:black;stroke-width:2" />
                                                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="10" fill="white" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">NO</text>
                                                    </svg>
                                                </button>
                                        </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $tickets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
