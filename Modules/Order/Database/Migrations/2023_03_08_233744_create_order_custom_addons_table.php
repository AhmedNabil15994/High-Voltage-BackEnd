<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderCustomAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_custom_addons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->unsigned();
            $table->bigInteger('order_product_id')->unsigned()->nullable();
            $table->bigInteger('addon_id')->unsigned()->nullable();
            $table->decimal('price', 9, 3);
            $table->integer('qty');
            $table->decimal('total', 9, 3);
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('order_product_id')->references('id')->on('order_products')->onUpdate('cascade');
            $table->foreign('addon_id')->references('id')->on('custom_addons')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_custom_addons');
    }
}
