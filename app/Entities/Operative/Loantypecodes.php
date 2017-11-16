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
use App\Entities\Operative\Loan;
use App\Entities\Operative\Issuedetails;
use App\Entities\Administrative\Organism;

/**
 *  Modelo de codigos de tipo de prestamos
 */

class Loantypecodes extends Model {

    use Notifiable, UuidScopeTrait;

    
    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "loan_type_codes";

    /**
     * The attributes that are mass assignable.
     *
     * @var Integer  montoMaximo
     */

    protected $fillable = ['uuid',
                           'loan_code',
                           'loantypes_id',
                           'organism_id'];

    /* 
     * RELACIONES 
     */

    /**
      * El código del tipo de préstamo es asignado por un tipo de prestamo
      * 
      * @return type
      */ 

    public function loantypes() {

        return $this->belongsTo(Loantypes::class);
    }


    /**
      * El código del tipo de préstamo es asignado por un prestamo
      * 
      * @return type
      */ 
    public function loan() 
    {
        return $this->belongsTo(Loan::class);
    }

    /**
      * El código del tipo de préstamo es asignado por un organismo
      * 
      * @return type
      */ 

    public function organism() {

        return $this->belongsTo(Organism::class);
    }

    /**
      * El código del tipo de préstamo es asignado a un detalle de emision
      * @return type
      */ 

    public function issuedetails() {

        return $this->hasOne(Issuedetails::class);
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
