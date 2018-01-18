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
use App\Entities\Operative\Loantypecodes;
use App\Entities\Administrative\Organism;
use App\Transformers\Operative\IssueTransformer;

use League\Fractal;

/**
 *  Controlador Emision
 */

class IssueController extends Controller {

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

    public function show($id) {
        
        $issue = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($issue, new IssueTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'date_issue'    => 'required',
            //'amount'        => 'required',
            //'status'        => 'required',
            'organism_id'  => 'required',
            'type_file'    => 'required'
        ]);

        $organism = Organism::byUuid($request->organism_id)->firstOrFail();

        $request->merge(array('organism_id' => $organism->id));

        // Obtenemos el mes de la emision a generar

        $month= substr($request->date_issue, 5, -3);

        // Buscamos todas las cuotas que correspondan a la emision de ese mes que no han sido cobradas

        $amortizationLoans = Amortdefloans::whereMonth('quota_date', $month)->where('status', 'P')->get();

        /*
        // Guardamos todas las cuotas que van a ser cobradas

        foreach ($amortizationLoans as $amortization)
        {
            // Guardamos la Emision
            
            $issueQuota = new Issue();

            $issueQuota->date_issue  = $request->date_issue;

            $issueQuota->amount      = $amortization->quota_amount;
            $issueQuota->status      = 'P';
            $issueQuota->organism_id = $organism->id;

            $issueQuota->save();

            // Guardamos el detalle de la emision

            $issueDetails = new Issuedetails();

            $issueDetails->amount          = $amortization->quota_amount;

            $issueDetails->capital         = $amortization->capital_quota_ordinary + $amortization->capital_quota_special;
            $issueDetails->interests       = $amortization->interests_quota_ordinary;
            $issueDetails->loan_balance    = $amortization->loan->balance;
            $issueDetails->quota_balance   = 0;
            $issueDetails->quota_date      = $amortization->quota_date;

            // Verificamos si el prestamo tiene cuota especial

            if ($amortization->capital_quota_special == 0) 
            {
                $issueDetails->type = 'O';
            }
            else 
            {   
               $issueDetails->type = 'E';
            }

            $issueDetails->quota_number    = $amortization->quota_number;
            $issueDetails->days            = 0; // Ni idea..
            $issueDetails->amortdefloan_id = $amortization->id;

            $loantypecode = Loantypecodes::where('loantypes_id', $amortization->loan->loantypes_id)->firstOrFail();

            $issueDetails->loantypecode_id = $loantypecode->id; 
            $issueDetails->issue_id        = $issueQuota->id;

            $issueDetails->save();
        }

        */


        // Generacion de archivo de emision

        \Excel::create('Emission', function($excel) {
            
            $amortizationLoans = Amortdefloans::whereMonth('quota_date', 01)->where('status', 'P')->get();

            # Configuramos los parametros del archivo excel 

            $excel->setTitle('Emisión de préstamos ', getdate() ,' - SOFIAH');

            $excel->setCreator('Sofiah')->setCompany('Idepixel');

            $excel->setDescription('Listado de todas las cuotas de préstamos a cobrar.');

            # Personalizamos el documento

            $excel->sheet('Emisión de préstamos', function($sheet) use($amortizationLoans)
            {

                # Formateamos el encabezado del archivo

                $sheet->row(1, [
                                'Fecha de Emisión', 
                                'Monto', 
                                'Estatus de la Emisión (P: Pendiente. A: Aplicada. C: Cobrada.)', 
                                'Nombre del Organismo' ]);

                # Seleccionamos los campos a imprimir 

                foreach($amortizationLoans as $index => $emission) 
                {
                    $sheet->row($index+2, [
                                          //  getDate(), 
                                            $emission->quota_amount, 
                                            $emission->status, 
                                            $emission->organism->name, ]); 
                } 


            });

        })->export($request->type_file);


        return response()->json([ 
            'status'  => true, 
            'message' => 'La emision se ha registrado exitosamente!', 
            'object'  => $issueQuota
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
