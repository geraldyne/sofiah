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
use App\Entities\Administrative\City;
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

        $employee = $this->model->with(

            'association', 
            'direction', 
            'user', 
            'bankdetails'

        )->get();

        return $this->response->collection($employee, new EmployeeTransformer());
    }

    public function create() {
        
        $associations   = $this->api->get('administrative/associations');

        $banks          = $this->api->get('administrative/banks');

        return response()->json([

            'status'        => true,
            'associations'  => $associations,
            'banks'         => $banks

        ]);
    }

    public function show($id) {
        
        $employee = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($employee, new EmployeeTransformer());  
    }

    public function store(Request $request) {
        
        # Evalúa cada dato recibido
         
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
                'status'           => 'required',
                'birthdate'        => 'required',
                'date_of_admision' => 'required',
                'retirement_date'  => 'required',
                'association_id'   => 'required|alpha_dash',

                'direction'         => 'required',
                'city_id'           => 'required|alpha_dash',

                'bankuuid'          => 'alpha_dash',
                'account_number'    => 'unique:bank_details',
                'account_type'      => '',
            ]);

        # Obtiene la asocición mediante el UUID

            $association = Association::byUuid($request->association_id)->firstOrFail();

            $request->merge(array('association_id' => $association->id));

        # Obtiene la ciudad mediante el UUID
        
            $city = City::byUuid($request->city_id)->firstOrFail();

            $request->merge(array('city_id' => $city->id));

        # Crea la dirección
        
            $direction = Direction::create($request->only('city_id','direction'));

            if($direction) $request->merge(array('direction_id' => $direction->id));

            else return response()->json([

                'status'    => false,
                'message'   => '¡No se ha podido almacenar la dirección de la asociación! Por favor verifique los datos he intente nuevamente.'
            ]);

        # Crea el nombre de usuario y luego el usuario si no existe
        
            $username = substr(strtolower($request->names), 0, 3).explode(' ', strtolower($request->lastnames.''))[0].substr($request->id_card, -3);

            if( ! User::where('name','=',$username)->exists()) {

                $request->merge(array('name'        => $username));

                $request->merge(array('email'       => $request->email));

                $request->merge(array('password'    => $username)); # ALMACENA LA CLAVE COMO EL NOMBRE DE USUARIO, DEBE ACTUALIZAR EN SU PRIMER INICIO DE SESIÓN

                $request->merge(array('status'      => true));

                $user = User::create($request->only(['name', 'email', 'password', 'status']));

                if( ! $user) return response()->json([

                    'status'    => false,
                    'message'   => '¡Ha ocurrido un error al registrar el usuario! Por favor verifique los datos he intente nuevamente.'
                ]);

                $user->assignRole('employee');

                if( $user->roles->count() == 0) { $user->forceDelete(); }

                $request->merge(array('user_id' => $user->id));

                $preference = Preference::create($request->only(['user_id']));

                if( ! $preference) return response()->json([

                    'status'    => false,
                    'message'   => '¡Ha ocurrido un error al registrar las preferencias de usuario! Por favor verifique los datos he intente nuevamente.'
                ]);
            
            } else {

                $user = User::where('name','=',$username)->firstOrFail();

                $request->merge(array('user_id' => $user->id));
            }

            $request->merge(array('account_code' => bcrypt($request->names.$request->lastnames)));

        # Crea los detalles de bancos

            $bank = Bank::byUuid($request->bankuuid)->firstOrFail();
            
            if(! Bankdetails::where('account_number','=',$request->account_number)->exists()) {

                $request->merge(array('bank_id' => $bank->id));

                $details = Bankdetails::create($request->only(['bank_id','account_number','account_type']));
                
                $request->merge(array('bankdetails_id' => $details->id));
            
            } else return response()->json([

                    'status'    => false,
                    'message'   => 'La cuenta bancaria ingresada ya existe'
                ]);
            
        # Crea el empleado
            
            $employee = $this->model->create($request->except([

                'direction', 
                'city_id',
                'bankuuid',
                'account_number',
                'account_type'

            ]));

            if( ! $employee) {

                $user->forceDelete();

                $direction->delete();

                $details->delete();

                return response()->json([

                    'status'    => false,
                    'message'   => '¡Ha ocurrido un error al registrar el empleado! Por favor verifique los datos he intente nuevamente.'
                ]);

            } else return response()->json([ 
                
                    'status'  => true, 
                    'message' => '¡El empleado se ha registrado exitosamente!', 
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
