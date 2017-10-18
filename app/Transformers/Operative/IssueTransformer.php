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

use App\Entities\Operative\Issue;
use League\Fractal\TransformerAbstract;

/**
 * Class IssueTransformer.
 */

class IssueTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loans
     */
    protected $availableIncludes = [
        'organism',
        'issuedetails'
    ];

    /**
     * @param Issue $model
     * @return array
     */
    public function transform(Issue $model)
    {
        return [
            'uuid'           => $model->uuid,
            'date_issue'     => $model->date_issue,
            'amount'         => $model->amount,
            'status'         => $model->status,
            'organisms_id'   => $model->organisms_id,
            'created_at'     => $model->created_at->toIso8601String(),
            'updated_at'     => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Issuedetails
     *
     * @return League\Fractal\ItemResource
     */
    public function includeIssuedetails(Issue $model)
    {
        return $this->item($model->issuedetails, new IssuedetailsTransformer);
    }


    /**
     * Include Organism
     *
     * @return League\Fractal\ItemResource
     */
    public function includeOrganism(Issue $model)
    {
        return $this->item($model->organism, new OrganismTransformer);
    }


}
