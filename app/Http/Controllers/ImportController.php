<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientsImport;
use App\Imports\VehiclesImport;
use App\Imports\SubscriptionsImport;

class ImportController extends Controller
{
    //
public function importClients(Request $request)
{
    Excel::import(new ClientsImport, $request->file('file'));
    return back()->with('success', 'Clients imported successfully.');
}

public function importVehicles(Request $request)
{
    Excel::import(new VehiclesImport, $request->file('file'));
    return back()->with('success', 'Vehicles imported successfully.');
}

public function importSubscriptions(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,csv',
    ]);

    Excel::import(new SubscriptionsImport, $request->file('file'));

    return back()->with('success', 'Subscriptions imported successfully.');
}
}
