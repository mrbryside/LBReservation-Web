<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('Roomid');
            $table->string('RoomName');
            $table->text('RoomDescription');
            $table->string('RoomPeople');
            $table->string('RoomFloor');
            $table->Integer('RoomStatus')->default('0');
            $table->string('ImageName');
            $table->string('ImageSize');
            $table->string('ImageType');
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
        Schema::dropIfExists('rooms');
    }
}
