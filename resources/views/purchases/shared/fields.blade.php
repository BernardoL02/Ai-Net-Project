@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="name" label="Name" :readonly="$readonly"
            value="{{ old('name', $purchase->customer_name) }}" />
        <x-field.input name="email" label="Email" :readonly="$readonly"
            value="{{ old('name', $purchase->customer_email) }}" />
        <x-field.radiogroup name="type" label="Payment Type" :readonly="$readonly"
            value="{{ old('gender', $purchase->payment_type) }}" :options="['MBWAY' => 'MBWAY', 'VISA' => 'VISA', 'PAYPAL' => 'PAYPAL']" />
        <x-field.input name="reference" label="Payment Reference" :readonly="$readonly"
            value="{{ old('name', $purchase->payment_ref) }}" />



        <div class="flex space-x-4">
            <x-field.input name="date" type="date" label="Date" :readonly="$readonly"
                value="{{ old('email', $purchase->date) }}" />
            <x-field.input name="price" label="Total price" :readonly="$readonly"
                value="{{ old('extension', $purchase->total_price) }}" />

        </div>
    </div>
</div>
