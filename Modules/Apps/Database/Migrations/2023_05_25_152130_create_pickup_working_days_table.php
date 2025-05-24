<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePickupWorkingDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_working_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('day_code', 20);
            $table->json('day_name')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('is_full_day')->default(true);
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
        Schema::dropIfExists('pickup_working_days');
    }
}
