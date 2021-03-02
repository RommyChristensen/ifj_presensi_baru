<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableHeaderKehadiran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('header_kehadiran', function (Blueprint $table) {
            $table->increments('id_header_kehadiran');
            $table->string('judul_absensi');
            $table->string('deskripsi')->nullable();
            $table->date('tanggal_absensi');
            $table->string('link_absensi')->nullable();
            $table->boolean('status')->default(0);
            $table->string('slug', 255);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('header_kehadiran');
    }
}
