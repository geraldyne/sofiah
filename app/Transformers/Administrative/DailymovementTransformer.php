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
        'userorigin',
        'userapply',
        'details',
        'accountingyear'
    ];

    /**
     * @param Daily_movement $model
     * @return array
     */
    public function transform(Dailymovement $model)
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
    public function includeUserorigin(Dailymovement $model)
    {
        return $this->item($model->user_origin, new UserTransformer);
    }

    /**
     * Include User_apply
     *
     * @return League\Fractal\ItemResource
     */
    public function includeUserapply(Dailymovement $model)
    {
        return $this->item($model->user_apply, new UserTransformer);
    }

    /**
     * Include Details
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDetails(Dailymovement $model)
    {
        return $this->collection($model->details, new DailymovementdetailsTransformer);
    }

    /**
     * Include Accounting_year
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountingyear(Dailymovement $model)
    {
        return $this->item($model->accounting_year, new AccountingyearTransformer);
    }
}
