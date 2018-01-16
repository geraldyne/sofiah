<?php

/**
 *  @package        SOFIAH.App.Transformers.Operative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 09-10-2017.
 *  @version        1.0
 * 
 *  @final  
 */

 /**
 * Incluye la implementación de los siguientes Librerias
 */

namespace App\Transformers\Operative;

use App\Transformers\Administrative\PartnerTransformer;
use App\Entities\Operative\Guarantor;
use League\Fractal\TransformerAbstract;

/**
 * Class GuarantorTransformer.
 */

class GuarantorTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loans
     */
    protected $availableIncludes = [
        'loan',
        'partner'
    ];

    /**
     * @param Guarantor $model
     * @return array
     */
    public function transform(Guarantor $model)
    {
        return [
            'uuid'           => $model->uuid,
            'amount'         => $model->amount,
            'balance'        => $model->balance,
            'percentage'     => $model->percentage,
            'status'         => $model->status,
            'partner_id'     => $model->partner_id,
            'loan_id'        => $model->loan_id,
            'created_at'     => $model->created_at->toIso8601String(),
            'updated_at'     => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Loans
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoan(Guarantor $model)
    {
        return $this->item($model->loan, new LoansTransformer);
    }


    /**
     * Include Partners
     *
     * @return League\Fractal\ItemResource
     */
    public function includePartner(Guarantor $model)
    {
        return $this->item($model->partner, new PartnerTransformer);
    }

}
