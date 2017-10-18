<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Cashflow;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class CashflowTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'accounts'
    ];

    /**
     * @param Cash_flow $model
     * @return array
     */
    public function transform(Cashflow $model)
    {
        return [

            'id' => $model->uuid,
            'concept' => $model->concept,
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
    public function includeAccounts(Cashflow $model)
    {
        return $this->collection($model->accounts, new Accountlvl6Transformer);
    }
}
