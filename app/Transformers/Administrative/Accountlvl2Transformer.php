<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Accountlvl2;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class Accountlvl2Transformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'accountlvl1',
        'accountslvl3'
    ];

    /**
     * @param Accountlvl2 $model
     * @return array
     */
    public function transform(Accountlvl2 $model)
    {
        return [

            'id' => $model->uuid,
            'account_code' => $model->account_code,
            'account_name' => $model->account_name,
            'account_type' => $model->account_type,
            'balance_type' => $model->balance_type,
            'apply_balance' => $model->apply_balance,
            'accountlvl1_id' => $model->accountlvl1_id
        ];
    }

    // Relaciones
    
    /**
     * Include Accountlvl1
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountlvl1(Accountlvl2 $model)
    {
        return $this->item($model->accountlvl1, new Accountlvl1Transformer);
    }

    /**
     * Include Accountlvl3
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountslvl3(Accountlvl2 $model)
    {
        return $this->collection($model->accountslvl3, new Accountlvl3Transformer);
    }
}
