<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Vehicle;
use Carbon\Carbon;


class ClientsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {


        $clients = Clients::with('vehicles')->orderBy('id', 'desc')->paginate(100); // Eager load vehicles

        //$clients = Clients::orderBy('id', 'desc')->paginate(5);
        $clientsCount = $clients->count();

        return view('clients.clients', compact('clients', 'clientsCount'));
    }


    public function filter(Request $request)
    {

        $clients = Clients::orderBy('id', 'desc')->paginate(20);
        $clientsCount = $clients->count();

        return view('clients.clients', compact('clients', 'clientsCount'));
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create-client');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([

            'email' => 'required|email|unique:clients,email',
            'old_id' => 'required',
        ]);

        if ($request->file('image')) {
            $image_path = $request->file('image')->store('image', 'public');
        } else {
            $image_path = "";
        }
        $request->request->add(['image' =>  $image_path]); //add request





        Clients::create($request->post());

        return redirect()->route('clients')->with('success', 'Company has been created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Clients  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clients $clients)
    {
        $clients->delete();
        return redirect()->route('clients')->with('success', 'Company has been Updated successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Clients  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Clients $client)
    {
        $client->load(['vehicles.subscriptions.service']); // Includes service details in subscriptions
        
        $upcomingRenewals = $client->vehicles
        ->flatMap->subscriptions // Flatten subscriptions from all vehicles
        ->filter(function ($subscription) {
            return $subscription->status === 'active' &&
                   Carbon::parse($subscription->renewal_date)->between(Carbon::now(), Carbon::now()->addDays(30));
        });
        
        //dd($client->vehicles);
        
        $vehicleCount = $client->vehicles->count();

        // Count the number of unique services
        $serviceCount = $client->vehicles
            ->flatMap->subscriptions // Flatten subscriptions from all vehicles
            ->filter(function ($subscription) {
                return $subscription->status === 'active'; // Filter only active subscriptions
            })
            ->pluck('service_id') // Extract service IDs
            ->count();
        return view('clients.edit', compact('client', 'upcomingRenewals', 'vehicleCount', 'serviceCount'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Clients $Client)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required',
            'phoneNumber' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        unset($request['email']);
        unset($request['_method']);

        unset($request['_token']);
        if ($request->dltio) {
            $dltio = $request->dltio;
        } else {
            $dltio = 0;
        }


        if ($request->doctor) {
            $doctor = $request->doctor;
        } else {
            $doctor = 0;
        }


        $category_athlete = $request->category_athlete;

        $result = DB::table('clients')->where('id', $request->clientid)->update(array('dltio' => $dltio, 'zipCode' =>  $request->zipCode, 'firstName' =>  $request->firstName, 'lastName' =>  $request->lastName, 'phoneNumber' =>  $request->phoneNumber, 'birth_day' =>  $request->birth_day, 'address' =>  $request->address, 'state' =>  $request->state, 'doctor' =>  $doctor, 'category_athlete' => $category_athlete));


        if ($result) {
            return redirect()->route('clients')->with('success', 'Ο Αθλητης έχει ενημερωθεί');
        } else {
            return redirect()->route('clients')->with('success', 'Ο Αθλητης δεν έχει ενημερωθεί');
        }
    }
    
    

public function upcomingRevenueByClient($clientId, $year = null)
{

    $client = Clients::with(['vehicles.subscriptions.service'])->findOrFail($clientId);
    // Set default year to the current year if not provided
    $year = $year ?? request()->input('year', Carbon::now()->year);

    // Generate years array for the dropdown
    $years = range(Carbon::now()->year, 2000);

    // Filter subscriptions for the specified year
    $subscriptionsForYear = $client->vehicles
        ->flatMap->subscriptions
        ->filter(function ($subscription) use ($year) {
            return $subscription->status === 'active' &&
                   Carbon::parse($subscription->renewal_date)->year == $year;
        });

    // Calculate total revenue for the year
    $totalRevenue = $subscriptionsForYear->sum('total_cost');

    return view('clients.upcoming_revenue', compact('client', 'subscriptionsForYear', 'totalRevenue', 'year', 'years'));
}

}
