<?php

namespace App\Transformers\Users;

use App\Entities\Administrative\Preference;
use League\Fractal\TransformerAbstract;

/**
 * Class PermissionTransformer.
 */
class PreferenceTransformer extends TransformerAbstract
{
    /**
     * @param Preference $model
     * @return array
     */
    public function transform(Preference $model)
    {
        return [
            'id' => $model->id,
            'style' => $model->style,
            'lang' => $model->lang,
            'zoom' => $model->zoom,
            'user_id' => $model->user_id
        ];
    }
}
