<?php

/**
 *  @package        SOFIAH.App.Transformers.Operative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 22-10-2017.
 *  @version        1.0
 * 
 *  @final  
 */

 /**
 * Incluye la implementación de los siguientes Librerias
 */

namespace App\Transformers\Operative;

use App\Entities\Operative\Assetsbalance;
use League\Fractal\TransformerAbstract;

/**
 * Class AssetsbalanceTransformer.
 */

class AssetsbalanceTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var amortization
     */
    protected $availableIncludes = [
        'partner'
    ];

    /**
     * @param Assetsbalance $model
     * @return array
     */
    public function transform(Assetsbalance $model)
    {
        return [
            'uuid'                              => $model->uuid,
            'balance_employers_contribution'    => $model->status,
            'balance_individual_contribution'   => $model->total_amount,
            'balance_voluntary_contribution'    => $model->description,
            'partner_id'                        => $model->partner_id,
            'created_at'                        => $model->created_at->toIso8601String(),
            'updated_at'                        => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Partner
     *
     * @return League\Fractal\ItemResource
     */
    public function includePartner(Assetsbalance $model)
    {
        return $this->item($model->partner, new PartnerTransformer);
    }

}
