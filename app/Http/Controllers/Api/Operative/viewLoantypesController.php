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
use App\Entities\Operative\Loantypes;
use App\Entities\Operative\Loansgroups;
use App\Entities\Operative\Loantypecodes;
use App\Entities\Operative\Guarantor;
use App\Entities\Operative\Specialfee;
use App\Entities\Operative\Specialfeedetails;
use App\Entities\Administrative\Organism;
use App\Entities\Administrative\Accountingintegration;
use App\Transformers\Operative\LoantypesTransformer;

use League\Fractal;

/**
 *  Controlador tipos de préstamos
 */

class viewLoantypesController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Loantypes $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'loansgroups',
            'loantypecodes',
            'loans',
            'specialfees'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new LoantypesTransformer());
    }

    public function create()
    {

        $organisms = Organism::all();

        $loansgroups = Loansgroups::all();

        $accountingintegration = Accountingintegration::all();

        return response()->json([ 
            'organisms'              => $organisms, 
            'loansgroups'            => $loansgroups, 
            'accountingintegration'  => $accountingintegration
        ]);

    }

    public function show($id) {
        
        $loantypes = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($loantypes, new LoantypesTransformer());  
    }

    public function store(Request $request) {

        $this->validate($request, [

            'name'                                => 'required|unique:loan_types',
            'guarantor'                           => 'required|boolean',
            'guarantee'                           => 'required|boolean',
            'guarantee_comision'                  => 'required|boolean',
            'refinancing'                         => 'required|boolean',
            'valid_availability'                  => 'required|boolean',
            'affect_availability'                 => 'required|boolean',
            'special_fees'                        => 'required|boolean',
            'third_party_payment'                 => 'required|boolean',
            'paid_capacity'                       => 'required|boolean',
            'valid_policy'                        => 'required|boolean',
            'web_based'                           => 'required|boolean',
            'administrative_expenditure'          => 'required|boolean',
            'deduct_administrative_expense'       => 'required|boolean',
            'interest'                            => 'required|numeric',
            'bond_commission'                     => 'required|numeric',
            'refinancing_amount'                  => 'required|numeric',
            'percent_special_quotes'              => 'required|numeric',
            'percent_administrative_expenditure'  => 'required|numeric',
            'refinance_days'                      => 'required|numeric',
            'term'                                => 'required',
            'number_guarantors'                   => 'required|numeric',
            'receivable_id'                       => 'required',
            'billtopay_id'                        => 'required',
            'incomeaccount_id'                    => 'required',
            'max_amount'                          => 'required|numeric',
            'operatingexpenseaccount_id'          => 'required',
            //'organismList'                        => 'required',
            'loan_code'                           => 'required|numeric',
            'organism_id'                         => 'required',
            //'specialfeeList'                      => 'required',
            'month_specialfee'                    => 'required'
        ]);

        // Registramos un tipo de prestamo


        $receivable = Accountingintegration::byUuid($request->receivable_id)->firstOrFail();

        $request->merge(array('receivable_id' => $receivable->id));


        $billtopay = Accountingintegration::byUuid($request->billtopay_id)->firstOrFail();

        $request->merge(array('billtopay_id' => $billtopay->id));


        $incomeaccount = Accountingintegration::byUuid($request->incomeaccount_id)->firstOrFail();

        $request->merge(array('incomeaccount_id' => $incomeaccount->id));


        $operatingexpenseaccount = Accountingintegration::byUuid($request->operatingexpenseaccount_id)->firstOrFail();

        $request->merge(array('operatingexpenseaccount_id' => $operatingexpenseaccount->id));


        $loantypes = $this->model->create($request->all());


        // Registramos un codigo de tipo de prestamo

        /*

        foreach ($organismList as $organisms) 
        {
            $organism = Organism::byUuid($organisms->organism_id)->firstOrFail();

            $request->merge(array('organism_id' => $organism->id));

            $loantypecodes =  new Loantypecodes();

            $loantypecodes->loan_code = $organisms->loan_code;

            $loantypecodes->loantypes_id = $loantypes->id;

            $loantypecodes->organism_id = $organism->id;

            $loantypecodes->save();
        }

        */

        $organism = Organism::byUuid($request->organism_id)->firstOrFail();

        $request->merge(array('organism_id' => $organism->id));

        $loantypecodes =  new Loantypecodes();

        $loantypecodes->loan_code = $request->loan_code;

        $loantypecodes->loantypes_id = $loantypes->id;

        $loantypecodes->organism_id = $organism->id;

        $loantypecodes->save();

        /*

        foreach ($specialfeeList as $month) 
        {
            // Registramos el detalle de la cuota especial

            $specialfeedetails = new Specialfeedetails();

            $specialfeedetails->month = $month;

            $specialfeedetails->save();


            // Registramos la cuota especial

            $specialfee = new Specialfee();

            $specialfee->loantypes_id = $loantypes->id;

            $specialfee->specialfeedetails_id = $specialfeedetails->id;

            $specialfee->save();
        }


        */

        // Registramos el detalle de la cuota especial

        $specialfeedetails = new Specialfeedetails();

        $specialfeedetails->month = $request->month_specialfee;

        $specialfeedetails->save();


        // Registramos la cuota especial

        $specialfee = new Specialfee();

        $specialfee->loantypes_id = $loantypes->id;

        $specialfee->specialfeedetails_id = $specialfeedetails->id;

        $specialfee->save();



        return response()->json([ 
            'status'             => true, 
            'message'            => 'El tipo de prestamo se ha registrado exitosamente!', 
            'loantypes'          => $loantypes,
            'loantypecodes'      => $loantypecodes,
            'specialfeedetails'  => $specialfeedetails,
            'specialfee'         => $specialfee 
        ]);
    }

    public function update(Request $request, $uuid) {

        $loantypes = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'name'                                => 'required|unique:loan_types',
            'guarantor'                           => 'required|boolean',
            'guarantee'                           => 'required|boolean',
            'guarantee_comision'                  => 'required|boolean',
            'refinancing'                         => 'required|boolean',
            'valid_availability'                  => 'required|boolean',
            'affect_availability'                 => 'required|boolean',
            'special_fees'                        => 'required|boolean',
            'third_party_payment'                 => 'required|boolean',
            'paid_capacity'                       => 'required|boolean',
            'valid_policy'                        => 'required|boolean',
            'web_based'                           => 'required|boolean',
            'administrative_expenditure'          => 'required|boolean',
            'deduct_administrative_expense'       => 'required|boolean',
            'interest'                            => 'required|numeric',
            'bond_commission'                     => 'required|numeric',
            'refinancing_amount'                  => 'required|numeric',
            'percent_special_quotes'              => 'required|numeric',
            'percent_administrative_expenditure'  => 'required|numeric',
            'refinance_days'                      => 'required|numeric',
            'term'                                => 'required',
            'number_guarantors'                   => 'required|numeric',
            'receivable_id'                       => 'required',
            'billtopay_id'                        => 'required',
            'incomeaccount_id'                    => 'required',
            'max_amount'                          => 'required|numeric',
            'operatingexpenseaccount_id'          => 'required',
            //'organismList'                        => 'required',
            'loan_code'                           => 'required|numeric',
            'organism_id'                         => 'required',
            //'specialfeeList'                      => 'required',
            'month_specialfee'                    => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'name'                                => 'required|unique:loan_types',
                'guarantor'                           => 'required|boolean',
                'guarantee'                           => 'required|boolean',
                'guarantee_comision'                  => 'required|boolean',
                'refinancing'                         => 'required|boolean',
                'valid_availability'                  => 'required|boolean',
                'affect_availability'                 => 'required|boolean',
                'special_fees'                        => 'required|boolean',
                'third_party_payment'                 => 'required|boolean',
                'paid_capacity'                       => 'required|boolean',
                'valid_policy'                        => 'required|boolean',
                'web_based'                           => 'required|boolean',
                'administrative_expenditure'          => 'required|boolean',
                'deduct_administrative_expense'       => 'required|boolean',
                'interest'                            => 'required|numeric',
                'bond_commission'                     => 'required|numeric',
                'refinancing_amount'                  => 'required|numeric',
                'percent_special_quotes'              => 'required|numeric',
                'percent_administrative_expenditure'  => 'required|numeric',
                'refinance_days'                      => 'required|numeric',
                'term'                                => 'required',
                'number_guarantors'                   => 'required|numeric',
                'receivable_id'                       => 'required',
                'billtopay_id'                        => 'required',
                'incomeaccount_id'                    => 'required',
                'max_amount'                          => 'required|numeric',
                'operatingexpenseaccount_id'          => 'required',
                //'organismList'                        => 'required',
                'loan_code'                           => 'required|numeric',
                'organism_id'                         => 'required',
                //'specialfeeList'                      => 'required',
                'month_specialfee'                    => 'required'
            ];
        }

        // Registramos un tipo de prestamo


        $receivable = Accountingintegration::byUuid($request->receivable_id)->firstOrFail();

        $request->merge(array('receivable_id' => $receivable->id));


        $billtopay = Accountingintegration::byUuid($request->billtopay_id)->firstOrFail();

        $request->merge(array('billtopay_id' => $billtopay->id));


        $incomeaccount = Accountingintegration::byUuid($request->incomeaccount_id)->firstOrFail();

        $request->merge(array('incomeaccount_id' => $incomeaccount->id));


        $operatingexpenseaccount = Accountingintegration::byUuid($request->operatingexpenseaccount_id)->firstOrFail();

        $request->merge(array('operatingexpenseaccount_id' => $operatingexpenseaccount->id));


        $this->validate($request, $rules);

        $loantypes->update($request->all());


        // Registramos un codigo de tipo de prestamo

        /*

        foreach ($organismList as $organisms) 
        {
            $organism = Organism::byUuid($organisms->organism_id)->firstOrFail();

            $request->merge(array('organism_id' => $organism->id));

            // Registramos un codigo de tipo de prestamo asociado a ese organismo

            $loantypecodes =  new Loantypecodes();

            $loantypecodes->loan_code = $organisms->loan_code;

            $loantypecodes->loantypes_id = $loantypes->id;

            $loantypecodes->organism_id = $organism->id;

            $loantypecodes->save();
        }

        */

        $organism = Organism::byUuid($request->organism_id)->firstOrFail();

        $request->merge(array('organism_id' => $organism->id));

        $loantypecodes =  new Loantypecodes();

        $loantypecodes->loan_code = $request->loan_code;

        $loantypecodes->loantypes_id = $loantypes->id;

        $loantypecodes->organism_id = $organism->id;

        $loantypecodes->save();

        /*

        foreach ($specialfeeList as $month) 
        {
            // Registramos el detalle de la cuota especial

            $specialfeedetails = new Specialfeedetails();

            $specialfeedetails->month = $month;

            $specialfeedetails->save();


            // Registramos la cuota especial

            $specialfee = new Specialfee();

            $specialfee->loantypes_id = $loantypes->id;

            $specialfee->specialfeedetails_id = $specialfeedetails->id;

            $specialfee->save();
        }


        */

        // Registramos el detalle de la cuota especial

        $specialfeedetails = new Specialfeedetails();

        $specialfeedetails->month = $request->month_specialfee;

        $specialfeedetails->save();


        // Registramos la cuota especial

        $specialfee = new Specialfee();

        $specialfee->loantypes_id = $loantypes->id;

        $specialfee->specialfeedetails_id = $specialfeedetails->id;

        $specialfee->save();


        return $this->response->item($loantypes->fresh(), new LoantypesTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $loantypes = $this->model->byUuid($uuid)->firstOrFail();

        $loantypes->status= 0;

        $loantypes->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El tipo de prestamo se ha suspendido exitosamente!', 
        ]);
    }
}
