<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaqatOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baqat_offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('start_at');
            $table->date('end_at');
            $table->boolean('status')->default(false);
            $table->decimal('offer_price', 9, 3)->nullable();
            $table->decimal('percentage', 9, 3)->nullable();
            $table->bigInteger('baqat_id')->unsigned();
            $table->foreign('baqat_id')->references('id')->on('baqat')->onDelete('cascade');
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
        Schema::dropIfExists('baqat_offers');
    }
}
