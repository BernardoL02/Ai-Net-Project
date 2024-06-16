<table class="table-auto  border-2 border-gray-600 w-full ">
    <thead>
        <tr class=" border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-2 py-2 text-left">ID</th>
            <th class="px-2 py-2 text-left">Poster</th>
            <th class="px-2 py-2 text-left">Movie ID</th>
            <th class="px-2 py-2 text-left">Theater ID</th>
            <th class="px-2 py-2 text-left">Date</th>
            <th class="px-2 py-2 text-left">Start Time</th>
            <th class="px-2 py-2 text-left">Created At</th>
            <th class="px-2 py-2 text-left">Updated At</th>
            <th></th> {{-- View Button --}}
            <th></th> {{-- Edit Button --}}
            <th></th> {{-- Delete Button --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($screenings as $screening)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left">{{ $screening->id }}</td>
                <td class="px-2 py-2 text-left">
                    @php
                        $defaultPosterUrl = asset('img/default_poster.png');
                        $posterUrl = $screening->poster_filename ? asset('storage/posters/' . $screening->poster_filename) : $defaultPosterUrl;
                    @endphp
                    <img src="{{ $posterUrl }}" alt="Poster" class=" w-24 h-36 object-cover">
                </td>
                <td class="px-2 py-2 text-left">{{ $screening->movie_id }}</td>
                <td class="px-2 py-2 text-left">{{ $screening->theater_id }}</td>
                <td class="px-2 py-2 text-left">{{ $screening->date }}</td>
                <td class="px-2 py-2 text-left">{{ $screening->start_time }}</td>
                <td class="px-2 py-2 text-left">{{ $screening->created_at }}</td>
                <td class="px-2 py-2 text-left">{{ $screening->updated_at }}</td>
                {{-- View Button --}}
                <td>
                    <x-table.icon-show class="ps-3 px-0.5"
                        href="{{ route('screenings.show', ['screening' => $screening]) }}" />
                </td>
                {{-- Edit Button --}}
                <td>
                    @can('update', $screening)
                        <x-table.icon-edit class="px-0.5"
                            href="{{ route('screenings.edit', ['screening' => $screening]) }}" />
                    @else
                        <x-table.icon-edit class="px-0.5" :enabled="false" />
                    @endcan
                </td>
                {{-- Delete Button --}}
                <td>
                    @can('delete', $screening)
                        <x-table.icon-delete class="px-0.5"
                            action="{{ route('screenings.destroy', ['screening' => $screening]) }}" />
                    @else
                        <x-table.icon-delete class="px-0.5" :enabled="false" />
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
