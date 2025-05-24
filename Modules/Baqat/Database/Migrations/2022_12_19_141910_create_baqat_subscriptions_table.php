<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaqatSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baqat_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('baqat_id')->unsigned()->nullable();
            $table->foreign('baqat_id')->references('id')->on('baqat')->onDelete('set null');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('price', 10, 3)->nullable();
            $table->integer('duration_by_days')->nullable();
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->date('new_end_at')->nullable();
            $table->enum('type', ['client', 'admin'])->default('client');
            $table->bigInteger('payment_status_id')->unsigned()->nullable();
            $table->foreign('payment_status_id')->references('id')->on('payment_statuses');
            $table->dateTime("payment_confirmed_at")->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('baqat_subscriptions');
    }
}
