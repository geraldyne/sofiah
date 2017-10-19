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
use App\Entities\Operative\Loantypes;
use App\Entities\Administrative\Accountingintegration;
use App\Transformers\Operative\LoanTransformer;

use League\Fractal;

/**
 *  Controlador tipos de préstamos
 */

class LoanController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Loan $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'loantypes',
            'amortdefloans',
            'policies',
            'bonds',
            'guarantors',
            'partners',
            'loanmovements'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new LoanTransformer());
    }

    public function show($id) {
        
        $loan = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($loan, new LoanTransformer());  
    }

    public function store(Request $request) {

        
        $this->validate($request, [

            'issue_date'                 => 'required|date',
            'amount'                     => 'required',
            'rate'                       => 'required',
            'balance'                    => 'required',
            'administrative_expenditure' => 'required',
            'fee_frequency'              => 'required',
            'status'                     => 'required',
            'destination'                => 'required',
            'monthly_fees'               => 'required',
            'loantypes_id'               => 'required'
            
        ]);

        $loantypes = Loantypes::byUuid($request->loantypes_id)->firstOrFail();

        $request->merge(array('loantypes_id' => $loantypes->id));


        $loan = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El prestamo se ha registrado exitosamente!', 
            'object'  => $loan 
        ]);
    }

    public function update(Request $request, $uuid) {

        $loan = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'issue_date'                 => 'required|date',
            'amount'                     => 'required',
            'rate'                       => 'required',
            'balance'                    => 'required',
            'administrative_expenditure' => 'required',
            'fee_frequency'              => 'required',
            'status'                     => 'required',
            'destination'                => 'required',
            'monthly_fees'               => 'required',
            'loantypes_id'               => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'issue_date'                 => 'required|date',
                'amount'                     => 'required',
                'rate'                       => 'required',
                'balance'                    => 'required',
                'administrative_expenditure' => 'required',
                'fee_frequency'              => 'required',
                'status'                     => 'required',
                'destination'                => 'required',
                'monthly_fees'               => 'required',
                'loantypes_id'               => 'required'
            ];
        }

        $loantypes = Loantypes::byUuid($request->loantypes_id)->firstOrFail();

        $request->merge(array('loantypes_id' => $loantypes->id));
        

        $this->validate($request, $rules);
 
        $loan->update($request->all());

        return $this->response->item($loan->fresh(), new LoanTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $loan = $this->model->byUuid($uuid)->firstOrFail();

        if($loan->loans->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'El tipo de prestamo posee prestamos asociados, no se puede eliminar.', 
            ]);
        }

        $loan->status= 0;

        $loan->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El tipo de prestamo se ha suspendido exitosamente!', 
        ]);
    }
}
