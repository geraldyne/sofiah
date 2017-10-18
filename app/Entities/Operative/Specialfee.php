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
use App\Entities\Operative\Specialfeedetails;

/**
 *  Modelo de Cuota Especial
 */

class Specialfee extends Model {
    
    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "special_fee";

    /**
     * The attributes that are mass assignable.
     *
     * @var Date    loantype_id
     * @var Double  specialfeedetails_id
     */

    protected $fillable = ['uuid',
                           'loantype_id',
                           'specialfeedetails_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Una cuota especial tiene un tipo de préstamo
      * 
      * @return type
      */ 

    public function loantypes() {

        return $this->belongsTo(Loantypes::class);
    }


    /**
      * Una cuota especial tiene muchas cuotas especiales detalles
      * 
      * @return type
      */ 

    public function specialfeedetails() {

        return $this->hasMany(Specialfeedetails::class);
    }
}
