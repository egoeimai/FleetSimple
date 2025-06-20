<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduledEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendScheduledEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled emails at 09:00 local time for today';
    /**
     * Execute the console command.
     *
     * @return int
     */


    public function handle()
    {
        $now = Carbon::now();
        $today = $now->toDateString();

        // Get all unsent scheduled emails for today
        $emails = ScheduledEmail::with('client')
            ->where('send_date', $today)
            ->where('sent', false)
            ->get();

        if ($emails->isEmpty()) {
            $this->info('No scheduled emails to send today.');
            return;
        }

        foreach ($emails as $email) {
            // Prepare email data
            $client = $email->client;
            $subscriptions = $email->subscriptions; // This is already an array
            $totalCost = $email->total_cost;

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

            // Optionally, fetch your custom greeting/settings if needed

            // Send the email (replace with your actual Mailable if you have one)
            Mail::send('emails.subscription_reminder', [
                'client' => $client,
                'groupedSubscriptions' => $subscriptions,
                'totalCost' => $totalCost,
                'greeting' => $greeting,
                'customGreeting' => $customGreeting,
            ], function ($message) use ($client) {
                $message->to(['nziozas@gmail.com', 'gstavrou@fleetsimple.gr'])
                    ->subject("Subscription Reminder");
            });

            // Mark as sent
            $email->update([
                'sent' => true,
                'sent_at' => now(),
            ]);

            $this->info("Sent scheduled email to {$client->email}");
        }

        $this->info('All scheduled emails sent for today.');
    }
}
