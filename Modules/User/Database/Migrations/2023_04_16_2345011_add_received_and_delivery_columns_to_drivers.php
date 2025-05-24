<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceivedAndDeliveryColumnsToDrivers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('maximum_received_orders_count')->nullable()->after('subscriptions_balance');
            $table->integer('maximum_delivery_orders_count')->nullable()->after('maximum_received_orders_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['maximum_received_orders_count', 'maximum_delivery_orders_count']);
        });
    }
}
