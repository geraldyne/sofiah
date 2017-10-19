<?php

/**
 *  @package        SOFIAH.App.Http.Controllers.Api.Administrative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 14-07-2017.
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
use App\Transformers\Administrative\BankTransformer;

use League\Fractal;

/**
 *  Controlador Bancos
 */

class BanksController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Bank $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }
        
        $paginator = $this->model->with('bankdetails')->paginate($request->get('limit', config('app.pagination_limit')));
        
        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new BankTransformer());
    }

    public function show($id) {
        
        $bank = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($bank, new BankTransformer());  
    }

    public function store(Request $request) {

        if(Bank::where('bank', '=', $request->bank)->exists()) return $this->response->error('El banco ya existe', 409);

        $this->validate($request, [

            'bank' => 'required'
        ]);

        $bank = $this->model->create($request->all());

        return $this->response->created(url('api/banks/'.$bank->uuid));
    }

    public function update(Request $request, $uuid) {

        if(Bank::where('bank', '=', $request->bank)->exists()) return $this->response->error('El banco ya existe', 409);

        $bank = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'bank' => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'bank' => 'sometimes|required'
            ];
        }
        
        $this->validate($request, $rules);
 
        $bank->update($request->all());

        return $this->response->item($bank->fresh(), new BankTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $bank = $this->model->byUuid($uuid)->firstOrFail();

        if($bank->bankdetails->count() > 0) 

            return response()->json([

                'status'    => false,
                'message'   => 'El banco está registrado en una cuenta de detalles bancarios, no se puede eliminar.'
            ]);
        
        $bank->delete();

        return $this->response->noContent();
    }
}
