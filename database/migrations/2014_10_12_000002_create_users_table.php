<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->bigIncrements('idUser');
            $table->string('name');
            $table->string('lastname');
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('seguro_social')->nullable();
            $table->string('curp')->nullable();
            $table->string('password');
            $table->string('confirm_email')->nullable();
            $table->string('cellphone');
            $table->string('indicative')->nullable();
            $table->unsignedBigInteger('idPerfil');
            $table->tinyInteger('state')->default(1);
            $table->rememberToken();
            $table->timestamps();

            // $table->foreign('idPerfil')->references('idValueParameter')->on('parameter_values');

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
