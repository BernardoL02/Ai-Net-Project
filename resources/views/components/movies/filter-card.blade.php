<div {{ $attributes }}>
    <form method="GET" action="{{ $filterAction }}>
        <div class="flex justify-between space-x-3">
            <div class="flex space-x-3">
                <x-field.select name="genre" label="Genre"
                        value="{{ $genre }}"
                        :options="$listGenres"/>
            </div>
            <div class="grow-0 flex flex-col space-y-3 justify-start p-2">
                <div class="pt-6">
                    <x-button element="submit" type="dark" text="Filter"/>
                </div>
                <div>
                    <x-button element="a" type="light" text="Cancel" :href="$resetUrl"/>
                </div>
            </div>
        </div>
    </form>
</div>
