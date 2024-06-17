<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Screening;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScreeningController extends Controller
{

    public function index(Request $request)
    {
        $date = date("Y-m-d");
        $endDate = date("Y-m-d", strtotime("+2 weeks"));

        $query = Screening::query();

        $query->select('screenings.*', 'movies.poster_filename', 'movies.title')
            ->join('movies', 'movies.id', '=', 'screenings.movie_id');

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

        // Filter by movie title if provided
        if ($request->filled('title')) {
            $query->where('movies.title', 'like', '%' . $request->title . '%');
        }

        // Fetch the filtered screenings based on date, genre, movie_id, and title filters
        $filteredScreenings = $query->paginate(10)->appends($request->query());

        // Get unique dates for the date filter dropdown
        $screeningByDates = Screening::whereBetween('date', [$date, $endDate])
            ->pluck('date')
            ->unique()
            ->toArray();

        $screeningByDates = array_combine($screeningByDates, $screeningByDates);
        $screeningByDates = collect($screeningByDates)->sort();
        $screeningByDates = array('-' => 'All Dates') + $screeningByDates->toArray();

        $genres = Genre::all();
        $arrayGenresCode = $genres->pluck('name', 'code')->toArray();

        $allScreenings = Screening::paginate(10);

        $screenings = $filteredScreenings->isEmpty() && !$request->filled('date') && !$request->filled('genre') && !$request->filled('movie_id') && !$request->filled('title')
            ? $allScreenings
            : $filteredScreenings;

        return view('screenings.index', [
            'screenings' => $screenings,
            'arrayGenresCode' => $arrayGenresCode,
            'screeningByDates' => $screeningByDates,
        ]);
    }

    public function storeMultiple(Request $request)
    {
        $validatedData = $request->validate([
            'movie_id' => 'required|integer',
            'theater_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_times' => 'required|string',
        ]);

        $movie_id = $validatedData['movie_id'];
        $theater_id = $validatedData['theater_id'];
        $start_date = Carbon::parse($validatedData['start_date']);
        $end_date = Carbon::parse($validatedData['end_date']);
        $start_times = explode(',', $validatedData['start_times']);

        $dates = [];
        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        $conflictingScreenings = [];

        foreach ($dates as $date) {
            foreach ($start_times as $start_time) {
                $existingScreening = Screening::where('theater_id', $theater_id)
                                              ->where('date', $date)
                                              ->where('start_time', trim($start_time))
                                              ->first();

                if ($existingScreening) {
                    $conflictingScreenings[] = ['date' => $date, 'start_time' => trim($start_time)];
                } else {
                    Screening::create([
                        'movie_id' => $movie_id,
                        'theater_id' => $theater_id,
                        'date' => $date,
                        'start_time' => trim($start_time),
                    ]);
                }
            }
        }

        if (count($conflictingScreenings) > 0) {
            $conflictMessage = 'Some screenings could not be created due to conflicts: ';
            foreach ($conflictingScreenings as $conflict) {
                $conflictMessage .= 'Date: ' . $conflict['date'] . ', Start Time: ' . $conflict['start_time'] . '; ';
            }
            return redirect()->back()->with('error', $conflictMessage);
        }

        return redirect()->route('screenings.index')->with('success', 'Screenings created successfully.');

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
            'movie_id' => 'required|integer',
            'theater_id' => 'required|integer',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'custom' => 'nullable|json',
        ]);

        $existingScreening = Screening::where('theater_id', $validatedData['theater_id'])
                                      ->where('date', $validatedData['date'])
                                      ->where('start_time', $validatedData['start_time'])
                                      ->first();

        if ($existingScreening) {
            return redirect()->back()->with('error', 'A screening already exists at the selected time in this theater.');
        }

        Screening::create($validatedData);

        return redirect()->route('screenings.index')->with('success', 'Screening created successfully.');
    }

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
