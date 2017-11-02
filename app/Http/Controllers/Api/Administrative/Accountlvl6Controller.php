<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Administrative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 11-10-2017.
 *  @version        1.0
 * 
 *  @final  
 */

/**
 * Incluye la implementación de los siguientes Controladores y Modelos
 */

namespace App\Http\Controllers\Api\Administrative;


use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Entities\Administrative\Accountlvl5;
use App\Entities\Administrative\Accountlvl6;
use App\Transformers\Administrative\Accountlvl6Transformer;

use League\Fractal;

/**
 *  Controlador Cuentas Nivel 6
 */

class Accountlvl6Controller extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Accountlvl6 $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $accountlvl6 = $this->model->get();

        return $this->response->collection($accountlvl6, new Accountlvl6Transformer());
    }

    public function show($id) {
        
        $accountlvl6 = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($accountlvl6, new Accountlvl6Transformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'account_code'    => 'required|numeric|unique:accounts_lvl6',
            'account_name'    => 'required|unique:accounts_lvl6',
            'account_type'    => 'required',
            'cash_flow'       => 'required|boolean',
            'accountlvl5_id'  => 'required'
        ]);

        # Establece el tipo de saldo
        
        if($request->account_type == 'activo' || 
           $request->account_type == 'ingreso')

            $request->merge(array('balance_type' => 'deudor'));

        else 

            $request->merge(array('balance_type' => 'acreedor'));

        # Establece si aplica balance
        
        if($request->account_type == 'activo' || 
           $request->account_type == 'patrimonio' ||
           $request->account_type == 'pasivo')

            $request->merge(array('apply_balance' => true));

        else 

            $request->merge(array('apply_balance' => false));
        
        #  Establece si es cuenta efectivo
          
        if($request->cash_flow)

            $request->merge(array('cash_flow' => true));

        else

            $request->merge(array('cash_flow' => false));

        # Ubica la cuenta padre (nivel 5) por el UUID

        $accountlvl5 = Accountlvl5::byUuid($request->accountlvl5_id)->firstOrFail();

        $request->merge(array('accountlvl5_id' => $accountlvl5->id));

        # Crea la cuenta de nivel 6
        
        $accountlvl6 = $this->model->create($request->all());

        if( ! $accountlvl6) return response()->json([

            'status'  => false, 
            'message' => '¡Ha ocurrido un error al agregar la cuenta! Por favor verifique los datos he intente nuevamente.'
        ]);
        
        else return response()->json([ 
            'status'  => true, 
            'message' => 'La cuenta se ha registrado exitosamente!', 
            'object'  => $accountlvl6 
        ]);
    }

    public function update(Request $request, $uuid) {

        $accountlvl6 = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'account_code'   => 'required|numeric|unique:accounts_lvl6',
            'account_name'   => 'required|unique:accounts_lvl6',
            'account_type'   => 'required',
            'balance_type'   => 'required',
            'apply_balance'  => 'required|boolean',
            'accountlvl5_id' => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'account_code'   => 'required|numeric|unique:accounts_lvl6',
                'account_name'   => 'required|unique:accounts_lvl6',
                'account_type'   => 'required',
                'balance_type'   => 'required',
                'apply_balance'  => 'required|boolean',
                'accountlvl5_id' => 'required'
            ];
        }

        $accountlvl5 = Accountlvl5::byUuid($request->accountlvl5_id)->firstOrFail();

        $request->merge(array('accountlvl5_id' => $accountlvl5->id));
        
        $this->validate($request, $rules);
 
        $accountlvl6->update($request->all());

        return $this->response->item($accountlvl6->fresh(), new Accountlvl6Transformer());
    }

    public function destroy(Request $request, $uuid) {

        $accountlvl6 = $this->model->byUuid($uuid)->firstOrFail();
        
        if($accountlvl6->dailymovementdetails->count() > 0) 

            return response()->json([

                'status'    => false,
                'message'   => 'La cuenta posee un movimiento registrado, no se puede eliminar.'
            ]);

        $accountlvl6->delete();

        return response()->json([ 
            'status' => true, 
            'message' => 'La cuenta se ha eliminado exitosamente!', 
        ]);
    }
}
