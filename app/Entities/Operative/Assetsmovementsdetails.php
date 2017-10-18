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
}