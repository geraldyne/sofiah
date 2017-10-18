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
namespace App\Entities\Administrative;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Support\UuidScopeTrait;
use Webpatser\Uuid\Uuid;

use App\Entities\Administrative\Organism;
use App\Entities\Operative\Issuedetails;

/**
 *  Modelo de emisión
 */

class Issue extends Model {

	// Nombre de la tabla a la que pertenece el modelo

    protected $table = "issues";

    /**
     * The attributes that are mass assignable.
     *
     * @var Date    date_issue
     * @var Double  amount
     * @var Enum    status
     * @var Integer organisms_id
     */

    protected $fillable = ['uuid',
                           'date_issue',
              					   'amount',
              					   'status',
              					   'organisms_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Una emisión va dirigido a un organismo
      * 
      * @return type
      */ 

    public function organism() {

        return $this->belongsTo(Organism::class);
    }

    /**
      * Una emisión tiene un detalle
      * 
      * @return type
      */ 

    public function issuedetail() {

        return $this->hasOne(Issuedetails::class);
    }
}
