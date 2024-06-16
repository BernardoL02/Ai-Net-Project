@extends('layouts.main')

@section('header-title', 'My purchases')

@section('main')
<main>
    <div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">
            <div class="flex justify-center">
                <div class="my-4 p-6 bg-white dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
                    <div>
                        <div>
                            <h3 class="text-2xl font-semibold"> Tickets </h3>
                            <p class="mt-1 mb-6 text-sm text-gray-600 dark:text-gray-300">
                                Number of tickets: {{ $tickets->count()}}
                            </p>
                        </div>

                        <x-tickets.table :tickets="$tickets"/>

                    </div>

                    <div class="pt-10 flex flex-row">
                        <x-button
                            element="a"
                            type="light"
                            text="Return"
                            class="mr-5"
                            href="{{ route('customer.my-purchases')  }}"
                        />

                        @php
                            $hasInvalid = $tickets->contains(function($ticket) {
                                    return $ticket->status === 'invalid';
                            });
                        @endphp

                        @if ($hasInvalid)
                        <p class="text-red-700 pt-2 ml-auto">
                            The download cannot proceed because there are invalid tickets in your selection.
                        </p>
                        @else
                            <x-button class="ml-auto"
                                {{-- Downloading tickets should only be possible if the they are valid --}}
                                href="{{ route('tickets.download',['purchase' => $purchase]) }}"
                                text="Download Tickets"
                                type="success"/>
                        @endif

                        <x-button
                        {{-- Creates a receipt --}}
                        href="{{ route('receipt.generatePDF',['purchase' => $purchase]) }}"
                        text="Download Receipt"
                        type="success"
                        class="pl-6"/>
                        </div>

                </div>
            </div>
        </div>
    </div>
</main>
@endsection
