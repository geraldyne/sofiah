<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Operative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 18-10-2017.
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
use App\Entities\Operative\Loantypes;
use App\Entities\Operative\Specialfeedetails;
use App\Transformers\Operative\SpecialfeedetailsTransformer;

use League\Fractal;

/**
 *  Controlador cuota especial
 */

class SpecialfeedetailsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Specialfeedetails $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'specialfees'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new SpecialfeedetailsTransformer());
    }

    public function show($id) {
        
        $specialfeedetails = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($specialfeedetails, new SpecialfeedetailsTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'month'         => 'required'
        ]);

        $specialfeedetails = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El detalle de la cuota especial se ha registrado exitosamente!', 
            'object'  => $specialfeedetails 
        ]);
    }

    public function update(Request $request, $uuid) {

        $specialfeedetails = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'month'         => 'required'  
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'month'         => 'required'
            ];
        }
    
        $this->validate($request, $rules);

        $specialfeedetails->update($request->all());

        return $this->response->item($specialfeedetails->fresh(), new specialfeedetailsTransformer());
    }

    
}
