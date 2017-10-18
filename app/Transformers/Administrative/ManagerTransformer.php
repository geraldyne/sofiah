<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Manager;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class ManagerTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'partner',
        'charge'
    ];

    /**
     * @param Manager $model
     * @return array
     */
    public function transform(Manager $model)
    {
        return [

            'id' => $model->uuid,
            'partner_id' => $model->partner_id,
            'charge_id' => $model->charge_id,
            //'created_at' => $model->created_at->toIso8601String(),
            //'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Partner
     *
     * @return League\Fractal\ItemResource
     */
    public function includePartner(Manager $model)
    {
        return $this->item($model->partner, new PartnerTransformer);
    }

    /**
     * Include Charge
     *
     * @return League\Fractal\ItemResource
     */
    public function includeCharge(Manager $model)
    {
        return $this->item($model->charge, new ChargeTransformer);
    }
}
