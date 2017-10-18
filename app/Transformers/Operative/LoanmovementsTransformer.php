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
        'loans',
        'issuedetails'
    ];

    /**
     * @param Loanmovements $model
     * @return array
     */
    public function transform(Loanmovements $model)
    {
        return [
            'uuid'           => $model->uuid,
            'date_issue'     => $model->date_issue,
            'amount'         => $model->amount,
            'type'           => $model->type,
            'status'         => $model->status,
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
    public function includeLoans(Loanmovements $model)
    {
        return $this->item($model->loans, new LoansTransformer);
    }

    /**
     * Include Issuedetails
     *
     * @return League\Fractal\ItemResource
     */
    public function includeIssuedetails(Loanmovements $model)
    {
        return $this->item($model->issuedetails, new IssuedetailsTransformer);
    }

}
