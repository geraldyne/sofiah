<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Direction;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class DirectionTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'city',
        'associations',
        'organism',
        'employee'
    ];

    /**
     * @param Direction $model
     * @return array
     */
    public function transform(Direction $model)
    {
        return [

            'id' => $model->uuid,
            'direction' => $model->direction,
            'city_id' => $model->city_id
        ];
    }

    // Relaciones
    
    /**
     * Include City
     *
     * @return League\Fractal\ItemResource
     */
    public function includeCity(Direction $model)
    {
        return $this->item($model->city, new CityTransformer);
    }

    /**
     * Include Associations
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAssociations(Direction $model)
    {
        return $this->item($model->associations, new AssociationTransformer);
    }

    /**
     * Include Organism
     *
     * @return League\Fractal\ItemResource
     */
    public function includeOrganism(Direction $model)
    {
        return $this->item($model->organism, new OrganismTransformer);
    }

    /**
     * Include Employee
     *
     * @return League\Fractal\ItemResource
     */
    public function includeEmployee(Direction $model)
    {
        return $this->item($model->employee, new EmployeeTransformer);
    }
}
