<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Dailymovementdetails;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class DailymovementdetailsTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'dailymovement',
        'account'
    ];

    /**
     * @param Daily_movement_details $model
     * @return array
     */
    public function transform(Dailymovementdetails $model)
    {
        return [

            'id' => $model->uuid,
            'description' => $model->description,
            'debit' => $model->debit,
            'asset' => $model->asset,
            'dailymovement_id' => $model->dailymovement_id,
            'accountlvl6_id' => $model->accountlvl6_id,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Daily_movement
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDailymovement(Dailymovementdetails $model)
    {
        return $this->item($model->dailymovement, new DailymovementTransformer);
    }

    /**
     * Include Accounts
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccount(Dailymovementdetails $model)
    {
        return $this->item($model->account, new Accountlvl6Transformer);
    }
}
