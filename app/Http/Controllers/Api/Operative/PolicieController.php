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
use App\Entities\Operative\Loan;
use App\Entities\Operative\Policie;
use App\Entities\Operative\Provider;
use App\Transformers\Operative\PolicieTransformer;

use League\Fractal;

/**
 *  Controlador poliza
 */

class PolicieController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Policie $model) {
        
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

        return $this->response->paginator($paginator, new PolicieTransformer());
    }

    public function show($id) {
        
        $policie = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($policie, new PolicieTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'number'          => 'required',
            'type'            => 'required',
            'issue_date'      => 'required',
            'due_date'        => 'required',
            'amount'          => 'required',
            'status'          => 'required',
            'provider_id'     => 'required',
            'loan_id'         => 'required'
            
        ]);

        $provider = Provider::byUuid($request->provider_id)->firstOrFail();

        $request->merge(array('provider_id' => $provider->id));


        $loan = Loan::byUuid($request->loan_id)->firstOrFail();

        $request->merge(array('loan_id' => $loan->id));


        $policie = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'La poliza se ha registrado exitosamente!', 
            'object'  => $policie 
        ]);
    }

    public function update(Request $request, $uuid) {

        $policie = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'number'          => 'required',
            'type'            => 'required',
            'issue_date'      => 'required',
            'due_date'        => 'required',
            'amount'          => 'required',
            'status'          => 'required',
            'provider_id'     => 'required',
            'loan_id'         => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'number'          => 'required',
                'type'            => 'required',
                'issue_date'      => 'required',
                'due_date'        => 'required',
                'amount'          => 'required',
                'status'          => 'required',
                'provider_id'     => 'required',
                'loan_id'         => 'required'
            ];
        }

        $provider = Provider::byUuid($request->provider_id)->firstOrFail();

        $request->merge(array('provider_id' => $provider->id));


        $loan = Loan::byUuid($request->loan_id)->firstOrFail();

        $request->merge(array('loan_id' => $loan->id));


        $this->validate($request, $rules);
 
        $policie->update($request->all());

        return $this->response->item($policie->fresh(), new PolicieTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $Policie = $this->model->byUuid($uuid)->firstOrFail();

        if($Policie->Policies->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'El tipo de prestamo posee prestamos asociados, no se puede eliminar.', 
            ]);
        }

        $Policie->status= 0;

        $Policie->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El tipo de prestamo se ha suspendido exitosamente!', 
        ]);
    }
}
