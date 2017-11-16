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
use App\Entities\Administrative\State;
use App\Entities\Administrative\Country;
use App\Transformers\Administrative\StateTransformer;

use League\Fractal;

/**
 *  Controlador Estados
 */

class StatesController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(State $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }
        
        $paginator = $this->model->with('country','cities')->get();

        return $this->response->collection($paginator, new StateTransformer());
    }

    public function show($id) {
        
        $state = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($state, new StateTransformer());  
    }

    public function store(Request $request) {

        $this->validate($request, [

            'state' => 'required',
            'country_id' => 'required'
        ]);

        $country = Country::byUuid($request->country_id)->firstOrFail();

        $request->merge(array('country_id' => $country->id));

        $state = $this->model->create($request->all());

        return $this->response->created(url('api/states/'.$state->uuid));
    }

    public function update(Request $request, $uuid) {

        $state = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'state' => 'required',
            'country_id' => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'state' => 'sometimes|required',
                'country_id' => 'sometimes|required'
            ];
        }

        $country = Country::byUuid($request->country_id)->firstOrFail();

        $request->merge(array('country_id' => $country->id));
        
        $this->validate($request, $rules);
 
        $state->update($request->all());

        return $this->response->item($state->fresh(), new StateTransformer());
    }
}
