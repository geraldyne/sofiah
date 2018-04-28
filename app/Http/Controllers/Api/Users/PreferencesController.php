<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Users
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 26-04-2018.
 *  @version        1.0
 * 
 *  @final  
 */

/**
 * Incluye la implementación de los siguientes Controladores y Modelos
 */

namespace App\Http\Controllers\Api\Users;


use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;

use App\Entities\User;
use App\Entities\Preference;

use App\Transformers\Users\PreferenceTransformer;

use Carbon\Carbon;
use League\Fractal;

/**
 *  Controlador de Preferencias
 */

class PreferencesController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Preference $model) {

        $this->model = $model;
    }
	
    public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $preference = $this->model->with('user')->get();

        return $this->response->collection($preference, new PreferenceTransformer());
    }

    public function show($id) {
        
        $preference = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($preference, new PreferenceTransformer());
    }
    
    public function store(Request $request) {

        $user = User::byUuid($request->user_id)->firstOrFail();

        $request->merge(array('user_id' => $user->id));

        # Evalúa cada dato recibido
        
            $this->validate($request, [

                'style'     => 'required',
                'lang'      => 'required',
                'zoom'      => 'required',
                'user_id'   => 'required|unique:preferences'
            ]);

        $preference = $this->model->create($request->all());

        return response()->json([
            'status'    => true,
            'message'   => '¡Las configuraciones de preferencias se ha creado éxitosamente!',
            'object'    => $preference
        ]);
    }

    public function update(Request $request, $uuid) {

        $preference = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [
            'style'     => 'required',
            'lang'      => 'required',
            'zoom'      => 'required',
            'user_id'   => 'required|unique:preferences'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [
                'style'     => 'required',
                'lang'      => 'required',
                'zoom'      => 'required',
                'user_id'   => 'required|unique:preferences'
            ];
        }

        $this->validate($request, $rules);

        $user = User::byUuid($request->user_id)->firstOrFail();

        $request->merge(array('user_id' => $user->id));

        $preference->update($request->all());

        return $this->response->item($preference->fresh(), new PreferenceTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $preference = $this->model->byUuid($uuid)->firstOrFail();

        $preference->delete();

        return response()->json([

            'status'    => true,
            'message'   => '¡Las configuraciones de preferencias han sido eliminadas con éxito!'
        ]);
    }

}

