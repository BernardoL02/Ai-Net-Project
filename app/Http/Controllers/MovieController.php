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

        if ($request->filled('query')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->query('query') . '%')
                  ->orWhere('synopsis', 'like', '%' . $request->query('query') . '%');
            });
        }

        if ($request->filled('genre')) {
            $query->where('genre_code', $request->query('genre'));
        }

        $genres = Genre::all();

        $arrayGenresCode = $genres->pluck('name', 'code')->toArray();
        $arrayGenresCode = array(' ' => 'All Genres') + $arrayGenresCode;

        $screenings = Screening::whereBetween('date', [$date, $endDate])->pluck('movie_id');

        $moviesByScreening = $query->whereIn('id', $screenings)->get();

        $selectedGenre = $request->query('genre', '');

        return view('movies.index', compact('moviesByScreening', 'genres', 'selectedGenre','arrayGenresCode'));

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

    public function search(Request $request): View
    {
        return $this->index($request);
    }
}
