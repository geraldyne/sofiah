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
use App\Entities\Administrative\Partner;
use App\Entities\Operative\Assetsmovements;
use App\Entities\Operative\Assetsmovementsdetails;
use App\Transformers\Operative\AssetsmovementsTransformer;

use League\Fractal;

/**
 *  Controlador haberes detalles
 */

class AssetsmovementsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Assetsmovements $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'partner',
            'assetsmovementsdetails'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new AssetsmovementsTransformer());
    }

    public function show($id) {
        
        $assetsmovements = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($assetsmovements, new AssetsmovementsTransformer());  
    }

    public function assetsdisponibility(Request $request) 
    {

        if ($request->id_card) // Si existe una cedula como parametro
        {
            $partner = Partner::where('id_card', $request->id_card)->firstOrFail();
        }
        else if ($request->employee_code) // Si existe un codigo de empleado como parametro
        {
            $partner = Partner::where('employee_code', $request->employee_code)->firstOrFail();
        }

        return response()->json([

            'status'        => true,
            'partner'       => $partner,
            'organism'      => $partner->organism,
            'disponibility' => $partner->organism->disponibility,
            'assetsbalance' => $partner->assetsbalance
        ]);
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'date_issue'         => '',
            'reason'             => 'required',
            'status'             => 'required',
            'type'               => 'required',
            'total_amount'       => 'required',
            'description'        => 'required',
            'partner_id'         => 'required'
            
        ]);

        $partner = Partner::byUuid($request->partner_id)->firstOrFail();

        $request->merge(array('partner_id' => $partner->id));

        /*
        
        // Verificamos que tipo de aporte tiene como concepto de retiro y descontamos de su saldo de haberes

        if ($request->type === 'AP') 
        {
            $partner->assetsbalance->balance_employers_contribution-= $request->total_amount; 
        }
        else if ($request->type === 'AI') 
            {
                $partner->assetsbalance->balance_individual_contribution-= $request->total_amount; 
            }
            else if($request->type === 'AV') 
                {
                    $partner->assetsbalance->balance_voluntary_contribution-= $request->total_amount; 
                }

        // Actualizamos el saldo de haberes del asociado

        $partner->assetsbalance->update();

        */

        $assetsmovements = $this->model->create($request->all());

        // Guardamos el detalle del movimiento de haberes

        $assetsmovementsdetails= new Assetsmovementsdetails();

        $assetsmovementsdetails->amount= $request->total_amount;
        $assetsmovementsdetails->type= $request->type;
        $assetsmovementsdetails->assetsmovements_id= $assetsmovements->id;

        $assetsmovementsdetails->save();

        return response()->json([ 
            'status'  => true, 
            'message' => 'El movimiento de haberes se ha registrado exitosamente!', 
            'object'  => $assetsmovements 
        ]);
    }

    public function update(Request $request, $uuid) {

        $assetsmovements = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'date_issue'         => 'required',
            'reason'             => 'required',
            'status'             => 'required',
            'total_amount'       => 'required',
            'description'        => 'required',
            'partner_id'         => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'date_issue'         => 'required',
                'reason'             => 'required',
                'status'             => 'required',
                'total_amount'       => 'required',
                'description'        => 'required',
                'partner_id'         => 'required'
            ];
        }

        $partner = Partner::byUuid($request->partner_id)->firstOrFail();

        $request->merge(array('partner_id' => $partner->id));


        $this->validate($request, $rules);
 
        $assetsmovements->update($request->all());

        return $this->response->item($assetsmovements->fresh(), new AssetsmovementsTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $Assetsmovements = $this->model->byUuid($uuid)->firstOrFail();

        if($Assetsmovements->Assetsmovementss->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'El tipo de prestamo posee prestamos asociados, no se puede eliminar.', 
            ]);
        }

        $Assetsmovements->status= 0;

        $Assetsmovements->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El tipo de prestamo se ha suspendido exitosamente!', 
        ]);
    }
}
