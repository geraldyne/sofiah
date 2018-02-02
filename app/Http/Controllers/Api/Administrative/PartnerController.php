<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 14-07-2017.
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
use App\Entities\Administrative\Partner;
use App\Entities\Administrative\Organism;
use App\Entities\Administrative\Preference;
use App\Entities\Administrative\Bankdetails;

use App\Entities\Operative\Assetsbalance;

use App\Transformers\Administrative\PartnerTransformer;

use Carbon\Carbon;
use League\Fractal;

/**
 *  Controlador Asociados
 */

class PartnerController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Partner $model) {

        $this->model = $model;
    }
	
    public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $partner = $this->model->with(
            
            'organism',
            'user',
            'bankdetails',
            'managers',
            'dividends',
            'guarantors',
            'loans',
            'assetsmovements',
            'assetsbalance'

        )->get();

        return $this->response->collection($partner, new PartnerTransformer());
    }

    public function create() {
        
        $associations   = $this->api->get('administrative/associations?include=organisms');

        $banks          = $this->api->get('administrative/banks');

        return response()->json([

            'status'        => true,
            'associations'  => $associations,
            'banks'         => $banks

        ]);
    }

    public function show($id) {
        
        $partner = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($partner, new PartnerTransformer());
    }
    
    public function store(Request $request) {

        # Evalúa cada dato recibido
        
            $this->validate($request, [

                'employee_code'     => 'required|unique:partners',
                'names'             => 'required',
                'lastnames'         => 'required',
                'email'             => 'required|email|max:120|unique:partners',
                'title'             => 'required',
                'local_phone'       => 'required|numeric',
                'nationality'       => 'required',
                'status'            => 'required',
                'id_card'           => 'required|unique:partners',
                'phone'             => 'required|numeric',
                'organism_id'       => 'required|alpha_dash',
                'bankuuid'          => 'alpha_dash',
                'account_number'    => 'unique:bank_details',
                'account_type'      => '',
            ]);

        # Obtiene el organismo mediante el UUID

            $organism = Organism::byUuid($request->organism_id)->firstOrFail();

            $request->merge(array('organism_id' => $organism->id));

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

                $user->assignRole('partner');

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
            
            } else {

                return response()->json([

                    'status'    => false,
                    'message'   => 'La cuenta bancaria ingresada ya existe'
                ]);
            }

        $partner = $this->model->create($request->except(['bankuuid', 'account_number','account_type']));

        if($partner) {

            $request->merge(array('balance_individual_contribution' => 0));

            $request->merge(array('balance_employers_contribution' => 0));
            
            $request->merge(array('balance_voluntary_contribution' => 0));
            
            $request->merge(array('partner_id' => $partner->id));

            $balance = Assetsbalance::create($request->only(['balance_individual_contribution','balance_employers_contribution','balance_voluntary_contribution', 'partner_id']));

            return response()->json([

                'status'    => true,
                'message'   => '¡El asociado se ha creado con éxito!',
                'object'    => $partner
            ]);
        
        } else {

            $user->forceDelete();

            $direction->delete();

            $details->delete();

            return response()->json([

                'status'    => false,
                'message'   => '¡Ha ocurrido un error al registrar el asociado! Por favor verifique los datos he intente nuevamente.'
            ]);
        }
    }

    public function update(Request $request, $uuid) {

        $partner = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'title' => 'required',
            'local_phone' => 'required|numeric',
            'status' => 'required',
            'phone' => 'required|numeric'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'title' => 'sometimes|required',
                'local_phone' => 'sometimes|required|numeric',
                'status' => 'sometimes|required',
                'phone' => 'sometimes|required|numeric',
            ];
        }

        $user = $partner->user;

        switch($request->status) {

            case 'A':

                $managers = $partner->managers;

                if($managers) {

                    foreach($managers as $manager) {

                        $manager->status = false;

                        $manager->save();
                    }
                }

                $request->merge(array('retirement_date' => null));

                $request->merge(array('status' => true));

                $user->update($request->only('status'));

                $request->merge(array('status' => 'A'));

                break;

            case 'R':

                $managers = $partner->managers;

                if($managers) {

                    foreach($managers as $manager) {

                        $manager->status = false;

                        $manager->save();
                    }
                }

                if($partner->retirement_date) {

                    $request->merge(array('retirement_last_date' => Carbon::now()));
                
                } else {

                    $request->merge(array('retirement_date' => Carbon::now()));
                    $request->merge(array('retirement_last_date' => Carbon::now()));                
                }

                $request->merge(array('status' => false));

                $user->update($request->only('status'));

                $request->merge(array('status' => 'R'));

                break;

            case 'F':

                $managers = $partner->managers;

                if($managers) {

                    foreach($managers as $manager) {

                        $manager->status = false;

                        $manager->save();
                    }
                }

                if($partner->retirement_date) {

                    $request->merge(array('retirement_last_date' => Carbon::now()));
                
                } else {

                    $request->merge(array('retirement_date' => Carbon::now()));
                    $request->merge(array('retirement_last_date' => Carbon::now()));                
                }

                $request->merge(array('status' => false));

                $user->update($request->only('status'));

                $request->merge(array('status' => 'F'));

                break;
        }

        $this->validate($request, $rules);

        $partner->update($request->all());

        return $this->response->item($partner->fresh(), new PartnerTransformer());
    }

    public function updateDataBanks(Request $request) {

        $partner = $this->model->byUuid($request->id)->firstOrFail();

        $bank = Bank::byUuid($request->bankuuid)->firstOrFail();

        if($bank) {

            $bankd = Bankdetails::where('account_number','=',$request->account_number)->get();

            if($bankd->count() >= 2)

                return response()->json([

                    'status'    => false,
                    'message'   => '¡El número de cuenta ingresado ya existe! Por favor verifique he intente nuevamente.'
                ]);
        
            $request->merge(array('bank_id' => $bank->id));

            $bankdetails = $partner->bankdetails;

            $bankdetails->update($request->only(['account_number', 'account_type', 'bank_id']));

        }

        return response()->json([

            'status'    => true,
            'message'   => '¡Datos Bancarios Actualizados Exitosamente!'
        ]);
    }
}

