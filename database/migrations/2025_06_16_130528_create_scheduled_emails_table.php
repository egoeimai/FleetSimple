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
        Schema::create('scheduled_emails', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->constrained()->cascadeOnDelete();

            $table->date('send_date'); // When the email should be sent
            $table->string('type');    // e.g., '30_days', '15_days', '5_days'

            $table->json('subscriptions'); // Grouped services by vehicle (JSON structure)

            $table->boolean('sent')->default(false);
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            $table->unique(['client_id', 'send_date', 'type']); // Prevent duplicate scheduling
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_emails');
    }
};
