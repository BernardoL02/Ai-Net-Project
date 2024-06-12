@extends('layouts.main')

@section('header-title', 'Screening')

@section('main')
    <main>


        <img src="{{ $screening->movie->poster_full_url }}" alt="{{ $screening->movie->title }}" class="w-96 h-96">

        Screening: {{ $screening->date . ' ' . $screening->start_time }}

        <h3>{{ $screening->movie->title }}</h3>
        <p>
            {{ $screening->theater->name }}
        </p>

        <p>Lugares:</p>
        <form action="{{route('cart.add',['screening'=>$screening])}}" method="POST">
            @csrf
        @foreach ($screening->theater->rows as $row)
            <div class="flex">

                <strong class="mr-10 mt-5">{{ $row }}</strong>
                <div class="flex ">

                    @foreach ($screening->theater->seatsRow($row) as $seat)
                        @if ($screening->tickets()->where('seat_id', $seat->id)->count())

                            <div class="relative mt-3 ml-2">
                                <input class="sr-only peer" type="checkbox" disabled
                                    id="row_{{ $row}}_seat_{{ $seat->seat_number }}">
                                <label
                                    class=" w-10 h-10 bg-red-700 border border-gray-300 rounded-lg inline-block text-center pt-2"
                                    for="row_{{ $row}}_seat_{{ $seat->seat_number }}">{{ $seat->seat_number }}</label>
                            </div>

                        @else
                            <div class="relative mt-3 ml-2 " >
                                <input class="sr-only peer" type="checkbox" value="{{ $seat->seat_number }}" name="seats[]"
                                    id="row_{{ $row}}_seat_{{ $seat->seat_number }}">
                                <label
                                    class=" w-10 h-10 bg-white border border-gray-300 rounded-lg cursor-pointer focus:outline-none hover:bg-gray-50 peer-checked:ring-green-500 peer-checked:ring-2 peer-checked:border-transparent inline-block text-center pt-2"
                                    for="row_{{ $row}}_seat_{{ $seat->seat_number }}">{{ $seat->id }}</label>

                                <div class="absolute hidden w-5 h-5 peer-checked:blocked  top-5 right-10">
                                </div>
                            </div>
                        @endif

                    @endforeach

                </div>
            </div>
        @endforeach

        <x-button
            element="submit"
            type="black"
            text="Add to Cart"
            class="mr-5 mt-5" href="{{ url()->full() }}"
        />
    </form>


    </main>
@endsection
