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
use App\Entities\Association;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Entities\User;
use App\Entities\Administrative\Bank;
use App\Entities\Administrative\Partner;
use App\Entities\Administrative\Organism;
use App\Entities\Administrative\Preference;
use App\Entities\Administrative\Bankdetails;
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

        $paginator = $this->model->with(
            'organism',
            'user',
            'bankdetails',
            'managers',
            'dividends'
        )->get();

        return $this->response->collection($paginator, new PartnerTransformer());
    }

    public function create() {
            'dividends',
            'guarantors',
            'loans',
            'assetsmovements',
            'assetsbalance'
        )->paginate($request->get('limit', config('app.pagination_limit')));
        
        if ($request->has('limit')) {
        
        $organisms = $this->api->get('administrative/organisms');
        $banks     = $this->api->get('administrative/banks');

        return response()->json([

            'status'    => true,
            'organisms' => $organisms,
            'banks'     => $banks

        ]);
    }

    public function show($id) {
        
        $partner = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($partner, new PartnerTransformer());
    }
    
    public function store(Request $request) {


        $this->validate($request, [

            'employee_code' => 'required|unique:partners',
            'names' => 'required',
            'lastnames' => 'required',
            'email' => 'required|email|max:120|unique:partners',
            'title' => 'required',
            'local_phone' => 'required|numeric',
            'nationality' => 'required',
            'status' => 'required',
            'id_card' => 'required|unique:partners|unique:partners',
            'phone' => 'required|numeric',
            'organism_id' => 'required|alpha_dash',
            'bankuuid' => '',
            'account_number' => 'unique:bank_details',
            'account_type' => '',
        ]);

        $organism = Organism::byUuid($request->organism_id)->firstOrFail();

        $request->merge(array('organism_id' => $organism->id));

        # crea el usuario
        
        $username = substr(strtolower($request->names), 0, 3).explode(' ', strtolower($request->lastnames.''))[0].substr($request->id_card, -3);

        if( ! User::where('name','=',$username)->exists()) {

            $request->merge(array('name' => $username));
            $request->merge(array('password' => $username));

            $request->merge(array('status' => true));

            $user = User::create($request->all());

            $user->assignRole('partner');

            $request->merge(array('style'     => 'bg-blue'));
            $request->merge(array('ĺang'      => 'es'));
            $request->merge(array('zoom'      => '80'));
            $request->merge(array('user_id'   => $user->id));

            $preference = Preference::create($request->all());
        
        } else {

            $user = User::where('name','=',$username)->first();

            $request->merge(array('user_id' => $user->id));
        }

        $request->merge(array('account_code' => bcrypt($request->names.$request->lastnames)));

        # crea los detalles de bancos

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

        return response()->json([

            'status'    => true,
            'message'   => '¡El asociado se ha creado con éxito!',
            'object'    => $partner
        ]);
    }

    public function update(Request $request, $uuid) {

        $partner = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'title' => 'required',
            'local_phone' => 'required|numeric',
            'nationality' => 'required',
            'status' => 'required',
            'phone' => 'required|numeric',
            'account_number' => 'unique:bank_details',
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'title' => 'sometimes|required',
                'local_phone' => 'sometimes|required|numeric',
                'nationality' => 'sometimes|required',
                'status' => 'sometimes|required',
                'phone' => 'sometimes|required|numeric',
                'account_number' => 'sometimes|unique:bank_details',
            ];
        }

        if($request->status === 'A') {

            $managers = $partner->managers;

            if($managers) {

                foreach($managers as $manager) {

                    $manager->status = false;

                    $manager->save();
                }
            }

            $request->merge(array('retirement_date' => null));

            $user = $partner->user;

            $request->merge(array('status' => true));

            $user->update($request->only('status'));

            $request->merge(array('status' => 'A'));

        } else if($request->status === 'R') {

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

            $user = $partner->user;

            $request->merge(array('status' => false));

            $user->update($request->only('status'));

            $request->merge(array('status' => 'R'));

        } else if($request->status === 'F') {

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

            $user = $partner->user;

            $request->merge(array('status' => false));

            $user->update($request->only('status'));

            $request->merge(array('status' => 'F'));
        }

        $this->validate($request, $rules);

        $bank = Bank::byUuid($request->bankuuid)->first();

        if($bank) {

            $request->merge(array('bank_id' => $bank->id));

            $bankdetails = $partner->bankdetails;

            $bankdetails->update($request->only(['account_number', 'account_type', 'bank_id']));

            $request->merge(array('bankdetails_id' => $bankdetails->id));
        }

        $partner->update($request->all());

        return $this->response->item($partner->fresh(), new PartnerTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $partner = $this->model->byUuid($uuid)->firstOrFail();

        if($partner->managers->count() > 0) 

            return response()->json([

                'status'    => false,
                'message'   => 'El asociado posee un cargo de directivo, no se puede eliminar.'
            ]);

        $user = $partner->user;

        $bankdetails = $partner->bankdetails;

        $user->forceDelete();
        
        $bankdetails->delete();

        $partner->delete();

        return response()->json([

            'status'    => true,
            'message'   => 'El asociado ha sido eliminado con éxito.'
        ]);
    }
}
