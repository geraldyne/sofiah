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

use App\Entities\Operative\Loanamortmovements;
use League\Fractal\TransformerAbstract;

/**
 * Class Amort_defTransformer.
 */

class LoanamortmovementsTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var loans
     * @var amort_def_details
     */
    protected $availableIncludes = [
        'loanmovements',
        'amortdefdetails'
    ];

    /**
     * @param Amort_def $model
     * @return array
     */
    public function transform(Loanamortmovements $model)
    {
        return [

            'id'                    => $model->uuid,
            'loanmovement_id'       => $model->loanmovement_id,
            'amortdefdetails_id'    => $model->amortdefdetails_id,
            'created_at'            => $model->created_at->toIso8601String(),
            'updated_at'            => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones

    /**
     * Include Loans
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoanmovements(Loanamortmovements $model)
    {
        return $this->item($model->loanmovements, new LoanmovementsTransformer);
    }

    /**
     * Include Loans
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAmortdefdetails(Loanamortmovements $model)
    {
        return $this->item($model->amortdefdetails, new AmortdefdetailsTransformer);
    }
}
