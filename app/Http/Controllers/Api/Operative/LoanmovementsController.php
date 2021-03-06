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
use App\Entities\Operative\Amortdefdetails;
use App\Entities\Operative\Loanmovements;
use App\Transformers\Operative\LoanmovementsTransformer;

use League\Fractal;

/**
 *  Controlador movimientos prestamos
 */

class LoanmovementsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Loanmovements $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'loan',
            'amortdefdetails',
            'loanamortmovements'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new LoanmovementsTransformer());
    }

    public function show($id) {
        
        $loanmovements = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($loanmovements, new LoanmovementsTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'date_issue'          => 'required',
            'amount'              => 'required',
            'type'                => 'required',
            'status'              => 'required',
            'loan_id'             => 'required',
            'amortdefdetails_id'  => 'required'
        ]);

        $loan = Loan::byUuid($request->loan_id)->firstOrFail();

        $request->merge(array('loan_id' => $loan->id));


        $amortdefdetails = Amortdefdetails::byUuid($request->amortdefdetails_id)->firstOrFail();

        $request->merge(array('amortdefdetails_id' => $amortdefdetails->id));


        $loanmovements = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El movimiento del prestamo se ha registrado exitosamente!', 
            'object'  => $loanmovements 
        ]);
    }

    public function update(Request $request, $uuid) {

        $loanmovements = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'date_issue'          => 'required',
            'amount'              => 'required',
            'type'                => 'required',
            'status'              => 'required',
            'loan_id'             => 'required',
            'amortdefdetails_id'  => 'required' 
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'date_issue'          => 'required',
                'amount'              => 'required',
                'type'                => 'required',
                'status'              => 'required',
                'loan_id'             => 'required',
                'amortdefdetails_id'  => 'required'
            ];
        }
    
        $loan = Loan::byUuid($request->loan_id)->firstOrFail();

        $request->merge(array('loan_id' => $loan->id));


        $amortdefdetails = Amortdefdetails::byUuid($request->amortdefdetails_id)->firstOrFail();

        $request->merge(array('amortdefdetails_id' => $amortdefdetails->id));


        $this->validate($request, $rules);

        $loanmovements->update($request->all());

        return $this->response->item($loanmovements->fresh(), new LoanmovementsTransformer());
    }

    
}
