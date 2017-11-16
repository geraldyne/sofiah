<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        // TIPO DE PRESTAMOS
        // En esta tabla se almacenan los tipos de préstamos y sus características según lo definan los estatutos internos de la asociación.
        
        Schema::create('loan_types', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('name')->comment('Nombre que le da la asociación al tipo de préstamo según sus estatutos internos');
            $table->boolean('guarantor')->comment('V: Acepta fiador - F: No acepta fiador');
            $table->boolean('guarantee')->comment('V: Acepta fianza de terceros - F: No acepta fianza de terceros');
            $table->boolean('guarantee_comision')->comment('V: Indica que el porcentaje de comisión por la fianza es sobre el total del préstamo otorgado - F: Indica que el porcentaje de comisión es por el monto de fianza otorgado');
            $table->boolean('refinancing')->comment('V: Indica que el préstamo puede ser refinanciado - F: Indica que el préstamo no puede ser refinanciado');
            $table->boolean('valid_availability')->comment('V: Se toma en cuenta la disponibilidad del asociado - F: No se toma en cuenta la disponibilidad del asociado');
            $table->boolean('affect_availability')->comment('V: Se toma en cuenta el saldo del préstamo para el cálculo de la disponibilidad del asociado - F: No se toma en cuenta el saldo del préstamo para el cálculo de la disponibilidad del asociado');
            $table->boolean('special_fees')->comment('V: Permite cuotas especiales - F: No permite cuotas especiales');
            $table->boolean('third_party_payment')->comment('V: Permite que el préstamo salga a nombre de un tercero - F: No permite que el préstamo salga a nombre de un tercero');
            $table->boolean('paid_capacity')->comment('V: Validar capacidad de pago para otorgar el préstamo - F: No validar capacidad de pago para otorgar el préstamo');
            $table->boolean('valid_policy')->comment('V: Validar vigencia póliza como garantía del préstamo - F: No validar vigencia póliza como garantía del préstamo');
            $table->boolean('web_based')->comment('V: El préstamo puede ser tramitado desde la web - F: El préstamo no puede ser tramitado desde la web');
            $table->boolean('administrative_expenditure')->comment('V: Carga gasto administrativo al préstamo - F: No carga gasto administrativo al préstamo');
            $table->boolean('deduct_administrative_expense')->comment('V: Descuenta el porcentaje del gasto administrativo al monto solicitado - F: Suma el porcentaje del gasto administrativo al monto solicitado');
            $table->float('interest')->comment('Indica el porcentaje de interés anual que se cobrará para el tipo de préstamo');
            $table->float('bond_commission')->comment('Indica el porcentaje de interés que se cobrará la afianzadora por el monto afianzado');
            $table->float('refinancing_amount')->comment('Indica el porcentaje mínimo que debe haber cancelado a capital para poder refinanciar el préstamo');
            $table->float('percent_special_quotes')->comment('Indica el porcentaje a cobrar del préstamo en cuotas especiales');
            $table->float('percent_administrative_expenditure')->comment('Indica el porcentaje a cobrar por gastos administrativos');
            $table->integer('refinance_days')->unsigned()->comment('Indica el mínimo de días que deben transcurrir para poder refinanciar el préstamo');
            $table->integer('term')->comment('Tiempo máximo, en meses, en que puede otorgarse el tipo de préstamo según los estatutos internos');
            $table->integer('number_guarantors')->unsigned()->comment('Indica el número máximo de fiadores que acepta para el tipo de préstamo');
            $table->integer('receivable_id')->unsigned()->comment('Id en la tabla accounting_integrations de la cuenta a afectar para generar la cuenta por cobrar al otorgar el préstamo');
            $table->integer('billtopay_id')->unsigned()->comment('Id en la tabla accounting_integrations de la cuenta a afectar para generar la CxP o Cuenta de Inventario al otorgar el préstamo');
            $table->integer('incomeaccount_id')->unsigned()->comment('Id en la tabla accounting_integrations de la cuenta a afectar cuando se causan los ingresos por préstamo');
            $table->integer('operatingexpenseaccount_id')->unsigned()->comment('Id de la tabla accounting_integrations de la cuenta de ingresos por gastos operativos a afectar');
            $table->integer('max_amount')->unsigned()->comment('Indica el monto máximo a otorgar de este tipo de préstamo. Si es 0 no tiene límites salvo la disponibilidad del asociado');
            $table->boolean('status')->default(1)->comment('Indica el estatus del tipo de prestamo. Si es 1 esta vigente. 0 si esta suspendido');
            $table->foreign('receivable_id')->references('id')->on('accounting_integrations')->onDelete('cascade');
            $table->foreign('billtopay_id')->references('id')->on('accounting_integrations')->onDelete('cascade');
            $table->foreign('incomeaccount_id')->references('id')->on('accounting_integrations')->onDelete('cascade');
            $table->foreign('operatingexpenseaccount_id')->references('id')->on('accounting_integrations')->onDelete('cascade');
            
            $table->timestamps();
        });
    
        // CODIGO TIPO DE PRESTAMOS
        // En esta tabla se almacenan el código que asigna cada organismo al tipo de préstamos para efectos de la lectura y envío del archivo de emisión y amortización.
        
        Schema::create('loan_type_codes', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->integer('loan_code')->unsigned()->comment('Código para el tipo de préstamo asignado por el organismo');
            $table->integer('loantypes_id')->unsigned()->comment('Id del tipo de préstamo que por configuración acepta cuotas especiales');            
            $table->integer('organism_id')->unsigned()->comment('Id en la tabla organisms del organismo que asigna el código al tipo de préstamo');            
            $table->foreign('loantypes_id')->references('id')->on('loan_types')->onDelete('cascade');
            $table->foreign('organism_id')->references('id')->on('organisms')->onDelete('cascade');
            $table->timestamps();
        });

        // GRUPO DE TIPOS DE PRESTAMOS
        // En esta tabla se almacena la definición del grupo de préstamo que es utilizado para definir un monto de otorgamiento máximo en préstamo al asociado aplicable a la suma de los montos otorgados en los tipos de préstamos relacionados al grupo.
        
        Schema::create('loan_type_groups', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->double('max_amount')->comment('Monto máximo a prestar al asociado para este grupo de tipo de préstamos, se debe sumar todos los montos otorgados para el tipo de préstamo del grupo');            
            $table->string('name')->comment('Nombre del grupo de tipo de préstamo');
             $table->boolean('status')->default(1)->comment('Indica el estatus del tipo de prestamo. Si es 1 esta vigente. 0 si esta suspendido');
            $table->timestamps();
        });

        // GRUPO TIPO DE PRESTAMO -> TIPO DE PRESTAMOS
        // En esta tabla se almacena la relación entre el tipo de préstamo y el grupo de préstamo al que este asociado.
        
        Schema::create('loans_groups', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('name')->comment('Nombre del grupo de préstamo');
            $table->integer('loantypes_id')->unsigned()->comment('Id del tipo de préstamo que pertenece al grupo.');            
            $table->integer('loantypegroups_id')->unsigned()->comment('Id del grupo al que pertenece el tipo de préstamo.');            
            $table->foreign('loantypes_id')->references('id')->on('loan_types')->onDelete('cascade');
            $table->foreign('loantypegroups_id')->references('id')->on('loan_type_groups')->onDelete('cascade');

            $table->timestamps();
        });


        // CUOTA ESPECIAL DETALLE
        // En esta tabla se almacenan los meses autorizados para la retención de estas cuotas especiales, se relaciona con la tabla Cuota Especial que almacena la definición de las cuotas especiales por tipo de préstamo.
        
        Schema::create('special_fee_details', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->integer('month')->unsigned()->comment('Número del mes correspondiente al mes en donde se permitirá la cuota especial');

            $table->timestamps();
        });


        // CUOTA ESPECIAL 
        // En esta tabla se almacena la relacion entre el tipo de préstamo y la cuota especial
        
        Schema::create('special_fee', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->integer('loantypes_id')->unsigned()->comment('Id del tipo de préstamo.');
            $table->integer('specialfeedetails_id')->unsigned()->comment('Id del detalle de la cuota especial.');   

            $table->foreign('loantypes_id')->references('id')->on('loan_types')->onDelete('cascade');
            $table->foreign('specialfeedetails_id')->references('id')->on('special_fee_details')->onDelete('cascade');

            $table->timestamps();
        });


        // PRESTAMOS
        // En esta tabla se almacenan los préstamos otorgados a los asociados.
        
        Schema::create('loans', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->date('issue_date')->nullable()->comment('Fecha en que el préstamo es otorgado');
            $table->float('amount')->comment('Monto otorgado al asociado en el préstamo solicitado');
            $table->float('rate')->comment('Tasa de interés a cobrar');
            $table->float('balance')->comment('Saldo del préstamo, mantener el campo actualizado');            
            $table->float('administrative_expenditure')->comment('Si el tipo de préstamo tiene configurado gasto administrativo ingresar el monto correspondiente, sino ingresar 0');            
            $table->enum('fee_frequency', ['S','Q','M'])->comment('Almacena el tipo de nómina del organismo. S: Semanal Q: Quincenal M: Mensual');
            $table->enum('status', ['PEN','PAP','APR', 'APL'])->comment('Almacena el estatus del préstamo. PEN: Pendiente PAP: Por aprobar APR: Aprobado APL: Aplicado');
            $table->string('destination')->comment('Indicar el motivo por el cual está solicitando el préstamo');
            $table->integer('monthly_fees')->unsigned()->comment('Número de coutas para el pago del préstamo');

            $table->integer('loantypes_id')->unsigned()->comment('Id del tipo de préstamo que pertenece al grupo.');
            $table->foreign('loantypes_id')->references('id')->on('loan_types')->onDelete('cascade');       

            $table->integer('partner_id')->unsigned()->comment('Id del asociado a la cual corresponde el prestamo.');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');           
            
            $table->timestamps();
        });

        // PROVEEDORES
        // En esta tabla se almacenan los proveedores.
        
        Schema::create('providers', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('name')->comment('Nombre completo del proveedor');
            $table->string('email')->comment('Dirección de correo electrónico del proveedor');
            $table->string('web_site')->default('No posee')->comment('Página web del proveedor');
            $table->string('contact')->comment('Persona de contacto en el proveedor (Referencial)');
            $table->string('slug')->nullable()->comment('Campo que almacena el nombre de manera legible en la url');
            $table->enum('rif_type', ['J','N'])->comment('Almacena el tipo de rif. J: Jurídico. N: Natural');
            $table->integer('rif')->comment('Número de Registro de Información Fiscal del proveedor');
            $table->integer('phone')->comment('Número de teléfono de contacto del proveedor');
            $table->integer('direction_id')->unsigned()->comment('Direccion del proveedor');
            $table->enum('status', ['A','S'])->default('A')->comment('A: Activo - S: Suspendido');
            $table->foreign('direction_id')->references('id')->on('directions')->onDelete('cascade');
            
            $table->timestamps();
        });

        // FIANZAS
        // En esta tabla se almacenan las fianzas otorgadas a un préstamo.
        
        Schema::create('bonds', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('number')->comment('Numero de la fianza');
            $table->date('issue_date')->nullable()->comment('Fecha en que la fianza es otorgada');
            $table->float('amount')->comment('Monto otorgado de fianza al asociado en el préstamo solicitado');
            $table->float('commission')->comment('Comisión de la fianza');
            $table->enum('status', ['P','C'])->comment('P: Pendiente - C: Cancelado');

            $table->integer('provider_id')->unsigned()->comment('Id del proveedor de la fianza');  
            $table->integer('loan_id')->unsigned()->comment('Prestamo que posee una fianza.');

            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            
            $table->timestamps();
        });

        // POLIZAS
        // En esta tabla se almacenan las polizas otorgadas a un préstamo.
        
        Schema::create('policies', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->string('number')->comment('Numero de la poliza');
            $table->string('type')->comment('Tipo de la poliza');
            $table->date('issue_date')->comment('Fecha en que la poliza es otorgada');
            $table->date('due_date')->comment('Fecha en que la poliza se vence');
            $table->float('amount')->comment('Monto otorgado de la poliza');
            $table->enum('status', ['A','V'])->comment('A: Activa - V: Vencida');

            $table->integer('provider_id')->unsigned()->comment('Id del proveedor de la poliza');            
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');

            $table->integer('loan_id')->unsigned()->comment('Prestamo que posee una poliza.');
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');  
            
            $table->timestamps();
        });

        // FIADORES
        // En esta tabla se almacenan los fiadores del préstamo.
        
        Schema::create('guarantors', function (Blueprint $table) {

            $table->increments('id');
            $table->uuid('uuid')->index()->unique();
            $table->float('amount')->comment('Monto afianzado por el fiador');
            $table->float('balance')->comment('Saldo pendiente del monto afianzado');
            $table->float('percentage')->comment('Porcentaje del monto afianzado en base al total de la fianza');
            $table->enum('status', ['P','C'])->comment('P: Pendiente - C: Cancelado');

            $table->integer('partner_id')->unsigned()->comment('Id del proveedor de la poliza');           
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');

            $table->integer('loan_id')->unsigned()->comment('Prestamo que posee una poliza.');
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        
        Schema::dropIfExists('guarantors');
        Schema::dropIfExists('policies');
        Schema::dropIfExists('bonds');
        Schema::dropIfExists('providers');
        Schema::dropIfExists('loans');
        Schema::dropIfExists('special_fee');
        Schema::dropIfExists('special_fee_details');
        Schema::dropIfExists('loans_groups');
        Schema::dropIfExists('loan_type_groups');
        Schema::dropIfExists('loan_type_codes');
        Schema::dropIfExists('loan_types');
    }
}
