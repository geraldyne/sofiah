<?php

namespace App\Transformers\Administrative;

use App\Transformers\Operative\LoantypesTransformer;
use App\Entities\Administrative\Accountingintegration;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class AccountingintegrationTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'accountlvl6',
        'loantypes'
    ];

    /**
     * @param Accounting_integration $model
     * @return array
     */
    public function transform(Accountingintegration $model)
    {
        return [

            'id' => $model->uuid,
            'accounting_integration_name' => $model->accounting_integration_name,
            'accountlvl6_id' => $model->accountlvl6_id,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Accounts
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountlvl6(Accountingintegration $model)
    {
        return $this->item($model->accountlvl6, new Accountlvl6Transformer);
    }

    /**
     * Include Accounts
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoantypes(Accountingintegration $model)
    {
        return $this->collection($model->loantypes, new LoantypesTransformer);
    }
}
