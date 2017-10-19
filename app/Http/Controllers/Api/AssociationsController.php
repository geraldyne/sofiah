<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 14-07-2017.
 *  @version        1.0
 * 
 *  @final  
 */

/**
 * Incluye la implementación de los siguientes Controladores y Modelos
 */

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Entities\Association;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Entities\Administrative\City;
use App\Entities\Administrative\Direction;
use App\Entities\Administrative\Accountlvl6;
use App\Transformers\AssociationTransformer;

use League\Fractal;

/**
 *  Controlador Asociación
 */

class AssociationsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Association $model) {

        $this->model = $model;
    }
	
    public function index(Request $request) {
        
        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with('organisms', 'direction', 'employees')->paginate($request->get('limit', config('app.pagination_limit')));
        
        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new AssociationTransformer());
    }

    public function show($id) {
        
        $association = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($association, new AssociationTransformer());
    }
    
    public function store(Request $request) {

        # Si el logo no existe carga la imágen 'noposee.jpg' por defecto

        if(!$request->logo) {

            $request->merge(array('logo' => 'noposee.jpg'));
        }

        # Si la asociación no tiene sitio web carga automáticamente el sitio del sistema Sofiah
    
        if(!$request->web_site) {

            $request->merge(array('web_site' => 'https://sofiah.com.ve'));
        }

        # Evalúa cada dato recibido

        $this->validate($request, [

            'name' => 'required|unique:associations',
            'alias' => 'required|unique:associations',
            'sudeca' => 'required|alpha_dash|max:6|unique:associations',
            'email' => 'required|email|unique:users|max:120|unique:associations',
            'web_site' => 'sometimes|required|url',
            'phone' => 'required|numeric',
            'rif' => 'required|alpha_dash|max:12|unique:associations',
            'lock_date' => 'required|date',
            'time_to_reincorporate' => 'required|numeric',
            'loan_time' => 'required|numeric',
            'percent_legal_reserve' => 'required|numeric',
            'logo' => 'sometimes|required',

            'employers_contribution_account_id' => 'required|alpha_dash',
            'deferred_employer_contribution_account_id' => 'required|alpha_dash',
            'individual_contribution_account_id' => 'required|alpha_dash',
            'deferred_individual_contribution_account_id' => 'required|alpha_dash',
            'voluntary_contribution_account_id' => 'required|alpha_dash',
            'deferred_voluntary_contribution_account_id' => 'required|alpha_dash',
            'legal_reserve_account_id' => 'required|alpha_dash',

            'direction' => 'required',
            'city_id' => 'required|alpha_dash'
        ]);

        # Obtiene la ciudad mediante el UUID
         
        $city = City::byUuid($request->city_id)->firstOrFail();

        $request->merge(array('city_id' => $city->id));

        $direction = Direction::create($request->only('city_id','direction'));
        
        $request->merge(array('direction_id' => $direction->id));
        
        // FALTA
        // EL
        // LOGO

        $employers_contribution = Accountlvl6::byUuid($request->employers_contribution_account_id)->firstOrFail();

        $request->merge(array('employers_contribution_account_id' => $employers_contribution->id));

        $deferred_employer_contribution_account_id = Accountlvl6::byUuid($request->deferred_employer_contribution_account_id)->firstOrFail();

        $request->merge(array('deferred_employer_contribution_account_id' => $deferred_employer_contribution_account_id->id));

        $individual_contribution = Accountlvl6::byUuid($request->individual_contribution_account_id)->firstOrFail();

        $request->merge(array('individual_contribution_account_id' => $individual_contribution->id));

        $deferred_individual_contribution_account_id = Accountlvl6::byUuid($request->deferred_individual_contribution_account_id)->firstOrFail();

        $request->merge(array('deferred_individual_contribution_account_id' => $deferred_individual_contribution_account_id->id));

        $voluntary_contribution = Accountlvl6::byUuid($request->voluntary_contribution_account_id)->firstOrFail();

        $request->merge(array('voluntary_contribution_account_id' => $voluntary_contribution->id));

        $deferred_voluntary_contribution_account_id = Accountlvl6::byUuid($request->deferred_voluntary_contribution_account_id)->firstOrFail();

        $request->merge(array('deferred_voluntary_contribution_account_id' => $deferred_voluntary_contribution_account_id->id));

        $legal_reserve_account_id = Accountlvl6::byUuid($request->legal_reserve_account_id)->firstOrFail();

        $request->merge(array('legal_reserve_account_id' => $legal_reserve_account_id->id));

        $association = $this->model->create($request->except(['direction', 'city_id']));

        return response()->json([

            'status'    => true,
            'message'   => '¡La asociación se ha creado con éxito!',
            'object'    => $association
        ]);
    }

    public function update(Request $request, $uuid) {

        $association = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'name' => 'required',
            'alias' => 'required',
            'sudeca' => 'required|alpha_dash|max:6',
            'email' => 'required|email|unique:users|max:120',
            'web_site' => 'sometimes|required|url',
            'phone' => 'required|numeric',
            'rif' => 'required|alpha_dash|max:12',
            'lock_date' => 'required|date',
            'time_to_reincorporate' => 'required|numeric',
            'loan_time' => 'required|numeric',
            'percent_legal_reserve' => 'required|numeric',
            'logo' => 'sometimes|required',

            'employers_contribution_account_id' => 'required|alpha_dash',
            'deferred_employer_contribution_account_id' => 'required|alpha_dash',
            'individual_contribution_account_id' => 'required|alpha_dash',
            'deferred_individual_contribution_account_id' => 'required|alpha_dash',
            'voluntary_contribution_account_id' => 'required|alpha_dash',
            'deferred_voluntary_contribution_account_id' => 'required|alpha_dash',
            'legal_reserve_account_id' => 'required|alpha_dash',

            'direction' => 'required',
            'city_id' => 'required|alpha_dash',
            'direction_id' => 'required|alpha_dash'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'name' => 'sometimes|required',
                'alias' => 'sometimes|required',
                'sudeca' => 'sometimes|required|alpha_dash|max:6',
                'email' => 'sometimes|required|email|unique:users|max:120',
                'web_site' => 'sometimes|required|url',
                'phone' => 'sometimes|required|numeric',
                'rif' => 'sometimes|required|alpha_dash|max:12',
                'lock_date' => 'sometimes|required|date',
                'time_to_reincorporate' => 'sometimes|required|numeric',
                'loan_time' => 'sometimes|required|numeric',
                'percent_legal_reserve' => 'sometimes|required|numeric',
                'logo' => 'sometimes|required',

                'employers_contribution_account_id' => 'sometimes|required|alpha_dash',
                'deferred_employer_contribution_account_id' => 'sometimes|required|alpha_dash',
                'individual_contribution_account_id' => 'sometimes|required|alpha_dash',
                'deferred_individual_contribution_account_id' => 'sometimes|required|alpha_dash',
                'voluntary_contribution_account_id' => 'sometimes|required|alpha_dash',
                'deferred_voluntary_contribution_account_id' => 'sometimes|required|alpha_dash',
                'legal_reserve_account_id' => 'sometimes|required|alpha_dash',

                'direction' => 'sometimes|required',
                'city_id' => 'sometimes|required|alpha_dash',
                'direction_id' => 'sometimes|required|alpha_dash'
            ];
        }

        $city = City::byUuid($request->city_id)->firstOrFail();

        $request->merge(array('city_id' => $city->id));

        $direction = Direction::byUuid($request->direction_id)->firstOrFail();

        $direction->update($request->only('city_id','direction'));

        $request->merge(array('direction_id' => $direction->id));

        #
        
        $employers_contribution = Accountlvl6::byUuid($request->employers_contribution_account_id)->firstOrFail();

        $request->merge(array('employers_contribution_account_id' => $employers_contribution->id));

        $deferred_employer_contribution_account_id = Accountlvl6::byUuid($request->deferred_employer_contribution_account_id)->firstOrFail();

        $request->merge(array('deferred_employer_contribution_account_id' => $deferred_employer_contribution_account_id->id));

        $individual_contribution = Accountlvl6::byUuid($request->individual_contribution_account_id)->firstOrFail();

        $request->merge(array('individual_contribution_account_id' => $individual_contribution->id));

        $deferred_individual_contribution_account_id = Accountlvl6::byUuid($request->deferred_individual_contribution_account_id)->firstOrFail();

        $request->merge(array('deferred_individual_contribution_account_id' => $deferred_individual_contribution_account_id->id));

        $voluntary_contribution = Accountlvl6::byUuid($request->voluntary_contribution_account_id)->firstOrFail();

        $request->merge(array('voluntary_contribution_account_id' => $voluntary_contribution->id));

        $deferred_voluntary_contribution_account_id = Accountlvl6::byUuid($request->deferred_voluntary_contribution_account_id)->firstOrFail();

        $request->merge(array('deferred_voluntary_contribution_account_id' => $deferred_voluntary_contribution_account_id->id));

        $legal_reserve_account_id = Accountlvl6::byUuid($request->legal_reserve_account_id)->firstOrFail();

        $request->merge(array('legal_reserve_account_id' => $legal_reserve_account_id->id));
        
        #

        $this->validate($request, $rules);

        $association->update($request->all());

        return $this->response->item($association->fresh(), new AssociationTransformer());
    }

    public function destroy(Request $request, $uuid) {
        
        $association = $this->model->byUuid($uuid)->firstOrFail();

        if($association->organisms->count() > 0) 

            return response()->json([

                'status'    => false,
                'message'   => 'La asociación posee un organismo registrado, no se puede eliminar.'
            ]);

        $direction = $association->direction;

        $association->delete();
        
        $direction->delete();

        return $this->response->json([

                'status'    => true,
                'message'   => '¡La asociación se ha eliminado con éxito!'
            ]);
    }
}
