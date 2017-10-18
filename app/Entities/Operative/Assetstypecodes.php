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

use App\Entities\Administrative\Organism;

/**
 *  Modelo de Amortizacion
 */

class Assetstypecodes extends Model {

	// Nombre de la tabla a la que pertenece el modelo

    protected $table = "assets_type_codes";

    /**
     * The attributes that are mass assignable.
     *
     * @var Date    date_issue
     * @var Double  amount
     * @var Enum    status
     */

    protected $fillable = [
                          'uuid',
                          'assets_organisms_code',
                          'type',
                          'organisms_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Una amortización tiene muchos detalles de amortización
      * 
      * @return type
      */ 

    public function organism() {

        return $this->belongsTo(Organism::class);
    }
}
