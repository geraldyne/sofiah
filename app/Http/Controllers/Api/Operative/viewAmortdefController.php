<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Operative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 15-10-2017.
 *  @version        1.0
 * 
 *  @final  
 */

/**
 * Incluye la implementación de los siguientes Controladores y Modelos
 */

namespace App\Http\Controllers\Api\Operative;


use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Entities\Operative\Amortdef;
use App\Entities\Operative\Amortdefloans;
use App\Entities\Operative\Issuedetails;
use App\Entities\Operative\Amortdefdetails;
use App\Entities\Operative\Loantypecodes;
use App\Entities\Administrative\Partner;
use App\Transformers\Operative\AmortdefTransformer;

use League\Fractal;

/**
 *  Controlador Amortizacion
 */

class viewAmortdefController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Amortdef $model) {
        
        $this->model = $model;
    }

    public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'amortdefdetails'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new AmortdefTransformer());
    }

    public function show($id) {
        
        $amortdef = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($amortdef, new AmortdefTransformer());  
    }

    public function store(Request $request) {

        
        $this->validate($request, [

            'date_issue'    => 'required',
            'amount'        => 'required',
            'status'        => 'required'
            
            
        ]);

        # Leemos el archivo recibido como parametro..

            # code.. code_type_loan.. monto

        \Excel::load($request->excel, function($reader) {
 
        $excel = $reader->get();
 
        // iteracción
        $reader->each(function($row) {
 
            $user->name = $row->nombre;
            $user->email = $row->email;
            $user->password = bcrypt('secret');
            $user->save();

            if ($file->id_card) // Si existe una cedula
            {
                $partner = Partner::where('id_card', $file->id_card)->firstOrFail; 
            
            }else if ($file->employee_code) // Si existe un codigo de empleado
                {
                    $partner = Partner::where('employee_code', $file->employee_code)->firstOrFail;
                }
                else if ($file->loan_code) // Si existe el codigo de tipo de prestamo
                {
                    $loan_code = Loantypecodes::where('loan_code', $file->loan_code)->firstOrFail;
                }

            # Buscamos la emision detalle de ese tipo de prestamo

            $issuedetails = Issuedetails::where([
                                                ['uuid', $request->issuedetails_id],
                                                ['loantypecode_id', $file->loantypecode_id ],])->firstOrFail;

            $request->amount= $file->amount;

            # Guardamos la amortizacion

            $amortdef = $this->model->create($request->all());

            # Verificamos si el monto de la amortizacion es igual al monto de la cuota a cancelar

            if ( $issuedetails->amount == $file->amount )  
            {
                $amortdefdetails = new Amortdefdetails();


                $amortdefdetails->amount = $issuedetails->amount;

                $amortdefdetails->capital = $issuedetails->capital;

                $amortdefdetails->interests = $issuedetails->interests;

                $amortdefdetails->loan_balance = $issuedetails->loan_balance;

                $amortdefdetails->quota_balance = $issuedetails->quota_balance;

                $amortdefdetails->quota_date = $issuedetails->quota_date;

                $amortdefdetails->type = $issuedetails->type;

                $amortdefdetails->quota_number = $issuedetails->quota_number;

                //$amortdefdetails->days = $issuedetails->days;

                $amortdefdetails->issuedetails_id = $issuedetails->issuedetails_id;

                $amortdefdetails->amortdef_id = $issuedetails->amortdef_id;
            }
            else // El monto de la amortizacion es diferente
            {
                # Actualizamos diferencia de amortizacion y saldo deudor del prestamo

                $issuedetails->quota_balance = $issuedetails->amount - $file->amount;

                $issuedetails->loan_balance += $issuedetails->quota_balance;


                # Recalculo de amortizacion prestamo
            }

            // Guardamos la amortizacion detalle

            $amortdefdetails->save();

            # Guardamos la amortizacion detalle 

            $amortdefdetails = new Amortdefdetails();

            $amortdefdetails->amount = $issuedetails->amount;

            $amortdefdetails->capital = $request->capital;

            $amortdefdetails->interests = $request->interests;

            $amortdefdetails->loan_balance = $request->loan_balance;

            $amortdefdetails->quota_balance = $request->quota_balance;

            $amortdefdetails->quota_date = $request->quota_date;

            $amortdefdetails->quota_number = $request->quota_number;

            $amortdefdetails->days = $request->days;

            $amortdefdetails->issuedetails_id = $issuedetails->issuedetails_id;

            $amortdefdetails->amortdef_id = $amortdef->amortdef_id;


            // Verificamos el tipo de emision a la que pertenece

            if ($issuedetails->type == 'E') // Si es de tipo Especial 
            {
                $amortdefdetails->type = 'E';
            }
            else // Es de tipo Ordinaria
            {
                $amortdefdetails->type = 'O';
            }


            $issuedetails->update();

            $amortdefdetails->save();

        });


        return response()->json([ 
            'status'  => true, 
            'message' => 'La amortizacion se ha registrado exitosamente!', 
            'object'  => $amortdef 
        ]);
    }

    public function update(Request $request, $uuid) {

        $amortdef = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'date_issue'    => 'required',
            'amount'        => 'required',
            'status'        => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'date_issue'    => 'required',
                'amount'        => 'required',
                'status'        => 'required'
            ];
        }

        $this->validate($request, $rules);
 
        $amortdef->update($request->all());

        return $this->response->item($amortdef->fresh(), new AmortdefTransformer());
    }

}
