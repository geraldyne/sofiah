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
use App\Entities\Operative\Assetstypecodes;
use App\Entities\Administrative\Organism;
use App\Transformers\Operative\AssetstypecodesTransformer;

use League\Fractal;

/**
 *  Controlador grupos de préstamos
 */

class AssetstypecodesController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Assetstypecodes $model) {
        
        $this->model = $model;
    }

    public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'organism'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new AssetstypecodesTransformer());
    }

    public function show($id) {
        
        $assetstypecodes = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($assetstypecodes, new AssetstypecodesTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'assets_organisms_code' => 'required|unique:assets_type_codes',
            'type'                  => 'required',
            'organisms_id'          => 'required'
        ]);
        
        $organism = Organism::byUuid($request->organisms_id)->firstOrFail();

        $request->merge(array('organisms_id' => $organism->id));


        $assetstypecodes = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El codigo de tipo de haberes se ha registrado exitosamente!', 
            'object'  => $assetstypecodes 
        ]);
    }

    public function update(Request $request, $uuid) {

        $assetstypecodes = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'assets_organisms_code' => 'required|unique:assets_type_codes',
            'type'                  => 'required',
            'organisms_id'          => 'required' 
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'assets_organisms_code' => 'required|unique:assets_type_codes',
                'type'                  => 'required',
                'organisms_id'          => 'required'
            ];
        }

    
        $organism = Organism::byUuid($request->organisms_id)->firstOrFail();

        $request->merge(array('organisms_id' => $organism->id));


        $this->validate($request, $rules);

        $assetstypecodes->update($request->all());

        return $this->response->item($assetstypecodes->fresh(), new AssetstypecodesTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $assetstypecodes = $this->model->byUuid($uuid)->firstOrFail();
        
        $assetstypecodes->delete();

        return response()->json([ 
                                'status' => true, 
                                'message' => 'El codigo de tipo de haberes se ha eliminado exitosamente!', 
                                ]);
    }
}
