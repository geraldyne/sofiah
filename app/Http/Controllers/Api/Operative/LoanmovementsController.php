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
use App\Entities\Administrative\Partner;
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

    public function querypartner(Request $request) 
    {

        if ($request->id_card) // Si existe una cedula como parametro
        {
            $partner = Partner::where('id_card', $request->id_card)->firstOrFail();
            $loan = Loan::where('partner_id', $partner->id)->firstOrFail();
        }
        else if ($request->employee_code) // Si existe un codigo de empleado como parametro
        {
            $partner = Partner::where('employee_code', $request->employee_code)->firstOrFail();
            $loan = Loan::where('partner_id', $partner->id)->firstOrFail();
        }

        return response()->json([

            'status'    => true,
            'partner'   => $partner,
            'loans'     => $partner->loans,
            'loanType'  => $loan->loantypes
        ]);
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'loanMovements'          => 'required',
        ]);

        foreach ($request->loanMovements as $movement)
        {
            $loan = Loan::byUuid($movement['loan_id'])->firstOrFail();
        
            $loanmovements = new Loanmovements();

            // Monto del movimiento. Si es de tipo préstamo registrar en positivo, si es amortización o abono registrar en negativo.

            if ($movement['type']=='PR')
            {
                $loanmovements->amount = $movement['amount'];
            }
            else
            {
                $loanmovements->amount = -1 * $movement['amount'];
                $loan->balance-= $movement['amount']; 
            }

            $loanmovements->date_issue = $movement['date_issue'];
            $loanmovements->amount     = $movement['amount'];
            $loanmovements->type       = $movement['type'];
            $loanmovements->status     = 'P';
            $loanmovements->loan_id    = $loan->id;

            $loanmovements->save();
            $loan->update();

        }

        return response()->json([ 
            'status'  => true, 
            'message' => 'El movimiento del prestamo se ha registrado exitosamente!', 
            'object'  => $loanmovements 
        ]);
    }
    
}
