<?php

/**
 *  @package        SOFIAH.App.Transformers.Operative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 13-10-2017.
 *  @version        1.0
 * 
 *  @final  
 */

 /**
 * Incluye la implementación de los siguientes Librerias
 */

namespace App\Transformers\Operative;

use App\Entities\Operative\Loansgroups;
use League\Fractal\TransformerAbstract;

/**
 * Class LoansgroupsTransformer.
 */

class LoansgroupsTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loan_types
     * @var loan_type_groups
     */
    protected $availableIncludes = [
        'loanstype',
        'loantypegroups'
    ];

    /**
     * @param Loansgroups $model
     * @return array
     */
    public function transform(Loansgroups $model)
    {
        return [
            'uuid'              => $model->uuid,
            'name'              => $model->name,
            'loantype_id'       => $model->loantype_id,
            'loantypegroup_id'  => $model->loantypegroup_id,
            'created_at'        => $model->created_at->toIso8601String(),
            'updated_at'        => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Loanstypes
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoanstypes(Loansgroups $model)
    {
        return $this->item($model->loanstypes, new LoanstypesTransformer);
    }


    /**
     * Include Loantypegroups
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoantypegroups(Loansgroups $model)
    {
        return $this->item($model->loantypegroups, new LoantypegroupsTransformer);
    }


}
