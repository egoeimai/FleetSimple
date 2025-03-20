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
    
        $reminderDays = array_map('intval', (array) $settings->email_days ?? [30, 15, 5]); // Ensure it's an array of integers

        $excludedDays = $settings->excluded_days ?? []; // Excluded weekdays
        $excludedDates = $settings->excluded_dates ?? []; // Specific excluded dates
        $now = Carbon::now();
    
        // Skip sending emails on excluded days (e.g., weekends)
        if (in_array($now->format('l'), $excludedDays)) {
            $this->info("Skipping reminders for {$now->format('l')} (excluded).");
            return;
        }
    
        // Skip sending emails on specific excluded dates (e.g., holidays)
        if (in_array($now->toDateString(), $excludedDates)) {
            $this->info("Skipping reminders for {$now->toDateString()} (excluded date).");
            return;
        }
    
        // Find subscriptions expiring in the next 30 days
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays(max($reminderDays));
    
        // Get all clients with subscriptions expiring within this period
        $clients = Clients::whereHas('vehicles.subscriptions', function ($query) use ($startDate, $endDate) {
            $query->where('status', 'active')
                ->whereBetween('renewal_date', [$startDate->toDateString(), $endDate->toDateString()]);
        })->with(['vehicles.subscriptions' => function ($query) use ($startDate, $endDate) {
            $query->where('status', 'active')
                ->whereBetween('renewal_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->with('service', 'vehicle');
        }])->get();
    
       
        foreach ($clients as $client) {
            $servicesByVehicle = [];
    
            foreach ($client->vehicles as $vehicle) {
                $expiringSubscriptions = $vehicle->subscriptions->filter(function ($sub) use ($startDate, $endDate) {
                    return Carbon::parse($sub->renewal_date)->between($startDate, $endDate);
                });
    
                if ($expiringSubscriptions->isNotEmpty()) {
                    $servicesByVehicle[] = [
                        'vehicle' => "{$vehicle->brand} {$vehicle->model} ({$vehicle->license_plate})",
                        'services' => $expiringSubscriptions->map(function ($sub) {
                            return [
                                'service' => $sub->service->title,
                                'renewal_date' => Carbon::parse($sub->renewal_date)->format('d M Y'),
                                'cost' => $sub->total_cost
                            ];
                        })->toArray()
                    ];
                }
            }
    
            if (!empty($servicesByVehicle)) {
                $totalCost = collect($servicesByVehicle)->flatMap(fn($v) => $v['services'])->sum('cost');
                $this->sendReminderEmail($client, $servicesByVehicle, $totalCost);
            }
        }
    
        $this->info('Subscription reminders sent successfully.');
    }
    

    /**
     * Send reminder email to the client.
     */
    private function sendReminderEmail($client, $servicesByVehicle, $totalCost)
    {
        $now = Carbon::now();
        $greeting = "Καλημέρα!"; // Default greeting
    
        // If it's the first of the month, send only "Καλό Μήνα!"
        if ($now->day == 1) {
            $greeting = "Καλό Μήνα!";
        }
        // If it's Monday but NOT the first of the month, send "Καλή Εβδομάδα!"
        elseif ($now->isMonday()) {
            $greeting = "Καλή Εβδομάδα!";
        }
    
        // Retrieve the admin's custom greeting message
        $settings = Setting::first();
        $customGreeting = $settings->greeting_text ?? '';
        
        SentEmail::create([
            'client_id' => $client->id,
            'email' => $client->email,
            'services' => $servicesByVehicle,
            'total_cost' => $totalCost,
            'greeting' => $greeting,
            'custom_message' => $customGreeting
        ]);
    
        Mail::send('emails.subscription_reminder', [
            'client' => $client,
            'servicesByVehicle' => $servicesByVehicle,
            'totalCost' => $totalCost,
            'greeting' => $greeting,  
            'customGreeting' => $customGreeting 
        ], function ($message) use ($client) {
            $message->to($client->email)
                ->subject("Subscription Reminder");
        });
    }
    
}