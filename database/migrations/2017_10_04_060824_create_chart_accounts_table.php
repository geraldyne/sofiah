<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // PLAN DE CUENTAS NIVEL 1
        // En esta tabla se almacena la estructura del plan de cuentas los atributos necesarios para la generación de movimientos contables, cierre de período y reportes del Módulo Administrativo de SOFIAH

        Schema::create('accounts_lvl1', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->integer('account_code')->comment('Código de la cuenta contable');
            $table->string('account_name')->comment('Nombre de la cuenta contable');
            $table->enum('account_type', ['activo', 'pasivo', 'patrimonio', 'egreso', 'ingreso', 'orden'])->comment('Tipo de cuenta contable');
            $table->enum('balance_type', ['deudor', 'acreedor'])->comment('Tipo de saldo de la cuenta contable');
            $table->boolean('apply_balance')->comment('V: Balance general - F: Ganancias y perdidas');
            $table->timestamps();
        });

        // PLAN DE CUENTAS NIVEL 2
        // En esta tabla se almacena la estructura del plan de cuentas los atributos necesarios para la generación de movimientos contables, cierre de período y reportes del Módulo Administrativo de SOFIAH

        Schema::create('accounts_lvl2', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->integer('account_code')->comment('Código de la cuenta contable');
            $table->string('account_name')->comment('Nombre de la cuenta contable');
            $table->enum('account_type', ['activo', 'pasivo', 'patrimonio', 'egreso', 'ingreso', 'orden'])->comment('Tipo de cuenta contable');
            $table->enum('balance_type', ['deudor', 'acreedor'])->comment('Tipo de saldo de la cuenta contable');
            $table->boolean('apply_balance')->comment('V: Balance general - F: Ganancias y perdidas');
            
            $table->integer('accountlvl1_id')->unsigned()->comment('Cuenta afectada en la integración');
            $table->foreign('accountlvl1_id')->references('id')->on('accounts_lvl1')->onDelete('cascade');
            
            $table->timestamps();
        });

        // PLAN DE CUENTAS NIVEL 3
        // En esta tabla se almacena la estructura del plan de cuentas los atributos necesarios para la generación de movimientos contables, cierre de período y reportes del Módulo Administrativo de SOFIAH

        Schema::create('accounts_lvl3', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->integer('account_code')->comment('Código de la cuenta contable');
            $table->string('account_name')->comment('Nombre de la cuenta contable');
            $table->enum('account_type', ['activo', 'pasivo', 'patrimonio', 'egreso', 'ingreso', 'orden'])->comment('Tipo de cuenta contable');
            $table->enum('balance_type', ['deudor', 'acreedor'])->comment('Tipo de saldo de la cuenta contable');
            $table->boolean('apply_balance')->comment('V: Balance general - F: Ganancias y perdidas');
            
            $table->integer('accountlvl2_id')->unsigned()->comment('Cuenta afectada en la integración');
            $table->foreign('accountlvl2_id')->references('id')->on('accounts_lvl2')->onDelete('cascade');
            
            $table->timestamps();
        });

        // PLAN DE CUENTAS NIVEL 4
        // En esta tabla se almacena la estructura del plan de cuentas los atributos necesarios para la generación de movimientos contables, cierre de período y reportes del Módulo Administrativo de SOFIAH

        Schema::create('accounts_lvl4', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->integer('account_code')->comment('Código de la cuenta contable');
            $table->string('account_name')->comment('Nombre de la cuenta contable');
            $table->enum('account_type', ['activo', 'pasivo', 'patrimonio', 'egreso', 'ingreso', 'orden'])->comment('Tipo de cuenta contable');
            $table->enum('balance_type', ['deudor', 'acreedor'])->comment('Tipo de saldo de la cuenta contable');
            $table->boolean('apply_balance')->comment('V: Balance general - F: Ganancias y perdidas');
            
            $table->integer('accountlvl3_id')->unsigned()->comment('Cuenta afectada en la integración');
            $table->foreign('accountlvl3_id')->references('id')->on('accounts_lvl3')->onDelete('cascade');
            
            $table->timestamps();
        });

        // PLAN DE CUENTAS NIVEL 5
        // En esta tabla se almacena la estructura del plan de cuentas los atributos necesarios para la generación de movimientos contables, cierre de período y reportes del Módulo Administrativo de SOFIAH

        Schema::create('accounts_lvl5', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->integer('account_code')->comment('Código de la cuenta contable');
            $table->string('account_name')->comment('Nombre de la cuenta contable');
            $table->enum('account_type', ['activo', 'pasivo', 'patrimonio', 'egreso', 'ingreso', 'orden'])->comment('Tipo de cuenta contable');
            $table->enum('balance_type', ['deudor', 'acreedor'])->comment('Tipo de saldo de la cuenta contable');
            $table->boolean('apply_balance')->comment('V: Balance general - F: Ganancias y perdidas');
            
            $table->integer('accountlvl4_id')->unsigned()->comment('Cuenta afectada en la integración');
            $table->foreign('accountlvl4_id')->references('id')->on('accounts_lvl4')->onDelete('cascade');
            
            $table->timestamps();
        });

        // PLAN DE CUENTAS NIVEL 6
        // En esta tabla se almacena la estructura del plan de cuentas los atributos necesarios para la generación de movimientos contables, cierre de período y reportes del Módulo Administrativo de SOFIAH

        Schema::create('accounts_lvl6', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->integer('account_code')->comment('Código de la cuenta contable');
            $table->string('account_name')->comment('Nombre de la cuenta contable');
            $table->enum('account_type', ['activo', 'pasivo', 'patrimonio', 'egreso', 'ingreso', 'orden'])->comment('Tipo de cuenta contable');
            $table->enum('balance_type', ['deudor', 'acreedor'])->comment('Tipo de saldo de la cuenta contable');
            $table->boolean('apply_balance')->comment('V: Balance general - F: Ganancias y perdidas');
            
            $table->integer('accountlvl5_id')->unsigned()->comment('Cuenta afectada en la integración');
            $table->foreign('accountlvl5_id')->references('id')->on('accounts_lvl5')->onDelete('cascade');
            
            $table->timestamps();
        });

        // INTEGRACION CONTABLE
        /*
            En esta tabla se almacenan las máscaras o alias que se mostrará a 
            los usuarios de los módulos Operativo, Financiero y Administrativo
            de SOFIAH para seleccionar la cuenta contable a afectar según la 
            naturaleza de la operación que estén registrando de manera de que
            evitar errores en la generación de comprobantes contables automáticos 
            y facilitar la operación del sistema
        */

        Schema::create('accounting_integrations', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('accounting_integration_name')->comment('Nombre o alias para la cuenta contable');
            
            $table->integer('accountlvl6_id')->unsigned()->comment('Cuenta afectada en la integración');
            $table->foreign('accountlvl6_id')->references('id')->on('accounts_lvl6')->onDelete('cascade');
            
            $table->timestamps();
        });

    /*-- Generación de reportes --*/

        // FLUJO DE EFECTIVO
        // Tabla de encabezado para estructurar los títulos que agrupan a las cuentas a presentar en el reporte de Flujo de efectivo

        Schema::create('cash_flow', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('concept')->comment('Concepto de la cuenta que agrupa para reflejarlo en el Reporte Flujo de Efectivo');
            $table->timestamps();
        });

        // FLUJO DE EFECTIVO -> CUENTA
        /*
            Tabla de detalle, relacionada con tFlujoEfectivo en donde 
            se almacenan los id del código de cuenta según la tabla accounts que
            deben ser tomadas en cuenta para la presentación del reporte Flujo de Efectivo
        */

        Schema::create('cash_flow_account', function (Blueprint $table) {

            $table->increments('id');
            
            $table->integer('cashflow_id')->unsigned()->comment('Cuenta a la que se agrupa la cuenta de detalle para presentar en el reporte flujo de efectivo');
            $table->integer('accountlvl6_id')->unsigned()->comment('Cuenta afectada en la integración');
            
            $table->foreign('cashflow_id')->references('id')->on('cash_flow')->onDelete('cascade');
            $table->foreign('accountlvl6_id')->references('id')->on('accounts_lvl6')->onDelete('cascade');
            
            $table->timestamps();
        });

        // CAMBIOS DE PATRIMONIO
        // Tabla de encabezado para estructurar los conceptos que agrupan a las cuentas a presentar en el reporte de Cambios del Patrimonio

        Schema::create('heritage_changes', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('concept')->comment('Concepto por el que se agrupan las partidas a reflejar en el reporte Cambios de Patrimonio');
            $table->timestamps();
        });

        // CAMBIOS DE PATRIMONIO -> CUENTA
        /*
            Tabla de detalle, relacionada con heritage_changes en donde 
            se almacenan los id del código de cuenta según la tabla accounts
            que deben ser tomadas en cuenta para la presentación del reporte 
            Cambios del Patrimonio
        */

        Schema::create('heritage_changes_account', function (Blueprint $table) {

            $table->increments('id');
            
            $table->integer('heritagechange_id')->unique()->unsigned()->comment('Cuenta perteneciente a la partida a agrupar en el concepto para su cálculo en el reporte cambios de patrimonio');
            $table->integer('accountlvl6_id')->unsigned()->comment('Cuenta afectada en la integración');
            
            $table->foreign('heritagechange_id')->references('id')->on('heritage_changes')->onDelete('cascade');
            $table->foreign('accountlvl6_id')->references('id')->on('accounts_lvl6')->onDelete('cascade');
            
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
        Schema::dropIfExists('heritage_changes_account');
        Schema::dropIfExists('heritage_changes');
        Schema::dropIfExists('cash_flow_account');
        Schema::dropIfExists('cash_flow');
        Schema::dropIfExists('accounting_integrations');
        Schema::dropIfExists('accounts_lvl6');
        Schema::dropIfExists('accounts_lvl5');
        Schema::dropIfExists('accounts_lvl4');
        Schema::dropIfExists('accounts_lvl3');
        Schema::dropIfExists('accounts_lvl2');
        Schema::dropIfExists('accounts_lvl1');
    }
}
