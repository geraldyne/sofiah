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

use App\Entities\Operative\Specialfee;
use League\Fractal\TransformerAbstract;

/**
 * Class SpecialfeeTransformer.
 */

class SpecialfeeTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var policies
     * @var bonds
     * @var direction
     */
    protected $availableIncludes = [
        'loantypes',
        'specialfeedetails'
    ];

    /**
     * @param Specialfee $model
     * @return array
     */
    public function transform(Specialfee $model)
    {
        return [
            'uuid'                   => $model->uuid,
            'loantype_id'            => $model->loantype_id,
            'specialfeedetails_id'   => $model->specialfeedetails_id,
            'created_at'             => $model->created_at->toIso8601String(),
            'updated_at'             => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Loantypes
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoantypes(Specialfee $model)
    {
        return $this->item($model->loantypes, new LoantypesTransformer);
    }


    /**
     * Include Specialfeedetails
     *
     * @return League\Fractal\ItemResource
     */
    public function includeSpecialfeedetails(Specialfee $model)
    {
        return $this->collection($model->specialfeedetails, new SpecialfeedetailsTransformer);
    }


}
