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

namespace App\Http\Controllers\Api\Administrative;


use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Entities\Administrative\Accountlvl4;
use App\Transformers\Administrative\Accountlvl4Transformer;

use League\Fractal;

/**
 *  Controlador Cuentas Nivel 4
 */

class Accountlvl4Controller extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Accountlvl4 $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $account = $this->model->get();

        return $this->response->collection($account, new Accountlvl4Transformer());
    }

    public function show($id) {
        
        $accountlvl4 = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($accountlvl4, new Accountlvl4Transformer());  
    }
}
