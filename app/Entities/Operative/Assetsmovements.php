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

use App\Entities\Administrative\Partner;
use App\Entities\Operative\Assetsmovementsdetails;

/**
 *  Modelo de movimiento de haberes de un asociado
 */

class Assetsmovements extends Model {

    use Notifiable, UuidScopeTrait;

	// Nombre de la tabla a la que pertenece el modelo

    protected $table = "assets_movements";

    /**
     * The attributes that are mass assignable.
     *
     * @var Date    date_issue
     * @var Enum    reason
     * @var Enum    status
     * @var Double  total_amount
     * @var String  description
     * @var Integer partner_id
     */

    protected $fillable = ['uuid',
                           'date_issue',
    					   'reason',
    					   'status',
    					   'total_amount',
    					   'description',
    					   'partner_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Un movimiento de haberes pertenece a un asociado
      * 
      * @return type
      */ 

    public function partner() {

        return $this->belongsTo(Partner::class);
    }

    /**
      * Un movimiento de haberes tiene muchos detalles
      * 
      * @return type
      */ 

    public function assetsmovementsdetails() {

        return $this->hasMany(Assetsmovementsdetails::class);
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