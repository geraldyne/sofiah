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
use App\Entities\Association;
use App\Entities\Administrative\Dailymovement;
use App\Entities\Administrative\Dailymovementdetails;
use App\Entities\Administrative\Accountlvl6;
use App\Entities\Administrative\Direction;
use App\Entities\Administrative\Employee;
use App\Entities\Administrative\Bank;
use App\Transformers\Administrative\DailymovementTransformer;

use Carbon\Carbon;
use League\Fractal;

/**
 *  Controlador Asociados
 */

class DailymovementsController extends Controller {

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
            'user_origin',
            'user_apply',
            'details',
            'accounting_year'
        )->paginate($request->get('limit', config('app.pagination_limit')));
        
        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new PartnerTransformer());
    }

    public function show($id) {
        
        $dailymovements = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($dailymovements, new DailymovementsTransformer());
    }
    
    public function store(Request $request) {

        $this->validate($request, [

            'date'          => 'required|date',
            'description'   => 'required',
            'status'        => 'required',
            'debit'         => 'required|numeric',
            'asset'         => 'required|numeric',
            'number'        => 'required|numeric|unique:daily_movements',
            'origin'        => 'required',
            'type'          => 'required'
        ]);

        if($request->status == 'A') {

            $debit = $request->debit;
            $asset = $request->asset;

            if($debit != $asset) {

                return response()->json([

                    'status'    => false,
                    'message'   => 'Los montos debe y haber deben ser iguales. Por favor verifique e intente nuevamente.'
                ]);
            }
        }

        $organism = Organism::byUuid($request->organism_id)->firstOrFail();

        $request->merge(array('organism_id' => $organism->id));

        # crea el usuario
        
        $username = substr(strtolower($request->names), 0, 3).explode(' ', strtolower($request->lastnames.''))[0].substr($request->id_card, -3);

        if( ! User::where('name','=',$username)->exists()) {

            $request->merge(array('name' => $username));
            $request->merge(array('password' => $username));

            $request->merge(array('status' => true));

            $user = User::create($request->all());

            $user->assignRole('associate');

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

            foreach($managers as $manager) {

                $manager->status = false;

                $manager->save();
            }

            $request->merge(array('retirement_date' => null));

            $user = $partner->user;

            $request->merge(array('status' => true));

            $user->update($request->only('status'));

            $request->merge(array('status' => 'A'));

        } else if($request->status === 'R') {

            $managers = $partner->managers;

            foreach($managers as $manager) {

                $manager->status = false;

                $manager->save();
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

            foreach($managers as $manager) {

                $manager->status = false;

                $manager->save();
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

        $request->merge(array('bank_id' => $bank->id));

        $bankdetails = $partner->bankdetails;

        $bankdetails->update($request->only(['account_number', 'account_type', 'bank_id']));

        $request->merge(array('bankdetails_id' => $bankdetails->id));

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

        $user->delete();
        
        $bankdetails->delete();

        $partner->delete();

        return response()->json([

            'status'    => true,
            'message'   => 'El asociado ha sido eliminado con éxito.'
        ]);
    }
}
