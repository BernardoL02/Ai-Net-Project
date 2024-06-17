<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

        if ($request->filled('state')) {
            $query->where('blocked', '=', $request->state);
        }

        $allCustomers = $query->paginate(10);

        return view('customers.index', compact('allCustomers'));
    }

    public function myPurchases(Request $request)
    {
        if(!Auth::user()){
            return redirect(route('login', absolute: false))->with('alert-type', 'danger')->with('alert-msg', 'You need to log in to view your purchases.');
        }

        $validator = Validator::make($request->all(), [
            'type' => 'nullable|string|in:MBWAY,PAYPAL,VISA', // Assume que estes são os tipos de pagamento válidos
            'price' => 'nullable|numeric|min:0',
            'priceOption' => 'nullable|integer|in:0,1',
            'email' => 'nullable|email',
            'date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $filterByPaymentType = $request->query('type');
        $filterByPrice = $request->input('price');
        $filterByPriceOption= $request->priceOption;

        $customer = auth()->user()->customer;
        if(!$customer){
            $numberPurchases = 0;
            return view('customers.my-purchases',compact('numberPurchases'));
        }

        $purchasesQuery = $customer->purchases();

        if ($filterByPaymentType !== null) {
            $purchasesQuery->where('payment_type', $filterByPaymentType);
        }

        if($request->filled('email')){
            $purchasesQuery->where('customer_email', $request->email)->pluck('customer_email');
        }

        if($request->filled('date')){
            $purchasesQuery->where('date', $request->date)->pluck('date');
        }

        if ($filterByPrice !== null) {
            if($filterByPriceOption == 0){
                $purchasesQuery->where('total_price','>=' , $filterByPrice);

            }else{
                $purchasesQuery->where('total_price','<=', $filterByPrice);

            }
        }

        $purchases = $purchasesQuery->orderby('customer_name')->paginate(20)->withQueryString();                                /* ->orderby('total_price') */
        $numberPurchases = $purchases->count();

        return view('customers.my-purchases', compact('purchases','numberPurchases','filterByPaymentType','filterByPrice','filterByPriceOption'));
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
