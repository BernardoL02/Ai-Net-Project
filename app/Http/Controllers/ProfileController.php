<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = Auth::user();

        $customer = Customer::with('user')
                            ->where('id', $user->id)
                            ->first();

        return view('profile.edit', [
            'user' => $user,
            'customer' => $customer,
            'editPassword' => false
        ]);
    }

    public function editPassword(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'editPassword' => true,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->hasFile('photo_file')) {
            $path = $request->photo_file->store('public/photos');
            $request->user()->photo_filename = basename($path);
        }

        $request->user()->save();

        $customer = Customer::where('id', $request->user()->id)->first();

        if (!$customer) {
            $customer = new Customer();
            $customer->id = $request->user()->id;
            $customer->nif = $request->nif ?? null;
            $customer->payment_type = $request->payment_type ?? null;
            $customer->payment_ref = $request->payment_ref ?? null;
        }
        else{
            if ($request->has('nif')) {
                $customer->nif = $request->nif;
            }

            if ($request->has('payment_type')) {
                $customer->payment_type = $request->payment_type;
            }

            if ($request->has('payment_ref')) {
                $customer->payment_ref= $request->payment_ref;
            }
        }

        $customer->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePhoto(Request $request, User $user)
    {
        $request->validate([
            'photo_file' => 'sometimes|image|max:4096',
        ]);

        if ($request->hasFile('photo_file')) {
            $path = $request->photo_file->store('public/photos');
            $user->photo_filename = basename($path);
            $user->save();
        }

        return back()->with('alert-type', 'success')->with('alert-msg', 'User photo updated successfully!');
    }

    public function destroyPhoto(User $user): RedirectResponse
    {
        if ($user->photo_filename) {

            if (Storage::fileExists('public/photos/' . $user->photo_filename)) {
                Storage::delete('public/photos/' . $user->photo_filename);
            }

            $user->photo_filename = null;
            $user->save();

            return redirect()->back()
                    ->with('alert-type', 'success')
                    ->with('alert-msg', "Photo has been deleted successfully.");
        }

        return redirect()->back();
    }
}
