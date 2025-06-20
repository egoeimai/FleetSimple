<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduledEmail;
use App\Models\Clients;

use Illuminate\Support\Facades\Mail;

class ScheduledEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    public function sendNow($id)
{
    $email = \App\Models\ScheduledEmail::with('client')->findOrFail($id);

    if ($email->sent) {
        return back()->with('error', 'Το email έχει ήδη σταλεί.');
    }

    
    $this->sendReminderEmail($email);

    return back()->with('success', 'Το email εστάλη επιτυχώς!');
}

    private function sendReminderEmail($email)
    {
        $client = $email->client;
        $groupedSubscriptions = $email->subscriptions; // Already array
        $totalCost = $email->total_cost;

        // Set greeting based on send_date (not now), for accurate future email rendering
        $sendDate = $email->send_date instanceof \Carbon\Carbon ? $email->send_date : \Carbon\Carbon::parse($email->send_date);

        if ($sendDate->day == 1) {
            $greeting = "Καλημέρα και Καλό Μήνα";
        } elseif ($sendDate->isMonday()) {
            $greeting = "Καλημέρα και Καλή Εβδομάδα";
        } else {
            $greeting = "Καλημέρα!";
        }

        $settings = \App\Models\Setting::first();
        $customGreeting = $settings->greeting_text ?? '';

        Mail::send('emails.subscription_reminder', [
            'client' => $client,
            'groupedSubscriptions' => $groupedSubscriptions,
            'totalCost' => $totalCost,
            'greeting' => $greeting,
            'customGreeting' => $customGreeting,
        ], function ($message) use ($client) {
           $message->to(['nziozas@gmail.com', 'gstavrou@fleetsimple.gr'])
                ->subject("Subscription Reminder");
        });

        $email->update([
            'sent' => true,
            'sent_at' => now(),
        ]);
    }
}
