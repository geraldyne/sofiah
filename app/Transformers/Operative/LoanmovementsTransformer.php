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

use App\Entities\Operative\Loanmovements;
use League\Fractal\TransformerAbstract;

/**
 * Class LoanmovementsTransformer.
 */

class LoanmovementsTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loans
     */
    protected $availableIncludes = [
        'loan',
        'amortdefdetails',
        'loanamortmovements'
    ];

    /**
     * @param Loanmovements $model
     * @return array
     */
    public function transform(Loanmovements $model)
    {
        return [
            'id'                 => $model->uuid,
            'date_issue'         => $model->date_issue,
            'amount'             => $model->amount,
            'type'               => $model->type,
            'status'             => $model->status,
            'loan_id'            => $model->loan_id
            'created_at'         => $model->created_at->toIso8601String(),
            'updated_at'         => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Loan
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoan(Loanmovements $model)
    {
        return $this->item($model->loan, new LoanTransformer);
    }

    /**
     * Include amortdefdetails
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAmortdefdetails(Loanmovements $model)
    {
        return $this->item($model->amortdefdetails, new AmortdefdetailsTransformer);
    }

    /**
     * Include loanamortmovements
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoanamortmovements(Loanmovements $model)
    {
        return $this->collection($model->loanamortmovements, new LoanamortmovementsTransformer);
    }

}
