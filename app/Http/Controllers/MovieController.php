<?php

namespace App\Http\Controllers;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Screening;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $date = date("Y-m-d");
        $endDate = date("Y-m-d", strtotime("+2 weeks"));

        $query = Movie::query();

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('synopsis')) {
            $query->where('synopsis', 'like', '%' . $request->synopsis . '%');
        }

        if ($request->filled('genre')) {
            $query->where('genre_code', $request->query('genre'));
        }

        $genres = Genre::all();
        $arrayGenresCode = $genres->pluck('name', 'code')->toArray();
        $arrayGenresCode = array(' ' => 'All Genres') + $arrayGenresCode;

        if($request->filled('date') && $request->date != '-') {

            $screenings = Screening::where('date', $request->date)->pluck('movie_id');

        } else {

            $screenings = Screening::whereBetween('date', [$date, $endDate])->pluck('movie_id');

        }

        $moviesByScreening = $query->whereIn('id', $screenings)->get();
        $selectedGenre = $request->query('genre', '');

        $screeningByDates = Screening::whereBetween('date', [$date, $endDate])->pluck('date')->unique()->toArray();
        $screeningByDates = array_combine($screeningByDates, $screeningByDates);
        $screeningByDates = collect($screeningByDates)->sort();
        $screeningByDates = array('-' => 'All Dates') + $screeningByDates->toArray();

        return view('movies.index', compact('moviesByScreening', 'genres', 'selectedGenre', 'arrayGenresCode', 'screeningByDates'));
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
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        //
    }

}
