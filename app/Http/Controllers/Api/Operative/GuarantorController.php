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
use App\Entities\Operative\Guarantor;
use App\Entities\Administrative\Partner;
use App\Transformers\Operative\GuarantorTransformer;

use League\Fractal;

/**
 *  Controlador fiadores
 */

class GuarantorController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Guarantor $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }

        $paginator = $this->model->with(
            'loan',
            'partner'
        )->paginate($request->get('limit', config('app.pagination_limit')));

        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new GuarantorTransformer());
    }

    public function show($id) {
        
        $Guarantor = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($Guarantor, new GuarantorTransformer());  
    }

    public function store(Request $request) {
        
        $this->validate($request, [

            'amount'         => 'required',
            'balance'        => 'required',
            'percentage'     => 'required',
            'status'         => 'required',
            'partner_id'     => 'required',
            'loan_id'        => 'required'
            
        ]);

        $partner = Partner::byUuid($request->partner_id)->firstOrFail();

        $request->merge(array('partner_id' => $partner->id));


        $loan = Loan::byUuid($request->loan_id)->firstOrFail();

        $request->merge(array('loan_id' => $loan->id));


        $guarantor = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => 'El fiador se ha registrado exitosamente!', 
            'object'  => $guarantor 
        ]);
    }

    public function update(Request $request, $uuid) {

        $guarantor = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'amount'         => 'required',
            'balance'        => 'required',
            'percentage'     => 'required',
            'status'         => 'required',
            'partner_id'     => 'required',
            'loan_id'        => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'amount'         => 'required',
                'balance'        => 'required',
                'percentage'     => 'required',
                'status'         => 'required',
                'partner_id'     => 'required',
                'loan_id'        => 'required'
            ];
        }

        $partner = Partner::byUuid($request->partner_id)->firstOrFail();

        $request->merge(array('partner_id' => $partner->id));


        $loan = Loan::byUuid($request->loan_id)->firstOrFail();

        $request->merge(array('loan_id' => $loan->id));


        $this->validate($request, $rules);
 
        $guarantor->update($request->all());

        return $this->response->item($guarantor->fresh(), new GuarantorTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $Guarantor = $this->model->byUuid($uuid)->firstOrFail();

        if($Guarantor->Guarantors->count() > 0) {

            return response()->json([ 
                'status' => false, 
                'message' => 'El tipo de prestamo posee prestamos asociados, no se puede eliminar.', 
            ]);
        }

        $Guarantor->status= 0;

        $Guarantor->update();

        return response()->json([ 
            'status' => true, 
            'message' => '¡El tipo de prestamo se ha suspendido exitosamente!', 
        ]);
    }
}
