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
            $table->increments('ReservationID');
            $table->datetime('ReservationStart');
            $table->datetime('ReservationEnd');
            $table->Integer('emailStatus')->default('0');
            $table->Integer('ActiveStatus')->default('0');
            $table->string('student_id')->nullable();


            $table->integer('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('room_id')->nullable()->unsigned();
            $table->foreign('room_id')->references('Roomid')->on('rooms');
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
