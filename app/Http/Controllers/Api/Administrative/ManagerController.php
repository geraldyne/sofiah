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
use App\Entities\Association;
use App\Entities\Administrative\Manager;
use App\Entities\Administrative\Partner;
use App\Entities\Administrative\Charge;
use App\Transformers\Administrative\ManagerTransformer;

use League\Fractal;

/**
 *  Controlador Empleados
 */

class ManagerController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Manager $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $managers = $this->model->with('partner', 'charge')->get();

        return $this->response->collection($managers, new ManagerTransformer());
    }

    public function create($uuid) {
        
        $associations = $this->api->get('administrative/associations?include=organisms');
        $partner      = $this->api->get('administrative/partners/'.$uuid);
        $charges      = $this->api->get('administrative/charges');

        return response()->json([

            'status'       => true,
            'associations' => $associations,
            'partner'      => $partner,
            'charges'      => $charges
        ]);
    }

    public function show($id) {
        
        $Manager = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($Manager, new ManagerTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'partner_id' => 'required|alpha_dash',
            'charge_id'  => 'required|alpha_dash'
        ]);
        
        # Obtiene el asociado mediante el UUID

            $partner = Partner::byUuid($request->partner_id)->firstOrFail();

            $request->merge(array('partner_id' => $partner->id));

        # Obtiene el cargo mediante el UUID

            $charge = Charge::byUuid($request->charge_id)->firstOrFail();

            $request->merge(array('charge_id' => $charge->id));

        # Verifica que ese cargo no esté activo
        
            $manager_active = Manager::where('status',   '=', true)->
                                       where('charge_id','=', $charge->id)->
                                       first();

            if(count($manager_active) > 0) 

                return response()->json([ 
                
                    'status'  => false, 
                    'message' => '¡El cargo ya está ocupado! Por favor verifique he intente nuevamente.'
                ]);

        # Verifica que el asociado no tenga un cargo activo
         
            $active_manager = $partner->managers;

            foreach ($active_manager as $manager) {
                
                if($manager->status) 

                    return response()->json([ 
                    
                        'status'  => false, 
                        'message' => '¡El asociado ya tiene un cargo activo! Por favor verifique he intente nuevamente.'
                    ]);
            }

        # Guarda el estatus activo del directivo
         
            $request->merge(array('status' => true));

        $manager = $this->model->create($request->all());

        return response()->json([ 
                                
            'status'  => true, 
            'message' => '¡El directivo se ha registrado exitosamente!', 
            'object'  => $manager 
        ]);
    }

    public function update(Request $request, $uuid) {

        $manager = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'status'    => 'required|boolean'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'status'    => 'required|boolean'
            ];
        }

        $manager->status = $request->status;

        $manager->save();

        return $this->response->item($manager->fresh(), new ManagerTransformer());
    }
}
