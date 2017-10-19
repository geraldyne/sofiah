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
use App\Entities\Operative\Loantypecodes;
use App\Entities\Administrative\Organism;
use App\Transformers\Operative\LoantypecodesTransformer;

use League\Fractal;

/**
 *  Controlador grupos de tipos de préstamos
 */

class LoantypecodesController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Loantypecodes $model) {
        
        $this->model = $model;
    }

    public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'loantypes',
            'loan',
            'organism'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new LoantypecodesTransformer());
    }

    public function show($id) {
        
        $loantypecodes = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($loantypecodes, new LoantypecodesTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'loan_code'        => 'required|unique:loan_type_codes',
            'loantypes_id'     => 'required',
            'organism_id'      => 'required'
        ]);

        $loantypes = Loantypes::byUuid($request->loantypes_id)->firstOrFail();

        $request->merge(array('loantypes_id' => $loantypes->id));

        
        $organism = Organism::byUuid($request->organism_id)->firstOrFail();

        $request->merge(array('organism_id' => $organism->id));


        $loantypecodes = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El Codigo de tipo de prestamo se ha registrado exitosamente!', 
            'object'  => $loantypecodes 
        ]);
    }

    public function update(Request $request, $uuid) {

        $loantypecodes = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'loan_code'        => 'required|unique:loan_type_codes',
            'loantypes_id'     => 'required',
            'organism_id'      => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'loan_code'        => 'required|unique:loan_type_codes',
                'loantypes_id'     => 'required',
                'organism_id'      => 'required'
            ];
        }
 
        $loantypes = Loantypes::byUuid($request->loantypes_id)->firstOrFail();

        $request->merge(array('loantypes_id' => $loantypes->id));

        
        $organism = Organism::byUuid($request->organism_id)->firstOrFail();

        $request->merge(array('organism_id' => $organism->id));


        $this->validate($request, $rules);

        $loantypecodes->update($request->all());

        return $this->response->item($loantypecodes->fresh(), new LoantypecodesTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $loantypecodes = $this->model->byUuid($uuid)->firstOrFail();

        if($loantypecodes->loantypes->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'El grupo tipo de prestamo posee tipos de prestamos, no se puede eliminar.', 
            ]);
        }
        
        $loantypecodes->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El grupo tipo de prestamo se ha suspendido exitosamente!', 
        ]);
    }
}
