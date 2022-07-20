<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('idReservation');
            $table->unsignedBigInteger('idClient');
            $table->unsignedBigInteger('idManager')->nullable();
            $table->unsignedBigInteger('idReceptionist');
            $table->unsignedBigInteger('idCenter');
            $table->integer('mes')->nullable();
            $table->unsignedBigInteger('idSchedule');
            $table->string('pax');
            $table->string('num_habitacion');
            $table->dateTime('date', $precision = 0);
            $table->tinyInteger('state')->default(1);
            $table->timestamps();

            // $table->foreign('idClient')->references('idUser')->on('users');
            // $table->foreign('idManager')->references('idUser')->on('users');
            // $table->foreign('idReceptionist')->references('idUser')->on('users');
            // $table->foreign('idSchedule')->references('idSchedule')->on('schedules');
            // $table->foreign('idCenter')->references('idCenter')->on('centers');
        });

        Schema::create('reservations_info_finish', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('nombre_cliente')->nullable();
            $table->string('id_reserva')->nullable();
            $table->string('forma_cobro')->nullable();
            $table->string('email')->nullable();
            $table->string('email2')->nullable();
            $table->string('micelaneo')->nullable();
            $table->string('recibo')->nullable();
            $table->string('linkPagina')->nullable();
            $table->string('codigoFoto')->nullable();
            // $table->string('num_habitacion');
            $table->string('total_venta')->nullable();
            $table->string('telefono1')->nullable();
            $table->string('telefono2')->nullable();
            $table->string('canva')->nullable();
            $table->string('direccion')->nullable();
            $table->string('autoriza_fotografia')->nullable();
            $table->tinyInteger('state')->default(0);
            $table->timestamps();

        });

        
        Schema::create('comisiones', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('id_trabajador');
            $table->string('tipo_comision')->nullable();
            $table->string('dia')->nullable();
            $table->string('mes')->nullable();
            $table->string('ano')->nullable();
            $table->string('monto_comision')->nullable();
            $table->tinyInteger('state')->default(1);
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
        Schema::dropIfExists('reservations');
    }
}
