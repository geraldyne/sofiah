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
use App\Entities\Operative\Loansgroups;
use App\Entities\Operative\Loantypes;
use App\Entities\Operative\Loantypegroups;
use App\Entities\Administrative\Accountingintegration;
use App\Transformers\Operative\LoantypegroupsTransformer;

use League\Fractal;

/**
 *  Controlador grupos de tipos de préstamos
 */

class LoantypegroupsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Loantypegroups $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'loansgroups'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new LoantypegroupsTransformer());
    }

    public function show($id) {
        
        $loantypegroups = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($loantypegroups, new LoantypegroupsTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'name'           => 'required|unique:loan_type_groups',
            'max_amount'     => 'required|numeric',
            'loanTypeList'   => 'required'
        ]);

        // Registramos el Grupo de tipo de prestamo

        $loantypegroups = $this->model->create($request->except([ 'loanTypeList']));

        // Registramos los tipos de prestamos asociados al Grupo de prestamo

        foreach ($request->loanTypeList as $loantype)
        {
            $loantype = Loantypes::byUuid($loantype['loantypes_id'])->firstOrFail();

            $loansgroups = new Loansgroups ();

            $loansgroups->name              = $request->name;
            $loansgroups->loantypes_id      = $loantype->id;
            $loansgroups->loantypegroups_id = $loantypegroups->id;

            $loansgroups->save();
        }

        

        return response()->json([ 
            'status'  => true, 
            'message' => 'El Grupo tipo de prestamo se ha registrado exitosamente!', 
            'object'  => $loantypegroups 
        ]);
    }

    public function update(Request $request, $uuid) {

        $loantypegroups = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'name'           => 'required|unique:loan_type_groups',
            'max_amount'     => 'required|numeric'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'name'           => 'required|unique:loan_type_groups',
                'max_amount'     => 'required|numeric'
            ];
        }

        $this->validate($request, $rules);
 
        $loantypegroups->update($request->all());

        return $this->response->item($loantypegroups->fresh(), new LoantypegroupsTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $loantypegroups = $this->model->byUuid($uuid)->firstOrFail();

        if($loantypegroups->loansgroups->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'El grupo tipo de prestamo posee tipos de prestamos, no se puede eliminar.', 
            ]);
        }

        $loantypegroups->status= 0;

        $loantypegroups->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El grupo tipo de prestamo se ha suspendido exitosamente!', 
        ]);
    }
}
