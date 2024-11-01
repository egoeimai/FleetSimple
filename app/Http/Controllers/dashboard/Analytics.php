<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Analytics extends Controller

{

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    return view('content.dashboard.dashboards-analytics');
  }
}
