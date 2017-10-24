<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Administrative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 23-10-2017.
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

use App\Entities\Association;

use App\Http\Controllers\Api\AssociationsController;

/**
 *  Controlador de vista principal del módulo administrativo
 */

class AdministrativeController extends Controller {

    use Helpers;

    public function index() {

        $association = Association::first();

        if($association) {

            $association = $this->api->get('administrative/associations/'.$association->uuid);

            return response()->json([

                'status'        => true,
                'association'   => $association,
                'organisms'     => $association->organisms,
                'employees'     => $association->employees

            ]);

        } else {

            return response()->json([

                'status' => false
            ]);
        }
    }
}
