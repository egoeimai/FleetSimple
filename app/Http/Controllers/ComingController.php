<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use Illuminate\Http\Request;
use App\Models\Payments;
use App\Models\ComingModel;

class ComingController extends Controller
{
    public function index()
    {

        return view('content.pages.cominguser');
    }

    public function search(Request $request, Clients $client, Payments $payment)
    {

        // Process the form data and perform any necessary actions
        // For demonstration purposes, we'll just return a response.
        $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
        $client = Clients::where('phoneNumber', $request->email)->orderBy('id', 'DESC')->first();
        $payment = Payments::where('clientid', $client->id)->orderBy('month', 'DESC')->first();

        $last_day_this_month  = date('m-t-Y');
        $modal = "success-modal";
        $text_payment = "Έχει πληρώσει";
        $text_doc = " ";


        if ($client) {
            if ($client->doctor == 0) {
                $modal = "no-modal-doc";
                $text_doc .= " Λείπει Κάρτα Υγείας";
            }
        }

        if ($client) {
            if ($client->dltio == 0) {
                $modal = "no-modal-doc";
                $text_doc .= " Λείπει Δελτίο Ομοσπονδίας";
            }
        }

        if ($payment) {
            if ($payment->month < $first_day_this_month && date('Y-m-d') > $payment->month) {
                $modal = "no-payment";
                $text_payment = "Δεν έχει πληρώσει";
            }
        } else {
            $modal = "no-payment";
            $text_payment = "Δεν έχει πληρώσει";
        }


        ComingModel::create(['clientid' =>  $client->id, 'payment' =>  $text_payment]);

        return response()->json(['modal' => $modal, 'text_doc' => $text_doc]);
    }
}
