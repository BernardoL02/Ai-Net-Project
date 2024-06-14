@extends('layouts.main')

@section('header-title', 'Shopping Cart')

@section('main')
    <div class="flex justify-center">
        <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-sm overflow-hidden rounded-lg">
            <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
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
                                <th class="px-4 py-2 border border-gray-300">Price</th>
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
                                <td class="px-4 py-2 border border-gray-300 text-center"> {{ $ticketPrice }} $</td>
                                <td class="px-4 py-2 border border-gray-300 text-center">
                                    <x-table.icon-minus method="delete" action="{{ route('cart.remove', ['id' => $id]) }}" class="text-red-600 hover:text-red-800 cursor-pointer flex justify-center items-cente items-centerr"/>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-12">
                    <div class="flex justify-between space-x-12 items-end">
                        <div>
                            <h3 class="mb-4 text-2xl">Shopping Cart Confirmation </h3>
                            <form action="{{ route('cart.confirm') }}" method="post">
                                @csrf

                                <div class="grid grid-cols-3 gap-4 mb-10">
                                    <div>
                                        <x-input-label for="name" :value="__('Name')" />
                                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user?->name)" required autocomplete="name" />
                                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                    </div>

                                    <div>
                                        <x-input-label for="email" :value="__('Email')" />
                                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user?->email)" required autocomplete="username" />
                                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                    </div>

                                    <div>
                                        <x-input-label for="nif" :value="__('NIF')" />
                                        <x-text-input id="nif" name="nif" type="text" class="mt-1 block w-full" :value="old('nif', $customer?->nif)" required autocomplete="nif" />
                                        <x-input-error class="mt-2" :messages="$errors->get('nif')" />
                                    </div>

                                    <div>
                                        <x-input-label for="payment_ref" :value="__('Payment Reference')"/>
                                        <x-text-input id="payment_ref" name="payment_ref" type="text" class="mt-1 block w-full" :value="old('payment_ref', $customer?->payment_ref)" required autocomplete="payment_ref" />
                                        <x-input-error class="mt-2" :messages="$errors->get('payment_ref')" />
                                    </div>

                                    <div>
                                        <x-field.select
                                            name="payment_type"
                                            :options="['' => ' - ', 'VISA' => 'Visa', 'MBWAY' => 'MB Way', 'PAYPAL' => 'PayPal']"
                                            :value="(string)$customer?->payment_type"
                                            label="Payment Type"
                                            :required="True"
                                            :width="'full'"
                                        />
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <x-button
                                        element="submit"
                                        type="success"
                                        text="Finalize Purchase"
                                        class="mr-5"
                                    />
                                </div>
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
    </div>
@endsection

