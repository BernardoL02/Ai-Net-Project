<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuration;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Redirect;

class ConfigurationController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $ticketPrice = DB::table('configuration')->where('id', '1')->value('ticket_price');
        $discount = DB::table('configuration')->where('id', '1')->value('registered_customer_ticket_discount');

        return view('configuration.edit', compact('ticketPrice','discount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'ticket_price' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
        ]);


        $config = Configuration::findOrFail(1);
        $config->ticket_price = $request->ticket_price;
        $config->registered_customer_ticket_discount = $request->discount;
        $config->save();

        return back()->with('status', 'configuration-updated');
    }
}
