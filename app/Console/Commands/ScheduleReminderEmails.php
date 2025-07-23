<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\ScheduledEmail;
use App\Models\Setting;
use Carbon\Carbon;

class ScheduleReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:schedule-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scans for upcoming renewals and schedules a single, grouped reminder email per client per day.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $settings = Setting::first();
        if (!$settings) {
            $this->error('Settings not found. Please configure settings first.');
            return self::FAILURE;
        }

        // --- 1. Define Parameters & Date Ranges ---
        $reminderDays = array_map('intval', (array) $settings->email_days ?? [30, 15, 5]);
        $excludedDays = $settings->excluded_days ?? []; // e.g., ['Saturday', 'Sunday']
        $excludedDates = $settings->excluded_dates ?? []; // e.g., ['2023-12-25']

        $schedulingWindowStart = Carbon::today();
        $schedulingWindowEnd = Carbon::today()->addDays(max($reminderDays) + 5); // +5 for safety

        // Calculate the maximum lookahead needed for the database query
        $maxRenewalLookahead = $schedulingWindowEnd->copy()->addDays(max($reminderDays) + 21); // Add a buffer

        // --- 2. Fetch All Potentially Relevant Subscriptions Efficiently ---
        $subscriptions = Subscription::with(['vehicle.client', 'service'])
            ->where('status', 'active')
            ->whereNotNull('renewal_date')
            ->whereBetween('renewal_date', [Carbon::today(), $maxRenewalLookahead])
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No upcoming subscriptions found within the lookahead period.');
            return self::SUCCESS;
        }

        // --- 3. Process Subscriptions and Group Them by Client and Send Date ---
        $emailsToSchedule = [];

        foreach ($subscriptions as $subscription) {
            $renewalDate = Carbon::parse($subscription->renewal_date);

            // Replace this with your actual logic for checking payment
            $isPaid = $subscription->isPaid(); // You must implement this!

            // If already paid, skip all reminders
            if ($isPaid) {
                continue;
            }

            foreach ($reminderDays as $days) {
                $idealSendDate = $renewalDate->copy()->subDays($days);

                // Move to previous working day if excluded (weekends/holidays)
                while (
                    in_array($idealSendDate->format('l'), $excludedDays)
                    || in_array($idealSendDate->toDateString(), $excludedDates)
                ) {
                    $idealSendDate->subDay();
                }

                // Only schedule reminders that fall within the defined window
                if ($idealSendDate->between($schedulingWindowStart, $schedulingWindowEnd)) {
                    // Check if this reminder has already been scheduled
                    $alreadyScheduled = ScheduledEmail::where('subscription_id', $subscription->id)
                        ->where('send_date', $idealSendDate->toDateString())
                        ->where('type', 'reminder') // Adjust type if needed
                        ->exists();

                    if (!$alreadyScheduled) {
                        // Create scheduled email (adjust payload as needed)
                        ScheduledEmail::create([
                            'client_id'      => $subscription->vehicle->client_id,
                            'subscription_id' => $subscription->id,
                            'send_date'      => $idealSendDate->toDateString(),
                            'type'           => 'reminder', // or your preferred type
                            'data'           => json_encode([
                                'service'      => $subscription->service->title,
                                'renewal_date' => $renewalDate->format('d M Y'),
                                'cost'         => number_format($subscription->total_cost, 2),
                                'reminder_type' => "{$days} Day Reminder",
                            ]),
                            'sent'           => false,
                        ]);
                    }
                }
            }
        }


        // --- 4. Create or Update the ScheduledEmail Records in the Database ---
        if (empty($emailsToSchedule)) {
            $this->info('No emails to schedule for the next 7 days.');
            return self::SUCCESS;
        }

        $scheduledCount = 0;
        foreach ($emailsToSchedule as $scheduleData) {
            ScheduledEmail::updateOrCreate(
                [
                    // These fields uniquely identify a single scheduled email
                    'client_id' => $scheduleData['client_id'],
                    'send_date' => $scheduleData['send_date'],
                    'type' => 'grouped_reminder', // A generic type for these grouped emails
                ],
                [
                    // This is the payload, containing all subscriptions grouped by vehicle
                    'subscriptions' => json_encode($scheduleData['subscriptions_by_vehicle']),
                    'total_cost' => $scheduleData['total_cost'], // <<-- ADDED: Save the calculated 
                    'sent' => false,
                ]
            );
            $scheduledCount++;
        }

        $this->info("Successfully created or updated {$scheduledCount} grouped reminder emails to be sent over the next 7 days.");
        return self::SUCCESS;
    }
}
