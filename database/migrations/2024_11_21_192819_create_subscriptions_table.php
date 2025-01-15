<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade'); // Link to vehicles table
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // Link to services table
            $table->decimal('total_cost', 10, 2); // Total cost of the subscription
            $table->date('start_date'); // Subscription start date
            $table->date('renewal_date'); // Renewal date
            $table->enum('status', ['active', 'expired', 'renewed']); // Subscription status
            $table->decimal('amount_due', 10, 2)->nullable(); // Amount due
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
