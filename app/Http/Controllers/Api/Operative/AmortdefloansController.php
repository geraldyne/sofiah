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
use App\Entities\Operative\Loan;
use App\Entities\Operative\Amortdefloans;
use App\Transformers\Operative\AmortdefloansTransformer;

use League\Fractal;

/**
 *  Controlador grupos de tipos de préstamos
 */

class AmortdefloansController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Amortdefloans $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'loan'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new AmortdefloansTransformer());
    }

    public function show($id) {
        
        $amortdefloans = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($amortdefloans, new AmortdefloansTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'quota_amount'              => 'required',
            'quota_date'                => 'required',
            'quota_amount_ordinary'     => 'required',
            'capital_quota_ordinary'    => 'required',
            'interests_quota_ordinary'  => 'required',
            'capital_quota_special'     => 'required',
            'amount_quota_special'      => 'required',
            'balance_quota_ordinary'    => 'required',
            'balance_quota_special'     => 'required',
            'loan_id'                   => 'required'
        ]);

        $loan = Loan::byUuid($request->loan_id)->firstOrFail();

        $request->merge(array('loan_id' => $loan->id));


        $amortdefloans = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'La amortizacion prestamo se ha registrado exitosamente!', 
            'object'  => $amortdefloans 
        ]);
    }

    public function update(Request $request, $uuid) {

        $amortdefloans = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'quota_amount'              => 'required',
            'quota_date'                => 'required',
            'quota_amount_ordinary'     => 'required',
            'capital_quota_ordinary'    => 'required',
            'interests_quota_ordinary'  => 'required',
            'capital_quota_special'     => 'required',
            'amount_quota_special'      => 'required',
            'balance_quota_ordinary'    => 'required',
            'balance_quota_special'     => 'required',
            'loan_id'                   => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'quota_amount'              => 'required',
                'quota_date'                => 'required',
                'quota_amount_ordinary'     => 'required',
                'capital_quota_ordinary'    => 'required',
                'interests_quota_ordinary'  => 'required',
                'capital_quota_special'     => 'required',
                'amount_quota_special'      => 'required',
                'balance_quota_ordinary'    => 'required',
                'balance_quota_special'     => 'required',
                'loan_id'                   => 'required'
            ];
        }

        $loan = Loan::byUuid($request->loan_id)->firstOrFail();

        $request->merge(array('loan_id' => $loan->id));
        

        $this->validate($request, $rules);
 
        $amortdefloans->update($request->all());

        return $this->response->item($amortdefloans->fresh(), new AmortdefloansTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $amortdefloans = $this->model->byUuid($uuid)->firstOrFail();

        if($amortdefloans->loansgroups->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'El grupo tipo de prestamo posee tipos de prestamos, no se puede eliminar.', 
            ]);
        }

        $amortdefloans->status= 0;

        $amortdefloans->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El grupo tipo de prestamo se ha suspendido exitosamente!', 
        ]);
    }
}
