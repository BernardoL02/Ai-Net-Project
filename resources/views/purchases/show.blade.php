@extends('layouts.admin')

@section('header-title', 'Customer "' . $purchase->customer_name . '"')

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
                <div class="flex flex-wrap justify-end items-center gap-4 mb-4">


                    @if ($purchase->receipt_pdf_filename ==null)

                        <x-button
                        {{-- Creates a receipt --}}
                        href="{{ route('receipt.generatePDF',['purchase' => $purchase]) }}"
                        text="Download receipt"
                        type="success"/>

                    @else

                        <x-button
                        href="{{ route('receipt.show',['purchase' => $purchase]) }}"
                        text="View receipt"
                        type="success"/>

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
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Customer "{{ $purchase->customer_name }}"
                    </h2>
                </header>
                @include('purchases.shared.fields', ['mode' => 'show'])
                    <h3 class="pt-16 pb-4 text-2xl font-medium text-gray-900 dark:text-gray-100">
                        Tickets
                    </h3>
                    <x-tickets.table :tickets="$purchase->tickets"

                        class="pt-4"
                        />
            </section>
        </div>
    </div>
</div>
@endsection
