<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Administrative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 14-07-2017.
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
use App\Entities\Administrative\Cashflow;
use App\Transformers\Administrative\CashflowTransformer;

use League\Fractal;

/**
 *  Controlador Bancos
 */

class CashflowController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Cashflow $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }
        
        $paginator = $this->model->with('accounts')->paginate($request->get('limit', config('app.pagination_limit')));
        
        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new CashflowTransformer());
    }

    public function show($id) {
        
        $cashflow = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($cashflow, new CashflowTransformer());  
    }

    public function store(Request $request) {

        if(Cashflow::where('concept', '=', $request->concept)->exists()) return $this->response->error('El flujo de caja ya existe', 409);

        $this->validate($request, [

            'concept' => 'required'
        ]);

        $cashflow = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El flujo de caja se ha registrado exitosamente!', 
            'object'  => $cashflow 
        ]);
    }

    public function update(Request $request, $uuid) {

        if(Cashflow::where('concept', '=', $request->concept)->exists()) return $this->response->error('El flujo de caja ya existe', 409);

        $cashflow = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'concept' => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'concept' => 'sometimes|required'
            ];
        }
        
        $this->validate($request, $rules);
 
        $cashflow->update($request->all());

        return $this->response->item($cashflow->fresh(), new CashflowTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $cashflow = $this->model->byUuid($uuid)->firstOrFail();
        
        $cashflow->delete();

        return $this->response->noContent();
    }
}
