<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Clients;
use Illuminate\Http\Request;

class AthletsPaymentsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $now = Carbon::now();

        $payments = DB::table('payments')->select('payments.*')->whereBetween('payments.payment_date', [date('Y-m-01'), now()]);

        $clients = DB::table('clients')->select('clients.*', 'payments.*', DB::raw('count(coming_models.id) as comes'))->leftJoin('coming_models', 'coming_models.clientid', '=', 'clients.id')->leftjoinSub($payments, 'payments', function ($join) {
            $join->on('clients.id', '=', 'payments.clientid');
        })->whereBetween('coming_models.created_at', [date('Y-m-01'), now()])->groupBy('clients.id')->get();
        $clientsCount = $clients->count();
        return view('content.pages.athletspayments', compact('clients', 'clientsCount'));
    }


    public function get_payment_earnings(Request $request)
    {

        // Process the form data and perform any necessary actions
        // For demonstration purposes, we'll just return a response.
        $month = Carbon::createFromFormat('Y-m', $request->month)->format('Y-m-01 h:m');

        $payments = DB::table('payments')->select('payments.*')->whereBetween('payments.month', [$month, date("Y-m-t h:m", strtotime($month))]);

        $clients = DB::table('clients')->select('clients.*', 'payments.*', DB::raw('count(coming_models.id) as comes'))->leftJoin('coming_models', 'coming_models.clientid', '=', 'clients.id')->leftjoinSub($payments, 'payments', function ($join) {
            $join->on('clients.id', '=', 'payments.clientid');
        })->whereBetween('coming_models.created_at', [$month, date("Y-m-t h:m", strtotime($month))])->groupBy('clients.id')->get();
        $clientsCount = $clients->count();


        //return view('content.pages.endrances',  compact('coming'), compact('client'));
        return response()->json(['modal' => $clients, 'count' => $clientsCount]);
    }
}
