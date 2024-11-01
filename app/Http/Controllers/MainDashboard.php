<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainDashboard extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {

        return view('content.pages.maindashboard');
    }
}
