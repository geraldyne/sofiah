<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmortizationIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        // EMISIONES
        // En esta tabla se almacenan el resultado del documento de Nomina o Emisión que se ejecuta para enviar al organismo las retenciones por préstamo.
        
        Schema::create('issues', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->date('date_issue')->comment('Fecha en que se recibió la emisión');
            $table->float('amount')->comment('Monto total de la emisión al cobro');
            $table->enum('status', ['P','A','C'])->comment('Estatus de la emisión. P: Pendiente. A: Aplicada. C: Cobrada.');

            $table->integer('organisms_id')->unsigned()->comment('Id de la emision.');
            $table->foreign('organisms_id')->references('id')->on('organisms')->onDelete('cascade');
            
            $table->timestamps();
        });


        // EMISIONES DETALLES
        // En esta tabla se almacena el detalle de las cuotas que son enviadas al cobro en la Emisión.
        
        Schema::create('issue_details', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->float('amount')->comment('Monto de la cuota');
            $table->float('capital')->comment('Parte de la cuota que abona a capital');
            $table->float('interests')->comment('Parte de la cuota que abona a interéses');
            $table->float('loan_balance')->comment('Saldo deudor del préstamo al momento de pagar la cuota');
            $table->float('quota_balance')->comment('Se registra el diferencial de la amortizacion menos la emisión');
            $table->date('quota_date')->comment('Fecha en que la cuota es otorgada');
            $table->enum('type', ['O','E'])->comment('Tipo de la cuota. O: Ordinaria. E: Especial');
            $table->integer('quota_number')->unsigned()->comment('Número de la cuota, este número es consecutivo');
            $table->integer('days')->unsigned()->comment('Días de pago de la cuota');

            $table->integer('issues_id')->unsigned()->comment('Id de la emision.');
            $table->foreign('issues_id')->references('id')->on('issues')->onDelete('cascade');

            $table->timestamps();
        });


        // AMORTIZACION DETALLES (Amortizacion detalles)
        // En esta tabla se almacena el detalle de las cuotas que son enviadas al cobro en la Emisión.
        
        Schema::create('amort_def_details', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->float('amount')->comment('Monto de la cuota');
            $table->float('capital')->comment('Parte de la cuota que abona a capital');
            $table->float('interests')->comment('Parte de la cuota que abona a interéses');
            $table->float('loan_balance')->comment('Saldo deudor del préstamo al momento de pagar la cuota');
            $table->float('quota_balance')->comment('Se registra el diferencial de la amortizacion menos la emisión');
            $table->date('quota_date')->comment('Fecha en que la cuota es otorgada');
            $table->enum('type', ['O','E'])->comment('Tipo de la cuota. O: Ordinaria. E: Especial');
            $table->integer('quota_number')->unsigned()->comment('Número de la cuota, este número es consecutivo');
            $table->integer('days')->unsigned()->comment('Días de pago de la cuota');

            $table->integer('issuedetails_id')->unsigned()->comment('Id de los detalles de la emision');
            $table->foreign('issuedetails_id')->references('id')->on('issue_details')->onDelete('cascade');

            $table->timestamps();
        });


        // AMORTIZACIONES
        // En esta tabla se almacenan las cuotas efectivamente retenidas por el organismo según la emisión enviada al cobro.
        
        Schema::create('amort_def', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->date('date_issue')->comment('Fecha en que se generó la amortización');
            $table->float('amount')->comment('Monto total de la emisión al cobro');
            $table->enum('status', ['P','A','C'])->comment('Estatus de la emisión. P: Pendiente. A: Aplicada. C: Cobrada.');
            
            $table->timestamps();
        });

        

        // AMORTIZACION PRESTAMOS
        // En esta tabla se almacena el detalle de las cuotas que son enviadas al cobro en la Emisión.
        
        Schema::create('amortdef_loans', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->float('quota_amount')->comment('');
            $table->date('quota_date')->comment('');
            $table->float('quota_amount_ordinary')->comment('');
            $table->float('capital_quota_ordinary')->comment('');
            $table->float('interests_quota_ordinary')->comment('');
            $table->float('capital_quota_special')->comment('');
            $table->float('amount_quota_special')->comment('');
            $table->float('balance_quota_ordinary')->comment('');
            $table->float('balance_quota_special')->comment('');

            $table->integer('amortdef_id')->unsigned()->comment('Id de la amortización');
            $table->foreign('amortdef_id')->references('id')->on('amort_def')->onDelete('cascade');

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
        Schema::dropIfExists('amortdef_loans');
        Schema::dropIfExists('amort_def');
        Schema::dropIfExists('amort_def_details');
        Schema::dropIfExists('issue_details');
        Schema::dropIfExists('issues');
    }
}
