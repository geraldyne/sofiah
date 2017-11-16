<?php

namespace App\Transformers;

use App\Entities\Association;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class AssociationTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'organisms',
        'direction',
        'employees',
        'accountsassociation'
    ];

    /**
     * @param Association $model
     * @return array
     */
    public function transform(Association $model)
    {
        return [

            'id' => $model->uuid,
            'name' => $model->name,
            'alias' => $model->alias,
            'sudeca' => $model->sudeca,
            'web_site' => $model->web_site,
            'email' => $model->email,
            'logo' => $model->logo,
            'phone' => $model->phone,
            'rif' => $model->rif,
            'direction_id' => $model->direction_id,
            'lock_date' => $model->lock_date,
            'time_to_reincorporate' => $model->time_to_reincorporate,
            'loan_time' => $model->loan_time,
            'percent_legal_reserve' => $model->percent_legal_reserve,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Direction
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDirection(Association $model)
    {
        return $this->item($model->direction, new Administrative\DirectionTransformer);
    }
    
    /**
     * Include Organisms
     *
     * @return League\Fractal\ItemResource
     */
    public function includeOrganisms(Association $model)
    {
        return $this->collection($model->organisms, new Administrative\OrganismTransformer);
    }

    /**
     * Include Employees
     *
     * @return League\Fractal\ItemResource
     */
    public function includeEmployees(Association $model)
    {
        return $this->collection($model->employees, new Administrative\EmployeeTransformer);
    }

    /**
     * Include Accountsassociation
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAccountsassociation(Association $model)
    {
        return $this->collection($model->accountsassociation, new Administrative\AccountassociationTransformer);
    }
}
