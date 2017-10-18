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

use App\Entities\Operative\Loan;
use App\Entities\Operative\Provider;

/**
 *  Modelo de Fianza
 */

class Bond extends Model {

    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "bonds";

    /**
     * The attributes that are mass assignable.
     *
     * @var String  number
     * @var Date    issue_date
     * @var Double  amount
     * @var Double  commission
     * @var Integer provider_id
     * @var Integer loan_id
     */
    
    protected $fillable = ['uuid',
                           'number',
                           'issue_date',
                           'amount',
                           'commission',
                           'provider_id',  
                           'loan_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Una fianza pertenece a un préstamo
      * 
      * @return type
      */ 

    public function loan() {

        return $this->belongsTo(Loan::class);
    }

    /**
      * Un proveedor pertenece a muchas fianzas
      * 
      * @return type
      */ 

    public function provider() {

        return $this->belongsTo(Provider::class);
    }
}
