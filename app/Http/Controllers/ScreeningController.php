<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Screening;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScreeningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $date = date("Y-m-d");
        $endDate = date("Y-m-d", strtotime("+2 weeks"));

        // Initialize the query for filtering
        $query = Screening::query();

        // Join movies table to get poster filename
        $query->select('screenings.*', 'movies.poster_filename')
              ->join('movies', 'movies.id', '=', 'screenings.movie_id');


        // Filter by genre if provided
        if ($request->filled('genre')) {
            $query->where('movies.genre_code', $request->query('genre'));
        }

        // Filter by date if provided
        if ($request->filled('date') && $request->date != '-') {
            $query->where('screenings.date', $request->date);
        }

        // Filter by movie_id if provided
        if ($request->filled('movie_id')) {
            $query->where('screenings.movie_id', $request->movie_id);
        }

        // Fetch the filtered screenings based on date, genre, and movie_id filters
        $filteredScreenings = $query->paginate(10);

        // Get unique dates for the date filter dropdown
        $screeningByDates = Screening::whereBetween('date', [$date, $endDate])
            ->pluck('date')
            ->unique()
            ->toArray();

        $screeningByDates = array_combine($screeningByDates, $screeningByDates);
        $screeningByDates = collect($screeningByDates)->sort();
        $screeningByDates = array('-' => 'All Dates') + $screeningByDates->toArray();

        // Get genres for the genre filter dropdown
        $genres = Genre::all();
        $arrayGenresCode = $genres->pluck('name', 'code')->toArray();

        // Fetch all screenings for initial display
        $allScreenings = Screening::paginate(10);

        // Determine whether to use filtered screenings or all screenings
        $screenings = $filteredScreenings->isEmpty() && !$request->filled('date') && !$request->filled('genre') && !$request->filled('movie_id')
            ? $allScreenings
            : $filteredScreenings;

        // Return the view with the necessary data
        return view('screenings.index', [
            'screenings' => $screenings,
            'arrayGenresCode' => $arrayGenresCode,
            'screeningByDates' => $screeningByDates,
        ]);
    }

    public function create()
    {
        return view('screenings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'movie_id' => 'required|bigint',
            'theater_id' => 'required|bigint',
            'date' => 'required|date',
            'start_time' => 'required|time',
            'custom' => 'nullable|json',
        ]);

        Screening::create($validatedData);

        return redirect()->route('screenings.index')->with('success', 'Screening created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Screening $screening)
    {
        return view('screenings.show', compact('screening'));
    }


    public function edit(Screening $screening)
    {

        $screeningDateTime = Carbon::parse($screening->date . ' ' . $screening->start_time);

        if ($screeningDateTime->isPast()) {
            return redirect()->route('screenings.index')
                             ->with('error', 'You cannot edit a screening that has already occurred.');
        }

        return view('screenings.edit', compact('screening'));
    }


    public function update(Request $request, Screening $screening)
    {
        $validatedData = $request->validate([
            'movie_id' => 'required|integer',
            'theater_id' => 'required|integer',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
        ]);

        $screening->update($validatedData);

        return redirect()->route('screenings.index')->with('success', 'Screening updated successfully.');
    }

    public function destroy(Screening $screening)
    {
        $screening->delete();

        return redirect()->route('screenings.index')->with('success', 'Screening deleted successfully.');
    }

    public function showCase(Request $request, Screening $screening)
    {
        $screening = Screening::find($screening->id);
        $screeningsFull = false;

        return view('screenings.showcase', compact('screening'));
        if ($screening) {

            $max_seats = $screening->theater->seats->count();
            $occupiedSeats = (int)$screening->tickets()->count();

            if($max_seats == $occupiedSeats){
                $screeningsFull = true;
            }
        }

        return view('screenings.showcase', compact('screening','screeningsFull'));
    }
}






