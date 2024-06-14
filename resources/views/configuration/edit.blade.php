@extends('layouts.admin')

@section('main')


<div class="min-h-screen flex flex-col justify-start items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
    <div class="mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg">

        <h1>Welcome to the settings page.</h1>
        <p>You can set ticket prices and the discount value per ticket.</p>

        <form id="from_to_update_configs" method="post" action="{{ route('configuration.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="ticketPrice" :value="__('Ticket Price')" />
                <x-text-input id="ticketPrice" name="ticket_price" type="text" class="mt-1 block w-full" :value="old('ticket_price', $ticketPrice)" required autocomplete="ticketPrice" />
                <x-input-error class="mt-2" :messages="$errors->get('ticket_price')" />
            </div>

            <div>
                <x-input-label for="discount" :value="__('Discount')" />
                <x-text-input id="discountif" name="discount" type="text" class="mt-1 block w-full"   :value="old('discount', $discount)" required autocomplete="discount" />
                <x-input-error class="mt-2" :messages="$errors->get('discount')" />
            </div>

            <div class="flex items-center">
                <x-button element="a" type="light" text="Cancel" class="mr-5" href="{{ url()->full() }}"/>

                <x-primary-button>{{ __('Save') }}</x-primary-button>

                @if (session('status') === 'configuration-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-green-600 dark:text-green-400 ml-4"
                    >{{ __('Saved.') }}</p>
                @endif

            </div>
        </form>
    </div>
</div>

@endsection
