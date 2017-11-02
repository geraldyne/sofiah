<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Accountlvl6;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class Accountlvl6Transformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'accountlvl5',
        'accountingintegration',
        'cashflow',
        'heritagechange',
        'dailymovementdetails',
        'accountsassociation'
    ];

    /**
     * @param Account_lvl6 $model
     * @return array
     */
    public function transform(Accountlvl6 $model)
    {
        return [

            'id' => $model->uuid,
            'account_code' => $model->account_code,
            'account_name' => $model->account_name,
            'account_type' => $model->account_type,
            'balance_type' => $model->balance_type,
            'apply_balance' => $model->apply_balance,
            'accountlvl5_id' => $model->accountlvl5_id,
            'cash_flow' => $model->cash_flow
        ];
    }

    // Relaciones
    
    /**
     * Include Account_lvl5
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountlvl5(Accountlvl6 $model)
    {
        return $this->item($model->accountlvl5, new Accountlvl5Transformer);
    }

    /**
     * Include Accounting_integration
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountingintegration(Accountlvl6 $model)
    {
        return $this->collection($model->accountingintegration, new AccountingintegrationTransformer);
    }

    /**
     * Include Cash_flow
     *
     * @return League\Fractal\ItemResource
     */
    public function includeCashflow(Accountlvl6 $model)
    {
        return $this->collection($model->cashflow, new CashflowTransformer);
    }

    /**
     * Include Heritage_change
     *
     * @return League\Fractal\ItemResource
     */
    public function includeHeritagechange(Accountlvl6 $model)
    {
        return $this->collection($model->heritagechange, new HeritagechangeTransformer);
    }

    /**
     * Include Daily_movement_details
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDailymovementdetails(Accountlvl6 $model)
    {
        return $this->collection($model->dailymovementdetails, new DailymovementdetailsTransformer);
    }

    /**
     * Include Accountsassociation
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountsassociation(Accountlvl6 $model)
    {
        return $this->collection($model->accountsassociation, new AccountassociationTransformer);
    }
}
