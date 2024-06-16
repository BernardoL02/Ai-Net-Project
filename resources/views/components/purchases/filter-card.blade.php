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
                    <x-field.input name="date" label="Date " class="grow"
                    value="{{ $date }}"/>
                </div>

            </div>
            </div>

            <div class="grow-0 flex flex-row space-x-3 justify-start pt-6">
                <div>
                    <x-button element="submit" type="dark" text="Filter"/>
                </div>
                <div>
                    <x-button element="a" type="light" text="All Purchases" :href="$resetUrl"/>
                </div>
        </div>
    </form>
</div>


