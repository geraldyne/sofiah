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
 *  Modelo de polizas
 */

class Policie extends Model {
    
    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "policies";

    /**
     * The attributes that are mass assignable.
     *
     * @var String  number
     * @var String  type
     * @var Date    issue_date
     * @var Date    due_date
     * @var Double  amount
     * @var Boolean status
     * @var Integer provider_id
     * @var Integer loan_id
     */

    protected $fillable = ['uuid',
                           'number',
              			       'type',
              			       'issue_date',
              			       'due_date',
              			       'amount',
              			       'status',
              			       'provider_id',
              			       'loan_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Una póliza pertenece a un préstamo
      * 
      * @return type
      */ 

    public function loan() {

        return $this->belongsTo(Loan::class);
    }

    /**
      * Una poliza pertenece a un proveedor
      * 
      * @return type
      */ 

    public function provider() {

        return $this->belongsTo(Provider::class);
    }
}
