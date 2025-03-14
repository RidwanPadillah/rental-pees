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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->enum('status', ['pending', 'success', 'failed', 'cancel'])->default('pending');
            $table->decimal('amount', 10, 2);
            
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->integer('session');
            
            $table->string('payment_method')->nullable();
            $table->text('payment_details')->nullable();
            
            $table->integer('device_id');
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
