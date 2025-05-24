<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('order_type', ['direct_with_pieces', 'direct_without_pieces']);
            $table->boolean('unread')->default(false);
            $table->boolean('is_fast_delivery')->default(false);
            $table->boolean('increment_qty')->nullable();
            $table->decimal('original_subtotal', 30, 3)->nullable();
            $table->decimal('subtotal', 30, 3)->nullable();
            $table->decimal('off', 30, 3)->default(0.000);
            $table->decimal('shipping', 30, 3)->default(0.000);
            $table->decimal('total', 30, 3)->nullable();
            $table->decimal('total_profit', 30, 3)->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
            $table->bigInteger('order_status_id')->unsigned();
            $table->foreign('order_status_id')->references('id')->on('order_statuses');
            $table->bigInteger('payment_status_id')->unsigned()->nullable();
            $table->foreign('payment_status_id')->references('id')->on('payment_statuses');
            $table->dateTime("payment_confirmed_at")->nullable();
            $table->json('payment_commissions')->nullable();
            $table->text("admin_note")->nullable();
            $table->text('notes')->nullable();
            $table->text('order_notes')->nullable();
            $table->string('order_added_by')->comment('dashboard|web')->default('web')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
