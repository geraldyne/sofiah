<?php

namespace App\Transformers\Assets;

use App\Entities\Assets\Asset;
use League\Fractal\TransformerAbstract;

/**
 * Class AssetTransformer.
 */
class AssetTransformer extends TransformerAbstract
{
    /**
     * @param \App\Entities\Assets\Asset $model
     * @return array
     */
    public function transform(Asset $model)
    {
        return [
            'id'         => $model->uuid,
            'user_id'    => $model->user_id,
            'type'       => $model->type,
            'path'       => $model->path,
            'mime'       => $model->mime,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }

}
