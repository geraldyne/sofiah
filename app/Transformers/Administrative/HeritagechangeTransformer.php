<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Heritagechange;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class HeritagechangeTransformer extends TransformerAbstract
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
     * @param Heritagechange $model
     * @return array
     */
    public function transform(Heritagechange $model)
    {
        return [

            'id' => $model->uuid,
            'concept' => $model->concept
        ];
    }

    // Relaciones
    
    /**
     * Include Accounts
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccounts(Heritagechange $model)
    {
        return $this->collection($model->accounts, new AccountTransformer);
    }
}
