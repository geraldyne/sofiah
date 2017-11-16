<?php

/**
 *  @package        SOFIAH.App.Modelos.Administrativo
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 17-07-2017.
 *  @version        1.0
 * 
 *  @final  
 */

 /**
 * Incluye la implementación de los siguientes Librerias
 */

namespace App\Entities\Administrative;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Support\UuidScopeTrait;
use Webpatser\Uuid\Uuid;

use App\Entities\Association;
use App\Entities\Administrative\Direction;
use App\Entities\Administrative\Partner;
use App\Entities\Operative\Assetstypecodes;
use App\Entities\Operative\Loantypecodes;
use App\Entities\Operative\Amortdefloans;
use App\Entities\Operative\Issue;

class Organism extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "organisms";

    //protected $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'name',
        'alias',
        'email',
        'web_site',
        'zone',
        'contact',
        'phone',
        'rif',
        'payroll_type',
        'status',
        'disponibility',
        'percentage_employers_contribution',
        'percentage_individual_contribution',
        'percentage_voluntary_contribution',
        'direction_id',
        'association_id'
    ];

    /*
        Relaciones
     */

    /**
     * Un organismo pertenece a una asociacion
     * 
     * @return type
     */

    public function association() {

      return $this->belongsTo(Association::class);
    }

    /**
      * Un organismo tiene una direccion
      * 
      * @return type
      */ 

    public function direction() {

      return $this->belongsTo(Direction::class);
    }

    /**
     * Un organismo tiene muchos asociados
     * 
     * @return type
     */

    public function partners() {

    	return $this->hasMany(Partner::class);
    }


    /**
     * Un organismo tiene muchos codigo tipo haberes
     * 
     * @return type
     */

    public function assetstypecodes() {

        return $this->hasMany(Assetstypecodes::class);
    }


    /**
     * Un organismo tiene muchos codigos de tipo de prestamo
     * 
     * @return type
     */

    public function loantypecodes() {

        return $this->hasMany(Loantypecodes::class);
    }


    /**
     * Un organismo tiene muchas emisiones
     * 
     * @return type
     */

    public function issues() {

        return $this->hasMany(Issue::class);
    }


    /**
     * Un organismo tiene una emision
     * 
     * @return type
     */

    public function amortdefloans() {

        return $this->hasMany(Amortdefloans::class);
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
