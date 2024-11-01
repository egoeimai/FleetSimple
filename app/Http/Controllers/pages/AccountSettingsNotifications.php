<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountSettingsNotifications extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }
  public function index()
  {
    return view('content.pages.pages-account-settings-notifications');
  }
}
