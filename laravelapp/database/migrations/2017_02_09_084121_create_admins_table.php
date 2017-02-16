<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('admins', function (Blueprint $table) {
            $table->increments('adminid');
            $table->string('name');
            $table->string('account_number', 128);
            $table->string('password', 64);
            $table->rememberToken();
            $table->integer('create_time');
            $table->integer('update_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admins');
    }
}
