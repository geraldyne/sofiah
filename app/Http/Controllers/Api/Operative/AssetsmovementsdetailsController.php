<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Operative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 15-10-2017.
 *  @version        1.0
 * 
 *  @final  
 */

/**
 * Incluye la implementación de los siguientes Controladores y Modelos
 */

namespace App\Http\Controllers\Api\Operative;


use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Entities\Operative\Assetsmovements;
use App\Entities\Operative\Assetsmovementsdetails;
use App\Transformers\Operative\AssetsmovementsdetailsTransformer;

use League\Fractal;

/**
 *  Controlador haberes detalles
 */

class AssetsmovementsdetailsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Assetsmovementsdetails $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'assetsmovements'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new AssetsmovementsdetailsTransformer());
    }

    public function show($id) {
        
        $assetsmovementsdetails = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($assetsmovementsdetails, new AssetsmovementsdetailsTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'amount'             => 'required',
            'type'               => 'required',
            'assetsmovements_id'  => 'required'
            
        ]);

        $assetsmovements = Assetsmovements::byUuid($request->assetsmovements_id)->firstOrFail();

        $request->merge(array('assetsmovements_id' => $assetsmovements->id));


        $assetsmovementsdetails = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El detalle del movimiento haberes se ha registrado exitosamente!', 
            'object'  => $assetsmovementsdetails 
        ]);
    }

    public function update(Request $request, $uuid) {

        $assetsmovementsdetails = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'amount'             => 'required',
            'type'               => 'required',
            'assetsmovements_id'  => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'amount'             => 'required',
                'type'               => 'required',
                'assetsmovements_id'  => 'required'
            ];
        }

        $assetsmovements = Assetsmovements::byUuid($request->assetsmovements_id)->firstOrFail();

        $request->merge(array('assetsmovements_id' => $assetsmovements->id));


        $this->validate($request, $rules);
 
        $assetsmovementsdetails->update($request->all());

        return $this->response->item($assetsmovementsdetails->fresh(), new AssetsmovementsdetailsTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $Assetsmovementsdetails = $this->model->byUuid($uuid)->firstOrFail();

        if($Assetsmovementsdetails->Assetsmovementsdetailss->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'El tipo de prestamo posee prestamos asociados, no se puede eliminar.', 
            ]);
        }

        $Assetsmovementsdetails->status= 0;

        $Assetsmovementsdetails->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El tipo de prestamo se ha suspendido exitosamente!', 
        ]);
    }
}
