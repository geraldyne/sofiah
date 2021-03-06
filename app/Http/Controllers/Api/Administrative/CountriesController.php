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
use App\Entities\Administrative\Country;
use App\Transformers\Administrative\CountryTransformer;

use League\Fractal;

/**
 *  Controlador Paises
 */

class CountriesController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Country $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }
        
        $paginator = $this->model->with('states')->get();

        return $this->response->collection($paginator, new CountryTransformer());
    }

    public function show($id) {
        
        $country = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($country, new CountryTransformer());  
    }

    public function store(Request $request) {

        $this->validate($request, [

            'country' => 'required'
        ]);

        $country = $this->model->create($request->all());

        return $this->response->created(url('api/countries/'.$country->uuid));
    }

    public function update(Request $request, $uuid) {

        $country = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'country' => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'country' => 'sometimes|required'
            ];
        }
        
        $this->validate($request, $rules);
 
        $country->update($request->all());

        return $this->response->item($country->fresh(), new CountryTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $country = $this->model->byUuid($uuid)->firstOrFail();
        
        $country->delete();

        return $this->response->noContent();
    }
}
