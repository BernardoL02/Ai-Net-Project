@extends('layouts.main')

@section('header-title', 'My purchases')

@section('main')
<main>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">

                <div>
                    <h3 class="text-2xl font-semibold">My Purchase History</h3>
                    <p class="mt-1 mb-6 text-sm text-gray-600 dark:text-gray-300">
                        Here you can find a summary of all your past purchases.
                    </p>
                </div>

                @if ($numberPurchases == 0)
                    <h2 class="text-lg mt-10 text-red-600 dark:text-gray-300"> You have not made any purchases yet.</h2>
                @else
                    <x-purchases.filter-card class="mb-6"
                    :filterAction="route('customer.my-purchases')"
                    :resetUrl="route('customer.my-purchases')"
                    :type="$filterByPaymentType"
                    :price="$filterByPrice"
                    :priceOption="$filterByPriceOption"
                    />

                    <table class="table-auto border-collapse w-full border-2 border-gray-400 justify-center items-center">
                        <thead>
                        <tr class="border-2 border-gray-400 dark:border-gray-500 bg-gray-100 dark:bg-gray-800">
                            <th class="px-2 py-2 text-left hidden lg:table-cell">Date</th>
                            <th class="px-2 py-2 text-left">Customer Name</th>
                            <th class="px-2 py-2 text-left">NIF</th>
                            <th class="px-2 py-2 text-left">Email</th>
                            <th class="px-2 py-2 text-left">Price</th>
                            <th class="px-2 py-2 text-left">Payment Type</th>
                            <th class="px-2 py-2 text-left">Payment Reference</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="border-2 border-gray-400 dark:border-gray-500">
                        @foreach ($purchases as $purchase)
                            <tr class="border border-gray-400 dark:border-gray-500">
                                <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $purchase?->date }}</td>
                                <td class="px-2 py-2 text-left">{{ $purchase?->customer_name}}</td>
                                <td class="px-2 py-2 text-left">{{ $purchase?->nif }}</td>
                                <td class="px-2 py-2 text-left">{{ $purchase?->customer_email }}</td>
                                <td class="px-2 py-2 text-left">{{ $purchase?->total_price }}</td>
                                <td class="px-2 py-2 text-left">{{ $purchase?->payment_type }}</td>
                                <td class="px-2 py-2 text-left">{{ $purchase?->payment_ref }}</td>

                                <div class="ml-10">
                                    <td>
                                        <a  href="{{ route('purchases.showTicketsOfCostumer', ['purchase' => $purchase]) }}">
                                            <svg class="hover:text-gray-900 hover:stroke-2 w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </a>
                                    </td>
                                </div>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $purchases->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
