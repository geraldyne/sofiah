<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\City;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class CityTransformer extends TransformerAbstract
{
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'state',
        'directions'
    ];
 
    /**
     * @param City $model
     * @return array
     */
    public function transform(City $model)
    {
        return [

            'id' => $model->uuid,
            'city' => $model->city,
            'area_code' => $model->area_code,
            'state_id' => $model->state_id,
            //'created_at' => $model->created_at->toIso8601String(),
            //'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include City
     *
     * @return League\Fractal\ItemResource
     */
    public function includeState(City $model)
    {
        return $this->item($model->state, new StateTransformer);
    }

    /**
     * Include States
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDirections(City $model)
    {
        return $this->collection($model->directions, new DirectionTransformer);
    }
}
