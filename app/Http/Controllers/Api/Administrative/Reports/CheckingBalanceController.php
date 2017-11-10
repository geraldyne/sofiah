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

                    if( ! array_key_exists($account->account_code, $accountslvl6)) {

                        $accountslvl6[$account->account_code] = [

                            'name'      => $account->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    } else {

                        return response()->json([

                            'status'    => false,
                            'message'   => '¡La cuenta '.$account->account_code.' está repetida! Por favor verifique he intente nuevamente.'
                        ]);
                    }

                # Para las cuentas de NIVEL 5
                        
                    if( ! array_key_exists($account->accountlvl5->account_code, $accountslvl5)) {

                        $accountslvl5[$account->accountlvl5->account_code] = [

                            'name'   => $account->accountlvl5->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    } else {

                        $accountslvl5[$account->accountlvl5->account_code]['before'] += $before;
                        $accountslvl5[$account->accountlvl5->account_code]['debit'] += $detail_debit;
                        $accountslvl5[$account->accountlvl5->account_code]['asset'] += $detail_asset;
                        $accountslvl5[$account->accountlvl5->account_code]['actual'] = $accountslvl5[$account->accountlvl5->account_code]['before'] + 
                                                                                       $accountslvl5[$account->accountlvl5->account_code]['debit'] - 
                                                                                       $accountslvl5[$account->accountlvl5->account_code]['asset'];
                    }

                # Para las cuentas de NIVEL 4
                        
                    if( ! array_key_exists($account->accountlvl5->accountlvl4->account_code, $accountslvl4)) {

                        $accountslvl4[$account->accountlvl5->accountlvl4->account_code] = [

                            'name'   => $account->accountlvl5->accountlvl4->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    } else {

                        $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['before'] += $before;
                        $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['debit'] += $detail_debit;
                        $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['asset'] += $detail_asset;
                        $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['actual'] = $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['before'] + 
                                                                                                    $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['debit'] - 
                                                                                                    $accountslvl4[$account->accountlvl5->accountlvl4->account_code]['asset'];
                    }

                # Para las cuentas de NIVEL 3
                        
                    if( ! array_key_exists($account->accountlvl5->accountlvl4->accountlvl3->account_code, $accountslvl3)) {

                        $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code] = [

                            'name'   => $account->accountlvl5->accountlvl4->accountlvl3->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    } else {

                        $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['before'] += $before;
                        $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['debit'] += $detail_debit;
                        $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['asset'] += $detail_asset;
                        $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['actual'] = $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['before'] + 
                                                                                                                 $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['debit'] - 
                                                                                                                 $accountslvl3[$account->accountlvl5->accountlvl4->accountlvl3->account_code]['asset'];
                    }

                # Para las cuentas de NIVEL 2
                        
                    if( ! array_key_exists($account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code, $accountslvl2)) {

                        $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code] = [

                            'name'   => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    } else {

                        $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['before'] += $before;
                        $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['debit'] += $detail_debit;
                        $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['asset'] += $detail_asset;
                        $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['actual'] = $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['before'] + 
                                                                                                                              $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['debit'] - 
                                                                                                                              $accountslvl2[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code]['asset'];
                    }

                # Para las cuentas de NIVEL 1
                        
                    if( ! array_key_exists($account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code, $accountslvl1)) {

                        $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code] = [

                            'name'   => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_name,
                            'before' => $before,
                            'debit'  => $detail_debit,
                            'asset'  => $detail_asset,
                            'actual' => $actual
                        ];
                    
                    } else {

                        $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['before'] += $before;
                        $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['debit'] += $detail_debit;
                        $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['asset'] += $detail_asset;
                        $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['actual'] = $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['before'] + 
                                                                                                                                           $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['debit'] - 
                                                                                                                                           $accountslvl1[$account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code]['asset'];
                    }

                # Fin del calculo
            }

        # Switch de seleccion de niveles a mostrar

            switch ($request->level) {

                case 1: $merge = $accountslvl1; break;

                case 2: $merge = $accountslvl1 + $accountslvl2; break;

                case 3: $merge = $accountslvl1 + $accountslvl2 + $accountslvl3; break;

                case 4: $merge = $accountslvl1 + $accountslvl2 + $accountslvl3 + $accountslvl4; break;

                case 5: $merge = $accountslvl1 + $accountslvl2 + $accountslvl3 + $accountslvl4 + $accountslvl5; break;

                case 6: $merge = $accountslvl1 + $accountslvl2 + $accountslvl3 + $accountslvl4 + $accountslvl5 + $accountslvl6; break;
            }

        # Agrega las cuentas al arreglo que va a retornar

            array_push($accounts, $merge);

        return $accounts;
    }
}
