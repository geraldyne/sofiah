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

use App\Entities\Operative\Loantypes;
use League\Fractal\TransformerAbstract;

/**
 * Class LoantypesTransformer.
 */

class LoantypesTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var oan_type_groups
     * @var loantype_codes
     * @var loans
     */
    protected $availableIncludes = [
        'loansgroups',
        'loantypecodes',
        'loans',
        'specialfees'
    ];

    /**
     * @param Loantype $model
     * @return array
     */
    public function transform(Loantypes $model)
    {
        return [
            'id'                                  => $model->uuid,
            'name'                                => $model->name,
            'guarantor'                           => $model->guarantor,
            'guarantee'                           => $model->guarantee,
            'guarantee_comision'                  => $model->guarantee_comision,
            'refinancing'                         => $model->refinancing,
            'valid_availability'                  => $model->valid_availability,
            'affect_availability'                 => $model->affect_availability,
            'special_fees'                        => $model->special_fees,
            'third_party_payment'                 => $model->third_party_payment,
            'paid_capacity'                       => $model->paid_capacity,
            'valid_policy'                        => $model->valid_policy,
            'web_based'                           => $model->web_based,
            'administrative_expenditure'          => $model->administrative_expenditure,
            'deduct_administrative_expense'       => $model->deduct_administrative_expense,
            'interest'                            => $model->interest,
            'bond_commission'                     => $model->bond_commission,
            'refinancing_amount'                  => $model->refinancing_amount,
            'percent_special_quotes'              => $model->percent_special_quotes,
            'percent_administrative_expenditure'  => $model->percent_administrative_expenditure,
            'refinance_days'                      => $model->refinance_days,
            'term'                                => $model->term,
            'number_guarantors'                   => $model->number_guarantors,
            'receivable_id'                       => $model->receivable_id,
            'billtopay_id'                        => $model->billtopay_id,
            'incomeaccount_id'                    => $model->incomeaccount_id,
            'max_amount'                          => $model->max_amount,
            'operatingexpenseaccount_id'          => $model->operatingexpenseaccount_id,
            'status'                              => $model->status,
            'created_at'                          => $model->created_at->toIso8601String(),
            'updated_at'                          => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Loansgroups
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoansgroups(Loantypes $model)
    {
        return $this->item($model->loansgroups, new LoansgroupsTransformer);
    }


    /**
     * Include Loantypecodes
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoantypecodes(Loantypes $model)
    {
        return $this->collection($model->loantypecodes, new LoantypecodesTransformer);
    }


    /**
     * Include Loans
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoans(Loantypes $model)
    {
        return $this->collection($model->loans, new LoanTransformer);
    }


    /**
     * Include Specialfee
     *
     * @return League\Fractal\ItemResource
     */
    public function includeSpecialfees(Loantypes $model)
    {
        return $this->collection($model->specialfees, new SpecialfeeTransformer);
    }


}
