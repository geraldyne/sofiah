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
use App\Entities\Operative\Loan;
use League\Fractal\TransformerAbstract;

/**
 * Class LoanTransformer.
 */

class LoanTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loan_types
     * @var loantypecodes
     * @var amortdefloan
     * @var bonds
     * @var guarantors
     * @var partners
     * @var loan_movements
     */
    protected $availableIncludes = [
        'loantypes',
        'amortdefloans',
        'policies',
        'bonds',
        'guarantors',
        'partner',
        'loanmovements'
    ];

    /**
     * @param Loan $model
     * @return array
     */
    public function transform(Loan $model)
    {
        return [
            'id'                          => $model->uuid,
            'issue_date'                  => $model->issue_date,
            'amount'                      => $model->amount,
            'rate'                        => $model->rate,
            'balance'                     => $model->balance,
            'administrative_expenditure'  => $model->administrative_expenditure,
            'fee_frequency'               => $model->fee_frequency,
            'status'                      => $model->status,
            'destination'                 => $model->destination,
            'monthly_fees'                => $model->monthly_fees,
            'loantypes_id'                => $model->loantypes_id,
            'partner_id'                  => $model->partner_id,
            'created_at'                  => $model->created_at->toIso8601String(),
            'updated_at'                  => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Loantypes
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoantypes(Loan $model)
    {
        return $this->item($model->loantypes, new LoantypeTransformer);
    }


    /**
     * Include Loantypecodes
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoantypecodes(Loan $model)
    {
        return $this->item($model->loantypecodes, new LoantypecodesTransformer);
    }


    /**
     * Include Amortdefloans
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAmortdefloans(Loan $model)
    {
        return $this->collection($model->amortdefloans, new AmortdefloansTransformer);
    }

    /**
     * Include Policies
     *
     * @return League\Fractal\ItemResource
     */
    public function includePolicies(Loan $model)
    {
        return $this->collection($model->policies, new PolicieTransformer);
    }


    /**
     * Include Bonds
     *
     * @return League\Fractal\ItemResource
     */
    public function includeBonds(Loan $model)
    {
        return $this->collection($model->bonds, new BondTransformer);
    }


    /**
     * Include Guarantors
     *
     * @return League\Fractal\ItemResource
     */
    public function includeGuarantors(Loan $model)
    {
        return $this->collection($model->guarantors, new GuarantorTransformer);
    }


    /**
     * Include Partners
     *
     * @return League\Fractal\ItemResource
     */
    public function includePartner(Loan $model)
    {
        return $this->item($model->partner, new PartnerTransformer);
    }


    /**
     * Include Loanmovements
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoanmovements(Loan $model)
    {
        return $this->collection($model->loanmovements, new LoanmovementsTransformer);
    }

}
