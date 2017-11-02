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
use App\Entities\Administrative\Accountlvl1;
use App\Transformers\Administrative\Accountlvl1Transformer;

use League\Fractal;

/**
 *  Controlador Cuentas Nivel 1
 */

class Accountlvl1Controller extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Accountlvl1 $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $account = $this->model->get();

        return $this->response->collection($account, new Accountlvl1Transformer());
    }

    public function show($id) {
        
        $accountlvl1 = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($accountlvl1, new Accountlvl1Transformer());  
    }
}
