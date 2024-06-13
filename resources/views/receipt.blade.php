<!DOCTYPE html>
<html>

<head>
    <title>Receipt</title>
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
    </style>
</head>

<body>
    <div class="flex flex-col space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
            <div class="max-full">
                <section>
                    <h1>Receipt</h1>
                    <p><span class="label">Purchase number:</span> {{ $purchase->id }}</p>
                    <p><span class="label">Name:</span> {{ $purchase->customer_name }}</p>
                    <p><span class="label">Email:</span> {{ $purchase->customer_email }}</p>
                    <p><span class="label">Payment type:</span> {{ $purchase->payment_type }}</p>
                    <p><span class="label">Payment reference:</span> {{ $purchase->payment_ref }}</p>
                    <p><span class="label">Nif:</span> {{ $purchase->nif ?? 'Not Available' }}</p>
                    <p><span class="label">Date:</span> {{ $purchase->date }}</p>
                    <p><span class="label">Total price:</span> {{ $purchase->total_price }} €</p>

                    <h2>Tickets</h2>
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
                                            <img src="{{ $ticket->screening->movie->posterFullUrl }}" alt="" style="width: 50px; height: 65px;" >
                                            {{ $ticket->screening->movie->title }}
                                        </div>
                                    </td>
                                    <td>{{ $ticket->screening->date }}
                                        {{ $ticket->screening->start_time }} </td>
                                    <td>{{ $ticket->screening_id }}
                                    </td>
                                    <td>
                                        {{ $ticket->seat->row }}{{ $ticket->seat->seat_number }} </td>

                                    <td>{{ $ticket->price }} €</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>
</body>

</html>
