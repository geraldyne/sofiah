<?php

/**
 *  @package        SOFIAH.App.Transformers.Operative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 09-10-2017.
 *  @version        1.0
 * 
 *  @final  
 */

 /**
 * Incluye la implementación de los siguientes Librerias
 */

namespace App\Transformers\Operative;

use App\Entities\Operative\Amortdef;
use League\Fractal\TransformerAbstract;

/**
 * Class Amort_defTransformer.
 */

class AmortdefTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loans
     * @var amort_def_details
     */
    protected $availableIncludes = [
        'amortdefdetails'
    ];

    /**
     * @param Amort_def $model
     * @return array
     */
    public function transform(Amortdef $model)
    {
        return [

            'id'         => $model->uuid,
            'date_issue' => $model->date_issue,
            'amount'     => $model->amount,
            'status'     => $model->status,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones

    /**
     * Include Loans
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAmortdefdetails(Amortdef $model)
    {
        return $this->collection($model->amortdefdetails, new AmortdefdetailsTransformer);
    }
}
