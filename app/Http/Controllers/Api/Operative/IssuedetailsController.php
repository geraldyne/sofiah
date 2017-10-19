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
use App\Transformers\Operative\IssuedetailsTransformer;

use League\Fractal;

/**
 *  Controlador Emision Detalles
 */

class IssuedetailsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Issuedetails $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'issue'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new IssuedetailsTransformer());
    }

    public function show($id) {
        
        $issuedetails = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($issuedetails, new IssuedetailsTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'amount'        => 'required',
            'capital'       => 'required',
            'interests'     => 'required',
            'loan_balance'  => 'required',
            'quota_balance' => 'required',
            'quota_date'    => 'required',
            'type'          => 'required',
            'quota_number'  => 'required',
            'days'          => 'required',
            'issue_id'      => 'required'
        ]);

        $issue = Issue::byUuid($request->issue_id)->firstOrFail();

        $request->merge(array('issue_id' => $issue->id));


        $issuedetails = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El detalle de la emision se ha registrado exitosamente!', 
            'object'  => $issuedetails 
        ]);
    }

    public function update(Request $request, $uuid) {

        $issuedetails = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'amount'        => 'required',
            'capital'       => 'required',
            'interests'     => 'required',
            'loan_balance'  => 'required',
            'quota_balance' => 'required',
            'quota_date'    => 'required',
            'type'          => 'required',
            'quota_number'  => 'required',
            'days'          => 'required',
            'issue_id'      => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'amount'        => 'required',
                'capital'       => 'required',
                'interests'     => 'required',
                'loan_balance'  => 'required',
                'quota_balance' => 'required',
                'quota_date'    => 'required',
                'type'          => 'required',
                'quota_number'  => 'required',
                'days'          => 'required',
                'issue_id'      => 'required'
            ];
        }

        $issue = Issue::byUuid($request->issue_id)->firstOrFail();

        $request->merge(array('issue_id' => $issue->id));
        
    
        $this->validate($request, $rules);

        $issuedetails->update($request->all());

        return $this->response->item($issuedetails->fresh(), new IssuedetailsTransformer());
    }

    
}
