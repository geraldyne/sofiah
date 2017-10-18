<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        // MOVIMIENTOS DIARIOS
        /*
            En esta tabla de encabezado se almacenan todos los movimientos 
            o registros contables que se generen de forma automática desde 
            el resto de los módulos de SOFIAH o directamente desde el módulo
            Administrativo. 

            Desde esta tabla se consultan las transacciones, según sea el caso, 
            para la misión de estados financieros, indicadores financieros, proceso de
            cierre de ejercicio y cálculo de dividendos
        */

        Schema::create('daily_movements', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->dateTime('date')->comment('Fecha en que se registra el comprobante contable');
            $table->text('description')->comment('Descripción del comprobante contable');
            $table->enum('status', ['A','P','An'])->comment('A: Aplicado - P: Pendiente - An: Anulado');
            $table->float('debit')->comment('Suma del total de movimientos afectados al debe');
            $table->float('asset')->comment('Suma del total de movimientos registrados al haber');
            $table->integer('number')->comment('Número de control del comprobante contable');
            $table->enum('origin', ['administrativo', 'prestamo', 'nomina', 'tesoreria', 'ajuste', 'compras', 'contabilidad'])->default('contabilidad')->comment('Indica el módulo de donde se originó el registro contable');
            $table->enum('type', ['diario', 'cierre'])->default('diario')->comment('Indica el tipo de movimiento (diario, cierre) para diferenciar el comprobante de cierre del ejercicio');
            $table->timestamps();
        });

        // MOVIMIENTOS DIARIOS -> USUARIO ORIGINA
        /*
            En esta tabla se almacena el usuario que aplica la transacción
        */

        Schema::create('daily_movements_origin', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('dailymovement_id')->unsigned()->comment('Movimiento diario al que pertene');
            $table->integer('user_id')->unsigned()->comment('Usuario que origina el comprobante contable');
            $table->foreign('dailymovement_id')->references('id')->on('daily_movements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        // MOVIMIENTOS DIARIOS -> USUARIO APLICA
        /*
            En esta tabla se almacena el usuario que aplica la transacción
        */

        Schema::create('daily_movements_apply', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('dailymovement_id')->unsigned()->comment('Movimiento diario al que pertene');
            $table->integer('user_id')->unsigned()->comment('Usuario que aplica el comprobante contable');
            $table->foreign('dailymovement_id')->references('id')->on('daily_movements')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        // MOVIMIENTOS DIARIOS -> DETALLES
        /*
            En esta tabla de detalle se almacenan todos los movimientos
            o registros contables que se generen de forma automática desde
            el resto de los módulos de SOFIAH o directamente desde el módulo 
            Administrativo.

            Desde esta tabla se consultan las transacciones, según sea el caso, 
            para la misión de estados financieros, indicadores financieros, 
            proceso de cierre de ejercicio y cálculo de dividendos
        */

        Schema::create('daily_movements_details', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->text('description')->comment('Descripción del movimiento realizado a esta cuenta en el comprobante contable');
            $table->float('debit')->comment('Monto afectado al debe en el comprobante');
            $table->float('asset')->comment('Monto afectado al haber en el comprobante');
            $table->integer('dailymovement_id')->unsigned()->comment('Movimiento diario al que pertene');
            $table->integer('account_id')->unsigned()->comment('Cuenta afectada en la integración');
            
            $table->foreign('dailymovement_id')->references('id')->on('daily_movements')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts_lvl6')->onDelete('cascade');
            
            $table->timestamps();
        });

        // EJERCICIOS CONTABLES
        // En esta tabla se almacena los datos básicos resultado del cierre del ejercicio, 
        // e indica si el mismo ya está cerrado o permanece aún abierto. 
        // Mientras esté abierto permite registro de comprobantes contables y 
        // movimientos en SOFIAH en ese ejercicio, sin embargo, no permite aplicar 
        // el cálculo de Dividendos
        
        Schema::create('accounting_year', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->date('start_date')->nullable()->comment('Fecha de inicio del ejercicio contable');
            $table->date('deadline')->nullable()->comment('Fecha de cierre del ejercicio contable');            
            $table->boolean('status')->comment('V: Si el ejercicio está cerrado - F: Si el ejercicio está abierto');
            $table->integer('dailymovement_id')->unsigned()->comment('Movimiento diario que cierra el ejercicio, si está abierto el valor debe ser 0');
            $table->foreign('dailymovement_id')->references('id')->on('daily_movements')->onDelete('cascade');
            $table->timestamps();
        });

        // DIVIDENDOS
        // En esta tabla se almacena el resultado del cálculo de los dividendos 
        // del ejercicio por asociado y por mes para mantener un registro histórico 
        // del resultado obtenido y para facilitar la generación y consulta de este 
        // tipo de información.
        
        Schema::create('dividends', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->date('registration_date')->nullable()->comment('Se toma sólo el mes y el año dle registro. Ejemplo ENERO 2017');            
            $table->float('assets_associated')->comment('Total de haberes del asociado en el mes');
            $table->float('dividends')->comment('Total de dividendos ganados por el asociado en el mes');
            $table->float('assets_association')->comment('Total de haberes de la caja de ahorros para el mes y año');
            $table->float('factor')->comment('Factor aplicado al asociado para el cálculo de su dividendo para el mes');
            $table->boolean('status')->comment('V: Aplicado - F: Permite edición');
            $table->integer('partner_id')->unsigned()->comment('Asociado al que se realiza el cálculo de dividendos');
            $table->integer('accounting_id')->unsigned()->comment('Ejercicio en el cual se realiza el cálculo de dividendos del asociado');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
            $table->foreign('accounting_id')->references('id')->on('accounting_year')->onDelete('cascade');
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
        Schema::dropIfExists('dividends');
        Schema::dropIfExists('accounting_year');
        Schema::dropIfExists('daily_movements_details');
        Schema::dropIfExists('daily_movements_apply');
        Schema::dropIfExists('daily_movements_origin');
        Schema::dropIfExists('daily_movements');
    }
}
