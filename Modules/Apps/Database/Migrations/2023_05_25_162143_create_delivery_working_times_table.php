<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryWorkingTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_working_times', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->bigInteger('delivery_working_day_id')->unsigned();
            $table->time('from');
            $table->time('to');
            $table->foreign('delivery_working_day_id')->references('id')->on('delivery_working_days')->onDelete('cascade');
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
        Schema::dropIfExists('delivery_working_times');
    }
}
