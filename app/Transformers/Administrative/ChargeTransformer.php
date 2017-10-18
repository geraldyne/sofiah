<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Charge;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class ChargeTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'managers'
    ];

    /**
     * @param Charge $model
     * @return array
     */
    public function transform(Charge $model)
    {
        return [

            'id' => $model->uuid,
            'charge' => $model->charge
        ];
    }

    // Relaciones
    
    /**
     * Include Manager
     *
     * @return League\Fractal\ItemResource
     */
    public function includeManagers(Charge $model)
    {
        return $this->collection($model->managers, new ManagerTransformer);
    }
}
