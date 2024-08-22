<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTypeToRoomDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('room_dates', function (Blueprint $table) {
            $table->integer('number')->nullable();
            $table->enum('type', ['price', 'number']);
            $table->unsignedBigInteger('rate_plan_id');
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
            $table->dropColumn('number');
            $table->dropColumn('type');
            $table->dropColumn('rate_plan_id');
        });
    }
}
