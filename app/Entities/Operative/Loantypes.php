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

use App\Entities\Operative\Loantypegroups;
use App\Entities\Operative\Loantypecodes;
use App\Entities\Operative\Loan;
use App\Entities\Operative\Specialfee;

/**
 *  Modelo de Tipo de Prestamo
 */

class Loantype extends Model {

    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "loan_types";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var name
     * @var guarantor
     * @var guarantee
     * @var guarantee_comision
     * @var refinancing
     * @var valid_availability
     * @var affect_availability
     * @var special_fees
     * @var third_party_payment
     * @var paid_capacity
     * @var valid_policy
     * @var web_based
     * @var administrative_expenditure
     * @var deduct_administrative_expense
     * @var interest
     * @var bond_commission
     * @var refinancing_amount
     * @var percent_special_quotes
     * @var percent_administrative_expenditure
     * @var refinance_days
     * @var term
     * @var number_guarantors
     * @var receivable_id
     * @var bill_to_pay_id
     * @var income_account_id
     * @var operating_expense_account_id
     * @var max_amount
     */

    protected $fillable = ['uuid',
                           'name',
                           'guarantor',
                           'guarantee',
                           'guarantee_comision',
                           'refinancing',
                           'valid_availability',
                           'affect_availability',
                           'special_fees',
                           'third_party_payment',
                           'paid_capacity',
                           'valid_policy',
                           'web_based',
                           'administrative_expenditure',
                           'deduct_administrative_expense',
                           'interest',
                           'bond_commission',
                           'refinancing_amount',
                           'percent_special_quotes',
                           'percent_administrative_expenditure',
                           'refinance_days',
                           'term',
                           'number_guarantors',
                           'receivable_id',
                           'billtopay_id',
                           'incomeaccount_id',
                           'operatingexpenseaccount_id',
                           'max_amount'];

    /* 
     * RELACIONES 
     */

    /**
      * Un tipo de prestamos pertenece a un grupo de tipos de prestamos
      * 
      * @return type
      */ 

    public function loantypegroups() {

        return $this->hasOne(Loantypegroups::class);
    }

    /**
      * Un tipo de prestamo tiene muchos códigos de tipo de préstamos asignado por el organismo 
      * 
      * @return type
      */ 

    public function loantypecodes() {

        return $this->hasMany(Loantypecodes::class);
    }

    /**
      * Un tipo de préstamo pertenece a muchos préstamos
      * 
      * @return type
      */ 

    public function loans() {

        return $this->hasMany(Loan::class);
    }

    /**
      * Un tipo de préstamo tiene muchas cuotas especiales
      * 
      * @return type
      */ 

    public function specialfee() {

        return $this->hasMany(Specialfee::class);
    }
}

