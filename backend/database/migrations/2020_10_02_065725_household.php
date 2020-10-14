<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Household extends Migration
{
    public function up()
    {
        Schema::create('households', function (Blueprint $table) {
            $table->string('householdID')->primary();
            $table->integer('userID')->unsigned();
            $table->foreign('userID')->references('userID')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('households');
    }
}
