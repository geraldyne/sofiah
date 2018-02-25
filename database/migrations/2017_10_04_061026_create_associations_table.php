<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssociationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        // ASOCIACIONES
        // En esta tabla se almacenan los datos generales de identificación y configuración básica de la Caja de Ahorros
        
        Schema::create('associations', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('name')->comment('Nombre completo de la asociación');
            $table->string('alias')->comment('Nombre corto de la asociación');
            $table->string('sudeca')->comment('Número de registro en SUDECA de la asociación');
            $table->string('web_site')->default('No posee')->comment('Página web de la asociación');
            $table->string('email')->comment('Dirección de correo electrónico de la asociación');
            $table->string('logo')->comment('Almacena la ruta con el logo de la asociación');
            $table->integer('phone')->comment('Número de teléfono de contacto de la asociación');
            $table->integer('rif')->comment('Número de Registro de Información Fiscal de la asociación');
            $table->integer('direction_id')->unsigned()->comment('Dirección de la asociación');
            $table->date('lock_date')->comment('Fecha hasta donde los movimientos y operaciones están bloqueados en el sistema');
            
            $table->integer('time_to_reincorporate')->unsigned()->comment('Tiempo en meses para reingresar a la caja de caja de ahorros y solicitar un préstamo');
            $table->integer('loan_time')->unsigned()->comment('Tiempo en meses para solicitar un préstamo');
            
            // INTEGRACIÓN CONTABLE BÁSICA

            $table->float('percent_legal_reserve')->comment('Porcentaje destinado para la reserva legal');

            $table->foreign('direction_id')->references('id')->on('directions')->onDelete('cascade');
            $table->unique(['alias', 'rif', 'sudeca' ,'email', 'uuid']);
            $table->timestamps();
        });

        Schema::create('accountsassociation', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('description')->comment('Descripción de la cuenta');
            $table->integer('association_id')->unsigned()->comment('Código de la asociación');
            $table->integer('accountlvl6_id')->unsigned()->comment('Código de la cuenta de nivel 6');
            $table->foreign('association_id')->references('id')->on('associations')->onDelete('cascade');
            $table->foreign('accountlvl6_id')->references('id')->on('accounts_lvl6')->onDelete('cascade');
            $table->unique(['uuid']);
            $table->timestamps();
        });

        // BANCOS
        // En esta tabla se almacenan los bancos registrados en el sistema
        
        Schema::create('banks', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('bank')->comment('Nombre del banco');
            $table->timestamps();
        });

        // DETALLE DEL BANCO
        // En esta tabla se almacenan los bancos que pertenecen a un empleado
        
        Schema::create('bank_details', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('account_number')->comment('Número de cuenta del banco asociado');
            $table->enum('account_type', ['C','A'])->comment('Tipo de cuenta asociada (C = Corriente, A = Ahorros)');
            $table->integer('bank_id')->unsigned()->comment('Banco donde el asociado tiene su cuenta registrada');
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
            $table->timestamps();
        });

        // EMPLEADOS
        // En esta tabla se almacenan los datos de los empleados de la asociación
        
        Schema::create('employees', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('employee_code')->default('S/N')->comment('Código o número de empleado en la asociación');
            $table->string('names')->comment('Nombres del empleado');
            $table->string('lastnames')->comment('Apellidos del empleado');
            $table->string('email')->comment('Correo personal del empleado');
            $table->string('department')->comment('Departamento donde labora el empleado');
            $table->string('rif')->comment('Número de RIF del empleado EJ V123456789-0');
            $table->string('id_card')->comment('Número de cédula o pasaporte del empleado');
            $table->string('phone')->comment('Número de teléfono fijo o móvil del empleado');
            $table->integer('direction_id')->unsigned()->comment('Dirección del empleado');
            $table->enum('nationality', ['V','E','P'])->comment('Nacionalidad del empleado (V = Venezolano, E = Extranjero, P = Pasaporte)');
            $table->enum('status', ['A','R','F'])->comment('Estatus del empleado (A = Activo, R = Retirado, F = Fallecido)');
            $table->date('birthdate')->comment('Fecha de nacimiento del empleado');
            $table->date('date_of_admission')->nullable()->comment('Fecha de ingreso del empleado de la caja de ahorros, puede ser NULL si el empleado está en estatus ACTIVO');
            $table->date('retirement_date')->nullable()->comment('Fecha de retiro del empleado de la caja de ahorro puede ser NULL si el empleado está en estatus ACTIVO');

            $table->integer('user_id')->unsigned()->comment('Usuario del empleado');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('association_id')->unsigned()->comment('Asociación a la que pertenece el empleado');
            $table->foreign('association_id')->references('id')->on('associations')->onDelete('cascade');

            $table->integer('bankdetails_id')->unsigned()->comment('Banco donde el empleado tiene su cuenta registrada');
            $table->foreign('bankdetails_id')->references('id')->on('bank_details')->onDelete('cascade');

            $table->foreign('direction_id')->references('id')->on('directions')->onDelete('cascade');
            $table->unique(['employee_code', 'id_card', 'rif', 'uuid']);
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
        Schema::dropIfExists('employees');
        Schema::dropIfExists('bank_details');
        Schema::dropIfExists('banks');
        Schema::dropIfExists('accountsassociation');
        Schema::dropIfExists('associations');
    }
}
