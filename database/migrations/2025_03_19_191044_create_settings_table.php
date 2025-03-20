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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->json('excluded_days')->nullable(); // Store excluded weekdays (e.g., Saturday, Sunday)
            $table->json('excluded_dates')->nullable(); // Store specific excluded dates
            $table->json('email_days')->default(json_encode([30, 15, 5])); // Days before expiration
            $table->string('greeting_text')->nullable(); // Custom greeting text
            $table->text('email_message')->nullable(); // Custom email body
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
        Schema::dropIfExists('settings');
    }
};
