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
       Schema::table('scheduled_emails', function (Blueprint $table) {
            // Add a column for the total cost, placed after the 'subscriptions' column
            $table->decimal('total_cost', 10, 2)->default(0.00)->after('subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('scheduled_emails', function (Blueprint $table) {
            $table->dropColumn('total_cost');
        });
    }
};
