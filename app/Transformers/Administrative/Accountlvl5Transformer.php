<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Accountlvl5;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class Accountlvl5Transformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'accountlvl4',
        'accountslvl6'
    ];

    /**
     * @param Accountlvl $model
     * @return array
     */
    public function transform(Accountlvl5 $model)
    {
        return [

            'id' => $model->uuid,
            'account_code' => $model->account_code,
            'account_name' => $model->account_name,
            'account_type' => $model->account_type,
            'balance_type' => $model->balance_type,
            'apply_balance' => $model->apply_balance,
            'accountlvl4_id' => $model->accountlvl4_id
        ];
    }

    // Relaciones
    
    /**
     * Include Accountlvl4
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountlvl4(Accountlvl5 $model)
    {
        return $this->item($model->accountlvl4, new Accountlvl4Transformer);
    }

    /**
     * Include Accountlvl5
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountslvl6(Accountlvl5 $model)
    {
        return $this->collection($model->accountslvl6, new Accountlvl6Transformer);
    }
}
