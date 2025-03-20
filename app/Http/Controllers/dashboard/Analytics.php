<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SentEmail;
use App\Models\Clients;

class Analytics extends Controller

{

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $sentEmails = SentEmail::with('client')->latest()->paginate(10);
    return view('content.dashboard.dashboards-analytic' , compact('sentEmails'));
  }
}
