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

use App\Entities\Administrative\Accountlvl2;
use App\Entities\Administrative\Accountlvl4;

class Accountlvl3 extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "accounts_lvl3";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var Integer codigoCuenta
     * @var String nombreCuenta
     * @var Enum tipoCuenta
     * @var Enum tipoSaldo
     * @var Boolean aplicaBalance
     */

    protected $fillable = [
        'uuid',
        'account_code', 
    	'account_name',
    	'account_type',
    	'balance_type',
    	'apply_balance',
        'accountlvl2_id',
        'cash_flow'
    ];

    /* 
     * RELACIONES 
     */

    /**
     * Una cuenta de nivel 3 pertenece a una cuenta de nivel 2
     * 
     * @return type
     */

    public function accountlvl2() {

        return $this->belongsTo(Accountlvl2::class);
    }

    /**
     * Una cuenta de nivel 3 tiene muchas cuentas de nivel 4
     * 
     * @return type
     */

    public function accountslvl4() {

        return $this->hasMany(Accountlvl4::class);
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
