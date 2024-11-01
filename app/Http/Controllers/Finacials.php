<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\ExpensesModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Clients;

class Finacials extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    public function index()
    {

        return view('content.pages.financial-general');
    }

    public function earnings()
    {

        return view('content.pages.earnings');
    }
    public function expenses()
    {

        return view('content.pages.expenses');
    }


    public function get_earnings(Request $request, Clients $client)
    {

        // Process the form data and perform any necessary actions
        // For demonstration purposes, we'll just return a response.
        $month = Carbon::createFromFormat('Y-m', $request->month)->format('Y-m-01 h:m');
        $payments = Payments::leftJoin('clients', 'clients.id', '=', 'payments.clientid')->where('payment_date', '>=', $month)->where('payment_date', '<=', date("Y-m-t h:m", strtotime($month)))->orderBy('payments.id', 'DESC')->get();

        //return view('content.pages.endrances',  compact('coming'), compact('client'));
        return response()->json(['modal' => $payments]);
    }

    public function addexpense(Request $request)
    {

        $request->validate([
            'amount' => 'required',
            'payment_date' => 'required',
            'category' => 'required',
        ]);



        ExpensesModel::create($request->post());

        return redirect()->route('expenses')->with('success', 'Προστέθηκε με επιτυχία.');
    }


    public function get_expenses(Request $request)
    {

        // Process the form data and perform any necessary actions
        // For demonstration purposes, we'll just return a response.
        $month = Carbon::createFromFormat('Y-m', $request->month)->format('Y-m-01 h:m');
        $payments = ExpensesModel::where('payment_date', '>=', $month)->where('payment_date', '<=', date("Y-m-t h:m", strtotime($month)))->orderBy('id', 'DESC')->get();

        //return view('content.pages.endrances',  compact('coming'), compact('client'));
        return response()->json(['modal' => $payments]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Clients  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy_expenses(ExpensesModel $ExpensesModel, Request $request)
    {
        $ExpensesModel->delete();
        DB::table('expenses_models')->where('id', $request->value_id)->delete();
        return response()->json(['modal' => "success"]);
    }
}
