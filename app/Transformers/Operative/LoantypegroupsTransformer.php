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

use App\Entities\Operative\Loantypegroups;
use League\Fractal\TransformerAbstract;

/**
 * Class LoantypegroupsTransformer.
 */

class LoantypegroupsTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loan_types
     * @var organism
     */
    protected $availableIncludes = [
        'loansgroups'
    ];

    /**
     * @param Loantypegroups $model
     * @return array
     */
    public function transform(Loantypegroups $model)
    {
        return [
            'uuid'           => $model->uuid,
            'name'           => $model->name,
            'max_amount'     => $model->max_amount,
            'status'         => $model->status,
            'created_at'     => $model->created_at->toIso8601String(),
            'updated_at'     => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Loansgroups
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoansgroups(Loantypegroups $model)
    {
        return $this->collection($model->loansgroups, new LoansgroupsTransformer);
    }


}
