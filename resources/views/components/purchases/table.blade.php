<div {{ $attributes }}>
    <table class="table-auto border-collapse">
        <thead>
        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-2 py-2 text-left hidden md:table-cell">Customer´s name</th>
            <th class="px-2 py-2 text-left hidden xl:table-cell">Customer´s email</th>
            <th class="px-2 py-2 text-right hidden xl:table-cell">Total price</th>
            <th class="px-2 py-2 text-left hidden xl:table-cell">Date</th>
            <th class="px-2 py-2 text-left hidden xl:table-cell">Payment type</th>

        </tr>
        </thead>
        <tbody>
        @foreach ($purchases as $purchase)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left hidden md:table-cell">{{ $purchase->customer_name }}</td>
                <td class="px-2 py-2 text-left hidden xl:table-cell">{{ $purchase->customer_email }}</td>
                <td class="px-2 py-2 text-right hidden xl:table-cell">{{ $purchase->total_price }}</td>
                <td class="px-2 py-2 text-left hidden xl:table-cell">{{ $purchase->date }}</td>
                <td class="px-2 py-2 text-left hidden xl:table-cell">{{ $purchase->payment_type }}</td>

                @if($showView)
                        <td>
                            <x-table.icon-show class="ps-3 px-0.5"
                            href="{{route('purchases.show', ['purchase' => $purchase]) }}"/>
                        </td>
                    @else
                        <td></td>
                @endif

            </tr>
        @endforeach
        </tbody>
    </table>
</div>
