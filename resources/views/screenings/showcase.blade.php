@extends('layouts.main')

@section('header-title', 'Screening')

@section('main')
    <main>
        <div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
                <div class="mx-auto py-12">
                    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
                        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg flex flex-col">

                            <div class="flex flex-row">
                                <img class="w-[280px] h-[370px] mr-10 shadow-2xl border-8 border-white"
                                src="{{ $screening->movie->poster_full_url }}" alt="{{ $screening->movie->title }}">

                                <div>
                                    <h3 class="text-3xl font-bold">{{ $screening->movie->title }}</h3>
                                    <p class="text-base">{{ $screening->movie->genre->name}}</p>

                                    <div class="pt-6 space-y-4">
                                        <div> <h3 class="text-lg font-semibold">Date </h3> <p> {{ $screening->date}}  </p> </div>
                                        <div> <h3 class="text-lg font-semibold">Start Time </h3> <p> {{ $screening->start_time}}  </p> </div>
                                        <div> <h3 class="text-lg font-semibold">Theater </h3> <p> {{ $screening->theater->name}}  </p> </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-16">
                                @if (!$screeningsFull)
                                    <div class="pb-5">
                                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Choose your seats</h2>
                                        <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">Please select the seats you would like to purchase from the seating plan below. Seats that are already reserved are marked in <span class="text-red-700">red</span>. Click on the available seats to add them to your cart. </p>
                                        <p class="text-lg text-gray-700 dark:text-gray-300">Thank you for choosing our theater! Enjoy the show!</p>
                                    </div>
                                @endif

                                <div class="justify-center flex">
                                    @if ($screeningsFull)
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4"> Information </h2>

                                        <p class="text-lg text-gray-700 dark:text-gray-300 mb-4">
                                            We're sorry, but there are no more tickets available for this screening. <br> Please check back later or choose a different session. <br><br>  Thank you for your understanding!
                                        </p>

                                        <x-button
                                            element="a"
                                            type="primary"
                                            text="Choose Another Session"
                                            class="mr-5 mt-5"
                                            href="{{ route('movies.showcase', ['movie' => $screening->movie_id]) }}"
                                        />
                                    </div>

                                    @else

                                        <form id="form_add_to_card" action="{{route('cart.add',['screening'=>$screening])}}" method="POST">
                                            @csrf
                                            <div class="flex flex-col items-center">
                                                @foreach ($screening->theater->rows as $row)
                                                    <div class="flex">
                                                        <strong class="mr-10 mt-5">{{ $row }}</strong>
                                                        <div class="flex flex-wrap justify-start">
                                                            @foreach ($screening->theater->seatsRow($row) as $seat)
                                                                @if ($screening->tickets()->where('seat_id', $seat->id)->count())

                                                                    <div class="relative mt-3 ml-2">
                                                                        <input class="sr-only peer" type="checkbox" disabled
                                                                            id="row_{{$row}}_seat_{{ $seat->seat_number }}">

                                                                        <label
                                                                            class=" w-10 h-10 bg-red-700 border border-gray-300 rounded-lg inline-block text-center pt-2"
                                                                            for="row_{{$row}}_seat_{{ $seat->seat_number }}">{{ $seat->seat_number }}</label>
                                                                    </div>
                                                                @else
                                                                    <div class="relative mt-3 ml-2 " >

                                                                        <input class="sr-only peer" type="checkbox" value="{{ $seat->id }}" name="seats[]"
                                                                            id="row_{{ $row}}_seat_{{ $seat->seat_number }}">
                                                                        <label
                                                                            class=" w-10 h-10 bg-white border border-gray-300 rounded-lg cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent inline-block text-center pt-2"
                                                                            for="row_{{ $row}}_seat_{{ $seat->seat_number }}">{{ $seat->seat_number }}
                                                                        </label>

                                                                        <div class="absolute hidden w-5 h-5 peer-checked:blocked  top-5 right-10">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="ml-4 pt-10 flex justify-center pb-10">
                                                <div class="bg-black w-full max-w-2xl h-10 flex justify-center items-center rounded-lg shadow-lg ">
                                                    <h2 class="text-white text-xl font-bold">SCREEN</h2>
                                                </div>
                                            </div>
                                    </form>
                                    @endif
                                </div>

                                @if (!$screeningsFull)
                                    <x-button
                                        element="submit"
                                        type="black"
                                        text="Add to Cart"
                                        class="mr-5 mt-5" href="{{ url()->full() }}"
                                        onclick="document.getElementById('form_add_to_card').submit();"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
