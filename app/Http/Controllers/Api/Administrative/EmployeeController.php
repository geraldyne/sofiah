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
use App\Entities\User;
use App\Entities\Association;
use App\Entities\Administrative\Employee;
use App\Entities\Administrative\Direction;
use App\Entities\Administrative\Bankdetails;
use App\Transformers\Administrative\EmployeeTransformer;

use League\Fractal;

/**
 *  Controlador Empleados
 */

class EmployeeController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Employee $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with('association', 'direction', 'user', 'bankdetails')->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new EmployeeTransformer());
    }

    public function show($id) {
        
        $employee = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($employee, new EmployeeTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'employee_code'    => 'required|numeric|unique:employee',
            'names'            => 'required',
            'lastnames'        => 'required',
            'email'            => 'required',
            'department'       => 'required',
            'rif'              => 'required|unique:employee',
            'id_card'          => 'required|unique:employee',
            'phone'            => 'required|numeric',
            'nationality'      => 'required',
            'status'           => 'required|numeric',
            'birthdate'        => 'required',
            'date_of_admision' => 'required',
            'retirement_date'  => 'required',
            'user_id'          => 'required',
            'direction_id'     => 'required',
            'association_id'   => 'required',
            'bankdetails_id'   => 'required'
        ]);

        $user = User::byUuid($request->user_id)->firstOrFail();

        $request->merge(array('user_id' => $user->id));

        
        $direction = Direction::byUuid($request->direction_id)->firstOrFail();

        $request->merge(array('direction_id' => $direction->id));

        
        $association = Association::byUuid($request->association_id)->firstOrFail();

        $request->merge(array('association_id' => $association->id));


        $bankdetails = Bankdetails::byUuid($request->bankdetails_id)->firstOrFail();

        $request->merge(array('bankdetails_id' => $bankdetails->id));


        $employee = $this->model->create($request->all());

        return response()->json([ 
                                'status'  => true, 
                                'message' => 'El Empleado se ha registrado exitosamente!', 
                                'object'  => $employee 
                                ]);
    }

    public function update(Request $request, $uuid) {

        $employee = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'employee_code'    => 'required|numeric|unique:employee',
            'names'            => 'required',
            'lastnames'        => 'required',
            'email'            => 'required',
            'department'       => 'required',
            'rif'              => 'required|unique:employee',
            'id_card'          => 'required|unique:employee',
            'phone'            => 'required|numeric',
            'nationality'      => 'required',
            'status'           => 'required|numeric',
            'birthdate'        => 'required',
            'date_of_admision' => 'required',
            'retirement_date'  => 'required',
            'user_id'          => 'required',
            'direction_id'     => 'required',
            'association_id'   => 'required',
            'bankdetails_id'  => 'required',
            'updated_at'       =>  getdate()
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'employee_code'    => 'required|numeric|unique:employee',
                'names'            => 'required',
                'lastnames'        => 'required',
                'email'            => 'required',
                'department'       => 'required',
                'rif'              => 'required|unique:employee',
                'id_card'          => 'required|unique:employee',
                'phone'            => 'required|numeric',
                'nationality'      => 'required',
                'status'           => 'required|numeric',
                'birthdate'        => 'required',
                'date_of_admision' => 'required',
                'retirement_date'  => 'required',
                'user_id'          => 'required',
                'direction_id'     => 'required',
                'association_id'   => 'required',
                'bankdetails_id'  => 'required',
                'updated_at'       =>  getdate()
            ];
        }

        $user = User::byUuid($request->user_id)->firstOrFail();

        $request->merge(array('user_id' => $user->id));

        
        $direction = Direction::byUuid($request->direction_id)->firstOrFail();

        $request->merge(array('direction_id' => $direction->id));

        
        $association = Association::byUuid($request->association_id)->firstOrFail();

        $request->merge(array('association_id' => $association->id));


        $bankdetails = Bankdetails::byUuid($request->bankdetails_id)->firstOrFail();

        $request->merge(array('bankdetails_id' => $bankdetails->id));


        $employee = $this->model->create($request->all());

        return $this->response->item($employee->fresh(), new EmployeeTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $employee = $this->model->byUuid($uuid)->firstOrFail();
        
        $employee->delete();

        return response()->json([ 
                                'status' => true, 
                                'message' => 'Empleado eliminado exitosamente!', 
                                ]);
    }
}
