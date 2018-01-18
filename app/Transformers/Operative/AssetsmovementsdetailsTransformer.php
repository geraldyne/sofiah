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

use App\Entities\Operative\Assetsmovementsdetails;
use League\Fractal\TransformerAbstract;

/**
 * Class AssetsmovementsdetailsTransformer.
 */

class AssetsmovementsdetailsTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var amortization
     */
    protected $availableIncludes = [
        'assetsmovements'
    ];

    /**
     * @param Assetsmovementsdetails $model
     * @return array
     */
    public function transform(Assetsmovementsdetails $model)
    {
        return [
            'id'                   => $model->uuid,
            'amount'               => $model->amount,
            'type'                 => $model->type,
            'assetsmovements_id'   => $model->assetsmovements_id,
            'created_at'           => $model->created_at->toIso8601String(),
            'updated_at'           => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include assetsmovements
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAssetsmovements(Assetsmovementsdetails $model)
    {
        return $this->item($model->assetsmovements, new AssetsmovementsTransformer);
    }


}
