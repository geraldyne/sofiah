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

use App\Entities\Operative\Amortdefdetails;

/**
 *  Modelo de Amortizacion
 */

class Amortdef extends Model {

	// Nombre de la tabla a la que pertenece el modelo

    protected $table = "amort_def";

    /**
     * The attributes that are mass assignable.
     *
     * @var Date    date_issue
     * @var Double  amount
     * @var Enum    status
     */

    protected $fillable = [
                          'uuid',
                          'date_issue',
                          'amount',
                          'status'];

    /* 
     * RELACIONES 
     */

    /**
      * Una amortización tiene muchos detalles de amortización
      * 
      * @return type
      */ 

    public function amortdefdetails() {

        return $this->hasMany(Amortdefdetails::class);
    }
}
