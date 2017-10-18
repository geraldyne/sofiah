<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Accountlvl1;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class Accountlvl1Transformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'accountslvl2'
    ];

    /**
     * @param Accountlvl1 $model
     * @return array
     */
    public function transform(Accountlvl1 $model)
    {
        return [

            'id' => $model->uuid,
            'account_code' => $model->account_code,
            'account_name' => $model->account_name,
            'account_type' => $model->account_type,
            'balance_type' => $model->balance_type,
            'apply_balance' => $model->apply_balance
        ];
    }

    // Relaciones
    
    /**
     * Include Accountlvl2
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountslvl2(Accountlvl1 $model)
    {
        return $this->collection($model->accountslvl2, new Accountlvl2Transformer);
    }
}
