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

namespace App\Http\Controllers\Api\Administrative;


use Illuminate\Http\Request;
use App\Entities\Association;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Entities\Administrative\City;
use App\Entities\Administrative\Organism;
use App\Entities\Administrative\Direction;
use App\Transformers\Administrative\OrganismTransformer;

use League\Fractal;

/**
 *  Controlador Organismo
 */

class OrganismsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Organism $model) {

        $this->model = $model;
    }
	
    public function index(Request $request) {
        
        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) $fractal->parseIncludes($_GET['include']);

        return $this->response->collection($this->model->get(), new OrganismTransformer());
    }

    public function create() {
        
        $countries = $this->api->get('administrative/countries?include=states.cities');

        $association = $this->api->get('administrative/associations');

        return response()->json([

            'status'    => true,
            'countries' => $countries,
            'association'  => $association
        ]);
    }

    public function show($id) {

        $organism = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($organism, new OrganismTransformer());
    }
    
    public function store(Request $request) {

        # Si el organismo no tiene sitio web carga automáticamente el sitio del sistema Sofiah

            if( ! $request->web_site) $request->merge(array('web_site' => 'https://sofiah.com.ve'));

        # Evalúa cada dato recibido

            $this->validate($request, [

                'name' => 'required|unique:organisms',
                'alias' => 'required',
                'email' => 'required|email|unique:users|max:120',
                'web_site' => 'url',
                'zone' => 'required',
                'contact' => 'required',
                'phone' => 'required|numeric',
                'rif' => 'required|alpha_dash|max:12|unique:organisms',
                'payroll_type' => 'required',
                'status' => 'required|boolean',
                'disponibility' => 'required',
                'percentage_employers_contribution' => 'required|numeric',
                'percentage_individual_contribution' => 'required|numeric',
                'percentage_voluntary_contribution' => 'required|numeric',

                'direction' => 'required',
                'city_id' => 'required|alpha_dash',

                'association_id' => 'required|alpha_dash'
            ]);

        # Obtiene la ciudad mediante el UUID

            $city = City::byUuid($request->city_id)->firstOrFail();

            $request->merge(array('city_id' => $city->id));

        # Crea la dirección

            $direction = Direction::create($request->only('city_id','direction'));
        
            if($direction) $request->merge(array('direction_id' => $direction->id));

            else return response()->json([

                'status'    => false,
                'message'   => '¡No se ha podido almacenar la dirección del organismo! Por favor verifique los datos he intente nuevamente.'
            ]);

        # Obtiene la asociación mediante el UUID
        
            $association = Association::byUuid($request->association_id)->firstOrFail();

            $request->merge(array('association_id' => $association->id));

        # Crea el organismo

            $organism = $this->model->create($request->except(['direction', 'city_id']));

            if($organism) return response()->json([

                'status'    => true,
                'message'   => '¡El organismo se ha creado con éxito!',
                'object'    => $organism
            ]);

            else {

                $direction->delete();

                return response()->json([

                    'status'    => false,
                    'message'   => '¡Ha ocurrido un error al crear el organismo! Por favor verifique los datos he intente nuevamente.'
                ]);
            }
    }

    public function update(Request $request, $uuid) {

        $organism = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'alias' => 'required',
            'web_site' => 'url',
            'zone' => 'required',
            'contact' => 'required',
            'phone' => 'required|numeric',
            'payroll_type' => 'required',
            'status' => 'required|boolean',
            'disponibility' => 'required',
            'percentage_employers_contribution' => 'required|numeric',
            'percentage_individual_contribution' => 'required|numeric',
            'percentage_voluntary_contribution' => 'required|numeric',

            'direction' => 'required',
            'city_id' => 'required|alpha_dash',
            'direction_id' => 'required|alpha_dash',

            'association_id' => 'required|alpha_dash'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'alias' => 'required',
                'web_site' => 'url',
                'zone' => 'required',
                'contact' => 'required',
                'phone' => 'required|numeric',
                'payroll_type' => 'required',
                'status' => 'required|boolean',
                'disponibility' => 'required',
                'percentage_employers_contribution' => 'required|numeric',
                'percentage_individual_contribution' => 'required|numeric',
                'percentage_voluntary_contribution' => 'required|numeric',

                'direction' => 'required',
                'city_id' => 'required|alpha_dash',
                'direction_id' => 'required|alpha_dash',

                'association_id' => 'required|alpha_dash'
            ];
        }

        $city = City::byUuid($request->city_id)->firstOrFail();

        $request->merge(array('city_id' => $city->id));

        $direction = Direction::byUuid($request->direction_id)->firstOrFail();

        $direction->update($request->only('city_id','direction'));

        $request->merge(array('direction_id' => $direction->id));

        $association = Association::byUuid($request->association_id)->firstOrFail();

        $request->merge(array('association_id' => $association->id));

        $this->validate($request, $rules);

        $organism->update($request->all());

        return $this->response->item($organism->fresh(), new OrganismTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $organism = $this->model->byUuid($uuid)->firstOrFail();

        if($organism->partners->count() > 0) 

            return response()->json([

                'status'    => false,
                'message'   => 'El organismo posee asociados, no se puede eliminar.'
            ]);

        $direction = $organism->direction;

        $organism->delete();
        
        $direction->delete();

        return response()->json([

            'status'    => true,
            'message'   => 'El organismo ha sido eliminado con éxito.'
        ]);
    }

}