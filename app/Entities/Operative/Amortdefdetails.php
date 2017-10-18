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

use App\Entities\Operative\Amortdef;
use App\Entities\Operative\Issuedetails;

/**
 *  Modelo de detalles de la amortización
 */

class Amortdefdetails extends Model {

	// Nombre de la tabla a la que pertenece el modelo

    protected $table = "amort_def_details";

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
     * @var Integer issuedetails_id
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
    					   'issuedetails_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Un detalle de amortización pertenece a una amortización
      * 
      * @return type
      */ 

    public function amortdef() {

        return $this->belongsTo(Amortdef::class);
    }


    /**
      * Un detalle de amortización pertenece a un detalle de emision
      * 
      * @return type
      */ 

    public function issuedetails() {

        return $this->belongsTo(Issuedetails::class);
    }
}