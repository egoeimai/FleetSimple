<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Clients;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    //
    public function create(Clients $client)
    {
        return view('vehicles.create', compact('client'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'license_plate' => 'required|string|unique:vehicles,license_plate|max:255',
            'enable_reminder' => 'required|boolean',
        ]);

        Vehicle::create($validated);

        return redirect()->route('clients.edit', $request->client_id)->with('success', 'Vehicle added successfully!');
    }

    public function edit(Vehicle $vehicle)
    {
        $services = Service::all(); // Fetch all available services
        $subscriptions = $vehicle->subscriptions; // Fetch subscriptions for the vehicle
        $clients = Clients::all(); // Fetch all clients in case you want to allow changing the client
        return view('vehicles.edit', compact('vehicle', 'clients', 'subscriptions', 'services'));
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return redirect()->route('clients.edit', $vehicle->client_id)->with('success', 'Vehicle deleted successfully!');
    }


    public function update(Request $request, Vehicle $vehicle)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'brand' => 'nullable|string|max:255',
                'model' => 'nullable|string|max:255',
                'license_plate' => 'required|string|unique:vehicles,license_plate,' . $vehicle->id,
                'enable_reminder' => 'required|boolean',
            ]);

            // Attempt to update the vehicle
            $vehicle->update($validated);


            return redirect()
                ->route('clients.edit', $vehicle->client_id)
                ->with('success', 'Vehicle updated successfully!');
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return redirect()
                ->back()
                ->withInput() // Preserve input values
                ->withErrors(['error' => 'An error occurred while updating the vehicle. Please try again.']);
        }
    }
}
