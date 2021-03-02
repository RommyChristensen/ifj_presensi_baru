<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDetailKehadiran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_kehadiran', function (Blueprint $table) {
            $table->increments('id_detail_kehadiran');
            $table->integer('id_header_kehadiran')->unsigned();
            $table->integer('nrp')->unsigned();
            $table->timestamps();

            $table->foreign('id_header_kehadiran')->references('id_header_kehadiran')->on('header_kehadiran');
            $table->foreign('nrp')->references('nrp')->on('mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_kehadiran');
    }
}
