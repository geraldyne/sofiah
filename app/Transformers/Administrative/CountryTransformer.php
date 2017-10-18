<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Country;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class CountryTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'states'
    ];

    /**
     * @param Country $model
     * @return array
     */
    public function transform(Country $model)
    {
        return [

            'id' => $model->uuid,
            'country' => $model->country,
            //'created_at' => $model->created_at->toIso8601String(),
            //'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include States
     *
     * @return League\Fractal\ItemResource
     */
    public function includeStates(Country $model)
    {
        return $this->collection($model->states, new StateTransformer);
    }
}
