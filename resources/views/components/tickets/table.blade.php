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
                <td class="px-8 py-2 border border-gray-300">
                    @if ($ticket?->status == 'valid')
                        <svg width="24px" height="24px" viewBox="0 0 117 117" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">

                        <g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1">

                        <g fill-rule="nonzero" id="correct">

                        <path d="M34.5,55.1 C32.9,53.5 30.3,53.5 28.7,55.1 C27.1,56.7 27.1,59.3 28.7,60.9 L47.6,79.8 C48.4,80.6 49.4,81 50.5,81 C50.6,81 50.6,81 50.7,81 C51.8,80.9 52.9,80.4 53.7,79.5 L101,22.8 C102.4,21.1 102.2,18.5 100.5,17 C98.8,15.6 96.2,15.8 94.7,17.5 L50.2,70.8 L34.5,55.1 Z" fill="#17AB13" id="Shape"/>

                        <path d="M89.1,9.3 C66.1,-5.1 36.6,-1.7 17.4,17.5 C-5.2,40.1 -5.2,77 17.4,99.6 C28.7,110.9 43.6,116.6 58.4,116.6 C73.2,116.6 88.1,110.9 99.4,99.6 C118.7,80.3 122,50.7 107.5,27.7 C106.3,25.8 103.8,25.2 101.9,26.4 C100,27.6 99.4,30.1 100.6,32 C113.1,51.8 110.2,77.2 93.6,93.8 C74.2,113.2 42.5,113.2 23.1,93.8 C3.7,74.4 3.7,42.7 23.1,23.3 C39.7,6.8 65,3.9 84.8,16.2 C86.7,17.4 89.2,16.8 90.4,14.9 C91.6,13 91,10.5 89.1,9.3 Z" fill="#4A4A4A" id="Shape"/>
                        </g>
                        </g>
                        </svg>
                    @else
                        <svg width="24px" height="24px" viewBox="0 0 117 117" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1">
                            <g fill-rule="nonzero" id="cancel">
                            <path d="M58.5,116.6 C90.5,116.6 116.6,90.6 116.6,58.5 C116.6,26.4 90.5,0.4 58.5,0.4 C26.5,0.4 0.4,26.5 0.4,58.5 C0.4,90.5 26.5,116.6 58.5,116.6 Z M58.5,8.6 C86,8.6 108.4,31 108.4,58.5 C108.4,86 86,108.4 58.5,108.4 C31,108.4 8.6,86 8.6,58.5 C8.6,31 31,8.6 58.5,8.6 Z" fill="#4A4A4A" id="Shape"/>
                            <path d="M36.7,79.7 C37.5,80.5 38.5,80.9 39.6,80.9 C40.7,80.9 41.7,80.5 42.5,79.7 L58.5,63.7 L74.5,79.7 C75.3,80.5 76.3,80.9 77.4,80.9 C78.5,80.9 79.5,80.5 80.3,79.7 C81.9,78.1 81.9,75.5 80.3,73.9 L64.3,57.9 L80.3,41.9 C81.9,40.3 81.9,37.7 80.3,36.1 C78.7,34.5 76.1,34.5 74.5,36.1 L58.5,52.1 L42.5,36.1 C40.9,34.5 38.3,34.5 36.7,36.1 C35.1,37.7 35.1,40.3 36.7,41.9 L52.7,57.9 L36.7,73.9 C35.1,75.5 35.1,78.1 36.7,79.7 Z" fill="#FF0000" id="Shape"/>
                            </g>
                            </g>
                            </svg>
                    @endif
                </td>
                <td class="px-4 py-2 text-center hidden xl:table-cell border border-gray-300 border-r-2 border-r-gray-400">{{ $ticket->price }}$</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
