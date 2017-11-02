<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Bankdetails;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class BankdetailsTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'bank',
        'partner',
        'employee'
    ];

    /**
     * @param Bankdetails $model
     * @return array
     */
    public function transform(Bankdetails $model)
    {
        return [

            'id'             => $model->uuid,
            'account_number' => $model->account_number,
            'account_type'   => $model->account_type,
            'bank_id'        => $model->bank_id,
            'created_at'     => $model->created_at->toIso8601String(),
            'updated_at'     => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones

    /**
     * Include Bank
     *
     * @return League\Fractal\ItemResource
     */
    public function includeBank(Bankdetails $model)
    {
        return $this->item($model->bank, new BankTransformer);
    }

    /**
     * Include Employee
     *
     * @return League\Fractal\ItemResource
     */
    public function includeEmployee(Bankdetails $model)
    {
        return $this->item($model->employee, new EmployeeTransformer);
    }
    
    /**
     * Include Partner
     *
     * @return League\Fractal\ItemResource
     */
    public function includePartner(Bankdetails $model)
    {
        return $this->item($model->partner, new PartnerTransformer);
    }
}
