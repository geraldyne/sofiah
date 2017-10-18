<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Accountlvl3;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class Accountlvl3Transformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'accountlvl2',
        'accountslvl4'
    ];

    /**
     * @param Accountlvl $model
     * @return array
     */
    public function transform(Accountlvl3 $model)
    {
        return [

            'id' => $model->uuid,
            'account_code' => $model->account_code,
            'account_name' => $model->account_name,
            'account_type' => $model->account_type,
            'balance_type' => $model->balance_type,
            'apply_balance' => $model->apply_balance,
            'accountlvl2_id' => $model->accountlvl2_id
        ];
    }

    // Relaciones
    
    /**
     * Include Accountlvl2
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountlvl2(Accountlvl3 $model)
    {
        return $this->item($model->accountlvl2, new Accountlvl2Transformer);
    }

    /**
     * Include Accountlvl4
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountslvl4(Accountlvl3 $model)
    {
        return $this->collection($model->accountslvl4, new Accountlvl4Transformer);
    }
}
