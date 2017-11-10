<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Administrative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 11-10-2017.
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
use App\Entities\Association;
use App\Entities\Administrative\Manager;
use App\Transformers\Administrative\ManagerTransformer;

use League\Fractal;

/**
 *  Controlador Empleados
 */

class ManagerController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Manager $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $managers = $this->model->with('partner', 'charge')->get();

        return $this->response->collection($managers, new ManagerTransformer());
    }

    public function create($idcard) {
        
        $associations = $this->api->get('administrative/associations?include=organisms');
        $partner      = $this->api->get('administrative/partners/'.$idcard);
        $charges      = $this->api->get('administrative/charges');

        return response()->json([

            'status'       => true,
            'associations' => $associations,
            'partner'      => $partners,
            'charges'      => $charges
        ]);
    }

    public function show($id) {
        
        $Manager = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($Manager, new ManagerTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'Manager_code'    => 'required|numeric|unique:Manager',
            'names'            => 'required',
            'lastnames'        => 'required',
            'email'            => 'required',
            'department'       => 'required',
            'rif'              => 'required|unique:Manager',
            'id_card'          => 'required|unique:Manager',
            'phone'            => 'required|numeric',
            'nationality'      => 'required',
            'status'           => 'required|numeric',
            'birthdate'        => 'required',
            'date_of_admision' => 'required',
            'retirement_date'  => 'required',
            'user_id'          => 'required',
            'direction_id'     => 'required',
            'association_id'   => 'required',
            'bank_details_id'  => 'required'
        ]);

        $user = User::byUuid($request->user_id)->firstOrFail();

        $request->merge(array('user_id' => $user->id));

        
        $direction = Direction::byUuid($request->direction_id)->firstOrFail();

        $request->merge(array('direction_id' => $direction->id));

        
        $association = Association::byUuid($request->association_id)->firstOrFail();

        $request->merge(array('association_id' => $association->id));


        $bank_details = Bank_details::byUuid($request->bank_details_id)->firstOrFail();

        $request->merge(array('bank_details_id' => $bank_details->id));


        $Manager = $this->model->create($request->all());

        return response()->json([ 
                                'status'  => true, 
                                'message' => 'El Empleado se ha registrado exitosamente!', 
                                'object'  => $Manager 
                                ]);
    }

    public function update(Request $request, $uuid) {

        $Manager = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'Manager_code'    => 'required|numeric|unique:Manager',
            'names'            => 'required',
            'lastnames'        => 'required',
            'email'            => 'required',
            'department'       => 'required',
            'rif'              => 'required|unique:Manager',
            'id_card'          => 'required|unique:Manager',
            'phone'            => 'required|numeric',
            'nationality'      => 'required',
            'status'           => 'required|numeric',
            'birthdate'        => 'required',
            'date_of_admision' => 'required',
            'retirement_date'  => 'required',
            'user_id'          => 'required',
            'direction_id'     => 'required',
            'association_id'   => 'required',
            'bank_details_id'  => 'required',
            'updated_at'       =>  getdate()
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'Manager_code'    => 'required|numeric|unique:Manager',
                'names'            => 'required',
                'lastnames'        => 'required',
                'email'            => 'required',
                'department'       => 'required',
                'rif'              => 'required|unique:Manager',
                'id_card'          => 'required|unique:Manager',
                'phone'            => 'required|numeric',
                'nationality'      => 'required',
                'status'           => 'required|numeric',
                'birthdate'        => 'required',
                'date_of_admision' => 'required',
                'retirement_date'  => 'required',
                'user_id'          => 'required',
                'direction_id'     => 'required',
                'association_id'   => 'required',
                'bank_details_id'  => 'required',
                'updated_at'       =>  getdate()
            ];
        }

        $user = User::byUuid($request->user_id)->firstOrFail();

        $request->merge(array('user_id' => $user->id));

        
        $direction = Direction::byUuid($request->direction_id)->firstOrFail();

        $request->merge(array('direction_id' => $direction->id));

        
        $association = Association::byUuid($request->association_id)->firstOrFail();

        $request->merge(array('association_id' => $association->id));


        $bank_details = Bank_details::byUuid($request->bank_details_id)->firstOrFail();

        $request->merge(array('bank_details_id' => $bank_details->id));


        $Manager = $this->model->create($request->all());

        return $this->response->item($Manager->fresh(), new ManagerTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $Manager = $this->model->byUuid($uuid)->firstOrFail();
        
        $Manager->delete();

        return response()->json([ 
                                'status' => true, 
                                'message' => 'Empleado eliminado exitosamente!', 
                                ]);
    }
}
