<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganismsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        // ORGANISMOS
        // En esta tabla se almacenan los datos de los organismos o instituciones que están afiliadas a la Caja de Ahorros
        
        Schema::create('organisms', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('name')->comment('Nombre completo del organismo');
            $table->string('alias')->comment('Nombre corto del organismo');
            $table->string('email')->comment('Dirección de correo electrónico del organismo');
            $table->string('web_site')->default('No posee')->comment('Página web del organismo');
            $table->string('zone')->default('No posee')->comment('Campo para clasificar al organismo por grupo (no es requerido)');
            $table->string('contact')->comment('Persona de contacto en el organismo (Referencial)');
            $table->string('slug')->nullable()->comment('Campo que almacena el nombre de manera legible en la url');
            $table->integer('phone')->comment('Número de teléfono de contacto del organismo');
            $table->integer('rif')->comment('Número de Registro de Información Fiscal del organismo');
            $table->integer('direction_id')->unsigned()->comment('Direccion del organismo');
            $table->enum('payroll_type', ['S','Q','M'])->comment('Almacena el tipo de nómina del organismo. S: Semanal Q: Quincenal M: Mensual');
            $table->boolean('status')->comment('V: Activo - F: Inactivo');
            $table->float('disponibility')->comment('Porcentaje de ahorros para la disponibilidad');
            $table->float('percentage_employers_contribution')->comment('Porcentaje del aporte patronal a la asociacion');
            $table->float('percentage_individual_contribution')->comment('Porcentaje del aporte individual a la asociacion');
            $table->float('percentage_voluntary_contribution')->comment('Porcentaje del aporte voluntario a la asociacion');

            $table->integer('association_id')->unsigned()->comment('Asociación a la que pertenece el empleado');
            $table->foreign('association_id')->references('id')->on('associations')->onDelete('cascade');

            $table->foreign('direction_id')->references('id')->on('directions')->onDelete('cascade');
            $table->unique(['rif', 'alias', 'email', 'uuid']);
            $table->timestamps();
        });

        // ASOCIADOS
        // En esta tabla se almacenan los datos de los asociados afiliados a la Caja de Ahorros por Organismo
        
        Schema::create('partners', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('employee_code')->default('S/N')->comment('Código o número de empleado del asociado en el organismo');
            $table->string('names')->comment('Nombres del asociado');
            $table->string('lastnames')->comment('Apellidos del asociado');
            $table->string('email')->comment('Correo del asociado');
            $table->string('title')->comment('Grado universitario del asociado, rango militar no es obligatorio');
            $table->string('local_phone')->comment('Número de teléfono local del asociado');
            $table->date('retirement_date')->nullable()->comment('Fecha de retiro del asociado de la caja de ahorro puede ser NULL si el asociado está en estatus ACTIVO');
            $table->date('retirement_last_date')->nullable()->comment('Fecha del ultimo retiro de la caja de ahorros, si nunca se ha retirado es NULL, se actualiza solo con reincorporaciones a la caja de ahorros');
            $table->enum('nationality', ['V','E','P'])->comment('Nacionalidad del asociado (V = Venezolano, E = Extranjero, P = Pasaporte)');
            $table->enum('status', ['A','R','F'])->comment('Estatus del asociado (A = Activo, R = Retirado, F = Fallecido)');
            
            // Datos de acceso

            $table->string('account_code')->comment('Código generado para verificar la cuenta de asociado');
            $table->string('id_card')->comment('Número de cédula o pasaporte del asociado');
            $table->string('phone')->comment('Número de teléfono móvil del asociado');
            
            $table->integer('user_id')->unsigned()->comment('Usuario del asociado');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('bankdetails_id')->unsigned()->comment('Banco donde el asociado tiene su cuenta registrada');
            $table->foreign('bankdetails_id')->references('id')->on('bank_details')->onDelete('cascade');

            $table->integer('organism_id')->unsigned()->comment('Organismo al que pertenece el asociado');
            $table->foreign('organism_id')->references('id')->on('organisms')->onDelete('cascade');

            $table->unique(['employee_code', 'id_card', 'uuid']);
            $table->timestamps();
        });

        // CARGOS
        // En esta tabla se almacenan los cargos que existen dentro de la junta directiva de la caja de ahorros
        
        Schema::create('charges', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('charge')->comment('Nombre del cargo en la junta directiva');
            $table->timestamps();
        });

        // DIRECTIVOS
        // En esta tabla se almacenan los datos de los directivos vigentes de la Caja de Ahorro
        
        Schema::create('managers', function (Blueprint $table) {

            $table->increments('id');
            $table->boolean('status')->comment('V: Activo - F: Inactivo');
            $table->integer('partner_id')->unsigned()->comment('Asociado que pertenece a la junta directiva');
            $table->integer('charge_id')->unsigned()->comment('Cargo que posee el asociado en la junta directiva');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
            $table->foreign('charge_id')->references('id')->on('charges')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */

    public function down() {

        Schema::dropIfExists('managers');
        Schema::dropIfExists('charges');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('organisms');
    }
}
