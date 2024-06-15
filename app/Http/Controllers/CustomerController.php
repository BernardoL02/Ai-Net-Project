<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('type', 'C')->join('customers', 'users.id', '=', 'customers.id');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', '=', $request->email);
        }

        if ($request->filled('nif')) {
            $query->where('nif', '=', $request->nif);
        }

        $allCustomers = $query->paginate(10);

        return view('customers.index', compact('allCustomers'));
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
    public function show(Customer $customer)
    {
        $user = $customer->user;

        return view('customers.show', [
            'user' => $user,
            'mode' => 'show'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $user = $customer->user;

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

    public function block(Customer $customer)
    {
        $user = $customer->user;

        if($user->blocked == 0){
            $user->blocked = 1;
            $user->save();
            return back()->with('alert-type', 'success')->with('alert-msg', 'Customer blocked successfully.');
        }
        else{
            $user->blocked = 0;
            $user->save();
            return back()->with('alert-type', 'success')->with('alert-msg', 'Customer unblocked successfully.');
        }
    }
}
