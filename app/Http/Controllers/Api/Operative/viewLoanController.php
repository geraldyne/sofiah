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
use App\Entities\Operative\Amortdefloans;
use App\Entities\Operative\Loanmovements;
use App\Entities\Operative\Loantypes;
use App\Entities\Operative\Policie;
use App\Entities\Operative\Issuedetails;
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
            'partner',
            'loanmovements'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new LoanTransformer());
    }


    public function disponibility(Request $request)
    {

        if ($request->id_card) // Si existe una cedula como parametro
        {
            $partner = Partner::where('id_card', $request->id_card)->firstOrFail();

        }
        else if ($request->employee_code) // Si existe un codigo de empleado como parametro
        {
            $partner = Partner::where('employee_code', $request->employee_code)->firstOrFail();
        }


        # Verificamos la disponibilidad del asociado 

        if ($partner->guarantors->count() > 0) // Si es fiador
        {
            $disponibility = (($partner->assetsbalance->balance_voluntary_contribution + $partner->assetsbalance->balance_individual_contribution + $partner->assetsbalance->balance_employers_contribution) * $partner->organism->disponibility) - $partner->loans->sum('balance') - $partner->guarantors->sum('balance');
        }
        else // No es fiador
        {
            $disponibility = (($partner->assetsbalance->balance_voluntary_contribution + $partner->assetsbalance->balance_individual_contribution + $partner->assetsbalance->balance_employers_contribution) * $partner->organism->disponibility) - $partner->loans->sum('balance');
        }


        return response()->json([ 
            'partner'        => $partner, 
            'disponibility'  => $disponibility
        ]);
    }

    public function show($id) {
        
        $loan = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($loan, new LoanTransformer());  
    }

    public function store(Request $request) {

        
        $this->validate($request, [

            'issue_date'                 => 'required',
            'amount'                     => 'required',
            'rate'                       => 'required',
            'balance'                    => 'required',
            'administrative_expenditure' => '',
            'fee_frequency'              => 'required',
            'status'                     => 'required',
            'destination'                => 'required',
            'monthly_fees'               => 'required',
            'loantypes_id'               => 'required',
            'partner_id'                 => 'required',
            # Amortizacion Prestamo
            'amortizations'              => 'required',
            # Fianza
            'bonds'                      => '',
            # Fiadores 
            'guarantors'                 => '',
            # Polizas 
            'policies'                   => '',
            
        ]);


        # Buscamos el tipo de prestamo

        $loantypes = Loantypes::where('uuid', $request->loantypes_id)->firstOrFail();

        $request->merge(array('loantypes_id' => $loantypes->id));


        $partner = Partner::where('uuid', $request->partner_id)->firstOrFail();

        $request->merge(array('partner_id' => $partner->id));

        # Guardamos el prestamo

        $loan = $this->model->create($request->except([ 'amortizations', 'bonds', 'guarantors', 'policies']));


        # Guardamos las amortizaciones del prestamo

        foreach ($request->amortizations as $amort) 
        {
            $amortization = new Amortdefloans();


            $amortization->quota_number              = $amort['quota_number'];

            $amortization->quota_amount              = $amort['quota_amount'];

            $amortization->quota_date                = '2018-01-12 16:55:05';

            $amortization->status                    = 'P';

            $amortization->payroll_type              = 'M';

            $amortization->issue_date                = $request->issue_date;


            $amortization->quota_amount_ordinary     = $amort['quota_amount_ordinary'];

            $amortization->capital_quota_ordinary    = $amort['capital_quota_ordinary'];

            $amortization->interests_quota_ordinary  = $amort['interests_quota_ordinary'];

            $amortization->balance_quota_ordinary    = $amort['balance_quota_ordinary'];


            $amortization->capital_quota_special     = 0;

            $amortization->amount_quota_special      = 0;

            $amortization->balance_quota_special     = 0;

            $amortization->organism_id               = $partner->organism_id;

            $amortization->loan_id                   = $loan->id;


            $amortization->save();
        }

        # Verificamos si el tipo de prestamo admite Fianza y si existen fianzas

        if ($loantypes->guarantee == true && $request->bonds) 
        {
            # Guardamos los datos de la fianza correspondiente al prestamo

            foreach ($request->bonds as $bond) 
            {
                $provider = Provider::byUuid($bond['provider_id'])->firstOrFail();


                $Bond = new Bond();


                $Bond->number      = $bond['number'];;

                $Bond->issue_date  = $request->issue_date;
                
                $Bond->amount      = $bond['amount'];

                $Bond->commission  = $bond['commission'];

                $Bond->status      = 'P';

                $Bond->provider_id = $provider->id;
                
                $Bond->loan_id     = $loan->id;


                $Bond->save();
            }

        }

        # Verificamos si el tipo de prestamo admite Fiadores y si existen fiadores

        if ($loantypes->guarantor == true && $request->guarantors) 
        {
            # Guardamos los datos de los fiadores correspondientes al prestamo

            foreach ($request->guarantors as $guarantor) 
            {
                $partner = Partner::byUuid($guarantor['partner_id'])->firstOrFail();


                $Guarantor = new Guarantor();

             
                $Guarantor->amount     = $guarantor['amount'];

                $Guarantor->balance    = $guarantor['amount'];

                $Guarantor->percentage = $guarantor['percentage'];

                $Guarantor->status     = 'P';

                $Guarantor->partner_id = $partner->id;

                $Guarantor->loan_id    = $loan->id;


                $Guarantor->save();
            } 
        }

        # Verificamos si admite polizas

        if ($loantypes->valid_policy == true) 
        {

            # Guardamos los datos de la poliza correspondiente al prestamo

            foreach ($request->policies as $policie) 
            {
                $provider = Provider::byUuid($policie->provider_id)->firstOrFail();

                $policie->merge(array('provider_id' => $provider->id));

                
                $Policie = new Policie();


                $Policie->number      = $policie->number;

                $Policie->type        = $policie->type;

                $Policie->issue_date  = $request->issue_date;

                $Policie->due_date    = $policie->due_date;

                $Policie->amount      = $policie->amount;

                $Policie->status      = 'A';

                $Policie->provider_id = $policie->provider_id;

                $Policie->loan_id     = $loan->loan_id;


                $Policie->save();
            }
        }

        # Guardamos el movimiento del prestamo

        $loanmovement = new Loanmovements();

        $loanmovement->date_issue = $request->issue_date;

        $loanmovement->amount     = $request->amount;

        $loanmovement->type       = 'PR';

        $loanmovement->status     = 'P';

        $loanmovement->loan_id    = $loan->id;


        $loanmovement->save(); 


        return response()->json([ 
            'status'  => true, 
            'message' => 'El prestamo se ha registrado exitosamente!', 
            'object'  => $loan 
        ]);
    }

    public function update(Request $request, $uuid) {

        $loan = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'issue_date'                 => '',
            'amount'                     => 'required',
            'rate'                       => 'required',
            'balance'                    => 'required',
            'administrative_expenditure' => '',
            'fee_frequency'              => 'required',
            'status'                     => 'required',
            'destination'                => 'required',
            'monthly_fees'               => 'required',
            'loantypes_id'               => 'required',
            'partner_id'                 => 'required',
            # Amortizacion Prestamo
            'amortizations'              => 'required',
            # Fianza
            'bonds'                      => '',
            # Fiadores 
            'guarantors'                 => '',
            # Polizas 
            'policies'                   => '',
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'issue_date'                 => '',
                'amount'                     => 'required',
                'rate'                       => 'required',
                'balance'                    => 'required',
                'administrative_expenditure' => '',
                'fee_frequency'              => 'required',
                'status'                     => 'required',
                'destination'                => 'required',
                'monthly_fees'               => 'required',
                'loantypes_id'               => 'required',
                'partner_id'                 => 'required',
                # Amortizacion Prestamo
                'amortizations'              => 'required',
                # Fianza
                'bonds'                      => '',
                # Fiadores 
                'guarantors'                 => '',
                # Polizas 
                'policies'                   => '',
            ];
        }


       # Buscamos el tipo de prestamo

        $loantypes = Loantypes::where('uuid', $request->loantypes_id)->firstOrFail();

        $request->merge(array('loantypes_id' => $loantypes->id));


        $partner = Partner::where('uuid', $request->partner_id)->firstOrFail();

        $request->merge(array('partner_id' => $partner->id));
     
        # Guardamos el prestamo

        $this->validate($request, $rules);
 
        $loan->update($request->except([ 'amortizations', 'bonds', 'guarantors', 'policies']));


         # Guardamos las amortizaciones del prestamo

        foreach ($request->amortizations as $amort) 
        {
            $amortization = new Amortdefloans();


            $amortization->quota_number              = $amort['quota_number'];

            $amortization->quota_amount              = $amort['quota_amount'];

            $amortization->quota_date                = '2018-01-12 16:55:05';

            $amortization->status                    = 'P';

            $amortization->payroll_type              = 'M';

            $amortization->issue_date                = '2018-01-12 16:55:05';


            $amortization->quota_amount_ordinary     = $amort['quota_amount_ordinary'];

            $amortization->capital_quota_ordinary    = $amort['capital_quota_ordinary'];

            $amortization->interests_quota_ordinary  = $amort['interests_quota_ordinary'];

            $amortization->balance_quota_ordinary    = $amort['balance_quota_ordinary'];


            $amortization->capital_quota_special     = 0;

            $amortization->amount_quota_special      = 0;

            $amortization->balance_quota_special     = 0;

            $amortization->organism_id               = $partner->organism_id;

            $amortization->loan_id                   = $loan->id;


            $amortization->save();
        }

        # Verificamos si el tipo de prestamo admite Fianza y si existen fianzas

        if ($loantypes->guarantee == true && $request->bonds) 
        {
            # Guardamos los datos de la fianza correspondiente al prestamo

            foreach ($request->bonds as $bond) 
            {
                $provider = Provider::byUuid($bond['provider_id'])->firstOrFail();


                $Bond = new Bond();


                $Bond->number      = $bond['number'];;

                $Bond->issue_date  = '2018-01-12 16:55:05';
                
                $Bond->amount      = $bond['amount'];

                $Bond->commission  = $bond['commission'];

                $Bond->status      = 'P';

                $Bond->provider_id = $provider->id;
                
                $Bond->loan_id     = $loan->id;


                $Bond->save();
            }

        }

        # Verificamos si el tipo de prestamo admite Fiadores y si existen fiadores

        if ($loantypes->guarantor == true && $request->guarantors) 
        {
            # Guardamos los datos de los fiadores correspondientes al prestamo

            foreach ($request->guarantors as $guarantor) 
            {
                $partner = Partner::byUuid($guarantor['partner_id'])->firstOrFail();


                $Guarantor = new Guarantor();

             
                $Guarantor->amount     = $guarantor['amount'];

                $Guarantor->balance    = $guarantor['amount'];

                $Guarantor->percentage = $guarantor['percentage'];

                $Guarantor->status     = 'P';

                $Guarantor->partner_id = $partner->id;

                $Guarantor->loan_id    = $loan->id;


                $Guarantor->save();
            } 
        }

        # Verificamos si admite polizas

        if ($loantypes->valid_policy == true) 
        {

            # Guardamos los datos de la poliza correspondiente al prestamo

            foreach ($request->policies as $policie) 
            {
                $provider = Provider::byUuid($policie->provider_id)->firstOrFail();

                $policie->merge(array('provider_id' => $provider->id));

                
                $Policie = new Policie();


                $Policie->number      = $policie->number;

                $Policie->type        = $policie->type;

                $Policie->issue_date  = $policie->issue_date;

                $Policie->due_date    = $policie->due_date;

                $Policie->amount      = $policie->amount;

                $Policie->status      = 'A';

                $Policie->provider_id = $policie->provider_id;

                $Policie->loan_id     = $loan->loan_id;


                $Policie->save();
            }
        }

        # Guardamos el movimiento del prestamo

        $loanmovement = new Loanmovements();

        $loanmovement->date_issue = '2018-01-12 16:55:05';

        $loanmovement->amount     = $request->amount;

        $loanmovement->type       = 'PR';

        $loanmovement->status     = 'P';

        $loanmovement->loan_id    = $loan->id;


        $loanmovement->save(); 


        return $this->response->item($loan->fresh(), new LoanTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $loan = $this->model->byUuid($uuid)->firstOrFail();

        foreach ($loan->amortdefloans as $amortdefloans) 
        {
            $issuedetails = Issuedetails::where('amortdefloan_id', $amortdefloans->id)->first();

            if($issuedetails) 
            {
                return response()->json([ 
                    'status' => false, 
                    'message' => 'El prestamo posee detalles de emisiones asociadas, no se puede suspender!', 
                ]);
            }

            echo " JD";
        }

        // Suspendemos el prestamo

        /*

        $loan->status= 0;

        $loan->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El tipo de prestamo se ha suspendido exitosamente!', 
        ]);

        */
    }
}
