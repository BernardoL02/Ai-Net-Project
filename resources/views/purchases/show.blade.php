@extends('layouts.admin')

@section('header-title', 'Customer "' . $purchase->customer_name . '"')

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap items-center gap-4 mb-4">

                    <header class="flex-grow">
                        <h2 class="text-2xl font-medium text-gray-900 dark:text-gray-100 mr-auto">
                            Customer "{{ $purchase->customer_name }}"
                        </h2>
                    </header>

                    <div class="ml-auto flex gap-4">
                        @if ($purchase->receipt_pdf_filename == null)

                            <x-button
                            {{-- Creates a receipt --}}
                            href="{{ route('receipt.generatePDF',['purchase' => $purchase]) }}"
                            text="Download receipt"
                            type="success" />

                        @else

                            <x-button
                                href="{{ route('receipt.show',['purchase' => $purchase]) }}"
                                newTab="true"
                                text="View receipt"
                                type="success"
                            />

                            <x-button
                            {{-- This dowloands the already existing one --}}
                            href="{{ route('receipt.download',['purchase' => $purchase]) }}"
                            text="Download receipt "
                            type="success"/>

                            <x-button
                            href="{{ route('tickets.show',['purchase' => $purchase]) }}"
                            text="View tickets"
                            type="success"/>

                            @if ($invalidTickets->isEmpty())
                                <x-button

                                {{-- Downloading tickets should only be possible if the they are valid --}}
                                href="{{ route('tickets.download',['purchase' => $purchase]) }}"
                                text="Download tickets"
                                type="success"/>

                            @endif
                        @endif
                    </div>
                </div>

                @include('purchases.shared.fields', ['mode' => 'show'])

                    <div class="pt-10">
                        <h3 class="text-2xl font-semibold"> Tickets </h3>
                        <p class="mt-1 mb-6 text-sm text-gray-600 dark:text-gray-300">
                            Number of tickets: {{ $purchase->tickets->count()}}
                        </p>
                    </div>
                    <div>
                        <x-tickets.table :tickets="$purchase->tickets"/>
                    </div>
            </section>
        </div>
    </div>
</div>
@endsection
