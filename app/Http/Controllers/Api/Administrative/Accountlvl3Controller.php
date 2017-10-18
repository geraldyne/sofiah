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
use App\Entities\Administrative\Accountlvl3;
use App\Transformers\Administrative\Accountlvl3Transformer;

use League\Fractal;

/**
 *  Controlador Cuentas Nivel 3
 */

class Accountlvl3Controller extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Accountlvl3 $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'accountlvl2',
            'accountslvl4'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new Accountlvl3Transformer());
    }

    public function show($id) {
        
        $accountlvl3 = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($accountlvl3, new Accountlvl3Transformer());  
    }
}
