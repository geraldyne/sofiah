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
use App\Entities\Administrative\Accountlvl6;
use App\Entities\Administrative\Accountingintegration;
use App\Transformers\Administrative\AccountingintegrationTransformer;

use League\Fractal;

/**
 *  Controlador Cuentas de integración
 */

class AccountingintegrationController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Accountingintegration $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'accountlvl6'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new AccountingintegrationTransformer());
    }

    public function show($id) {
        
        $accountingintegration = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($accountingintegration, new AccountingintegrationTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'accounting_integration_name'   => 'required|unique:accounting_integrations',
            'accountlvl6_id'                => 'required'
        ]);

        $accountlvl6 = Accountlvl6::byUuid($request->accountlvl6_id)->firstOrFail();

        $request->merge(array('accountlvl6_id' => $accountlvl6->id));

        $accountingintegration = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'La cuenta se ha registrado exitosamente!', 
            'object'  => $accountingintegration 
        ]);
    }

    public function update(Request $request, $uuid) {

        $accountingintegration = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'accounting_integration_name'   => 'required|unique:accounting_integrations'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'accounting_integration_name'   => 'required|unique:accounting_integrations'
            ];
        }

        $this->validate($request, $rules);
 
        $accountingintegration->update($request->all());

        return $this->response->item($accountingintegration->fresh(), new AccountingintegrationTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $accountingintegration = $this->model->byUuid($uuid)->firstOrFail();

        if($accountingintegration->accountlvl6->dailymovementdetails->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'La cuenta '.$accountingintegration->account.' ya posee movimientos, no se puede eliminar.', 
            ]);
        }

        $accountingintegration->delete();

        return response()->json([ 
            'status' => true, 
            'message' => '¡La cuenta se ha eliminado exitosamente!', 
        ]);
    }
}
