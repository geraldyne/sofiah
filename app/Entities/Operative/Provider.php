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

use App\Entities\Operative\Policie;
use App\Entities\Operative\Bond;
use App\Entities\Operative\Direction;

/**
 *  Modelo de Proveedor
 */

class Provider extends Model {

    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "providers";

    /**
     * The attributes that are mass assignable.
     *
     * @var String  name
     * @var String  email
     * @var String  web_site
     * @var String  contact
     * @var         slug
     * @var Enum    rif_type
     * @var Integer rif
     * @var Integer phone
     * @var Integer direction_id
     */
    
    

    protected $fillable = ['uuid',
                           'name',
                           'email',
                           'web_site',
                           'contact',
                           'slug',
                           'rif_type',
                           'rif',
                           'phone',
                           'direction_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Un proveedor pertenece a muchas pólizas
      * 
      * @return type
      */ 

    public function policies() {

        return $this->hasMany(Policie::class);
    }

    /**
      * Un proveedor pertenece a muchas fianzas
      * 
      * @return type
      */ 

    public function bonds() {

        return $this->hasMany(Bond::class);
    }

    /**
      * Un proveedor tiene una dirección
      * 
      * @return type
      */ 

    public function direction() {

        return $this->belongsTo(Direction::class);
    }

}
