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

use App\Entities\Operative\Loantypes;
use App\Entities\Operative\Loantypecodes;
use App\Entities\Operative\Amortdefloans;
use App\Entities\Operative\Policie;
use App\Entities\Operative\Bond;
use App\Entities\Operative\Guarantor;
use App\Entities\Administrative\Partner;
use App\Entities\Operative\Loanmovements;

/**
 *  Modelo de Prestamo
 */

class Loan extends Model {

    use Notifiable, UuidScopeTrait;
    
    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "loans";
    

    /**
     * The attributes that are mass assignable.
     *
     * @var Date    issue_date
     * @var Double  amount
     * @var Double  rate
     * @var Double  balance          
     * @var Double  administrative_expenditure   
     * @var Enum    fee_frequency
     * @var Enum    status
     * @var String  destination
     * @var Integer monthly_fees
     * @var Integer loantype_id
     */

    protected $fillable = ['uuid',
                           'issue_date',
                           'amount',
                           'rate',
                           'balance',          
                           'administrative_expenditure',       
                           'fee_frequency',
                           'status',
                           'destination',
                           'monthly_fees',
                           'loantypes_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Un préstamo posee un tipo de préstamo
      * 
      * @return type
      */ 

    public function loantypes() {

        return $this->belongsTo(Loantypes::class);
    }


    /**
      * Un préstamo posee muchos codigos de tipo de préstamo
      * 
      * @return type
      */ 

    /*
    public function loantypecodes() {

        return $this->hasOne(Loantypecodes::class);
    }
    */

    /**
      * Un préstamo posee varias amortizacion préstamos
      * 
      * @return type
      */ 

    public function amortdefloans() {

        return $this->hasMany(Amortdefloans::class);
    }

    /**
      * Un préstamo posee varias polizas
      * 
      * @return type
      */ 

    public function policies() {

        return $this->hasMany(Policie::class);
    }

    /**
      * Un préstamo posee varias fianzas
      * 
      * @return type
      */ 

    public function bonds() {

        return $this->hasMany(Bond::class);
    }

    /**
      * Un préstamo posee varios fiadores
      * 
      * @return type
      */ 

    public function guarantors() {

        return $this->hasMany(Guarantor::class);
    }

    /**
      * Un préstamo pertenece a un asociado
      * 
      * @return type
      */ 

    public function partners() {

        return $this->belongsTo(Partner::class);
    }

    /**
      * Un préstamo posee varios movimientos prestamos
      * 
      * @return type
      */ 

    public function loanmovements() {

        return $this->hasMany(Loanmovements::class);
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
