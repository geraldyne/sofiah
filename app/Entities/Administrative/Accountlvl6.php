<?php

/**
 *  @package        SOFIAH.App.Entities.Administrative
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

namespace App\Entities\Administrative;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Support\UuidScopeTrait;
use Webpatser\Uuid\Uuid;

use App\Entities\Administrative\Accountlvl5;
use App\Entities\Administrative\Accountingintegration;
use App\Entities\Administrative\Cashflow;
use App\Entities\Administrative\Heritagechange;
use App\Entities\Administrative\Dailymovementdetails;
use App\Entities\Administrative\Accountassociation;

class Accountlvl6 extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "accounts_lvl6";

    //protected $timestamps = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var Integer codigoCuenta
     * @var String nombreCuenta
     * @var Enum tipoCuenta
     * @var Enum tipoSaldo
     * @var Integer nivelCuenta
     * @var Boolean cuentaTitulo
     * @var Boolean cuentaEfectivo
     * @var Boolean aplicaBalance
     */

    protected $fillable = [
        'uuid',
        'account_code', 
    	'account_name',
    	'account_type',
    	'balance_type',
    	'apply_balance',
        'accountlvl5_id',
        'cash_flow'
    ];

    /* 
     * RELACIONES 
     */

    /**
     * Una cuenta de nivel 6 pertenece a una cuenta de nivel 5
     * 
     * @return type
     */

    public function accountlvl5() {

        return $this->belongsTo(Accountlvl5::class);
    }

    // Una asociacion tiene muchos organismos

    public function accountsassociation() {

        return $this->hasMany(Accountassociation::class);
    }

    /**
     * Un plan de cuentas tiene muchas cuentas de integraciones contables (máscaras)
     * 
     * @return type
     */

    public function accountingintegration() {

        return $this->hasMany(Accountingintegration::class);
    }

    /**
     * Un plan de cuentas tiene muchas cuentas de flujo de efectivo
     * 
     * @return type
     */

    public function cashflow() {

        return $this->belongsToMany(
            Cashflow::class,
            'cash_flow_account',
            'accountlvl6_id',
            'cashflow_id');
    }

    /**
     * Un plan de cuentas tiene muchas cuentas de cambio de patrimonio
     * 
     * @return type
     */

    public function heritagechange() {

        return $this->belongsToMany(
            Heritagechange::class,
            'heritage_changes_account',
            'accountlvl6_id',
            'heritagechange_id');
    }

    /**
     * Las cuentas del plan de cuentas se ven afectadas por el movimiento diario
     * 
     * @return type
     */

    public function dailymovementdetails() {

        return $this->hasMany(Dailymovementdetails::class);
    }

    /**
     *  Setup model event hooks UUID
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string) Uuid::generate(4);
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = [])
    {
        $model = static::query()->create($attributes);

        return $model;
    }
}
