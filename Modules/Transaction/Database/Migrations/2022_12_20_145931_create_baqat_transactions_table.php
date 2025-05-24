<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaqatTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baqat_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('method')->nullable();
            $table->string('payment_id')->nullable();
            $table->string('tran_id')->nullable();
            $table->string('result')->nullable();
            $table->string('post_date')->nullable();
            $table->string('ref')->nullable();
            $table->string('track_id')->nullable();
            $table->string('auth')->nullable();
            $table->bigInteger('baqat_subscription_id')->unsigned()->nullable();
            $table->foreign('baqat_subscription_id')->references('id')->on('baqat_subscriptions');
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
        Schema::dropIfExists('baqat_transactions');
    }
}
