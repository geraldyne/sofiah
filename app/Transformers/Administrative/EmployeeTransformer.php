<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Employee;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class EmployeeTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'association',
        'direction',
        'user',
        'bankdetails'
    ];

    /**
     * @param Employee $model
     * @return array
     */
    public function transform(Employee $model)
    {
        return [

            'id' => $model->uuid,
            'employee_code' => $model->employee_code,
            'names' => $model->names,
            'lastnames' => $model->lastnames,
            'email' => $model->email,
            'department' => $model->department,
            'rif' => $model->rif,
            'id_card' => $model->id_card,
            'phone' => $model->phone,
            'nationality' => $model->nationality,
            'status' => $model->status,
            'birthdate' => $model->birthdate,
            'date_of_admision' => $model->date_of_admision,
            'retirement_date' => $model->retirement_date,
            'user_id' => $model->user_id,
            'direction_id' => $model->direction_id,
            'association_id' => $model->association_id,
            'bankdetails_id' => $model->bankdetails_id,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Association
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAssociation(Employee $model)
    {
        return $this->item($model->association, new AssociationTransformer);
    }

    /**
     * Include Direction
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDirection(Employee $model)
    {
        return $this->item($model->direction, new DirectionTransformer);
    }
    
    /**
     * Include User
     *
     * @return League\Fractal\ItemResource
     */
    public function includeUser(Employee $model)
    {
        return $this->item($model->user, new UserTransformer);
    }

    /**
     * Include Bankdetails
     *
     * @return League\Fractal\ItemResource
     */
    public function includeBankdetails(Employee $model)
    {
        return $this->collection($model->bankdetails, new BankdetailsTransformer);
    }
}
