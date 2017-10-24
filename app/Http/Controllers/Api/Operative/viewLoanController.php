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
use App\Entities\Operative\Bond;
use App\Entities\Operative\Guarantor;
use App\Entities\Operative\Provider;
use App\Entities\Operative\Amortdef;
use App\Entities\Operative\Loanmovements;
use App\Entities\Administrative\Partner;
use App\Transformers\Operative\LoanTransformer;

use League\Fractal;

/**
 *  Controlador tipos de préstamos
 */

class viewLoanController extends Controller {

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

    public function create(Request $request)
    {
        if ($request->id_card) 
        {
            $partner = Partner::where('id_card', $request->id_card)->firstOrFail();

        }
        else if ($request->employee_code) 
        {
            $partner = Partner::where('employee_code', $request->employee_code)->firstOrFail();
        }

        $disponibility = (($partner->assetsbalance->balance_voluntary_contribution + $partner->assetsbalance->balance_individual_contribution + $partner->assetsbalance->balance_employers_contribution) * $partner->organism->disponibility) - $partner->loans->sum('balance');

        $provider = Provider::all();

        return response()->json([ 
            'partner'        => $partner, 
            'disponibility'  => $disponibility,
            'provider'       => $provider
        ]);

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
            'loantypes_id'               => 'required',
            // Amortizacion Prestamo
            'amortizations'              => 'required',
            // Fianza
            'bond_number'                => 'required',
            'bond_amount'                => 'required',
            'bond_commission'            => 'required',
            'provider_id'                => 'required',
            // Fiadores 
            'guarantors'                 => 'required',
            
        ]);

        // Guardamos las amortizaciones del prestamo

        foreach ($amortizations as $amort) 
        {
            $amortization = new Amortdef();

            $amortization->date_issue = $amort->issue_date;
            $amortization->amount     = $amort->amount;
            $amortization->status     = 'P';

            $amortization->save();
        }


        // Guardamos el Prestamo

        $loantypes = Loantypes::byUuid($request->loantypes_id)->firstOrFail();

        $request->merge(array('loantypes_id' => $loantypes->id));


        $loan = $this->model->create($request->except([ 'amortizations', 'bond_number', 'bond_amount', 'bond_commission', 'provider_id', 'guarantors']));


        // Guardamos los detalles de la Fianza

        $provider = Provider::byUuid($request->provider_id)->firstOrFail();

        $request->merge(array('provider_id' => $provider->id));

        $bond = new Bond();

        $bond->number      = $request->bond_number;

        $bond->issue_date  = $request->issue_date;
        
        $bond->amount      = $request->bond_amount;

        $bond->commission  = $request->bond_commission;

        $bond->provider_id = $request->provider_id;
        
        $bond->loan_id     = $loan->id;

        $bond->save();


        // Guardamos los Fiadores

        $foreach ($guarantors as $guarantor) 
        {
            $Guarantor = new Guarantor();

            $Guarantor->amount     = $guarantor->amount;

            $Guarantor->balance    = $guarantor->balance;

            $Guarantor->percentage = $guarantor->percentage;

            $Guarantor->status     = 'P';

            $Guarantor->partner_id = $guarantor->partner_id;

            $Guarantor->loan_id    = $loan->id;

            $Guarantor->save();
        }

        $loanmovement = new Loanmovements();

        $loanmovement 

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
