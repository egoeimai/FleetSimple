<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Vehicle $vehicle)
    {
        //
        $services = Service::all(); // Fetch all available services
        return view('subscriptions.create', compact('vehicle', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_id' => 'required|exists:services,id',
            'total_cost' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'renewal_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,expired,renewed',
            'amount_due' => 'nullable|numeric|min:0',
        ]);


        Subscription::create($validated);

        return redirect()->route('vehicles.edit', $request->vehicle_id)->with('success', 'Subscription added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscription $subscription)
    {
        $services = Service::all(); // Fetch all available services
        return view('subscriptions.edit', compact('subscription', 'services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'total_cost' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'renewal_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,expired,renewed',
            'amount_due' => 'nullable|numeric|min:0',
        ]);

        $subscription->update($validated);

        return redirect()->route('vehicles.edit', $subscription->vehicle_id)->with('success', 'Subscription updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return redirect()->route('vehicles.edit', $subscription->vehicle_id)->with('success', 'Subscription deleted successfully!');
    }
    
    public function renew(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $renewalPeriod = $request->input('renewal_period');
        $customDate = $request->input('custom_date');
    
        // Calculate the new renewal date
        $newRenewalDate = $renewalPeriod === 'custom' ? $customDate : now()->addMonths($renewalPeriod);
    
        // Create a new subscription with the same data
        $newSubscription = $subscription->replicate();
        $newSubscription->start_date = $subscription->renewal_date; // New start date is the current renewal date
        $newSubscription->renewal_date = $newRenewalDate;
        $newSubscription->status = 'active'; // Set status to active
        $newSubscription->save();
    
        // Update the current subscription's status to 'Renewal'
        $subscription->status = 'renewed';
        $subscription->save();
    
        return redirect()->back()->with('success', 'Subscription renewed successfully!');
    }
    

}
