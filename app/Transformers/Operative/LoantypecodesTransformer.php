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

use App\Entities\Operative\Loantypecodes;
use League\Fractal\TransformerAbstract;

/**
 * Class LoantypecodesTransformer.
 */

class LoantypecodesTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loan_types
     * @var organism
     */
    protected $availableIncludes = [
        'loantypes',
        'loan',
        'organism'
    ];

    /**
     * @param Loantypecodes $model
     * @return array
     */
    public function transform(Loantypecodes $model)
    {
        return [
            'uuid'           => $model->uuid,
            'loan_code'      => $model->loan_code,
            'loantypes_id'    => $model->loantypes_id,
            'organism_id'    => $model->organism_id,
            'created_at'     => $model->created_at->toIso8601String(),
            'updated_at'     => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Loantypes
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoantypes(Loantypecodes $model)
    {
        return $this->item($model->loantypes, new LoantypesTransformer);
    }


    /**
     * Include Loan
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoan(Loantypecodes $model)
    {
        return $this->item($model->loan, new LoanTransformer);
    }


    /**
     * Include Organism
     *
     * @return League\Fractal\ItemResource
     */
    public function includeOrganism(Loantypecodes $model)
    {
        return $this->item($model->organism, new OrganismTransformer);
    }

}
