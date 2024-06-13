@extends('layouts.main')

@section('header-title', 'Shopping Cart')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            @empty($cart)
                <h3 class="text-xl w-96 text-center">Cart is Empty</h3>
            @else
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 border border-gray-300">Movie</th>
                            <th class="px-4 py-2 border border-gray-300">Session</th>
                            <th class="px-4 py-2 border border-gray-300">Theater</th>
                            <th class="px-4 py-2 border border-gray-300">Seat</th>
                            <th class="px-4 py-2 border border-gray-300"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $id=>$item)
                        <tr class="bg-white hover:bg-gray-100">
                            <td class="px-4 py-2 border border-gray-300">
                                <div class="flex items-center">
                                    <img src="{{$item['screening']->movie->poster_full_url}}" alt="{{$item['screening']->movie->title}}" class="h-28 w-18 border-2 border-black mr-4">
                                    <p class="text-base">{{$item['screening']->movie->title}}</p>
                                </div>
                            </td>
                            <td class="px-4 py-2 border border-gray-300 text-center">
                                <p>{{$item['screening']->date}}</p>
                                <p>{{$item['screening']->start_time}}</p>
                            </td>
                            <td class="px-4 py-2 border border-gray-300 text-center">{{$item['screening']->theater->name}}</td>
                            <td class="px-4 py-2 border border-gray-300 text-center">{{$item['seat']->row.$item['seat']->seat_number}}</td>
                            <td class="px-4 py-2 border border-gray-300 text-center">
                                <x-table.icon-minus method="delete" action="{{ route('cart.remove', ['id' => $id]) }}" class="text-red-600 hover:text-red-800 cursor-pointer"/>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-12">
                <div class="flex justify-between space-x-12 items-end">
                    <div>
                        <h3 class="mb-4 text-xl">Shopping Cart Confirmation </h3>
                        <form action="{{ route('cart.confirm') }}" method="post">
                            @csrf
                                <x-field.input name="student_number" label="Student Number" width="lg"
                                                :readonly="false"
                                                value="{{ old('student_number', Auth::User()?->student?->number ) }}"/>
                                <x-button element="submit" type="dark" text="Confirm" class="mt-4"/>
                        </form>
                    </div>
                    <div>
                        <form action="{{ route('cart.destroy') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <x-button element="submit" type="danger" text="Clear Cart" class="mt-4"/>
                        </form>
                    </div>
                </div>
            </div>
            @endempty
        </div>
    </div>
@endsection
