<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Accountlvl4;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class Accountlvl4Transformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'accountlvl3',
        'accountslvl5'
    ];

    /**
     * @param Accountlvl $model
     * @return array
     */
    public function transform(Accountlvl4 $model)
    {
        return [

            'id' => $model->uuid,
            'account_code' => $model->account_code,
            'account_name' => $model->account_name,
            'account_type' => $model->account_type,
            'balance_type' => $model->balance_type,
            'apply_balance' => $model->apply_balance,
            'accountlvl3_id' => $model->accountlvl3_id
        ];
    }

    // Relaciones
    
    /**
     * Include Accountlvl3
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountlvl3(Accountlvl4 $model)
    {
        return $this->item($model->accountlvl3, new Accountlvl3Transformer);
    }

    /**
     * Include Accountlvl5
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountslvl5(Accountlvl4 $model)
    {
        return $this->collection($model->accountslvl5, new Accountlvl5Transformer);
    }
}
