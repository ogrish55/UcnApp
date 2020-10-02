<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Measurement extends Migration
{
    public function up()
    {
        Schema::create('measurement', function (Blueprint $table) {
            $table->string('deviceID');
            $table->foreign('deviceID')->references('deviceID')->on('device');
            $table->string('meterType');
            $table->string('readingType');
            $table->string('measurement');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measurement');
    }
}
