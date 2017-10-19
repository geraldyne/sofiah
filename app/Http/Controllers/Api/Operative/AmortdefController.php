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
use App\Transformers\Operative\AmortdefTransformer;

use League\Fractal;

/**
 *  Controlador tipos de préstamos
 */

class AmortdefController extends Controller {

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

        $amortdef = $this->model->create($request->all());

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
