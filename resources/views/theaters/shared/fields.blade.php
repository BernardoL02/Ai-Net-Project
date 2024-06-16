@php
    $mode = $mode ?? 'edit';
    $readonly = $mode == 'show';
@endphp

<div class="flex flex-wrap space-x-8">
    <div class="grow mt-6 space-y-4">
        <x-field.input name="name" label="Name" :readonly="$readonly"
                        value="{{ old('name', $theater->name) }}"/>
        <div class=" grow w-96 ">
            <x-field.input name="rows" label="Number of Rows" type="number" min="1"/>
            <x-field.input name="columns" label="Number of Columns" type="number" min="1"/>
        </div>
        <x-button type="button" onclick="generateLayout()" class="uppercase" text="Generate Layout"/>
        <div class="flex items-center space-x-4 mt-4">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-500 mr-2"></div>
                <span class="text-gray-900 dark:text-gray-100">Available</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 mr-2"></div>
                <span class="text-gray-900 dark:text-gray-100">Unavailable</span>
            </div>
        </div>
        <div id="seatLayout" class="mt-6"></div>


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
                seat.onclick = function() {
                    seat.classList.toggle('bg-red-500');
                    seat.classList.toggle('bg-green-500');
                };
                rowDiv.appendChild(seat);
            }
            layoutDiv.appendChild(rowDiv);
        }
    }
</script>
