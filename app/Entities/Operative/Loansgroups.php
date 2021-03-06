<?php

/**
 *  @package        SOFIAH.App.Entities.Operative
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

namespace App\Entities\Operative;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Support\UuidScopeTrait;
use Webpatser\Uuid\Uuid;

use App\Entities\Operative\Loantypes;
use App\Entities\Operative\Loantypegroups;

/**
 *  Modelo de grupos de prestamos
 */

class Loansgroups extends Model {

    use Notifiable, UuidScopeTrait;

    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "loans_groups";

    /**
     * The attributes that are mass assignable.
     *
     * @var String  name
     * @var Double  max_amount
     */

    protected $fillable = ['uuid',
                           'name',
    					   'loantypes_id',
                           'loantypegroups_id'];

    /* 
     * RELACIONES 
     */


    /**
      * Un grupo de préstamo pertenece a un tipo de préstamo
      * 
      * @return type
      */ 

    public function loantypes() {

        return $this->belongsTo(Loantypes::class);
    }


    /**
      * Un grupo de prestamos pertenece a un grupo de tipos de prestamos
      * 
      * @return type
      */ 

    public function loantypegroups() {

        return $this->belongsTo(Loantypegroups::class);
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
