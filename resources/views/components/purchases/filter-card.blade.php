<div {{ $attributes }}>
    <form method="GET" action="{{ $filterAction }}">
        <div class="flex justify-between space-x-3">
            <div class="grow flex flex-col space-y-2">

                <div class="flex space-x-3">
                    <x-field.select name="type" label="Type"
                    value="{{ $type }}"
                    :options="$listPayments"/>
                    <!-- Input field do email-->
                    <x-field.input name="email" label="Customer Email " class="grow"
                        value="{{ $email }}"/>
                </div>

                <div class="flex space-x-3">
                    <x-field.input name="price" label="Price " class="grow"
                        value="{{ $price }}"/>
                    <x-field.select name="priceOption" label="Option"
                        value="{{ $priceOption }}"
                        :options="$listOptionPrice"/>
                </div>

            </div>
            <div class="grow-0 flex flex-col space-y-3 justify-start">
                <div class="pt-6">
                    <x-button element="submit" type="dark" text="Filter"/>
                </div>
                <div>
                    <x-button element="a" type="light" text="Cancel" :href="$resetUrl"/>
                </div>
            </div>
        </div>
    </form>
</div>


