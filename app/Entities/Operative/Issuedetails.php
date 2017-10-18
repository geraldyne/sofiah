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

use App\Entities\Operative\Issue;
use App\Entities\Operative\Loanmovements;
use App\Entities\Operative\Amortdefloans;
use App\Entities\Operative\Amortdefdetails;

/**
 *  Modelo de detalle de la emisión
 */

class Issuedetails extends Model {

	// Nombre de la tabla a la que pertenece el modelo

    protected $table = "issue_details";

    /**
     * The attributes that are mass assignable.
     *
     * @var Double  amount
     * @var Double  capital
     * @var Double  interests
     * @var Double  loan_balance
     * @var Double  quota_balance
     * @var Date    quota_date
     * @var Enum    type
     * @var Integer quota_number
     * @var Integer days
     * @var Integer issue_id
     * @var Integer amortDefDetails_id
     */

    protected $fillable = ['uuid',
                           'amount',
              					   'capital',
              					   'interests',
              					   'loan_balance',
              					   'quota_balance',
              					   'quota_date',
              					   'type',
              					   'quota_number',
              					   'days',
              					   'issue_id'
                           'amortDefDetails_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Un detalle de emision pertenece a una emisión
      * 
      * @return type
      */ 

    public function issue() {

        return $this->belongsTo(Issue::class);
    }


    /**
      * Un detalle de emision pertenece a muchos movimientos prestamos
      * 
      * @return type
      */ 

    public function loanmovements() {

        return $this->hasMany(Loanmovements::class);
    }


    /**
      * Un detalle de emision pertenece a un prestamo de amortizacion 
      * 
      * @return type
      */ 

    public function amortdefloans() {

        return $this->belongsTo(Amortdefloans::class);
    }


    /**
      * Un detalle emision tiene muchas detalles de amortizacion 
      * 
      * @return type
      */ 

    public function amortdefdetails() {

        return $this->hasOne(Amortdefdetails::class);
    }


    
}
