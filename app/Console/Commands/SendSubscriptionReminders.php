<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Clients;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\SentEmail;

class SendSubscriptionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send subscription reminders to clients 1 month, 15 days, and 5 days before expiration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $settings = Setting::first();
        if (!$settings) {
            $this->info('No settings found. Exiting.');
            return;
        }

        $reminderDays = array_map('intval', (array) $settings->email_days ?? [30, 15, 5]);
        $excludedDays = $settings->excluded_days ?? [];
        $excludedDates = $settings->excluded_dates ?? [];
        $now = Carbon::now();

        if (in_array($now->format('l'), $excludedDays)) {
            $this->info("Skipping reminders for {$now->format('l')} (excluded).");
            return;
        }

        if (in_array($now->toDateString(), $excludedDates)) {
            $this->info("Skipping reminders for {$now->toDateString()} (excluded date).");
            return;
        }

        // Get reminder dates
        $reminderDates = collect($reminderDays)->map(fn($days) => Carbon::now()->addDays($days)->toDateString());

        // Get all clients with subscriptions expiring in or near these dates
        $clients = Clients::whereHas('vehicles.subscriptions', function ($query) use ($reminderDates) {
            $query->where('status', 'active')->whereIn('renewal_date', $reminderDates->toArray());
        })->with(['vehicles.subscriptions' => function ($query) use ($reminderDates) {
            $query->where('status', 'active')
                ->whereIn('renewal_date', $reminderDates->toArray())
                ->with('service', 'vehicle');
        }])->get();


        foreach ($clients as $client) {
            $servicesByVehicle = [];
            $groupedSubscriptions = [];


            foreach ($client->vehicles as $vehicle) {
                // Get subscriptions that expire in the reminder period

                $expiringSubscriptions = $vehicle->subscriptions
                    ->filter(function ($sub) use ($reminderDates) {
                        return in_array(Carbon::parse($sub->renewal_date)->toDateString(), $reminderDates->toArray());
                    })
                    ->unique('id');


                // Now, also check for other subscriptions that expire within 10 days of any of those dates
                $processedSubscriptionIds = [];
                foreach ($expiringSubscriptions as $subscription) {
                    $reminderDate = Carbon::parse($subscription->renewal_date);
                    $mergeStart = $reminderDate->copy();
                    $mergeEnd = $reminderDate->copy()->addDays(30);

                    $nearbySubscriptions = $vehicle->subscriptions->filter(function ($sub) use ($mergeStart, $mergeEnd) {
                        $subDate = Carbon::parse($sub->renewal_date);
                        return $subDate->between($mergeStart, $mergeEnd);
                    });




                    foreach ($nearbySubscriptions as $nearSub) {
                        $nearDate = Carbon::parse($nearSub->renewal_date)->toDateString();

                        $alreadySent = SentEmail::where('client_id', $client->id)
                            ->where('sent_for_date', $nearDate)
                            ->exists();

                        if (!$alreadySent && !in_array($nearSub->id, $processedSubscriptionIds)) {
                            $vehicleKey = "{$vehicle->brand} {$vehicle->model} ({$vehicle->license_plate})";

                            $groupedSubscriptions[$nearDate][$vehicleKey][] = [
                                'service' => $nearSub->service->title,
                                'renewal_date' => Carbon::parse($nearSub->renewal_date)->format('d M Y'),
                                'cost' => number_format($nearSub->total_cost, 2)
                            ];

                            $processedSubscriptionIds[] = $nearSub->id;
                        }
                    }
                }
            }

            if (!empty($groupedSubscriptions)) {
                $totalCost = collect($groupedSubscriptions)
                    ->flatMap(fn($vehicles) => collect($vehicles)->flatMap(fn($services) => $services))
                    ->sum('cost');

                $this->sendReminderEmail($client, $groupedSubscriptions, $totalCost);
            }
        }

        $this->info('Subscription reminders sent successfully.');
    }



    /**
     * Send reminder email to the client.
     */


    private function sendReminderEmail($email)
    {
        $client = $email->client;
        $groupedSubscriptions = $email->subscriptions; // Already array
        $totalCost = collect($groupedSubscriptions)
            ->flatMap(fn($v) => $v)
            ->sum(fn($s) => (float)$s['cost']);

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
            $message->to($client->email)
                ->subject("Subscription Reminder");
        });

        $email->update([
            'sent' => true,
            'sent_at' => now(),
        ]);
    }
}
