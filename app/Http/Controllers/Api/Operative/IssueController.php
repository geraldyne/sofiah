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
use App\Entities\Administrative\Organism;
use App\Transformers\Operative\IssueTransformer;

use League\Fractal;

/**
 *  Controlador cuota especial
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
            'amount'        => 'required',
            'status'        => 'required',
            'organism_id'  => 'required'
        ]);

        $organism = Organism::byUuid($request->organism_id)->firstOrFail();

        $request->merge(array('organism_id' => $organism->id));

        $Issue = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'La emision se ha registrado exitosamente!', 
            'object'  => $Issue 
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
