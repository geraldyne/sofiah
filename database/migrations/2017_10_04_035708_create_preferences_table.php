<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // PREFERENCIAS
        
        Schema::create('preferences', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('style')->default('dark')->comment('Estilo de colores del sistema de preferencia del usuario');
            $table->string('lang')->default('es')->comment('Idioma de preferencia del usuario');
            $table->integer('zoom')->default('80')->comment('Nivel de zoom preferido por el usuario');
            $table->integer('user_id')->unsigned()->comment('Usuario que almacena las preferencias');
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
        Schema::dropIfExists('preferences');
    }
}
