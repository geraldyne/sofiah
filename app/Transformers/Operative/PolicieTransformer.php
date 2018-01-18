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

use App\Entities\Operative\Policie;
use League\Fractal\TransformerAbstract;

/**
 * Class PoliciesTransformer.
 */

class PolicieTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loan
     * @var providers
     */
    protected $availableIncludes = [
        'loan',
        'provider'
    ];

    /**
     * @param Policie $model
     * @return array
     */
    public function transform(Policie $model)
    {
        return [
            'id'             => $model->uuid,
            'number'         => $model->number,
            'type'           => $model->type,
            'issue_date'     => $model->issue_date,
            'due_date'       => $model->due_date,
            'amount'         => $model->amount,
            'status'         => $model->status,
            'provider_id'    => $model->provider_id,
            'loan_id'        => $model->loan_id,
            'created_at'     => $model->created_at->toIso8601String(),
            'updated_at'     => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Loan
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoan(Policie $model)
    {
        return $this->item($model->loan, new LoanTransformer);
    }


    /**
     * Include Provider
     *
     * @return League\Fractal\ItemResource
     */
    public function includeProvider(Policie $model)
    {
        return $this->item($model->provider, new ProvidersTransformer);
    }


}
