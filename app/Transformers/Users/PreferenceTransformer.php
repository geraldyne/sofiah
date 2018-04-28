<?php

namespace App\Transformers\Users;

use App\Entities\Preference;
use League\Fractal\TransformerAbstract;

/**
 * Class PermissionTransformer.
 */
class PreferenceTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'user',
    ];

    /**
     * @param Preference $model
     * @return array
     */
    public function transform(Preference $model)
    {
        return [
            'id'      => $model->uuid,
            'style'   => $model->style,
            'lang'    => $model->lang,
            'zoom'    => $model->zoom,
            'user_id' => $model->user_id
        ];
    }

    /**
     * Include User
     *
     * @return League\Fractal\ItemResource
     */
    public function includeUser(User $model)
    {
        return $this->collection($model->user, new UserTransformer);
    }
}
