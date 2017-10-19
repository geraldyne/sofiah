<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Administrative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 11-10-2017.
 *  @version        1.0
 * 
 *  @final  
 */

/**
 * Incluye la implementación de los siguientes Controladores y Modelos
 */

namespace App\Http\Controllers\Api\Administrative;


use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Entities\Administrative\Bank;
use App\Entities\Administrative\Bankdetails;
use App\Transformers\Administrative\BankdetailsTransformer;

use League\Fractal;

/**
 *  Controlador detalles del banco
 */

class BankdetailsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Bankdetails $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with('bank', 'partner', 'employee')->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new BankdetailsTransformer());
    }

    public function show($id) {
        
        $bankdetails = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($bankdetails, new BankdetailsTransformer());  
    }

    public function update(Request $request, $uuid) {

        $bankdetails = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'account_number'    => 'required|max:20|unique:bankdetails',
            'account_type'      => 'required|max:1',
            'bank_id'           => 'required',
            'updated_at'        =>  getdate()
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'account_number'    => 'required|max:20|unique:bankdetails',
                'account_type'      => 'required|max:1',
                'bank_id'           => 'required',
                'updated_at'        =>  getdate()
            ];
        }

        $bank = Bank::byUuid($request->bank_id)->firstOrFail();

        $request->merge(array('bank_id' => $bank->id));

        $bankdetails->update($request->all());

        return $this->response->item($bankdetails->fresh(), new BankdetailsTransformer());
    }
}
