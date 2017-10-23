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
use App\Entities\Operative\Assetsbalance;
use App\Transformers\Operative\AssetsbalanceTransformer;

use League\Fractal;

/**
 *  Controlador saldo haberes
 */

class AssetsbalanceController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Assetsbalance $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'partner'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new AssetsbalanceTransformer());
    }

    public function show($id) {
        
        $assetsbalance = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($assetsbalance, new AssetsbalanceTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'balance_employers_contribution'    => 'required',
            'balance_individual_contribution'   => 'required',
            'balance_voluntary_contribution'    => 'required',
            'partner_id'                        => 'required'
            
        ]);

        $partner = Partner::byUuid($request->partner_id)->firstOrFail();

        $request->merge(array('partner_id' => $partner->id));


        $assetsbalance = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El saldo de haberes del asociado se ha registrado exitosamente!', 
            'object'  => $assetsbalance 
        ]);
    }

    public function update(Request $request, $uuid) {

        $assetsbalance = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'balance_employers_contribution'    => 'required',
            'balance_individual_contribution'   => 'required',
            'balance_voluntary_contribution'    => 'required',
            'partner_id'                        => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'balance_employers_contribution'    => 'required',
                'balance_individual_contribution'   => 'required',
                'balance_voluntary_contribution'    => 'required',
                'partner_id'                        => 'required'
            ];
        }

        $partner = Partner::byUuid($request->partner_id)->firstOrFail();

        $request->merge(array('partner_id' => $partner->id));


        $this->validate($request, $rules);
 
        $assetsbalance->update($request->all());

        return $this->response->item($assetsbalance->fresh(), new AssetsbalanceTransformer());
    }


}
