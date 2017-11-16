<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Accountassociation;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class AccountassociationTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'accountlvl6',
        'association'
    ];

    /**
     * @param Accounting_integration $model
     * @return array
     */
    public function transform(Accountassociation $model)
    {
        return [

            'id' => $model->uuid,
            'association_id' => $model->association_id,
            'accountlvl6_id' => $model->accountlvl6_id,
            'description'    => $model->description,
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
    public function includeAccountlvl6(Accountassociation $model)
    {
        return $this->item($model->accountlvl6, new Accountlvl6Transformer);
    }

    /**
     * Include Accounts
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAssociation(Accountassociation $model)
    {
        return $this->item($model->association, new AssociationTransformer);
    }
}
