<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->date('checkin_date');
            $table->date('checkout_date');
            $table->integer('night_stay');
            $table->unsignedBigInteger('booking_package_id')->nullable();
            $table->decimal('final_total')->nullable();
            $table->enum('status', ['processing', 'confirmed', 'cancel'])->default('processing');
            $table->string('payment_type')->nullable();
            $table->longText('guest_info')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
