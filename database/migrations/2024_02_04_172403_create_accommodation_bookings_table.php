<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accommodation_bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('accommodation_id');
            $table->foreign('accommodation_id')->references('id')->on('accommodations');
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            
            $table->enum('status', ['pending', 'paid']);
            $table->boolean('resort_approval')->default(false);
            $table->boolean('check_in')->default(false);
            $table->dateTime('time_check_in')->useCurrent();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('amount_of_days');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_bookings');
    }
};
