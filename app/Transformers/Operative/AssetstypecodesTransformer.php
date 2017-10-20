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

use App\Entities\Operative\Assetstypecodes;
use League\Fractal\TransformerAbstract;

/**
 * Class Amort_defTransformer.
 */

class AssetstypecodesTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loans
     * @var amort_def_details
     */
    protected $availableIncludes = [
        'organism'
    ];

    /**
     * @param Amort_def $model
     * @return array
     */
    public function transform(Assetstypecodes $model)
    {
        return [

            'uuid'                  => $model->uuid,
            'assets_organisms_code' => $model->assets_organisms_code,
            'type'                  => $model->type,
            'organism_id'           => $model->organism_id,
            'created_at'            => $model->created_at->toIso8601String(),
            'updated_at'            => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones

    /**
     * Include Organism
     *
     * @return League\Fractal\ItemResource
     */
    public function includeOrganism(Assetstypecodes $model)
    {
        return $this->item($model->organism, new OrganismTransformer);
    }
}
