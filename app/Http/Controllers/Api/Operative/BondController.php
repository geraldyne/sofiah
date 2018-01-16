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
use App\Entities\Operative\Bond;
use App\Entities\Operative\Loan;
use App\Entities\Operative\Provider;
use App\Transformers\Operative\BondTransformer;

use League\Fractal;

/**
 *  Controlador Fianza
 */

class BondController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Bond $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'loan',
            'provider'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new BondTransformer());
    }

    public function show($id) {
        
        $bond = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($bond, new BondTransformer());  
    }

    public function store(Request $request) {

        
        $this->validate($request, [

            'number'        => 'required',
            'issue_date'    => 'required',
            'amount'        => 'required',
            'commission'    => 'required',
            'status'        => 'required',
            'provider_id'   => 'required',
            'loan_id'       => 'required'
            
        ]);

        $provider = Provider::byUuid($request->provider_id)->firstOrFail();

        $request->merge(array('provider_id' => $provider->id));


        $loan = Loan::byUuid($request->loan_id)->firstOrFail();

        $request->merge(array('loan_id' => $loan->id));


        $bond = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'La fianza ha sido registrada exitosamente!', 
            'object'  => $bond 
        ]);
    }

    public function update(Request $request, $uuid) {

        $bond = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'number'        => 'required',
            'issue_date'    => 'required',
            'amount'        => 'required',
            'commission'    => 'required',
            'status'        => 'required',
            'provider_id'   => 'required',
            'loan_id'       => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'number'        => 'required',
                'issue_date'    => 'required',
                'amount'        => 'required',
                'commission'    => 'required',
                'status'        => 'required',
                'provider_id'   => 'required',
                'loan_id'       => 'required'
            ];
        }

        $provider = Provider::byUuid($request->provider_id)->firstOrFail();

        $request->merge(array('provider_id' => $provider->id));


        $loan = Loan::byUuid($request->loan_id)->firstOrFail();

        $request->merge(array('loan_id' => $loan->id));
        

        $this->validate($request, $rules);
 
        $bond->update($request->all());

        return $this->response->item($bond->fresh(), new BondTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $bond = $this->model->byUuid($uuid)->firstOrFail();

        if($bond->bonds->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'El tipo de prestamo posee prestamos asociados, no se puede eliminar.', 
            ]);
        }

        $bond->status= 0;

        $bond->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El tipo de prestamo se ha suspendido exitosamente!', 
        ]);
    }
}
