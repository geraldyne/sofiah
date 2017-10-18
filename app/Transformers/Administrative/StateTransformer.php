<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\State;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class StateTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'country',
        'cities'
    ];

    /**
     * @param State $model
     * @return array
     */
    public function transform(State $model)
    {
        return [

            'id' => $model->uuid,
            'state' => $model->state,
            'country_id' => $model->country_id,
            //'created_at' => $model->created_at->toIso8601String(),
            //'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Country
     *
     * @return League\Fractal\ItemResource
     */
    public function includeCountry(State $model)
    {
        return $this->item($model->country, new CountryTransformer);
    }

    /**
     * Include States
     *
     * @return League\Fractal\ItemResource
     */
    public function includeCities(State $model)
    {
        return $this->collection($model->cities, new CityTransformer);
    }
}
