<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Bank;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class BankTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'bankdetails'
    ];

    /**
     * @param Bank $model
     * @return array
     */
    public function transform(Bank $model)
    {
        return [

            'id' => $model->uuid,
            'bank' => $model->bank
        ];
    }

    // Relaciones

    /**
     * Include Bankdetails
     *
     * @return League\Fractal\ItemResource
     */
    public function includeBankdetails(Bank $model)
    {
        return $this->item($model->bankdetails, new BankdetailsTransformer);
    }
}
