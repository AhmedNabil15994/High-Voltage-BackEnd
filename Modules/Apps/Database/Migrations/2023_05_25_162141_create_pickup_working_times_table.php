<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupWorkingTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_working_times', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('pickup_working_day_id')->unsigned();
            $table->time('from');
            $table->time('to');
            $table->foreign('pickup_working_day_id')->references('id')->on('pickup_working_days')->onDelete('cascade');
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
        Schema::dropIfExists('pickup_working_times');
    }
}
