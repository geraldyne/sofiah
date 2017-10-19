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

use App\Entities\Operative\Assetsmovements;

/**
 *  Modelo de detalles de un movimiento de haberes detalles de un asociado
 */

class Assetsmovementsdetails extends Model {

    use Notifiable, UuidScopeTrait;

	// Nombre de la tabla a la que pertenece el modelo

    protected $table = "assets_movements_details";

    /**
     * The attributes that are mass assignable.
     *
     * @var Double  amount
     * @var Enum    type
     * @var Integer assetsmovement_id
     */

    protected $fillable = ['uuid',
                           'amount',
              					   'type',
              					   'assetsmovement_id'];

    /* 
     * RELACIONES 
     */

    /**
      * El detalle pertenece a un movimiento de haberes
      * 
      * @return type
      */ 

    public function assetsmovements() {

        return $this->belongsTo(Assetsmovements::class);
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