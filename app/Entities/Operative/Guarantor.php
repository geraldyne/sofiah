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
use App\Entities\Administrative\Partner;

/**
 *  Modelo de Fiadores
 */

class Guarantor extends Model {

    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "guarantors";

    /**
     * The attributes that are mass assignable.
     *
     * @var Double  amount
     * @var Double  balance
     * @var Double  percentage
     * @var Boolean status
     * @var Integer partner_id
     * @var Integer loan_id
     */

    protected $fillable = ['uuid',
                           'amount',
    					             'balance',
    					             'percentage',
              			       'status',
              			       'partner_id',
              			       'loan_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Un fiador pertenece a muchos préstamos
      * 
      * @return type
      */ 

    public function loan() {

        return $this->belongsTo(Loan::class);
    }

    /**
      * Un asociado es un fiador
      * 
      * @return type
      */ 

    public function partner() {

        return $this->belongsTo(Partner::class);
    }
}
