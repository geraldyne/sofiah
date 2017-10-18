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
use App\Entities\Administrative\City;
use App\Entities\Administrative\State;
use App\Transformers\Administrative\CityTransformer;

use League\Fractal;

/**
 *  Controlador Estados
 */

class CitiesController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(City $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }
        
        $paginator = $this->model->with('state','directions')->paginate($request->get('limit', config('app.pagination_limit')));
        
        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new CityTransformer());
    }

    public function show($id) {
        
        $city = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($city, new CityTransformer());  
    }

    public function store(Request $request) {

        $this->validate($request, [

            'city' => 'required',
            'state_id' => 'required',
            'area_code' => 'required'
        ]);

        $state = State::byUuid($request->state_id)->firstOrFail();

        $request->merge(array('state_id' => $state->id));

        $city = $this->model->create($request->all());

        return $this->response->created(url('api/cities/'.$city->uuid));
    }

    public function update(Request $request, $uuid) {

        $city = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'city' => 'required',
            'state_id' => 'required',
            'area_code' => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'city' => 'sometimes|required',
                'state_id' => 'sometimes|required',
                'area_code' => 'sometimes|required'
            ];
        }

        $state = State::byUuid($request->state_id)->firstOrFail();

        $request->merge(array('state_id' => $state->id));
        
        $this->validate($request, $rules);
 
        $city->update($request->all());

        return $this->response->item($city->fresh(), new CityTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $city = $this->model->byUuid($uuid)->firstOrFail();
        
        $city->delete();

        return $this->response->noContent();
    }
}
