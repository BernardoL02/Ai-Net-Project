<?php

namespace App\Http\Controllers;


use App\Models\Theater;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TheaterFormRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TheaterController extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Theater::class);
    }

    public function index(Request $request): View
    {
        $query = Theater::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        $theaters = $query->paginate(10)->appends($request->all());

        return view('theaters.index', compact('theaters'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $theater = new Theater();
        return view('theaters.create',compact('theater'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TheaterFormRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $theater = Theater::create([
            'name' => $validatedData['name'],
        ]);

        if ($request->has('seat_data')) {
            $seatData = json_decode($request->input('seat_data'), true);
            foreach ($seatData as $seatInfo) {
                $theater->seats()->create([
                    'row' => $seatInfo['row'],
                    'seat_number' => $seatInfo['seat_number'],
                    'theater_id' => $theater->id,
                ]);
            }
        }

        if ($request->hasFile('photo_file')) {
            $path = $request->photo_file->store('public/theaters');
            $theater->photo_url = basename($path);
            $theater->save();
        }

        $url = route('theaters.show', ['theater' => $theater]);
        $htmlMessage = "Theater <a href='$url'><u>{$theater->name}</u></a> ({$theater->abbreviation}) has been created successfully!";

        return redirect()->route('theaters.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function show(Theater $theater): View
    {
        return view('theaters.show', compact('theater'));
    }

    public function edit(Theater $theater): View
    {
        $theater->load('seats'); // Carregar os assentos associados
        return view('theaters.edit', compact('theater'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TheaterFormRequest $request, Theater $theater): RedirectResponse
    {
        $theater->update($request->validated());

        if ($request->hasFile('photo_file')) {
            // Delete previous file (if any)
            if ($theater->photo_url && Storage::exists('public/theaters/' . $theater->photo_url)) {
                Storage::delete('public/theaters/' . $theater->photo_url);
            }
            $path = $request->photo_file->store('public/theaters');
            $theater->photo_url = basename($path);
            $theater->save();
        }

        if ($request->has('seat_data')) {
            $seatData = json_decode($request->input('seat_data'), true);
            $theater->seats()->delete();
            foreach ($seatData as $seatInfo) {
                $theater->seats()->create([
                    'row' => $seatInfo['row'],
                    'seat_number' => $seatInfo['seat_number'],
                    'status' => $seatInfo['status'],
                    'theater_id' => $theater->id,
                ]);
            }
        }

        $url = route('theaters.show', ['theater' => $theater]);
        $htmlMessage = "Theater <a href='$url'><u>{$theater->name}</u></a> ({$theater->id}) has been updated successfully!";

        return redirect()->route('theaters.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(Theater $theater): RedirectResponse
    {
        try {
            $url = route('theaters.show', ['theater' => $theater]);
            $totalScreenings = $theater->screenings()->count();
            $totalSeats = $theater->seats()->count();
            if ($totalScreenings == 0 && $totalSeats == 0) {
                $theater->delete();
                $alertType = 'success';
                $alertMsg = "Theater {$theater->name} ({$theater->id}) has been deleted successfully!";
            } else {
                $alertType = 'warning';
                $screeningsStr = match (true) {
                    $totalScreenings <= 0 => "",
                    $totalScreenings == 1 => "there is 1 screewning enrolled in it",
                    $totalScreenings > 1 => "there are $totalScreenings screenings enrolled in it",
                };
                $theaterStr = match (true) {
                    $totalSeats <= 0 => "",
                    $totalSeats == 1 => "it already has 1 seat",
                    $totalSeats > 1 => "it already has $totalSeats seats",
                };
                $justification = $screeningsStr && $theaterStr
                    ? "$theaterStr and $screeningsStr"
                    : "$theaterStr$screeningsStr";
                $alertMsg = "Theater <a href='$url'><u>{$theater->name}</u></a> ({$theater->abbreviation}) cannot be deleted because $justification.";
            }
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the theater
                            <a href='$url'><u>{$theater->name}</u></a> ({$theater->abbreviation})
                            because there was an error with the operation!";
        }
        return redirect()->route('theaters.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }

    public function destroyPhoto(Theater $theater): RedirectResponse
    {
        if ($theater->user->photo_filename) {
            if (Storage::fileExists('public/theaters/' . $theater->user->photo_url)) {
                Storage::delete('public/theaters/' . $theater->user->photo_url);
            }
            $theater->user->photo_filename = null;
            $theater->user->save();
        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', "Photo of theater {$theater->name} has been deleted.");
        }
        return redirect()->back();
    }
}
