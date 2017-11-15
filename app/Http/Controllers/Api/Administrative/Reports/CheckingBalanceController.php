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

namespace App\Http\Controllers\Api\Administrative\Reports;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;

use App\Entities\Administrative\Accountlvl1;
use App\Entities\Administrative\Accountlvl2;
use App\Entities\Administrative\Accountlvl3;
use App\Entities\Administrative\Accountlvl4;
use App\Entities\Administrative\Accountlvl5;
use App\Entities\Administrative\Accountlvl6;
use App\Entities\Administrative\Dailymovement;
use App\Entities\Administrative\Dailymovementdetails;

use Carbon\Carbon;

/**
 *  Controlador para generar un balance de comprobacion
 */

class CheckingBalanceController extends Controller {

    use Helpers;

    public function generate(Request $request) {

        # Validar dato de entreda en el request (fechas y nivel)

            $this->validate($request, [

                'start_date'  => 'required|date',
                'finish_date' => 'required|date',
                'level'       => 'required|numeric',
                'show'        => 'required'
            ]);

        # Inicializa los arreglos para las cuentas
        
            $accounts = $accountslvl1 = $accountslvl2 = $accountslvl3 = $accountslvl4 = $accountslvl5 = $accountslvl6 = [];
        
            $arraybeforelvl5 = [];
            $arraybeforelvl4 = [];
            $arraybeforelvl3 = [];
            $arraybeforelvl2 = [];
            $arraybeforelvl1 = [];

            $arrayactuallvl5 = [];
            $arrayactuallvl4 = [];
            $arrayactuallvl3 = [];
            $arrayactuallvl2 = [];
            $arrayactuallvl1 = [];

            $arrayvariationlvl5 = [];
            $arrayvariationlvl4 = [];
            $arrayvariationlvl3 = [];
            $arrayvariationlvl2 = [];
            $arrayvariationlvl1 = [];

        # Query para obtener el plan de cuentas con las cuentas que aplica balance
            
            $accountslvl6_all = Accountlvl6::get();

        # Ciclo que recorre cada cuenta

            foreach ($accountslvl6_all as $account) {

                # Agrega o no las cuentas con 0 dependiendo del request (como lo indique el usuario)
                 
                    if( ! $request->show)

                        if(count($account->dailymovementdetails) == 0) 

                            continue;

                # Inicializa variables para cada iteración

                    $before = $actual = $last_debit = $last_asset = $actual_debit = $actual_asset = $variation = 0;

                # Guarda el id de la cuenta en una variable global

                    $GLOBALS['id'] = $account->id;

                # Obtiene los movimientos con estatus activo y de fecha anterior al rango del balance, incluye además las cuentas para el calculo del saldo anterior

                    $last_movements = Dailymovement::where('status','=','A')
                                                   ->where('date','<', $request->start_date)
                                                   ->with(['details' => function($query) {

                        $query->where('accountlvl6_id','=',$GLOBALS['id']);

                    }])->get();

                    foreach ($last_movements as $movements) {
                        
                        $last_debit += $movements['details']->sum('debit');
                        $last_asset += $movements['details']->sum('asset');
                    }

                    $before = $last_debit - $last_asset;

                # Obtiene los movimientos que pertenecen a la cuenta y recorre cada movimiento
                        
                    $details = $account->dailymovementdetails;

                    $detail_debit = 0;
                    $detail_asset = 0;

                    foreach ($details as $detail) {

                        if($detail->dailymovement->date < $request->start_date) continue;

                        # Obtiene los valores totales del debe y el haber de cada movimiento

                            $detail_debit += $detail['debit'];

                            $detail_asset += $detail['asset'];
                    }

                # Proceso para calcular el saldo actual

                    $actual = $before + $detail_debit - $detail_asset;

                # Para las cuentas de NIVEL 6

                    $data = [

                            'code'   => $account->account_code,
                            'name'   => $account->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    if(array_search($data['code'], array_column($accountslvl6, 'code')) === false) {

                        array_push($accountslvl6, $data);

                    } else {

                        return response()->json([

                            'status'    => false,
                            'message'   => '¡La cuenta '.$account->account_code.' está repetida! Por favor verifique he intente nuevamente.'
                        ]);
                    }

                # Para las cuentas de NIVEL 5
                        
                    $data = [

                            'code'   => $account->accountlvl5->account_code,
                            'name'   => $account->accountlvl5->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    if(array_search($data['code'], array_column($accountslvl5, 'code')) === false) {

                        array_push($accountslvl5, $data);

                    } else {

                        $key = array_search($data['code'], array_column($accountslvl5, 'code'));

                        $accountslvl5[$key]['before'] += $before;
                        $accountslvl5[$key]['debit']  += $detail_debit;
                        $accountslvl5[$key]['asset']  += $detail_asset;
                        $accountslvl5[$key]['actual']  = $accountslvl5[$key]['before'] + 
                                                         $accountslvl5[$key]['debit'] - 
                                                         $accountslvl5[$key]['asset'];
                    }

                # Para las cuentas de NIVEL 4
                        
                    $data = [

                            'code'   => $account->accountlvl5->accountlvl4->account_code,
                            'name'   => $account->accountlvl5->accountlvl4->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    if(array_search($data['code'], array_column($accountslvl4, 'code')) === false) {

                        array_push($accountslvl4, $data);

                    } else {

                        $key = array_search($data['code'], array_column($accountslvl4, 'code'));

                        $accountslvl4[$key]['before'] += $before;
                        $accountslvl4[$key]['debit']  += $detail_debit;
                        $accountslvl4[$key]['asset']  += $detail_asset;
                        $accountslvl4[$key]['actual']  = $accountslvl4[$key]['before'] + 
                                                         $accountslvl4[$key]['debit'] - 
                                                         $accountslvl4[$key]['asset'];
                    }

                # Para las cuentas de NIVEL 3
                        
                    $data = [

                            'code'   => $account->accountlvl5->accountlvl4->accountlvl3->account_code,
                            'name'   => $account->accountlvl5->accountlvl4->accountlvl3->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    if(array_search($data['code'], array_column($accountslvl3, 'code')) === false) {

                        array_push($accountslvl3, $data);

                    } else {

                        $key = array_search($data['code'], array_column($accountslvl3, 'code'));

                        $accountslvl3[$key]['before'] += $before;
                        $accountslvl3[$key]['debit']  += $detail_debit;
                        $accountslvl3[$key]['asset']  += $detail_asset;
                        $accountslvl3[$key]['actual']  = $accountslvl3[$key]['before'] + 
                                                         $accountslvl3[$key]['debit'] - 
                                                         $accountslvl3[$key]['asset'];
                    }

                # Para las cuentas de NIVEL 2
                        
                    $data = [

                            'code'   => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code,
                            'name'   => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    if(array_search($data['code'], array_column($accountslvl2, 'code')) === false) {

                        array_push($accountslvl2, $data);

                    } else {

                        $key = array_search($data['code'], array_column($accountslvl2, 'code'));

                        $accountslvl2[$key]['before'] += $before;
                        $accountslvl2[$key]['debit']  += $detail_debit;
                        $accountslvl2[$key]['asset']  += $detail_asset;
                        $accountslvl2[$key]['actual']  = $accountslvl2[$key]['before'] + 
                                                         $accountslvl2[$key]['debit'] - 
                                                         $accountslvl2[$key]['asset'];
                    }

                # Para las cuentas de NIVEL 1
                        
                    $data = [

                            'code'   => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code,
                            'name'   => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    if(array_search($data['code'], array_column($accountslvl1, 'code')) === false) {

                        array_push($accountslvl1, $data);

                    } else {

                        $key = array_search($data['code'], array_column($accountslvl1, 'code'));

                        $accountslvl1[$key]['before'] += $before;
                        $accountslvl1[$key]['debit']  += $detail_debit;
                        $accountslvl1[$key]['asset']  += $detail_asset;
                        $accountslvl1[$key]['actual']  = $accountslvl1[$key]['before'] + 
                                                         $accountslvl1[$key]['debit'] - 
                                                         $accountslvl1[$key]['asset'];
                    }

                # Fin del calculo
            }

        # Switch de seleccion de niveles a mostrar

            switch ($request->level) {

                case 1: $accounts = $accountslvl1; break;

                case 2: $accounts = array_merge($accountslvl1, $accountslvl2); break;

                case 3: $accounts = array_merge($accountslvl1, $accountslvl2, $accountslvl3); break;

                case 4: $accounts = array_merge($accountslvl1, $accountslvl2, $accountslvl3, $accountslvl4); break;

                case 5: $accounts = array_merge($accountslvl1, $accountslvl2, $accountslvl3, $accountslvl4, $accountslvl5); break;

                case 6: $accounts = array_merge($accountslvl1, $accountslvl2, $accountslvl3, $accountslvl4, $accountslvl5, $accountslvl6); break;
            }

        # Ordena el arreglo por código
        
            sort($accounts);

        return $accounts;
    }
}
