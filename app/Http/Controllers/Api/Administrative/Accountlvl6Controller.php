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

        $paginator = $this->model->with(
            'accountlvl5', 
            'accountingintegration', 
            'cashflow', 
            'heritagechange',
            'dailymovementdetails'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new Accountlvl6Transformer());
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
            'balance_type'    => 'required',
            'apply_balance'   => 'required|boolean',
            'accountlvl5_id'  => 'required'
        ]);

        $accountlvl5 = Accountlvl5::byUuid($request->accountlvl5_id)->firstOrFail();

        $request->merge(array('accountlvl5_id' => $accountlvl5->id));

        $accountlvl6 = $this->model->create($request->all());

        return response()->json([ 
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
