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

use App\Entities\Operative\Loantypes;
use App\Entities\Administrative\Accountlvl6;

class Accountingintegration extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "accounting_integrations";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var String  nombreCuentaIntegracion
     * @var integer cuenta_id
     */

    protected $fillable = [
        'uuid', 
        'accounting_integration_name', 
        'accountlvl6_id'
    ];

    /* 
     * RELACIONES 
     */

    /**
     * Una cuenta de integracion contable pertenece a una cuenta del plan de cuentas
     * 
     * @return type
     */

    public function accountlvl6() {

        return $this->belongsTo(Accountlvl6::class);
    }

    /**
     * Una cuenta de integracion contable pertenece a una cuenta del plan de cuentas
     * 
     * @return type
     */

    public function loantypes() {

        return $this->hasMany(Loantypes::class);
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
