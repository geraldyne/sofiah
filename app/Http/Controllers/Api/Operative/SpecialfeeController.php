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
use App\Entities\Operative\Specialfee;
use App\Entities\Operative\Specialfeedetails;
use App\Transformers\Operative\SpecialfeeTransformer;

use League\Fractal;

/**
 *  Controlador cuota especial
 */

class SpecialfeeController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Specialfee $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'loantypes',
            'specialfeedetails'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new SpecialfeeTransformer());
    }

    public function show($id) {
        
        $specialfee = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($specialfee, new SpecialfeeTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'loantypes_id'         => 'required',
            'specialfeedetails_id'  => 'required'
        ]);

        $Loantypes = Loantypes::byUuid($request->loantypes_id)->firstOrFail();

        $request->merge(array('loantypes_id' => $Loantypes->id));

        
        $specialfeedetails = Specialfeedetails::byUuid($request->specialfeedetails_id)->firstOrFail();

        $request->merge(array('specialfeedetails_id' => $specialfeedetails->id));

        $specialfee = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'La cuota especial del tipo de prestamo se ha registrado exitosamente!', 
            'object'  => $specialfee 
        ]);
    }

    public function update(Request $request, $uuid) {

        $specialfee = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'loantypes_id'         => 'required',
            'specialfeedetails_id' => 'required'   
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'loantypes_id'         => 'required',
                'specialfeedetails_id' => 'required'
            ];
        }
    
        $Loantypes = Loantypes::byUuid($request->loantypes_id)->firstOrFail();

        $request->merge(array('loantypes_id' => $Loantypes->id));

        
        $specialfeedetails = Specialfeedetails::byUuid($request->specialfeedetails_id)->firstOrFail();

        $request->merge(array('specialfeedetails_id' => $specialfeedetails->id));


        $this->validate($request, $rules);

        $specialfee->update($request->all());

        return $this->response->item($specialfee->fresh(), new SpecialfeeTransformer());
    }

    
}
