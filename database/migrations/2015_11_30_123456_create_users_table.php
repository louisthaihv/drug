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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->date('dob')->nullable();
            $table->string('mobile', 45)->nullable();
            $table->tinyInteger('gender')->default(1)
                ->comment('User Gender: 0: Female 1: Male');
            $table->tinyInteger('status')->default(0)
                ->comment('Active/Inactive');
            $table->string('avatar')->nullable()
                ->comment('ID of image in images table');

            $table->string('address', 255)->nullable();
            $table->integer('agent_id')->nullable()->unsigned();
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->integer('role_id')->nullable()->unsigned();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

            $table->softDeletes();
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
