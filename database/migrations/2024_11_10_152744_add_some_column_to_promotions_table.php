<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeColumnToPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->string('description')->nullable();
            $table->enum('discount_type', ['percent', 'amount'])->nullable();
            $table->string('percent')->nullable();
            $table->decimal('amount', 11,2)->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('discount_type');
            $table->dropColumn('percent');
            $table->dropColumn('amount');
        });
    }
}
