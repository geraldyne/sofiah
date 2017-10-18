<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Dividend;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class DividendTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'partner',
        'accountingyear'
    ];

    /**
     * @param Dividend $model
     * @return array
     */
    public function transform(Dividend $model)
    {
        return [

            'id' => $model->uuid,
            'registration_date' => $model->registration_date,
            'assets_associated' => $model->assets_associated,
            'dividends' => $model->dividends,
            'assets_association' => $model->assets_association,
            'factor' => $model->factor,
            'status' => $model->status,
            'partner_id' => $model->partner_id,
            'accounting_id' => $model->accounting_id,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Partner
     *
     * @return League\Fractal\ItemResource
     */
    public function includePartner(Dividend $model)
    {
        return $this->item($model->partner, new PartnerTransformer);
    }

    /**
     * Include Accountingyear
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountingyear(Dividend $model)
    {
        return $this->item($model->accountingyear, new AccountingyearTransformer);
    }
}
