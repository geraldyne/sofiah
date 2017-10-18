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
use App\Entities\Administrative\Dailymovement;
use App\Entities\Administrative\Accountingyear;
use App\Transformers\Administrative\AccountingyearTransformer;

use League\Fractal;

/**
 *  Controlador para ejercicios contables
 */

class AccountingyearController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Accountingyear $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'dividends',
            'dailymovement'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new AccountingyearTransformer());
    }

    public function show($id) {
        
        $accountingyear = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($accountingyear, new AccountingyearTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'start_date'        => 'required|date',
            'deadline'          => 'required|date',            
            'status'            => 'required|boolean',
            'dailymovement_id'  => 'required'
        ]);

        $dailymovement = Dailymovement::byUuid($request->dailymovement_id)->firstOrFail();

        $request->merge(array('dailymovement_id' => $dailymovement->id));

        $accountingyear = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El ejercicio contable se ha registrado exitosamente!', 
            'object'  => $accountingyear 
        ]);
    }

    public function update(Request $request, $uuid) {

        $accountingyear = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'accounting_integration_name'   => 'required|unique:accounting_integrations'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'accounting_integration_name'   => 'required|unique:accounting_integrations'
            ];
        }

        $this->validate($request, $rules);
 
        $accountingyear->update($request->all());

        return $this->response->item($accountingyear->fresh(), new AccountingyearTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $accountingyear = $this->model->byUuid($uuid)->firstOrFail();

        if($accountingyear->accountlvl6->dailymovementdetails->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'La cuenta '.$accountingyear->account.' ya posee movimientos, no se puede eliminar.', 
            ]);
        }

        $accountingyear->delete();

        return response()->json([ 
            'status' => true, 
            'message' => '¡La cuenta se ha eliminado exitosamente!', 
        ]);
    }
}
