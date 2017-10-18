<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // PAISES
        // En esta tabla se almacenan los países para uso en direcciones

        Schema::create('countries', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('country')->comment('Almacena los paises soportados por el sistema Sofiah');
            $table->timestamps();
        });

        // ESTADOS
        // En esta tabla se almacenan los estados relacionados a los países para uso en direcciones

        Schema::create('states', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('state')->comment('Almacena los estados');
            $table->integer('country_id')->unsigned()->comment('Pais al que pertenece el estado');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->timestamps();
        });

        // CIUDADES
        // En esta tabla se almacenan las ciudades relacionadas a los estados para uso en direcciones

        Schema::create('cities', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('city')->comment('Almacena las ciudades');
            $table->integer('area_code')->comment('Código de área de la ciudad');
            $table->integer('state_id')->unsigned()->comment('Estado al que pertenece la ciudad');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->timestamps();
        });

        // DIRECCIONES
        // En esta tabla se almacenan las direcciones

        Schema::create('directions', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('direction')->comment('Dirección. Ej Av, Calle, Cruce, Local');
            $table->integer('city_id')->unsigned()->comment('Ciudad donde se encuentra la direccion');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
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
        Schema::dropIfExists('directions');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
    }
}
