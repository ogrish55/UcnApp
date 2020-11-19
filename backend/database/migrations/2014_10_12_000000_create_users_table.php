<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('userID');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('address');
            $table->string('email')->unique();
            $table->string('phoneNumber')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('regionID');
            $table->string('aconto');
            $table->foreign('regionID')->references('regionID')->on('regions');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
