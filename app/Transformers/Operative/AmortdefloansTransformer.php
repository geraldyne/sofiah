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

use App\Entities\Operative\Amortdefloans;
use League\Fractal\TransformerAbstract;

/**
 * Class AmortdefloansTransformer.
 */

class AmortdefloansTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loans
     */
    protected $availableIncludes = [
        'loan'
    ];

    /**
     * @param Amortdefloans $model
     * @return array
     */
    public function transform(Amortdefloans $model)
    {
        return [
            'uuid'                     => $model->uuid,
            'quota_amount'             => $model->quota_amount,
            'quota_date'               => $model->quota_date,
            'quota_amount_ordinary'    => $model->quota_amount_ordinary,
            'capital_quota_ordinary'   => $model->capital_quota_ordinary,
            'interests_quota_ordinary' => $model->interests_quota_ordinary,
            'capital_quota_special'    => $model->capital_quota_special,
            'amount_quota_special'     => $model->amount_quota_special,
            'balance_quota_ordinary'   => $model->balance_quota_ordinary,
            'balance_quota_special'    => $model->balance_quota_special,
            'loan_id'                  => $model->loan_id,
            'created_at'               => $model->created_at->toIso8601String(),
            'updated_at'               => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Amortization
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoans(Amortdefloans $model)
    {
        return $this->item($model->loan, new LoansTransformer);
    }


}
