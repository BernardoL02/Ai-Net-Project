@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
    $seats = $theater->seats ?? [];

    // Calcular o número de linhas e colunas dinamicamente
    $rows = $seats->max('row') ? ord($seats->max('row')) - 64 : 0; // Convertendo a última letra da linha para número
    $columns = $seats->max('seat_number') ?? 0; // Pegando o maior número de assento
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="name" label="Name" :readonly="$readonly"
                        value="{{ old('name', $theater->name) }}"/>

        <div class="grow w-96">
            <x-field.input name="rows" label="Number of Rows" type="number" min="1"
                           value="{{ old('rows', $rows) }}" :readonly="$readonly"/>
            <x-field.input name="columns" label="Number of Columns" type="number" min="1"
                           value="{{ old('columns', $columns) }}" :readonly="$readonly"/>
        </div>

        @if(!$readonly)
        <x-button type="button" onclick="generateLayout()" class="uppercase" text="Generate Layout"/>
            <div class="flex items-center space-x-4 mt-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 mr-2"></div>
                    <span class="text-gray-900 dark:text-gray-100">Available</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-orange-500 mr-2"></div>
                    <span class="text-gray-900 dark:text-gray-100">Unavailable</span>
                </div>
            </div>
        @endif

        <div id="seatLayout" class="mt-6"></div>
        <input type="hidden" name="seat_data" id="seatData">
    </div>
    <div class="pb-6">
        <x-field.image
            name="photo_file"
            label="Photo"
            width="md"
            :readonly="$readonly"
            deleteTitle="Delete Photo"
            :deleteAllow="($mode == 'edit') && ($theater->photo_url)"
            deleteForm="form_to_delete_photo"
            :imageUrl="$theater->photoFullUrl"/>
    </div>
</div>

<script>
    const seatsData = @json($seats);
    const readonly = @json($readonly);

    function generateLayout() {
        const rows = document.querySelector('input[name="rows"]').value;
        const columns = document.querySelector('input[name="columns"]').value;
        const layoutDiv = document.getElementById('seatLayout');
        layoutDiv.innerHTML = '';

        for (let i = 0; i < rows; i++) {
            const rowDiv = document.createElement('div');
            rowDiv.className = 'flex space-x-2 mb-2';
            const rowLabel = String.fromCharCode(65 + i);
            for (let j = 0; j < columns; j++) {
                const seat = document.createElement('button');
                seat.className = 'w-8 h-8 bg-green-500';
                seat.textContent = rowLabel + (j + 1);
                seat.dataset.row = rowLabel;
                seat.dataset.seatNumber = j + 1;

                // Set seat status based on existing data
                const seatData = seatsData.find(s => s.row === rowLabel && s.seat_number == (j + 1));
                if (seatData && seatData.custom) {
                    const customData = JSON.parse(seatData.custom);
                    if (customData.status === 'unavailable') {
                        seat.classList.add('bg-orange-500');
                        seat.classList.remove('bg-green-500');
                    }
                }

                if (!readonly) {
                    seat.onclick = function(event) {
                        event.preventDefault();
                        seat.classList.toggle('bg-orange-500');
                        seat.classList.toggle('bg-green-500');
                        updateSeatData();
                    };
                } else {
                    seat.disabled = true; // Disable the button if readonly
                }

                rowDiv.appendChild(seat);
            }
            layoutDiv.appendChild(rowDiv);
        }
        updateSeatData();
    }

    function updateSeatData() {
        const layoutDiv = document.getElementById('seatLayout');
        const seats = layoutDiv.querySelectorAll('button');
        const seatData = Array.from(seats).map(seat => ({
            row: seat.dataset.row,
            seat_number: seat.dataset.seatNumber,
            status: seat.classList.contains('bg-orange-500') ? 'unavailable' : 'available'
        }));
        document.getElementById('seatData').value = JSON.stringify(seatData);
    }

    function init() {
        generateLayout();
    }

    if (document.readyState !== 'loading') {
        init();
    } else {
        document.addEventListener('DOMContentLoaded', init);
    }
</script>

