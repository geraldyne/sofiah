<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Administrative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 12-10-2017.
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
use App\Entities\Administrative\Direction;
use App\Transformers\Administrative\DirectionTransformer;

use League\Fractal;

/**
 *  Controlador Direccion
 */

class DirectionController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Direction $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with('city', 'associations', 'organisms', 'employees')->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new DirectionTransformer());
    }

    public function show($id) {
        
        $direction = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($direction, new DirectionTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'direction' => 'required',
            'city_id'   => 'required'
        ]);

        $city = City::byUuid($request->city_id)->firstOrFail();

        $request->merge(array('city_id' => $city->id));

        $direction = $this->model->create($request->all());

        return response()->json([ 
                                'status'  => true, 
                                'message' => 'La direccion se ha registrado exitosamente!', 
                                'object'  => $direction 
                                ]);
    }

    public function update(Request $request, $uuid) {

        $direction = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'direction' => 'required',
            'city_id'   => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'direction' => 'required',
                'city_id'   => 'required'
            ];
        }

        $city = City::byUuid($request->city_id)->firstOrFail();

        $request->merge(array('city_id' => $city->id));
        
        $this->validate($request, $rules);
 
        $direction->update($request->all());

        return $this->response->item($direction->fresh(), new DirectionTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $direction = $this->model->byUuid($uuid)->firstOrFail();
        
        $direction->delete();

        return response()->json([ 
                                'status' => true, 
                                'message' => 'La cuenta se ha eliminado exitosamente!', 
                                ]);
    }
}
