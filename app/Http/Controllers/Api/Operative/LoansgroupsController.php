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
use App\Entities\Operative\Loansgroups;
use App\Entities\Operative\Loantypegroups;
use App\Transformers\Operative\LoansgroupsTransformer;

use League\Fractal;

/**
 *  Controlador grupos de préstamos
 */

class LoansgroupsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Loansgroups $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'loantypes',
            'loantypegroups'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new LoansgroupsTransformer());
    }

    public function show($id) {
        
        $loansgroups = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($loansgroups, new LoansgroupsTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'name'              => 'required|unique:loans_groups',
            'loantypes_id'      => 'required',
            'loantypegroups_id' => 'required'
        ]);

        $Loantypes = Loantypes::byUuid($request->loantypes_id)->firstOrFail();

        $request->merge(array('loantypes_id' => $Loantypes->id));

        
        $Loantypegroups = Loantypegroups::byUuid($request->loantypegroups_id)->firstOrFail();

        $request->merge(array('loantypegroups_id' => $Loantypegroups->id));

        $loansgroups = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El tipo de prestamo se ha registrado en el grupo de prestamo exitosamente!', 
            'object'  => $loansgroups 
        ]);
    }

    public function update(Request $request, $uuid) {

        $loansgroups = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'name'              => 'required|unique:loans_groups',
            'loantypes_id'      => 'required',
            'loantypegroups_id' => 'required'   
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'name'              => 'required|unique:loans_groups',
                'loantypes_id'      => 'required',
                'loantypegroups_id' => 'required'
            ];
        }
    
        $Loantypes = Loantypes::byUuid($request->loantypes_id)->firstOrFail();

        $request->merge(array('loantypes_id' => $Loantypes->id));

        
        $Loantypegroups = Loantypegroups::byUuid($request->loantypegroups_id)->firstOrFail();

        $request->merge(array('loantypegroups_id' => $Loantypegroups->id));


        $this->validate($request, $rules);

        $loansgroups->update($request->all());

        return $this->response->item($loansgroups->fresh(), new LoansgroupsTransformer());
    }

    
}
