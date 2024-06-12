@extends('layouts.main')

@section('header-title', 'Ticket for "' . $ticket->screening->movie->title . '"')

@section('main')
<div class="flex flex-col space-y-6">
    <div class="p-4 sm:p-8 bg-white dark:bg-gray-900 shadow sm:rounded-lg">
        <div class="max-full">
            <section>
            </section>
        </div>
    </div>
</div>
@endsection
