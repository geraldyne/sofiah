<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Operative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 18-10-2017.
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
use App\Entities\Operative\Amortdef;
use App\Entities\Operative\Issuedetails;
use App\Entities\Operative\Amortdefdetails;
use App\Transformers\Operative\AmortdefdetailsTransformer;

use League\Fractal;

/**
 *  Controlador cuota especial
 */

class AmortdefdetailsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Amortdefdetails $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'amortdef',
            'issuedetails'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new AmortdefdetailsTransformer());
    }

    public function show($id) {
        
        $amortdefdetails = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($amortdefdetails, new AmortdefdetailsTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'amount'             => 'required',
            'capital'            => 'required',
            'interests'          => 'required',
            'loan_balance'       => 'required',
            'quota_balance'      => 'required',
            'quota_date'         => 'required',
            'type'               => 'required',
            'quota_number'       => 'required',
            'days'               => 'required',
            'issuedetails_id'    => 'required',
            'amortdef_id'        => 'required'
        ]);

        $issuedetails = Issuedetails::byUuid($request->issuedetails_id)->firstOrFail();

        $request->merge(array('issuedetails_id' => $issuedetails->id));

        
        $amortdef = Amortdef::byUuid($request->amortdef_id)->firstOrFail();

        $request->merge(array('amortdef_id' => $amortdef->id));


        $amortdefdetails = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'Los detalles de la amortizacion se ha registrado exitosamente!', 
            'object'  => $amortdefdetails 
        ]);
    }

    public function update(Request $request, $uuid) {

        $amortdefdetails = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'amount'             => 'required',
            'capital'            => 'required',
            'interests'          => 'required',
            'loan_balance'       => 'required',
            'quota_balance'      => 'required',
            'quota_date'         => 'required',
            'type'               => 'required',
            'quota_number'       => 'required',
            'days'               => 'required',
            'issuedetails_id'    => 'required',
            'amortdef_id'        => 'required' 
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'amount'             => 'required',
                'capital'            => 'required',
                'interests'          => 'required',
                'loan_balance'       => 'required',
                'quota_balance'      => 'required',
                'quota_date'         => 'required',
                'type'               => 'required',
                'quota_number'       => 'required',
                'days'               => 'required',
                'issuedetails_id'    => 'required',
                'amortdef_id'        => 'required'
            ];
        }
    
        $issuedetails = Issuedetails::byUuid($request->issuedetails_id)->firstOrFail();

        $request->merge(array('issuedetails_id' => $issuedetails->id));

        
        $amortdef = Amortdef::byUuid($request->amortdef_id)->firstOrFail();

        $request->merge(array('amortdef_id' => $amortdef->id));


        $this->validate($request, $rules);

        $amortdefdetails->update($request->all());

        return $this->response->item($amortdefdetails->fresh(), new AmortdefdetailsTransformer());
    }

    
}
