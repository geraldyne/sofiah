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

use App\Entities\Association;
use App\Entities\Administrative\Accountlvl6;

class Accountassociation extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "accountsassociation";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var String  nombreCuentaIntegracion
     * @var integer cuenta_id
     */

    protected $fillable = [
        'uuid', 
        'description', 
        'accountlvl6_id',
        'association_id'
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

    public function association() {

        return $this->belongsTo(Association::class);
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
