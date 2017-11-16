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

class AnaliticController extends Controller {

    use Helpers;

    public function generate(Request $request) {

        # Validar dato de entreda en el request (fechas y nivel)

            $this->validate($request, [

                'start_date'  => 'required|date',
                'finish_date' => 'required|date',
                'init_code'   => 'required|numeric',
                'finish_code' => 'required|numeric'
            ]);

        # Inicializa los arreglos para las cuentas
        
            $accounts = [];

        # Query para obtener el plan de cuentas con las cuentas que aplica balance
            
            $accountslvl6_all = Accountlvl6::where('account_code', '>=', $request->init_code)->
                                             where('account_code', '<=', $request->finish_code)->
                                             get();

        # Ciclo que recorre cada cuenta

            foreach ($accountslvl6_all as $account) {

                # Agrega o no las cuentas con 0 dependiendo del request (como lo indique el usuario)
                 
                    if( ! $request->show)

                        if(count($account->dailymovementdetails) == 0) 

                            continue;

                # Inicializa variables para cada iteración

                    $actual = $finish = $last_debit = $last_asset = $actual_debit = $actual_asset = 0;

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

                    $actual = $last_debit - $last_asset;

                # Agrega al arreglo el saldo anterior a la fecha de la cuenta nivel 6
                
                    $accounts[] = [
                                    'date'   => '-',
                                    'number' => '-',
                                    'code'   => $account->account_code,
                                    'name'   => $account->account_name,
                                    'descrp' => 'Saldo anterior', 
                                    'debit'  => 0,
                                    'asset'  => 0,
                                    'actual' => $actual
                                ];

                # Obtiene los movimientos que pertenecen a la cuenta y recorre cada movimiento
                        
                    $details = $account->dailymovementdetails;

                    $detail_debit = 0;
                    $detail_asset = 0;

                    foreach ($details as $detail) {

                        if($detail->dailymovement->date < $request->start_date) continue;

                        # Obtiene el saldo actual
                        
                            $actual = $actual + $detail['debit'] - $detail['asset'];

                        # Obtiene los valores totales del debe y el haber de cada movimiento

                            $accounts[] = [
                                            'date'   => $detail->dailymovement->date,
                                            'number' => $detail->dailymovement->number,
                                            'code'   => $account->account_code,
                                            'name'   => $account->account_name,
                                            'descrp' => $detail->dailymovement->description, 
                                            'debit'  => $detail['debit'],
                                            'asset'  => $detail['asset'],
                                            'actual' => $actual
                                        ];
                    }

                # Agrega al arreglo el saldo final de la cuenta nivel 6
                
                    $accounts[] = [
                                    'date'   => '-',
                                    'number' => '-',
                                    'code'   => $account->account_code,
                                    'name'   => $account->account_name,
                                    'descrp' => 'Saldo final', 
                                    'debit'  => 0,
                                    'asset'  => 0,
                                    'actual' => $actual
                                ];

                # Fin del calculo
            }

        return $accounts;
    }
}
