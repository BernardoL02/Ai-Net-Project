<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Screening;
use App\Models\Theater;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

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

        $filterByDate = $request->date??'-';
        $moviesByScreening = $query->whereIn('id', $screenings)->get();
        $selectedGenre = $request->query('genre', '');

        $screeningByDates = Screening::whereBetween('date', [$date, $endDate])->pluck('date')->unique()->toArray();
        $screeningByDates = array_combine($screeningByDates, $screeningByDates);
        $screeningByDates = collect($screeningByDates)->sort();
        $screeningByDates = array('-' => 'All Dates') + $screeningByDates->toArray();

        return view('movies.index', compact('moviesByScreening', 'genres', 'selectedGenre', 'arrayGenresCode', 'screeningByDates', 'filterByDate'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $movie = new Movie();
        $genres = Genre::orderBy("name")->pluck('name', 'code')->toArray();

        return view('movies.create', compact('movie', 'genres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newMovie = Movie::create($request->validated());

        if ($request->hasFile('photo_file')) {
            $path = $request->photo_file->store('public/posters');
            $newMovie->poster_filename = basename($path);
            $newMovie->save();
        }


        $url = route('movies.show', ['movie' => $newMovie]);
        $htmlMessage = "Movie <a href='$url'><u>{$newMovie->title}</u></a> ({$newMovie->id}) has been created successfully!";
        return redirect()->route('movies.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        $genres = Genre::orderBy("name")->pluck('name', 'code')->toArray();
        return view('movies.show', compact('movie', 'genres'));
    }

    public function showcase(Movie $movie, Request $request)
    {
        $date = date("Y-m-d");
        $endDate = date("Y-m-d", strtotime("+2 weeks"));
        $filterByDate = $request->date ?? '-';
        $filterByTime = $request->time ?? null;
        $theaterId = $request->theater_id ?? null;

        $screeningQuery = Screening::whereBetween('date', [$date, $endDate])
                                   ->where('movie_id', $movie->id);

        if ($filterByDate !== '-') {
            $screeningQuery->where('date', $filterByDate);
        }

        if ($filterByTime) {
            $screeningQuery->where('start_time', $filterByTime);
        }

        $screenings = $screeningQuery->get();
        $startTimes = $screenings->unique('start_time')->pluck('start_time')->toArray();

        $screeningByDates = $screenings->pluck('date')->unique()->toArray();
        $screeningByDates = array_combine($screeningByDates, $screeningByDates);
        $screeningByDates = ['-' => 'All Dates'] + $screeningByDates;

        $genres = Genre::orderBy("name")->pluck('name', 'code')->toArray();

        return view('movies.showcase', compact('movie', 'genres', 'screeningByDates', 'filterByDate', 'startTimes'));
    }

    public function screeningId(Movie $movie, Request $request)
    {
        $screening = Screening::where('movie_id', $movie->id)
                              ->where('date', $request->date)
                              ->first();

        if ($screening) {
            return redirect()->route('screenings.showcase', ['screening' => $screening->id]);
        } else {
            return redirect()->route('movies.showcase', $movie)
                             ->withErrors(['error' => 'Screening not found'])
                             ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie)
    {
        $genres = Genre::orderBy("name")->pluck('name', 'code')->toArray();
        return view('movies.edit', compact('movie', 'genres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        $movie->update($request->validated());

        if ($request->hasFile('photo_file')) {
            // Delete previous file (if any)
            if (
                $movie->poster_filename &&
                Storage::fileExists('public/posters/' . $movie->poster_filename)
            ) {
                Storage::delete('public/posters/' . $movie->poster_filename);
            }
            $path = $request->photo_file->store('public/posters');
            $movie->user->poster_filename = basename($path);
            $movie->user->save();
        }


        $url = route('movies.show', ['movie' => $movie]);
        $htmlMessage = "Movie <a href='$url'><u>{$movie->title}</u></a> ({$movie->id}) has been updated successfully!";
        return redirect()->route('movies.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        try {
            $url = route('movies.show', ['movie' => $movie]);
            $totalScreenings = $movie->screenings()->count();

            if ($totalScreenings == 0) {
                $movie->delete();
                $alertType = 'success';
                $alertMsg = "Movie {$movie->title} ({$movie->id}) has been deleted successfully!";
            } else {
                $alertType = 'warning';
                $justification = match (true) {
                    $totalScreenings <= 0 => "",
                    $totalScreenings == 1 => "there is 1 screening enrolled in it",
                    $totalScreenings > 1 => "there are $totalScreenings screenings enrolled in it",
                };

                $alertMsg = "Movie <a href='$url'><u>{$movie->title}</u></a> ({$movie->id}) cannot be deleted because $justification.";
            }
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the movie
                            <a href='$url'><u>{$movie->title}</u></a> ({$movie->id})
                            because there was an error with the operation!";
        }



        return redirect()->route('movies.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }

    public function destroyPhoto(Movie $movie): RedirectResponse
    {
        if ($movie->user->photo_url) {
            if (Storage::fileExists('public/posters/' . $movie->poster_filename)) {
                Storage::delete('public/posters/' . $movie->poster_filename);
            }
            $movie->poster_filename = null;
            $movie->save();
            return redirect()->back()
                ->with('alert-type', 'success')
                ->with('alert-msg', "Photo of movie $movie {$movie->title} has been deleted.");
        }
        return redirect()->back();
    }



}
