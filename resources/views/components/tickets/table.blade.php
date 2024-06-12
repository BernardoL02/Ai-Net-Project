<div {{ $attributes }}>
    <table class="table-auto border-collapse">
        <thead>
        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-2 py-2 text-left">Ticket No</th>
            <th class="px-2 py-2 text-left hidden xl:table-cell">Theater Name</th>
            <th class="px-2 py-2 text-left hidden xl:table-cell">Movie</th>
            <th class="px-2 py-2 text-left hidden xl:table-cell">Date and time</th>
            <th class="px-2 py-2 text-left hidden md:table-cell">Screening</th>
            <th class="px-2 py-2 text-left hidden xl:table-cell">Row</th>

            <th class="px-2 py-2 text-left hidden xl:table-cell">Price</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($tickets as $ticket)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left">{{ $ticket->id }}</td>
                <td class="px-2 py-2 text-left hidden xl:table-cell">{{ $ticket->screening->theater->name }} </td>

                <td class="px-2 py-2 text-left hidden xl:table-cell">
                    <div class="flex items-center">
                        <img src=" {{ $ticket->screening->movie->posterFullUrl }}" alt="MoviePoster" class="w-12">
                    {{ $ticket->screening->movie->title }}

                </div>
                </td>
                <td class="px-2 py-2 text-left hidden xl:table-cell">{{ $ticket->screening->date }} {{$ticket->screening->start_time}} </td>
                <td class="px-2 py-2 text-left hidden md:table-cell">{{ $ticket->screening_id }} </td>
                <td class="px-2 py-2 text-left hidden xl:table-cell">{{ $ticket->seat->row }}{{ $ticket->seat->seat_number }} </td>

                <td class="px-2 py-2 text-left hidden xl:table-cell">{{ $ticket->price }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
