<?php

namespace App\Http\Controllers;

use App\Models\SentEmail;
use App\Models\Clients;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Subscription;
use Carbon\Carbon;

class MainDashboard extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {

        $now = Carbon::now();
        $endDate = Carbon::now()->addDays(30);
        
        $totalClients = Clients::count();

        // Total Vehicles
        $totalVehicles = Vehicle::count();

        // Total Revenue for the Current Year
        $currentYear = Carbon::now()->year;
        $totalRevenue = Subscription::whereYear('renewal_date', $currentYear)
            ->sum('total_cost');

        // Get all clients with active subscriptions that will expire in the next 5 days
        $upcomingClients = Clients::whereHas('vehicles.subscriptions', function ($query) use ($now, $endDate) {
            $query->where('status', 'active')
                ->whereBetween('renewal_date', [$now->toDateString(), $endDate->toDateString()]);
        })->with(['vehicles.subscriptions' => function ($query) use ($now, $endDate) {
            $query->where('status', 'active')
                ->whereBetween('renewal_date', [$now->toDateString(), $endDate->toDateString()])
                ->with('service', 'vehicle');
        }])->get();
        
        
        $sentEmails = SentEmail::with('client')->latest()->paginate(10);
        
        return view('content.dashboard.dashboards-analytics' , compact('sentEmails', 'upcomingClients', 'totalClients', 'totalVehicles', 'totalRevenue'));
    }
    
    public function show($id)
    {
        $sentEmail = SentEmail::with('client')->findOrFail($id);
        return view('emails.display_email', compact('sentEmail'));
    }
    

}
