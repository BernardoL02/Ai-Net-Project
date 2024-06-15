<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = User::where('type', '!=', 'C');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', '=', $request->email);
        }

        if ($request->filled('type')) {
            $query->where('type', '=', $request->type);
        }

        $adminAndEmployees = $query->paginate(10);

        return view('users.index', compact('adminAndEmployees'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => 'required|string|min:9',
            'type' => 'required|string|in:A,E',
        ]);

        $user = DB::transaction(function () use ($request){
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'type' => $request->type,
            ]);
            Customer::create(['id'=>$user->id]);
            return $user;
        });

        return back()->with('alert-type', 'success')->with('alert-msg', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'mode' => 'show'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'mode' => 'edit'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255',  Rule::unique(User::class)->ignore($user->id)],
            'password' => 'nullable|string|min:9',
            'type' => 'required|string|in:A,E',
        ]);

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('type')) {
            $user->type = $request->type;
        }
        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('alert-type', 'success')->with('alert-msg', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if(Auth::user()->id == $user->id){
            return back()->with('alert-type', 'warning')->with('alert-msg', 'You can\'t remove yourself!');
        }

        $customer = Customer::where('id', $user->id)->first();

        if ($customer) {

            $purchases = Purchase::where('customer_id', $customer->id)->exists();

            if ($purchases) {
                return back()->with('alert-type', 'danger')->with('alert-msg', 'The user cannot be deleted because they are associated with purchases!');
            }
        }

        $user->delete();

        return back()->with('alert-type', 'success')->with('alert-msg', 'User deleted successfully.');
    }
}
