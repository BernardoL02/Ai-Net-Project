
<table class="table-auto border-collapse w-full">
    <thead>
        <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
            <th class="px-2 py-2 text-left">Photo</th>
            <th class="px-2 py-2 text-left">Title</th>
            <th class="px-2 py-2 text-left">Synopsis</th>
            <th></th> {{-- View Button --}}
            <th></th> {{-- Edit Button --}}
            <th></th> {{-- Delete Button --}}
        </tr>
    </thead>
    <tbody>
        @foreach ($movies as $movie)
            <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                <td class="px-2 py-2 text-left">
                    <img src="{{ $movie->posterFullUrl }}" alt="Movie Poster" width="75">
                </td>
                <td class="px-2 py-2 text-left">{{ $movie->title }}</td>
                <td class="px-2 py-2 text-left">{{ $movie->synopsis }}</td>
                {{-- View Button --}}
                <td>
                    <x-table.icon-show class="ps-3 px-0.5"
                        href="{{ route('movies.show', ['movie' => $movie]) }}" />
                </td>
                {{-- Edit Button --}}
                <td>
                    @can('update', $movie)
                        <x-table.icon-edit class="px-0.5"
                            href="{{ route('movies.edit', ['movie' => $movie]) }}" />
                    @else
                        <x-table.icon-edit class="px-0.5" :enabled="false" />
                    @endcan
                </td>
                {{-- Delete Button --}}
                <td>
                    @can('delete', $movie)
                        <x-table.icon-delete class="px-0.5"
                            action="{{ route('movies.destroy', ['movie' => $movie]) }}" />
                    @else
                        <x-table.icon-delete class="px-0.5" :enabled="false" />
                    @endcan
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
