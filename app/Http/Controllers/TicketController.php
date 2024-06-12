<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // TODOS , pode ser visto pelo admin , com o filto pelos screenings, e talvez pelas purchases
    {

        $tickets = Ticket::paginate(20);
    /*
        $filterByScreening = $request->input('screening');
       // $filterByPurchase = $request->input('purchase');

        $ticketQuery = Ticket::query();
        if ($filterByScreening !== null) {
            $ticketQuery->where('course', $filterByScreening);
        }

       if ($filterByPurchase !== null) {
            $ticketQuery->where('year', $filterByPurchase);
        }

       if ($filterByTeacher !== null) {
            $disciplinesQuery->with('teachers.user')->whereHas(
                'teachers.user',
                function ($userQuery) use ($filterByTeacher) {
                    $userQuery->where('name', 'LIKE', '%' . $filterByTeacher . '%');
                }
            );
        }*/

        return view(
            'tickets.index',
            compact('tickets')
        );


    }

    //All customer tickets user e o admin
    public function myTickets(Request $request)
    {



    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show')
            ->with('ticket', $ticket);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
