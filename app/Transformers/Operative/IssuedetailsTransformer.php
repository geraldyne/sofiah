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

use App\Entities\Operative\Issuedetails;
use League\Fractal\TransformerAbstract;

/**
 * Class IssuedetailsTransformer.
 */

class IssuedetailsTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loans
     */
    protected $availableIncludes = [
        'amortdefloans',
        'loantypecodes',
        'issue',
        'amortdefdetails'
    ];

    /**
     * @param Issuedetails $model
     * @return array
     */
    public function transform(Issuedetails $model)
    {
        return [
            'id'                   => $model->uuid,
            'amount'               => $model->amount,
            'capital'              => $model->capital,
            'interests'            => $model->interests,
            'loan_balance'         => $model->loan_balance,
            'quota_balance'        => $model->quota_balance,
            'quota_date'           => $model->quota_date,
            'type'                 => $model->type,
            'quota_number'         => $model->quota_number,
            'days'                 => $model->days,
            'amortdefloan_id'      => $model->amortdefloan_id,
            'loantypecode_id'      => $model->loantypecode_id,
            'issue_id'             => $model->issue_id,
            'created_at'           => $model->created_at->toIso8601String(),
            'updated_at'           => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Amortdefloans
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAmortdefloans(Issuedetails $model)
    {
        return $this->item($model->amortdefloans, new AmortdefloansTransformer);
    }

    /**
     * Include Loantypecodes
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoantypecodes(Issuedetails $model)
    {
        return $this->item($model->loantypecodes, new LoantypecodesTransformer);
    }

    /**
     * Include Issue
     *
     * @return League\Fractal\ItemResource
     */
    public function includeIssue(Issuedetails $model)
    {
        return $this->item($model->issue, new IssuesTransformer);
    }


    /**
     * Include Amortdefdetails
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAmortdefdetails(Issuedetails $model)
    {
        return $this->item($model->amortdefdetails, new AmortdefdetailsTransformer);
    }


}
