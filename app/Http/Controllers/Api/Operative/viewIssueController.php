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
use App\Entities\Operative\Issue;
use App\Entities\Operative\Issuedetails;
use App\Entities\Operative\Amortdefloans;
use App\Entities\Administrative\Organism;
use App\Transformers\Operative\IssueTransformer;

use League\Fractal;

/**
 *  Controlador cuota especial
 */

class viewIssueController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Issue $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'organism'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new IssueTransformer());
    }

    public function create(Request $request)
    {
        $organism = Organism::byUuid($request->organism_id)->firstOrFail();

        $request->merge(array('organism_id' => $organism->id));

        $amortdefloans = Amortdefloans::where([
                                               [ 'organism_id', $request->organism_id ],
                                               [ 'status', '=', 'P' ], ])
                                              ->whereDate('updated_at', '=<', $request->date)
                                              ->firstOrFail();

        return response()->json([ 
            'status'  => true,
            'object'  => $amortdefloans 
        ]);

    }


    public function show($id) {
        
        $issue = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($issue, new IssueTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'date_issue'     => 'required',
            'amount'         => 'required',
            'status'         => 'required',
            'organism_id'    => 'required',
            # Amortizacion
            'amortizations'  => 'required',
            //'payroll_type'   => 'required',
            //'issue_date'     => 'required',
            # Tipo de cuota a cancelar
            //'quota_type'     => 'required'
        ]);

        $organism = Organism::byUuid($request->organism_id)->firstOrFail();

        $request->merge(array('organism_id' => $organism->id));

        $issue = $this->model->create($request->except([ 'amortizations']));

        //$issue = $this->model->create($request->except([ 'payroll_type', 'issue_date', 'quota_type']));

        /*
        $amortizations = Amortdefloans::where([
                                              [ 'status', '=' , 'P' ],
                                              [ 'payroll_type', $request->payroll_type ],
                                              [ 'issue_date',   $request->issue_date ],  ]);
        */

        foreach ($amortizations as $amortization) 
        {
            /*

            # Validamos que esa amortizacion pertenezca a ese organismo 

            if ($amortization->loan->partners->organism_id == $request->organism_id) 
            {
            
            */

                # Verificamos si la amortizacion pertenece a una cuota ordinaria

                if ($amortization->balance_quota_ordinary > 0  && ($request->quota_type == 'O' ||  $request->quota_type == 'M')) 
                {
                    $issuedetails = new Issuedetails();

                    
                    $issuedetails->amount = $amortization->quota_amount;

                    $issuedetails->capital = $amortization->capital_quota_ordinary;

                    $issuedetails->interests = $amortization->interests_quota_ordinary;

                    $issuedetails->loan_balance = $amortization->balance_quota_ordinary;

                    $issuedetails->quota_balance = 0;

                    $issuedetails->quota_date = $amortization->quota_date;

                    $issuedetails->type = 'O';

                    $issuedetails->quota_number = $amortization->quota_number;

                    //$issuedetails->days = $amortization->;

                    $issuedetails->loantypecode_id = $amortization->loan->loantypes->loantypecodes->loan_code;

                    $issuedetails->issue_id = $issue->id;


                    $issuedetails->save();
                }


                # Verificamos si la amortization pertenece a una cuota especial

                if ($amortization->balance_quota_special > 0 && ($request->quota_type == 'E' ||  $request->quota_type == 'M') ) 
                {
                    $issuedetails = new Issuedetails();

                    
                    $issuedetails->amount = $amortization->quota_amount;

                    $issuedetails->capital = $amortization->capital_quota_special;

                    $issuedetails->interests = $amortization->interests;

                    $issuedetails->loan_balance = $amortization->balance_quota_special;

                    $issuedetails->quota_balance = 0;

                    $issuedetails->quota_date = $amortization->quota_date;

                    $issuedetails->type = 'O';

                    $issuedetails->quota_number = $amortization->quota_number;

                    //$issuedetails->days = $amortization->;

                    $issuedetails->loantypecode_id = $amortization->loan->loantypes->loantypecodes->loan_code;

                    $issuedetails->issue_id = $issue->id;


                    $issuedetails->save();
                }


                # Cambiamos el estatus de la amortizacion

                $amortization->status = 'A';

                $amortization->update();

            /*

            }

            */
            
        }


        return response()->json([ 
            'status'  => true, 
            'message' => 'La emision se ha registrado exitosamente!', 
            'object'  => $issue 
        ]);
    }

    public function update(Request $request, $uuid) {

        $Issue = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'date_issue'    => 'required',
            'amount'        => 'required',
            'status'        => 'required',
            'organism_id'  => 'required'  
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'date_issue'    => 'required',
                'amount'        => 'required',
                'status'        => 'required',
                'organism_id'  => 'required'
            ];
        }

        $organism = Organism::byUuid($request->organism_id)->firstOrFail();

        $request->merge(array('organism_id' => $organism->id));
    
        $this->validate($request, $rules);

        $issue->update($request->all());

        return $this->response->item($issue->fresh(), new IssueTransformer());
    }

    
}
