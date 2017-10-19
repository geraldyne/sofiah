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
use App\Entities\Operative\Provider;
use App\Entities\Administrative\Direction;
use App\Transformers\Operative\ProviderTransformer;

use League\Fractal;

/**
 *  Controlador proveedor
 */

class ProviderController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Provider $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'policies',
            'bonds',
            'direction'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new ProviderTransformer());
    }

    public function show($id) {
        
        $provider = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($provider, new ProviderTransformer());  
    }

    public function store(Request $request) {

        if(!$request->web_site) {

            $request->merge(array('web_site' => 'no posee'));
        }

        if(!$request->slug) {

            $request->merge(array('slug' => 'no posee'));
        }

        
        $this->validate($request, [

            'name'            => 'required',
            'email'           => 'required',
            'web_site'        => 'required',
            'contact'         => 'required',
            'slug'            => 'required',
            'rif_type'        => 'required',
            'rif'             => 'required',
            'phone'           => 'required',
            'direction_id'    => 'required'
            
        ]);

        $direction = Direction::byUuid($request->direction_id)->firstOrFail();

        $request->merge(array('direction_id' => $direction->id));


        $provider = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El proveedor se ha registrado exitosamente!', 
            'object'  => $provider 
        ]);
    }

    public function update(Request $request, $uuid) {

        if(!$request->web_site) {

            $request->merge(array('web_site' => 'no posee'));
        }

        if(!$request->slug) {

            $request->merge(array('slug' => 'no posee'));
        }

        $provider = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'name'            => 'required',
            'email'           => 'required',
            'web_site'        => 'required',
            'contact'         => 'required',
            'slug'            => 'required',
            'rif_type'        => 'required',
            'rif'             => 'required',
            'phone'           => 'required',
            'direction_id'    => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'name'            => 'required',
                'email'           => 'required',
                'web_site'        => 'required',
                'contact'         => 'required',
                'slug'            => 'required',
                'rif_type'        => 'required',
                'rif'             => 'required',
                'phone'           => 'required',
                'direction_id'    => 'required'
            ];
        }

        $direction = Direction::byUuid($request->direction_id)->firstOrFail();

        $request->merge(array('direction_id' => $direction->id));        

        $this->validate($request, $rules);
 
        $provider->update($request->all());

        return $this->response->item($provider->fresh(), new ProviderTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $provider = $this->model->byUuid($uuid)->firstOrFail();

        if($provider->providers->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'El tipo de prestamo posee prestamos asociados, no se puede eliminar.', 
            ]);
        }

        $provider->status= 0;

        $provider->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El tipo de prestamo se ha suspendido exitosamente!', 
        ]);
    }
}
