<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use Carbon\Carbon;

class CheckSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check subscriptions and update status to expired if renewal date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $subscriptions = Subscription::where('status', 'active')
            ->where('renewal_date', '<', $now)
            ->get();

        foreach ($subscriptions as $subscription) {
            $subscription->status = 'expired';
            $subscription->save();

            $this->info("Subscription ID {$subscription->id} marked as expired.");
        }

        $this->info('Subscription status check completed.');
    }
}
