<?php

namespace App\Http\Controllers\pages;

use App\Models\Clients;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Payments;

class AccountSettingsConnections extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index(Clients $client, Payments $payments)
  {

    $payments = Payments::where('clientid', $client->id)->orderBy('month', 'DESC')->get();
    return view('content.pages.pages-account-settings-connections', compact('payments'), compact('client'));
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
      'amount' => 'required',
      'payment_date' => 'required',
      'month' => 'required',
      'clientid' => 'required',
    ]);


    $request['month'] = Carbon::createFromFormat('Y-m', $request['month'])->format('Y-m-10');



    Payments::create($request->post());

    return redirect()->route('pages-account-settings-connections', $request['clientid'])->with('success', 'Η Πληρωμή Καταχωρήθηκε.');
  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Clients  $company
   * @return \Illuminate\Http\Response
   */
  public function delete(Payments $payment, Request $request)
  {

    $payment->delete();

    return redirect()->route('pages-account-settings-connections', $request->client)->with('success', 'Η Πληρωμή διαγράφθηκε');
  }
}
