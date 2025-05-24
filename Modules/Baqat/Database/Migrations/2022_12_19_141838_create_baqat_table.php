<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaqatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baqat', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('title');
            $table->json('slug')->nullable();
            $table->json('description')->nullable();
            $table->json('duration_description')->nullable();
            $table->boolean('status')->default(1);
            $table->integer('duration_by_days')->nullable();
            $table->decimal('price', 10, 3)->nullable();
            $table->decimal('client_price', 10, 3)->nullable();
            $table->integer('sort')->default(0);
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
        Schema::dropIfExists('baqat');
    }
}
