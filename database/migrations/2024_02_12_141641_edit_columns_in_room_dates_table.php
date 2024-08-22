<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditColumnsInRoomDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('room_dates', function (Blueprint $table) {
            $table->unsignedBigInteger('room_id')->nullable()->change();
            $table->unsignedBigInteger('rate_plan_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('room_dates', function (Blueprint $table) {
            //
        });
    }
}
