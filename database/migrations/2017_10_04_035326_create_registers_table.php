<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        // REGISTROS

        Schema::create('registers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('description')->comment('Descripción de la acción realizada por el usuario en el sistema');
            $table->string('affected_table')->comment('Tabla que se vió afectada por el usuario');
            $table->enum('crud', ['create', 'modify', 'delete', 'login', 'logout'])->comment('Acción que realizó el usuario');
            $table->ipaddress('ip_adress')->comment('Dirección IP del dispositivo donde el usuario se conectó');
            $table->integer('record_id')->unsigned()->comment('Registro que se vió afectado por el usuario');
            $table->integer('user_id')->unsigned()->comment('Usuario que afecta el registro');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('registers');
    }
}
