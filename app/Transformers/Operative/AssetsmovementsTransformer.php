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

use App\Entities\Operative\Assetsmovements;
use League\Fractal\TransformerAbstract;

/**
 * Class AssetsmovementsTransformer.
 */

class AssetsmovementsTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var amortization
     */
    protected $availableIncludes = [
        'partner',
        'assetsmovementsdetails'
    ];

    /**
     * @param Assetsmovements $model
     * @return array
     */
    public function transform(Assetsmovements $model)
    {
        return [
            'uuid'           => $model->uuid,
            'date_issue'     => $model->date_issue,
            'reason'         => $model->reason,
            'status'         => $model->status,
            'total_amount'   => $model->total_amount,
            'description'    => $model->description,
            'partner_id'     => $model->partner_id,
            'created_at'     => $model->created_at->toIso8601String(),
            'updated_at'     => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Partner
     *
     * @return League\Fractal\ItemResource
     */
    public function includePartner(Assetsmovements $model)
    {
        return $this->item($model->partner, new PartnerTransformer);
    }


    /**
     * Include Assetsmovementsdetails
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAssetsmovementsdetails(Assetsmovements $model)
    {
        return $this->collection($model->assetsmovementsdetails, new AssetsmovementsdetailsTransformer);
    }

}
