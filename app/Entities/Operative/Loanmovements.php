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
use App\Entities\Operative\Issuedetails;

/**
 *  Modelo de movimiento de prestamo
 */

class Loanmovements extends Model {

	// Nombre de la tabla a la que pertenece el modelo

    protected $table = "loan_movements";

    /**
     * The attributes that are mass assignable.
     *
     * @var Date    date_issue
     * @var Double  amount
     * @var Enum    type
     * @var Enum    status
     * @var Integer loan_id
     */

    protected $fillable = ['uuid',
                           'date_issue',
              					   'amount',
              					   'type',
              					   'status',
              					   'loan_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Un movimiento de préstamo pertenece a un préstamo
      * 
      * @return type
      */ 

    public function loan() {

        return $this->belongsTo(Loan::class);
    }


    /**
      * Un movimiento de préstamo pertenece a una emision detalles
      * 
      * @return type
      */ 

    public function issuedetails() {

        return $this->belongsTo(Issuedetails::class);
    }
}
