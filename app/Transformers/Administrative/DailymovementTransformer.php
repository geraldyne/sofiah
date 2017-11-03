<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Dailymovement;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class DailymovementTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user_origin',
        'user_apply',
        'details',
        'accounting_year'
    ];

    /**
     * @param Daily_movement $model
     * @return array
     */
    public function transform(Daily_movement $model)
    {
        return [

            'id' => $model->uuid,
            'date' => $model->date,
            'description' => $model->description,
            'status' => $model->status,
            'debit' => $model->debit,
            'asset' => $model->asset,
            'number' => $model->number,
            'origin' => $model->origin,
            'type' => $model->type,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include User_origin
     *
     * @return League\Fractal\ItemResource
     */
    public function includeUser_origin(User_origin $model)
    {
        return $this->item($model->user_origin, new UserTransformer);
    }

    /**
     * Include User_apply
     *
     * @return League\Fractal\ItemResource
     */
    public function includeUser_apply(User_apply $model)
    {
        return $this->item($model->user_apply, new UserTransformer);
    }

    /**
     * Include Details
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDetails(Daily_movement $model)
    {
        return $this->collection($model->details, new Daily_movement_detailsTransformer);
    }

    /**
     * Include Accounting_year
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccounting_year(Daily_movement $model)
    {
        return $this->item($model->accounting_year, new Accounting_yearTransformer);
    }
}
