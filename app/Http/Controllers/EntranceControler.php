<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use App\Models\Clients;
use App\Models\ComingModel;


use Illuminate\Http\Request;

class EntranceControler extends Controller
{
    public function index(Clients $client, ComingModel $coming)
    {
        $coming = ComingModel::where('clientid', $client->id)->orderBy('id', 'DESC')->get();
        return view('content.pages.endrances',  compact('coming'), compact('client'));
    }


    public function gomonth(Request $request, Clients $client, ComingModel $coming)
    {

        // Process the form data and perform any necessary actions
        // For demonstration purposes, we'll just return a response.
        $month = Carbon::createFromFormat('Y-m', $request->month)->format('Y-m-01 h:m');
        $month_end = Carbon::parse($month)->endOfMonth();
        $client = Clients::where('id', $request->clientid)->orderBy('id', 'DESC')->first();

        $coming = ComingModel::where('clientid',  $request->clientid)->where('created_at', '>', $month)->where('created_at', '<', $month_end)->orderBy('id', 'DESC')->get();
        //return view('content.pages.endrances',  compact('coming'), compact('client'));
        return response()->json(['modal' => $coming]);
    }

    public function date_entrances(Request $request, ComingModel $coming)
    {

        // Process the form data and perform any necessary actions
        // For demonstration purposes, we'll just return a response.


        $date = Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m-d H:i:s');
        $date = Carbon::parse($date)->startOfDay();
        $date_end = Carbon::parse($date)->endOfDay();
        $start = $date->toDateTimeString();
        $end = $date_end->toDateTimeString();

        $coming = ComingModel::select('coming_models.created_at as coming_date', 'coming_models.payment', 'clients.*')->rightJoin('clients', 'clients.id', '=', 'coming_models.clientid')->where('coming_models.created_at', '>', $start)->where('coming_models.created_at', '<', $end)->orderBy('coming_models.id', 'DESC')->get();
        //return view('content.pages.endrances',  compact('coming'), compact('client'));
        return response()->json(['modal' => $coming]);
    }
}
