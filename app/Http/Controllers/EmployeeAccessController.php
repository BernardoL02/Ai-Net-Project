<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Movie;
use App\Models\Ticket;
use App\Models\Theater;
use App\Models\Screening;
use Illuminate\View\View;
use Illuminate\Http\Request;

class EmployeeAccessController extends Controller
{
    public function index(): View
    {
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addWeeks(2);

        $movies = Movie::whereHas('screenings', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        })->orderBy('title')->pluck('title', 'title')->toArray();

        $theaters = Theater::orderBy('name')->pluck('name', 'name')->toArray();

        return view('employees.index', compact('theaters','movies'));
    }

    public function getTicketsOfScreeningSession(Request $request)
    {
        $movieTitle = $request->input('movie');
        $theaterName = $request->input('theater');
        $date = $request->input('date');
        $time = $request->input('start_time');

        $tickets = Ticket::whereHas('screening', function ($query) use ($movieTitle, $theaterName, $date, $time) {

            $query->where('date', $date)->where('start_time', $time)->whereHas('movie', function ($query) use ($movieTitle) {
                $query->where('title', $movieTitle);

            })->whereHas('theater', function ($query) use ($theaterName) {
                        $query->where('name', $theaterName);
            });

        })->with(['screening.movie', 'screening.theater'])->paginate(20);;

        if ($tickets->isEmpty()) {
            return back()->with('alert-type', 'danger')->with('alert-msg', 'No tickets found for the specified screening session!');
        }

        session([
            'movie' => $movieTitle,
            'theater' => $theaterName,
            'date' => $date,
            'start_time' => $time
        ]);


        if ($request->filled('id')) {
            $tickets->where('id', $request->id);
        }

        if ($request->filled('qrcode')) {
            $tickets->where('qrcode_url', $request->qrcode);
        }

        $screening = Screening::where('date', $date)
            ->where('start_time', $time)
            ->whereHas('movie', function ($query) use ($movieTitle) {
            $query->where('title', $movieTitle);
        })
        ->whereHas('theater', function ($query) use ($theaterName) {
            $query->where('name', $theaterName);
        })->first();

        return view('employees.get_tickets_of_screening_session', compact('tickets','screening'));
    }

    public function applyAdditionalFilters(Request $request)
    {
        $movieTitle = session('movie');
        $theaterName = session('theater');
        $date = session('date');
        $time = session('start_time');

        $query = Ticket::whereHas('screening', function ($query) use ($movieTitle, $theaterName, $date, $time) {
            $query->where('date', $date)
                ->where('start_time', $time)
                ->whereHas('movie', function ($query) use ($movieTitle) {
                    $query->where('title', $movieTitle);
                })
                ->whereHas('theater', function ($query) use ($theaterName) {
                    $query->where('name', $theaterName);
                });
        });

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }

        if ($request->filled('qrcode')) {
            $query->where('qrcode_url', $request->qrcode);
        }

        $tickets = $query->with(['screening.movie', 'screening.theater'])->paginate(20);

        if ($tickets->isEmpty()) {
            return back()->with('alert-type', 'danger')->with('alert-msg', 'No tickets found with the applied filters!');
        }

        $screening = Screening::where('date', $date)
            ->where('start_time', $time)
            ->whereHas('movie', function ($query) use ($movieTitle) {
                $query->where('title', $movieTitle);
            })
            ->whereHas('theater', function ($query) use ($theaterName) {
                $query->where('name', $theaterName);
            })->first();

        return view('employees.get_tickets_of_screening_session', compact('tickets', 'screening'));
    }

    public function validateTicket(Ticket $ticket){

        if($ticket->status == 'invalid'){
            $ticket->status = 'valid';
            $ticket->save();
            return back()->with('alert-type', 'success')->with('alert-msg', 'Ticket validated successfully.');
        }
        else{
            return back()->with('alert-type', 'warning')->with('alert-msg', 'The ticket was already valid.');
        }
    }

    public function invalidateTicket(Ticket $ticket){

        if($ticket->status == 'valid'){
            $ticket->status = 'invalid';
            $ticket->save();
            return back()->with('alert-type', 'success')->with('alert-msg', 'Ticket invalidated successfully.');
        }
        else{
            return back()->with('alert-type', 'warning')->with('alert-msg', 'The ticket was already invalid.');
        }
    }
}
