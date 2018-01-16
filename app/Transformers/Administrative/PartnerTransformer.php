<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Partner;
use League\Fractal\TransformerAbstract;

use App\Transformers\Users\UserTransformer;


/**
 * Class UserTransformer.
 */

class partnerTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'organism',
        'user',
        'bankdetails',
        'managers',
        'dividends',
        'guarantors',
        'loans',
        'assetsmovements',
        'assetsbalance'
    ];

    /**
     * @param Partner $model
     * @return array
     */
    public function transform(Partner $model)
    {
        return [

            'id' => $model->uuid,
            'employee_code' => $model->employee_code,
            'names' => $model->names,
            'lastnames' => $model->lastnames,
            'email' => $model->email,
            'title' => $model->title,
            'local_phone' => $model->local_phone,
            'retirement_date' => $model->retirement_date,
            'retirement_last_date' => $model->retirement_last_date,
            'nationality' => $model->nationality,
            'status' => $model->status,
            'account_code' => $model->account_code,
            'id_card' => $model->id_card,
            'phone' => $model->phone,
            'bankdetails_id' => $model->bankdetails_id,
            'user_id' => $model->user_id,
            'organism_id' => $model->organism_id,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Organism
     *
     * @return League\Fractal\ItemResource
     */
    public function includeOrganism(Partner $model)
    {
        return $this->item($model->organism, new OrganismTransformer);
    }

    /**
     * Include User
     *
     * @return League\Fractal\ItemResource
     */
    public function includeUser(Partner $model)
    {
        return $this->item($model->user, new UserTransformer);
    }

    /**
     * Include Bankdetails
     *
     * @return League\Fractal\ItemResource
     */
    public function includeBankdetails(Partner $model)
    {
        return $this->item($model->bankdetails, new BankdetailsTransformer);
    }
    
    /**
     * Include Manager
     *
     * @return League\Fractal\ItemResource
     */
    public function includeManager(Partner $model)
    {
        return $this->collection($model->managers, new ManagerTransformer);
    }

    /**
     * Include Dividends
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDividends(Partner $model)
    {
        return $this->collection($model->dividends, new DividendTransformer);
    }

    /**
     * Include Loan
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoans(Partner $model)
    {
        return $this->collection($model->loans, new LoanTransformer);
    }

    /**
     * Include Guarantor
     *
     * @return League\Fractal\ItemResource
     */
    public function includeGuarantor(Partner $model)
    {
        return $this->collection($model->guarantors, new GuarantorTransformer);
    }


    /**
     * Include Loans
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAssetsmovements(Partner $model)
    {
        return $this->collection($model->assetsmovements, new AssetsmovementsTransformer);
    }

    /**
     * Include Loans
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAssetsbalance(Partner $model)
    {
        return $this->item($model->assetsbalance, new AssetsbalanceTransformer);
    }

}
