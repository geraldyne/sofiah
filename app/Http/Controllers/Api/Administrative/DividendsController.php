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
use App\Entities\Administrative\Partner;
use App\Entities\Administrative\Dividend;
use App\Transformers\Administrative\DividendTransformer;

use League\Fractal;
use Carbon\Carbon;

/**
 *  Controlador dividendos
 */

class DividendsController extends Controller {

    use Helpers;

    protected $model;

    public function __construct(Dividend $model) {
        
        $this->model = $model;
    }

	public function index(Request $request) {

        $fractal = new Fractal\Manager();

        if (isset($_GET['include'])) {
            
            $fractal->parseIncludes($_GET['include']);
        }
        
        $paginator = $this->model->with(
            'partner',
            'accountingyear'
        )->paginate($request->get('limit', config('app.pagination_limit')));
        
        if ($request->has('limit')) {
        
            $paginator->appends('limit', $request->get('limit'));
        }

        return $this->response->paginator($paginator, new DividendTransformer());
    }

    public function show($id) {
        
        $dividend = $this->model->byUuid($id)->firstOrFail();

        return $this->response->item($dividend, new DividendTransformer());  
    }

    public function store(Request $request) {

        $date = Carbon::parse($request->registration_date)->format('m');
        $partner = Partner::byUuid($request->partner_id)->first();

        if(Dividend::whereMonth('registration_date', $date)
                     ->where('partner_id', '=', $partner->id)
                     ->exists()) return $this->response->error('El flujo de caja ya existe', 409);

        dd($date);
        
        $this->validate($request, [

            'registration_date'  => 'required|date',
            'assets_associated'  => 'required|numeric',
            'dividends'          => 'required|numeric',
            'assets_association' => 'required|numeric',
            'status'             => 'required|boolean',
            'factor'             => 'required',
            'partner_id'         => 'required|alpha_dash',
            'accounting_id'      => 'required|alpha_dash'
        ]);

        $dividend = $this->model->create($request->all());

        return response()->json([ 
            'status'  => true, 
            'message' => '¡El dividendo se ha registrado exitosamente!', 
            'object'  => $dividend 
        ]);
    }

    public function update(Request $request, $uuid) {

        if(Dividend::where('concept', '=', $request->concept)->exists()) return $this->response->error('El flujo de caja ya existe', 409);

        $dividend = $this->model->byUuid($uuid)->firstOrFail();

        $rules = [

            'concept' => 'required'
        ];

        if ($request->method() == 'PATCH') {

            $rules = [

                'concept' => 'sometimes|required'
            ];
        }
        
        $this->validate($request, $rules);
 
        $dividend->update($request->all());

        return $this->response->item($dividend->fresh(), new DividendTransformer());
    }

    public function destroy(Request $request, $uuid) {

        $dividend = $this->model->byUuid($uuid)->firstOrFail();
        
        $dividend->delete();

        return $this->response->noContent();
    }
}
