<?php

/**
 *  @package        SOFIAH.App.Entities.Operative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 16-07-2017.
 *  @version        1.0
 * 
 *  @final  
 */

 /**
 * Incluye la implementación de los siguientes Librerias
 */

namespace App\Entities\Operative;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Support\UuidScopeTrait;
use Webpatser\Uuid\Uuid;

use App\Entities\Operative\Loan;
use App\Entities\Operative\Issuedetails;
use App\Entities\Administrative\Organism;


/**
 *  Modelo de Amortizacion Prestamo
 */

class Amortdefloans extends Model {

    use Notifiable, UuidScopeTrait;
    
    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "amortdef_loans";
    

    /**
     * The attributes that are mass assignable.
     *
     * @var Date    quota_amount
     * @var Double  quota_date
     * @var Double  quota_amount_ordinary
     * @var Double  capital_quota_ordinary          
     * @var Double  interests_quota_ordinary   
     * @var Enum    capital_quota_special
     * @var Enum    amount_quota_special
     * @var String  balance_quota_ordinary
     * @var Integer balance_quota_special
     * @var Integer amortdef_id
     */

    protected $fillable = ['uuid',
                           'quota_number',
                           'quota_amount',
                           'quota_date',
                           'status',
                           'payroll_type',
                           'issue_date',
                           'quota_amount_ordinary',
                           'capital_quota_ordinary',          
                           'interests_quota_ordinary',       
                           'capital_quota_special',
                           'amount_quota_special',
                           'balance_quota_ordinary',
                           'balance_quota_special',
                           'loan_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Una amortizacion préstamo pertenece a un préstamo
      * 
      * @return type
      */ 

    public function loan() {

        return $this->belongsTo(Loan::class);
    }


    /**
      * Una amortizacion préstamo pertenece a un préstamo
      * 
      * @return type
      */ 

    public function issuedetails() {

        return $this->hasMany(Issuedetails::class);
    }

    /**
      * Una amortizacion préstamo pertenece a un préstamo
      * 
      * @return type
      */ 

    public function organism() {

        return $this->belongsTo(Organism::class);
    }


    /**
     *  Setup model event hooks UUID
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = [])
    {
        $model = static::query()->create($attributes);

        return $model;
    }

}
