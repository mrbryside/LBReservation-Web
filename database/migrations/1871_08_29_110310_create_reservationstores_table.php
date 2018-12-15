<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationstoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservationstores', function (Blueprint $table) {
            $table->increments('ReservationID');
            $table->datetime('ReservationStart');
            $table->datetime('ReservationEnd');
            $table->string('StudentID');
            $table->string('FirstName');
            $table->string('LastName');
            $table->string('Faculty');
            $table->string('RoomName');
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
        Schema::dropIfExists('reservationstores');
    }
}
