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
use App\Entities\Administrative\Bank;
use App\Entities\Administrative\City;
use App\Entities\Administrative\Employee;
use App\Entities\Administrative\Direction;
use App\Entities\Administrative\Preference;
use App\Entities\Administrative\Bankdetails;
use App\Transformers\Administrative\EmployeeTransformer;

use Carbon\Carbon;
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

        if (isset($_GET['include'])) $fractal->parseIncludes($_GET['include']);

        return $this->response->collection($this->model->get(), new EmployeeTransformer());
    }

    public function create() {
        
        $associations = $this->api->get('administrative/associations');

        $banks        = $this->api->get('administrative/banks');

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

                'employee_code'    => 'required|numeric|unique:employees|unique:partners',
                'names'            => 'required',
                'lastnames'        => 'required',
                'email'            => 'required|unique:employees|unique:partners|unique:organisms',
                'department'       => 'required',
                'rif'              => 'required|unique:employees|unique:organisms|unique:associations',
                'id_card'          => 'required|unique:employees|unique:partners',
                'phone'            => 'required|numeric',
                'nationality'      => 'required',
                'status'           => 'required',
                'birthdate'        => 'required|date',
                'date_of_admission'=> 'required|date',
                'association_id'   => 'required|alpha_dash',

                'direction'         => 'required',
                'city_id'           => 'required|alpha_dash',

                'bankuuid'          => 'required|alpha_dash',
                'account_number'    => 'required|unique:bank_details',
                'account_type'      => 'required',
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

                $request->merge(array('name'     => $username));

                $request->merge(array('email'    => $request->email));

                $request->merge(array('password' => $username)); # ALMACENA LA CLAVE COMO EL NOMBRE DE USUARIO, DEBE ACTUALIZAR EN SU PRIMER INICIO DE SESIÓN

                $request->merge(array('status'   => true));

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

        # Crea los detalles de bancos

            $bank = Bank::byUuid($request->bankuuid)->firstOrFail();
            
            if(! Bankdetails::where('account_number','=',$request->account_number)->exists()) {

                $request->merge(array('bank_id' => $bank->id));

                $details = Bankdetails::create($request->only(['bank_id','account_number','account_type']));
                
                $request->merge(array('bankdetails_id' => $details->id));
            
            } else {

                return response()->json([

                    'status'    => false,
                    'message'   => 'La cuenta bancaria ingresada ya existe'
                ]);
            }
            
        # Crea el empleado
         
            $request->merge(array('status' => 'A'));
            
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

            'names'            => 'required',
            'lastnames'        => 'required',
            'email'            => 'required',
            'department'       => 'required',
            'phone'            => 'required|numeric',
            'status'           => 'required',
            'birthdate'        => 'required',
            'date_of_admission'=> 'required',

            'direction' => 'required',
            'city_id' => 'required|alpha_dash'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'names'            => 'sometimes|required',
                'lastnames'        => 'sometimes|required',
                'email'            => 'sometimes|required',
                'department'       => 'sometimes|required',
                'phone'            => 'sometimes|required|numeric',
                'status'           => 'sometimes|required',
                'birthdate'        => 'sometimes|required',
                'date_of_admission'=> 'sometimes|required',
                
                'direction' => 'required',
                'city_id' => 'required|alpha_dash'
            ];
        }

        $user = $employee->user;

        switch($request->status) {

            case 'A':

                $request->merge(array('retirement_date' => null));

                $request->merge(array('status' => true));

                $user->update($request->only('status'));

                $request->merge(array('status' => 'A'));

                break;

            case 'R':

                $request->merge(array('retirement_last_date' => Carbon::now()));

                $request->merge(array('status' => false));

                $user->update($request->only('status'));

                $request->merge(array('status' => 'R'));

                break;

            case 'F':

                $request->merge(array('retirement_last_date' => Carbon::now()));

                $request->merge(array('status' => false));

                $user->update($request->only('status'));

                $request->merge(array('status' => 'R'));

                break;
        }

        $this->validate($request, $rules);

        # Actualiza la dirección

            $city = City::byUuid($request->city_id)->firstOrFail();

            if($city) {

                $request->merge(array('city_id' => $city->id));

                $direction = $employee->direction;

                $direction->update($request->only(['direction', 'city_id']));

                $request->merge(array('direction_id' => $direction->id));
            }

        # Guarda los valores actualizados

        $employee->update($request->all());

        return $this->response->item($employee->fresh(), new EmployeeTransformer());
    }

    public function updateDataBank(Request $request) {

        $employee = $this->model->byUuid($request->id)->firstOrFail();

        $bank = Bank::byUuid($request->bankuuid)->firstOrFail();

        if($bank) {

            $bankd = Bankdetails::where('account_number','=',$request->account_number)->get();

            if($bankd->count() >= 2)

                return response()->json([

                    'status'    => false,
                    'message'   => '¡El número de cuenta ingresado ya existe! Por favor verifique he intente nuevamente.'
                ]);
        
            $request->merge(array('bank_id' => $bank->id));

            $bankdetails = $employee->bankdetails;

            $bankdetails->update($request->only(['account_number', 'account_type', 'bank_id' => $bank->id]));
        }

        return response()->json([

            'status'    => true,
            'message'   => '¡Datos Bancarios Actualizados Exitosamente!'
        ]);
    }
}
