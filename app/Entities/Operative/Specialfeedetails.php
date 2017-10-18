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

use App\Entities\Operative\Specialfee;

/**
 *  Modelo de Cuota Especial Detalles
 */

class Specialfeedetails extends Model {
    
    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "special_fee_details";

    /**
     * The attributes that are mass assignable.
     *
     * @var Date    loantype_id
     * @var Double  special_fee_details
     */

    protected $fillable = ['uuid', 'month'];

    /* 
     * RELACIONES 
     */

    /**
      * Una cuota especial detalle pertenece a una cuota especial 
      * 
      * @return type
      */ 

    public function specialfee() {

        return $this->belongsTo(Specialfee::class);
    }
}
