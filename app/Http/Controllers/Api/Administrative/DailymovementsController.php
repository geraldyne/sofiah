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

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Entities\User;
use App\Entities\Association;
use App\Entities\Administrative\Accountlvl6;
use App\Entities\Administrative\Dailymovement;
use App\Entities\Administrative\Dailymovementdetails;
use App\Transformers\Administrative\DailymovementTransformer;

use Carbon\Carbon;
use League\Fractal;

/**
 *  Controlador Asociados
 */

class DailymovementsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Dailymovement $model) {

        $this->model = $model;
    }
	
    public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $dailymovement = $this->model->get();
        
        return $this->response->collection($dailymovement, new DailymovementTransformer());
    }

    public function create() {

        $movements = Dailymovement::get();

        $number = count($movements) + 1;

        $accounts = $this->api->get('administrative/accountlvl6');

        return response()->json([

            'status'    => true,
            'number' => $number,
            'accounts'  => $accounts
        ]);
    }

    public function show($id) {
        
        $dailymovements = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($dailymovements, new DailymovementTransformer());
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
                    'message'   => '¡La suma total del debe debe coincidir con la suma total del haber! Por favor verifique e intente nuevamente.'
                ]);
            }
        }

        $movement = $this->model->create($request->only([

            'date',
            'description',
            'status',
            'debit',
            'asset',
            'number',
            'origin',
            'type'

        ]));

        if($movement) {

            $movement = Dailymovement::all(); 

            foreach ($request->new_account as $detail) {
                
                $movementdetail = new Dailymovementdetails();

                $movementdetail->description = $detail['description'];

                $movementdetail->debit = $detail['debit'];

                $movementdetail->asset = $detail['asset'];

                $movementdetail->dailymovement_id = $movement->last()->id;

                $account = Accountlvl6::byUuid($detail['accountlvl6_id'])->firstOrFail();

                $movementdetail->accountlvl6_id = $account->id;

                $movementdetail->save();
            }

            $user = User::byUuid($request->user)->firstOrFail();

            # Guarda el usuario que originó el comprobante
             
            DB::table('daily_movements_origin')->insert(
                [
                    'dailymovement_id' => $movement->last()->id,
                    'user_id'          => $user->id
                ]
            );

            if($request->status == 'A') {

                DB::table('daily_movements_apply')->insert(
                    [
                        'dailymovement_id' => $movement->last()->id,
                        'user_id'          => $user->id
                    ]
                );
            } 

            return response()->json([

                'status'    => true,
                'message'   => '¡El comprobante contable se ha creado con éxito!',
                'object'    => $movement
            ]);
        }
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
}
