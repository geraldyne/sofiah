<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Accountingyear;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class AccountingyearTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'dividends',
        'dailymovement'
    ];

    /**
     * @param Accounting_year $model
     * @return array
     */
    public function transform(Accountingyear $model)
    {
        return [

            'id' => $model->uuid,
            'start_date' => $model->start_date,
            'deadline' => $model->deadline,
            'status' => $model->status,
            'dailymovements_id' => $model->daily_movements_id,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Dividends
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDividends(Accounting_year $model)
    {
        return $this->collection($model->dividends, new DividendTransformer);
    }

    /**
     * Include Daily_movement
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDailymovement(Accounting_year $model)
    {
        return $this->item($model->dailymovement, new DailymovementTransformer);
    }
}
