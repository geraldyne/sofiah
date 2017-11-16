<?php

namespace App\Transformers\Administrative;

use App\Entities\Administrative\Organism;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer.
 */
class OrganismTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'direction',
        'association',
        'partners',
        'assetstypecodes',
        'loantypecodes',
        'issues',
        'amortdefloans'
    ];

    /**
     * @param Organism $model
     * @return array
     */
    public function transform(Organism $model)
    {
        return [

            'id' => $model->uuid,
            'name' => $model->name,
            'alias' => $model->alias,
            'email' => $model->email,
            'web_site' => $model->web_site,
            'zone' => $model->zone,
            'contact' => $model->contact,
            'slug' => $model->slug,
            'phone' => $model->phone,
            'rif' => $model->rif,
            'direction_id' => $model->direction_id,
            'payroll_type' => $model->payroll_type,
            'status' => $model->status,
            'disponibility' => $model->disponibility,
            'percentage_employers_contribution' => $model->percentage_employers_contribution,
            'percentage_individual_contribution' => $model->percentage_individual_contribution,
            'percentage_voluntary_contribution' => $model->percentage_voluntary_contribution,
            'association_id' => $model->association_id,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String()
        ];
    }

    // Relaciones
    
    /**
     * Include Direction
     *
     * @return League\Fractal\ItemResource
     */
    public function includeDirection(Organism $model)
    {
        return $this->item($model->direction, new DirectionTransformer);
    }

    /**
     * Include Association
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAssociation(Organism $model)
    {
        return $this->item($model->association, new AssociationTransformer);
    }
    
    /**
     * Include Partner
     *
     * @return League\Fractal\ItemResource
     */
    public function includePartners(Organism $model)
    {
        return $this->item($model->partners, new PartnerTransformer);
    }

    /**
     * Include Assetstypecodes
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAssetstypecodes(Organism $model)
    {
        return $this->collection($model->assetstypecodes, new AssetstypecodesTransformer);
    }

    /**
     * Include Assetstypecodes
     *
     * @return League\Fractal\ItemResource
     */
    public function includeLoantypecodes(Organism $model)
    {
        return $this->collection($model->loantypecodes, new LoantypecodesTransformer);
    }

    /**
     * Include Issues
     *
     * @return League\Fractal\ItemResource
     */
    public function includeIssues(Organism $model)
    {
        return $this->collection($model->issues, new IssueTransformer);
    }

    /**
     * Include Amortdefloans
     *
     * @return League\Fractal\ItemResource
     */
    public function includeAmortdefloans(Organism $model)
    {
        return $this->collection($model->amortdefloans, new AmortdefloansTransformer);
    }
}
