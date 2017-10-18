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

use App\Entities\Operative\Loanstype;
use App\Entities\Operative\Loantypegroups;

/**
 *  Modelo de grupos de prestamos
 */

class Loansgroups extends Model {

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
    					             'loantype_id',
                           'loantypegroup_id'];

    /* 
     * RELACIONES 
     */


    /**
      * Un grupo de préstamo pertenece a un tipo de préstamo
      * 
      * @return type
      */ 

    public function loanstype() {

        return $this->belongsTo(Loanstype::class);
    }


    /**
      * Un grupo de prestamos pertenece a un grupo de tipos de prestamos
      * 
      * @return type
      */ 

    public function loantypegroups() {

        return $this->belongsTo(Loantypegroups::class);
    }

}
