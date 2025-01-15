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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // Foreign key with cascading delete
            $table->string('brand')->nullable(); // Optional brand field
            $table->string('model')->nullable(); // Optional model field
            $table->string('license_plate')->unique(); // Unique license plate
            $table->boolean('enable_reminder')->default(true); // Enable reminder field
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};
