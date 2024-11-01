<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Clients;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class ClientsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {

        if (request('search')) {
            $clients = Clients::where('firstName', 'like', '%' . request('search') . '%')->orWhere('lastName', 'like', '%' . request('search') . '%')->orWhere('phoneNumber', 'like', '%' . request('search') . '%')->orderBy('id', 'desc')->paginate(100);
        } else {
            $clients = Clients::orderBy('id', 'desc')->paginate(100);
        }
        //$clients = Clients::orderBy('id', 'desc')->paginate(5);
        $clientsCount = $clients->count();

        return view('content.pages.clients', compact('clients', 'clientsCount'));
    }


    public function filter(Request $request)
    {

        $clients = Clients::where('category_athlete', '=',   $request->category_athlete)->orderBy('id', 'desc')->paginate(100);
        //$clients = Clients::orderBy('id', 'desc')->paginate(5);
        $clientsCount = $clients->count();

        return view('content.pages.clients', compact('clients', 'clientsCount'));
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('content.pages.create-client');
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
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email|unique:clients,email',
            'phoneNumber' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
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
        return view('content.pages.pages-account-settings-account', compact('client'));
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
}
