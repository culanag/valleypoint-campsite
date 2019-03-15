<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('reservationDatetime');
            $table->string('lastName', 25);
            $table->string('firstName', 25);
            $table->integer('numberOfPax')->default(1);;
            $table->string('contactNumber', 11);
            $table->integer('serviceID')->unsigned();
            $table->foreign('serviceID')->references('id')->on('Services');
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
        Schema::dropIfExists('reservations');
    }
}
