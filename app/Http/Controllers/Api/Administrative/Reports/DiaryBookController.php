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

/**
 *  Controlador para generar un balance de comprobacion
 */

class DiaryBookController extends Controller {

    use Helpers;

    public function generate(Request $request) {

        # Validar dato de entreda en el request (fechas y nivel)

            $this->validate($request, [

                'start_date'  => 'required|date',
                'finish_date' => 'required|date' 
            ]);

        # Busca todos los movimientos diarios que estén aplicados y dentro del rango de fechas

            $dailymovements = Dailymovement::where('status','=','A')->
                                             where('date','>=',$request->start_date)->
                                             where('date','<=',$request->finish_date)->
                                             orderBy('number','asc')->
                                             get();

        # Valida si existe al menos un movimiento en el rango de fechas

            if(count($dailymovements) == 0) 

                return response()->json([

                    'status'    => false,
                    'message'   => '¡No se encontraron coincidencias! Por favor verifique e intente nuevamente.'
                ]);

        # Si existe continua el proceso inicializando los arreglos para las cuentas
        
            $accounts = [];
            $diary = [];

        # Ciclos aninados para obtener cada movimiento por separado
         
            foreach ($dailymovements as $movement) {

                # Obtiene los detalles del movimiento ordenados por codigo
                 
                    $details = Dailymovementdetails::where('dailymovement_id', '=', $movement->id)->
                                                     orderBy('accountlvl6_id', 'asc')->
                                                     get();

                    //dd($details);
                
                # Ciclo para obtener cada detalle por separado
                
                    foreach($details as $detail) {

                        # Obtiene la cuenta afectada en el detalle
                        
                            $account = Accountlvl6::find($detail['accountlvl6_id']);

                        # Guarda las cuentas de NIVEL 6 en el arreglo

                            $accounts[] = [

                                'date'   => $movement['date'],
                                'number' => $movement['number'],
                                'code'   => $account->account_code,
                                'name'   => $account->account_name,
                                'detail' => $movement['description'],
                                'debit'  => $detail['debit'],
                                'asset'  => $detail['asset']
                            ];
                    }

                # Fin del calculo
            }

        array_push($diary, $accounts);
        
        return $diary;
    }
}
