<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // MOVIMIENTOS PRESTAMOS
        // En esta tabla se almacenan todos los movimientos que se realizan a un préstamo otorgado hasta su cancelación.
        
        Schema::create('loan_movements', function (Blueprint $table) {
            
            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->date('date_issue')->comment('Fecha del movimiento al préstamo');
            $table->float('amount')->comment('Monto del movimiento. Si es de tipo préstamo registrar en positivo, si es amortización o abono registrar en negativo.');
            $table->enum('type', ['PR','AM','AB'])->comment('Tipo de movimiento. PR: Préstamo. AM: Amortización. AB: Abono.');
            $table->enum('status', ['P','A'])->comment('Estatus del movimiento. P: Pendiente. A: Aplicada.');

            $table->integer('loan_id')->unsigned()->comment('Id del préstamo al que pertenece el movimiento.');
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');

            $table->integer('amortdefdetails_id')->unsigned()->comment('Id de la amortización detalles');
            $table->foreign('amortdefdetails_id')->references('id')->on('amort_def_details')->onDelete('cascade');

            $table->timestamps();
        });

        // MOVIMIENTOS HABERES
        
        Schema::create('assets_movements', function (Blueprint $table) {
            
            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->date('date_issue')->comment('Fecha del movimiento de haberes');
            $table->enum('reason', ['AH','RP','RT','AJ'])->comment('Motivo del movimiento. AH: Ahorro. RP: Retiro parcial. RT: Retiro total. AJ: Ajuste');
            $table->enum('status', ['P','A'])->comment('Estatus del movimiento. P: Pendiente. A: Aplicada.');
            $table->float('total_amount')->comment('Monto total del movimiento');
            $table->string('description')->comment('Ingresar el motivo por el que está realizando el movimiento de haberes el asociado');
            
            $table->integer('partner_id')->unsigned()->comment('Id del préstamo al que pertenece el movimiento.');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');

            $table->timestamps();
        });

        // MOVIMIENTOS HABERES DETALLE
        
        Schema::create('assets_movements_details', function (Blueprint $table) {
            
            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->float('amount')->comment('Monto del movimiento. Si es de tipo ahorro registrar en positivo, si es cualquier otro en negativo.');
            $table->enum('type', ['AP','AI','AV'])->comment('Tipo de movimiento. AP: Aporte patronal. AI: Aporte individual. AV: Aporte voluntario.');
            $table->integer('assetsmovements_id')->unsigned()->comment('Id del movimiento de haberes al que pertenece el detalle.');
            $table->foreign('assetsmovements_id')->references('id')->on('assets_movements')->onDelete('cascade');

            $table->timestamps();
        });

        // SALDO HABERES 
        
        Schema::create('assets_balance', function (Blueprint $table) {
            
            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->float('balance_employers_contribution')->comment('Saldo del aporte patronal del asociado');
            $table->float('balance_individual_contribution')->comment('Saldo del aporte individual del asociado');
            $table->float('balance_voluntary_contribution')->comment('Saldo del aporte voluntario del asociado');
            
            $table->integer('partner_id')->unsigned()->comment('Id del asociado a la cual pertenece el saldo.');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');

            $table->timestamps();
        });


        // CODIGO TIPO HABERES 
        
        Schema::create('assets_type_codes', function (Blueprint $table) {
            
            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->integer('assets_organisms_code')->unsigned()->comment('Codigo de haberes organismos');
            $table->enum('type', ['AP','AI','AV'])->comment('Tipo de movimiento. AP: Aporte patronal. AI: Aporte individual. AV: Aporte voluntario.');    
            
            $table->integer('organism_id')->unsigned()->comment('id del organismo');            

            $table->foreign('organism_id')->references('id')->on('organisms')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    
    public function down() {

        Schema::dropIfExists('assets_type_codes');
        Schema::dropIfExists('assets_movements_details');
        Schema::dropIfExists('assets_movements');
        Schema::dropIfExists('loan_movements');
    }
}
