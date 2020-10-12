<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Device extends Migration
{
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->string('deviceID')->primary();
            $table->string('householdID');
            $table->foreign('householdID')->references('householdID')->on('households');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}

