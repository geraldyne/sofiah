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
use App\Entities\Administrative\Charge;
use App\Transformers\Administrative\ChargeTransformer;

use League\Fractal;

/**
 *  Controlador Bancos
 */

class ChargesController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Charge $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }
        
        $charges = $this->model->get();
        
        return $this->response->collection($charges, new ChargeTransformer());
    }
/*
    public function show($id) {
        
        $charge = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($charge, new ChargeTransformer());  
    }

    public function store(Request $request) {

        if(Charge::where('charge', '=', $request->charge)->exists()) return $this->response->error('El cargo ya existe', 409);

        $this->validate($request, [

            'charge' => 'required'
        ]);

        $charge = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => '¡El cargo se ha registrado exitosamente!', 
            'object'  => $charge 
        ]);
    }

    public function update(Request $request, $uuid) {

        if(Charge::where('charge', '=', $request->charge)->exists()) return $this->response->error('El cargo ya existe', 409);

        $charge = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'charge' => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'charge' => 'sometimes|required'
            ];
        }
        
        $this->validate($request, $rules);
 
        $charge->update($request->all());

        return $this->response->item($charge->fresh(), new ChargeTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $charge = $this->model->byUuid($uuid)->firstOrFail();
        
        if($charge->managers->count() > 0)

            return response()->json([

                'status'    => false,
                'message'   => 'El cargo posee un directivo, no se puede eliminar'
            ]);

        $charge->delete();

        return $this->response->noContent();
    }
    */
}
