<div {{ $attributes }} >
    <table class="table-auto border-collapse border-b-2 border-b-gray-400" >
        <thead>
        <tr class="border-2 border-gray-500 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-4 py-2 text-center hidden xl:table-cell">Movie</th>
            <th class="px-4 py-2 text-center">Ticket Nº</th>
            <th class="px-4 py-2 text-center hidden xl:table-cell">Theater Name</th>
            <th class="px-4 py-2 text-center hidden xl:table-cell">Session</th>
            <th class="px-4 py-2 text-center hidden xl:table-cell">Seat</th>
            <th class="px-4 py-2 text-center hidden xl:table-cell">Status</th>
            <th class="px-4 py-2 text-center hidden xl:table-cell">Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($tickets as $ticket)
            <tr class="border-l border-gray-400 dark:border-b-gray-500">
                <td class="px-4 py-2 border border-gray-300 border-l-2 border-l-gray-400">
                    <div class="flex items-center">
                        <img src="{{ $ticket->screening->movie->posterFullUrl }}" alt="{{ $ticket->screening->movie->title }}" class="h-28 w-18 border-2 border-black mr-4">
                        <p class="text-base">{{  $ticket->screening->movie->title }}</p>
                    </div>
                </td>

                <td class="px-4 py-2 text-center border border-gray-300">{{ $ticket->id }}</td>
                <td class="px-4 py-2 text-center hidden xl:table-cell border border-gray-300">{{ $ticket->screening->theater->name }} </td>

                <td class="px-4 py-2 text-center border border-gray-300">
                    <p>{{  $ticket->screening->date  }}</p>
                    <p>{{ $ticket->screening->start_time }}</p>
                </td>

                <td class="px-4 py-2 text-center hidden xl:table-cell border border-gray-300">{{ $ticket->seat->row }}{{ $ticket->seat->seat_number }} </td>
                <td class="px-4 py-2 text-center hidden xl:table-cell border border-gray-300">{{ $ticket->status }}</td>
                <td class="px-4 py-2 text-center hidden xl:table-cell border border-gray-300 border-r-2 border-r-gray-400">{{ $ticket->price }}$</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
