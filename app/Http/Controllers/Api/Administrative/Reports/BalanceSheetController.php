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

class BalanceSheetController extends Controller {

    use Helpers;

    public function generate(Request $request) {

        # Validar dato de entreda en el request (fechas y nivel)

            $this->validate($request, [

                'date'  => 'required|date',
                'type'  => 'required',
                'level' => 'required|numeric',
                'show'  => 'required'
            ]);

        # Determina las fechas dependiendo del tipo de balance

            $last = Carbon::createFromFormat('Y-m-d', $request->date);

            $init = Carbon::createFromFormat('Y-m-d', $request->date);

            $date = Carbon::createFromFormat('Y-m-d', $request->date);

            switch ($request->type) {
                
                case 'M': # Mensual

                    $last->subMonth(1);

                    $last->day = 1;

                    $init->day = 1;

                break;

                case 'T': # Trimestral

                    $last->day = 1;

                    $init->day = 1;

                    if($date->month >= 1 && $date->month <= 3) { # Primer trimestre del año toma el último trimestre del año anterior

                        $last->subYear(1);

                        $last->month = 10;

                        $init->month = 1;
                    
                    } else if($date->month >= 4 && $date->month <= 6) { # Segundo trimestre del año toma el primer trimestre del mismo

                        $last->month = 1;

                        $init->month = 4;

                    } else if($date->month >= 7 && $date->month <= 9) { # Tercer trimestre del año toma el segundo trimestre del mismo

                        $last->month = 4;

                        $init->month = 7;

                    } else if($date->month >= 10 && $date->month <= 12) { # Cuarto trimestre del año toma el tercer trimestre del mismo

                        $last->month = 7;

                        $init->month = 10;

                    } else {

                        return response()->json([

                            'status'    => false,
                            'message'   => '¡Por favor ingrese una fecha válida!'
                        ]);
                    }

                break;

                case 'A': # Anual

                    $last->subYear(1);

                    $last->month = 1;

                    $last->day = 1;

                    $init->month = 1;

                    $init->day = 1;

                break;
            }

        # Inicializa los arreglos para las cuentas
            
            $accounts = [];
            $accountslvl1 = [];
            $accountslvl2 = [];
            $accountslvl3 = [];
            $accountslvl4 = [];
            $accountslvl5 = [];
            $accountslvl6 = [];
        
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
            
            $accountslvl6_all = Accountlvl6::where('apply_balance','=',true)->get();

        # Ciclo que recorre cada cuenta

            foreach ($accountslvl6_all as $account) {

                # Agrega o no las cuentas con 0 dependiendo del request (como lo indique el usuario)
                 
                    if(!$request->show)

                        if(count($account->dailymovementdetails) == 0) 

                            continue;

                # Inicializa variables para cada iteración

                    $before = $actual = $last_debit = $last_asset = $actual_debit = $actual_asset = $variation = 0;

                # Guarda el id de la cuenta en una variable global

                    $GLOBALS['id'] = $account->id;

                # Obtiene los movimientos con estatus activo y de fecha anterior al rango del balance, incluye además las cuentas para el calculo del saldo anterior

                    $last_movements = Dailymovement::where('status','=','A')
                                                      ->where('date','>=',$last->format('Y-m-d'))
                                                      ->where('date','<', $init->format('Y-m-d'))
                                                      ->with(['details' => function($query) {

                        $query->where('accountlvl6_id','=',$GLOBALS['id']);

                    }])->get();

                    foreach ($last_movements as $movements) {
                        
                        $last_debit += $movements['details']->sum('debit');
                        $last_asset += $movements['details']->sum('asset');
                    }

                    $before = $last_debit - $last_asset;

                # Obtiene los movimientos con estatus activo y de fecha al rango del balance, incluye además las cuentas para el calculo del saldo actual
                        
                    $now = Dailymovement::where('status','=','A')
                                          ->where('date','>=', $init->format('Y-m-d'))
                                          ->where('date','<=', $date->format('Y-m-d'))
                                          ->with(['details' => function($query) {

                        $query->where('accountlvl6_id','=',$GLOBALS['id']);

                    }])->get();

                    foreach ($now as $mova) {
                        
                        $actual_debit += $mova['details']->sum('debit');
                        $actual_asset += $mova['details']->sum('asset');
                    }

                    $actual = $actual_debit - $actual_asset;

                # Proceso para calcular la variacion
                 
                    $variation = $actual - $before;

                # Para las cuentas de NIVEL 6
                
                    $data = [   
                                'code'      => $account->account_code,
                                'name'      => $account->account_name,
                                'before'    => $before,
                                'actual'    => $actual,
                                'variation' => $variation
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
                                'code'      => $account->accountlvl5->account_code,
                                'name'      => $account->accountlvl5->account_name,
                                'before'    => $before,
                                'actual'    => $actual,
                                'variation' => $variation
                            ];

                    if(array_search($data['code'], array_column($accountslvl5, 'code')) === false) {

                        array_push($accountslvl5, $data);

                    } else {

                        $key = array_search($data['code'], array_column($accountslvl5, 'code'));

                        $accountslvl5[$key]['before'] += $before;
                        $accountslvl5[$key]['actual'] += $actual;
                        $accountslvl5[$key]['variation'] = $accountslvl5[$key]['actual'] - 
                                                           $accountslvl5[$key]['before'];
                    }                        

                # Para las cuentas de NIVEL 4
                        
                    $data = [   
                                'code'      => $account->accountlvl5->accountlvl4->account_code,
                                'name'      => $account->accountlvl5->accountlvl4->account_name,
                                'before'    => $before,
                                'actual'    => $actual,
                                'variation' => $variation
                            ];

                    if(array_search($data['code'], array_column($accountslvl4, 'code')) === false) {

                        array_push($accountslvl4, $data);

                    } else {

                        $key = array_search($data['code'], array_column($accountslvl4, 'code'));

                        $accountslvl4[$key]['before'] += $before;
                        $accountslvl4[$key]['actual'] += $actual;
                        $accountslvl4[$key]['variation'] = $accountslvl4[$key]['actual'] - 
                                                           $accountslvl4[$key]['before'];
                    }

                # Para las cuentas de NIVEL 3
                        
                    $data = [   
                                'code'      => $account->accountlvl5->accountlvl4->accountlvl3->account_code,
                                'name'      => $account->accountlvl5->accountlvl4->accountlvl3->account_name,
                                'before'    => $before,
                                'actual'    => $actual,
                                'variation' => $variation
                            ];

                    if(array_search($data['code'], array_column($accountslvl3, 'code')) === false) {

                        array_push($accountslvl3, $data);

                    } else {

                        $key = array_search($data['code'], array_column($accountslvl3, 'code'));

                        $accountslvl3[$key]['before'] += $before;
                        $accountslvl3[$key]['actual'] += $actual;
                        $accountslvl3[$key]['variation'] = $accountslvl3[$key]['actual'] - 
                                                           $accountslvl3[$key]['before'];
                        
                    }

                # Para las cuentas de NIVEL 2
                    
                    $data = [   
                                'code'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_code,
                                'name'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->account_name,
                                'before'    => $before,
                                'actual'    => $actual,
                                'variation' => $variation
                            ];

                    if(array_search($data['code'], array_column($accountslvl2, 'code')) === false) {

                        array_push($accountslvl2, $data);

                    } else {

                        $key = array_search($data['code'], array_column($accountslvl2, 'code'));

                        $accountslvl2[$key]['before'] += $before;
                        $accountslvl2[$key]['actual'] += $actual;
                        $accountslvl2[$key]['variation'] = $accountslvl2[$key]['actual'] - 
                                                           $accountslvl2[$key]['before'];
                        
                    }

                # Para las cuentas de NIVEL 1
                    
                    $data = [   
                                'code'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_code,
                                'name'      => $account->accountlvl5->accountlvl4->accountlvl3->accountlvl2->accountlvl1->account_name,
                                'before'    => $before,
                                'actual'    => $actual,
                                'variation' => $variation
                            ];

                    if(array_search($data['code'], array_column($accountslvl1, 'code')) === false) {

                        array_push($accountslvl1, $data);

                    } else {

                        $key = array_search($data['code'], array_column($accountslvl1, 'code'));

                        $accountslvl1[$key]['before'] += $before;
                        $accountslvl1[$key]['actual'] += $actual;
                        $accountslvl1[$key]['variation'] = $accountslvl1[$key]['actual'] - 
                                                           $accountslvl1[$key]['before'];
                        
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
