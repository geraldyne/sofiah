<?php

namespace App\Transformers\Users;

use App\Entities\User;
use League\Fractal\TransformerAbstract;
use App\Transformers\Administrative\PartnerTransformer;
use App\Transformers\Assets\AssetTransformer;

/**
 * Class UserTransformer.
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['roles', 'preference', 'asset'];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    
    protected $availableIncludes = [
        'movement_origin',
        'movement_apply',
        'partner',
        'employee'
    ];

    /**
     * @param User $model
     * @return array
     */
    public function transform(User $model)
    {
        return [
            'id' => $model->uuid,
            'name' => $model->name,
            'email' => $model->email,
            'status' => $model->status,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }

    /**
     * @param User $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoles(User $model)
    {
        return $this->collection($model->roles, new RoleTransformer());
    }


    /**
     * @param User $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAsset(User $model)
    {
        return $this->item($model->asset, new AssetTransformer());
    }

    /**/

    /**
     * Include Movement_origin
     *
     * @return League\Fractal\ItemResource
     */
    public function includeMovement_origin(User $model)
    {
        $origin = $model->movement_origin;

        return $this->collection($origin, new Daily_movementTransformer);
    }

    /**
     * Include Movement_apply
     *
     * @return League\Fractal\ItemResource
     */
    public function includeMovement_apply(User $model)
    {
        return $this->collection($model->movement_apply, new Daily_movementTransformer);
    }

    /**
     * Include Partner
     *
     * @return League\Fractal\ItemResource
     */
    public function includePartner(User $model)
    {
        return $this->collection($model->partner, new PartnerTransformer);
    }

    /**
     * Include Employee
     *
     * @return League\Fractal\ItemResource
     */
    public function includeEmployee(User $model)
    {
        return $this->collection($model->employee, new EmployeeTransformer);
    }

    /**
     * Include Preference
     *
     * @return League\Fractal\ItemResource
     */
    public function includePreference(User $model)
    {
        return $this->item($model->preference, new PreferenceTransformer);
    }
}
