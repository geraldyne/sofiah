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

use App\Entities\Operative\Provider;
use League\Fractal\TransformerAbstract;

/**
 * Class ProviderTransformer.
 */

class ProviderTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var policies
     * @var bonds
     * @var direction
     */
    protected $availableIncludes = [
        'policies',
        'bonds',
        'direction'
    ];

    /**
     * @param Provider $model
     * @return array
     */
    public function transform(Provider $model)
    {
        return [
            'uuid'           => $model->uuid,
            'name'           => $model->name,
            'email'          => $model->email,
            'web_site'       => $model->web_site,
            'contact'        => $model->contact,
            'slug'           => $model->slug,
            'rif_type'       => $model->rif_type,
            'rif'            => $model->rif,
            'phone'          => $model->phone,
            'direction_id'   => $model->direction_id,
            'created_at'     => $model->created_at->toIso8601String(),
            'updated_at'     => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Policies
     *
     * @return League\Fractal\ItemResource
     */
    public function includePolicies(Provider $model)
    {
        return $this->collection($model->policies, new PoliciesTransformer);
    }


    /**
     * Include Bonds
     *
     * @return League\Fractal\ItemResource
     */
    public function includeBonds(Provider $model)
    {
        return $this->collection($model->bonds, new BondsTransformer);
    }


    /**
     * Include Direction
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDirection(Provider $model)
    {
        return $this->item($model->direction, new DirectionTransformer);
    }

}
