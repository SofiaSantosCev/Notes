<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('name', 30)->nullable(false);
            $table->string('email', 50)->unique()->nullable(false);
            $table->string('password');
            $table->integer('rol_id')->nullable(false);
            $table->timestamps();
        });
        
        /*Schema::table('users', function (Blueprint $table) {
            $table->foreign('rol_id')->references('id')->on('rols');
        });*/

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
