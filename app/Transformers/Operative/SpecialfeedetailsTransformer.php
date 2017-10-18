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

use App\Entities\Operative\Specialfeedetails;
use League\Fractal\TransformerAbstract;

/**
 * Class SpecialfeedetailsTransformer.
 */

class SpecialfeedetailsTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var policies
     * @var bonds
     * @var direction
     */
    protected $availableIncludes = [
        'specialfee'
    ];

    /**
     * @param Specialfeedetails $model
     * @return array
     */
    public function transform(Specialfeedetails $model)
    {
        return [
            'uuid'            => $model->uuid,
            'month'           => $model->month,
            'created_at'      => $model->created_at->toIso8601String(),
            'updated_at'      => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Specialfee
     *
     * @return League\Fractal\ItemResource
     */
    public function includeSpecialfee(Specialfeedetails $model)
    {
        return $this->item($model->specialfee, new SpecialfeeTransformer);
    }


}
