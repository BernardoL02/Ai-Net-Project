<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
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

        $request->user()->save();


        $customer = Customer::where('id', $request->user()->id)->first();

        if (!$customer) {
            $customer = new Customer();
            $customer->user_id = $request->user()->id;
        }

        $customer->nif = $request->nif;
        $customer->payment_type = $request->payment_type;
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
}
