<!DOCTYPE html>
<html>

<head>
    <title>Tickets</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
            /* Add padding to increase space between columns */
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .label {
            font-weight: bold;
            font-size: 1.3em;
            color: #521da8;
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="flex flex-col space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
            <div class="max-full">
                <section>
                    <h2>Tickets</h2>


                    <p><span class="label">Name:</span> {{ $purchase->customer_name }}</p>
                    <p><span class="label">Email:</span> {{ $purchase->customer_email ?? 'Not registered' }}</p>
                    @if ($purchase->customer_id != null)
                        <p><img src="{{ $purchase->customer->user->photo_filename }}" alt=""
                                style="width: 65px; height: 65px; "></p>
                    @endif

                    @if ($download == true)
                        <table>
                            <thead>
                                <tr>
                                    <th>Ticket No</th>
                                    <th>Theater Name</th>
                                    <th>Movie</th>
                                    <th>Date and time</th>
                                    <th>Screening</th>
                                    <th>Row</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchase->tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->id }}</td>
                                        <td>
                                            {{ $ticket->screening->theater->name }}
                                        </td>
                                        <td style="width: 220px;">
                                            <div class="flex items-center">
                                                <img src="{{ $ticket->screening->movie->posterFullUrl }}" alt=""
                                                    style="width: 50px; height: 65px; margin-top:10px">
                                                {{ $ticket->screening->movie->title }}
                                            </div>
                                        </td>
                                        <td>{{ $ticket->screening->date }}
                                            {{ $ticket->screening->start_time }} </td>
                                        <td>{{ $ticket->screening_id }}
                                        </td>
                                        <td>
                                            {{ $ticket->seat->row }}{{ $ticket->seat->seat_number }} </td>

                                        <td>{{ $ticket->price }} $</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                    @if ($validTickets->isEmpty())
                        <h2>No valid tickets available</h2>

                    @else
                        <p><span class="label">Valid tickets:</span></p>

                        <table>
                            <thead>
                                <tr>
                                    <th>Ticket No</th>
                                    <th>Theater Name</th>
                                    <th>Movie</th>
                                    <th>Date and time</th>
                                    <th>Screening</th>
                                    <th>Row</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($validTickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->id }}</td>

                                        <td>
                                            {{ $ticket->screening->theater->name }}

                                        </td>
                                        <td style="width: 220px;">
                                            <div class="flex items-center">
                                                <img src="{{ $ticket->screening->movie->posterFullUrl }}"
                                                    alt="" style="width: 50px; height: 65px; margin-top:20px;">
                                                {{ $ticket->screening->movie->title }}
                                            </div>
                                        </td>
                                        <td>{{ $ticket->screening->date }}
                                            {{ $ticket->screening->start_time }} </td>
                                        <td>{{ $ticket->screening_id }}
                                        </td>
                                        <td>
                                            {{ $ticket->seat->row }}{{ $ticket->seat->seat_number }} </td>

                                        <td>{{ $ticket->price }} $</td>

                                        {{-- <td>{{ $ticket->qrcode_url }} </td>
                                        Ver os QRcodes, agora estao todos a zero, talvez seja possivel gerar um
                                            --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    @if ($invalidTickets->isEmpty())
                        <h1>No invalid tickets available</h1>
                    @else
                        <p><span class="label">Invalid tickets:</span></p>
                        <table>
                            <thead>
                                <tr>
                                    <th>Ticket No</th>
                                    <th>Theater Name</th>
                                    <th>Movie</th>
                                    <th>Date and time</th>
                                    <th>Screening</th>
                                    <th>Row</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($invalidTickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket->id }}</td>

                                        <td>
                                            {{ $ticket->screening->theater->name }}

                                        </td>
                                        <td style="width: 220px;">



                                            <div class="flex items-center">
                                                <img src="{{ $ticket->screening->movie->posterFullUrl }}"
                                                    alt="" style="width: 50px; height: 65px;margin-top:20px;">
                                                {{ $ticket->screening->movie->title }}
                                            </div>
                                        </td>
                                        <td>{{ $ticket->screening->date }}
                                            {{ $ticket->screening->start_time }} </td>
                                        <td>{{ $ticket->screening_id }}
                                        </td>
                                        <td>
                                            {{ $ticket->seat->row }}{{ $ticket->seat->seat_number }} </td>

                                        <td>{{ $ticket->price }} $</td>

                                        {{-- <td>{{ $ticket->qrcode_url }} </td>
                                    Ver os QRcodes, agora estao todos a zero, talvez seja possivel gerar um
                                        --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>



                        @endif
                    @endif



                </section>
            </div>
        </div>
    </div>
</body>

</html>
