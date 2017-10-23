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

use App\Entities\Administrative\Partner;

/**
 *  Modelo de Saldo Haberes
 */

class Assetsbalance extends Model {

    use Notifiable, UuidScopeTrait;

    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "assets_balance";

    /**
     * The attributes that are mass assignable.
     *
     * @var float    balance_employers_contribution
     * @var float    balance_individual_contribution
     * @var float    balance_voluntary_contribution
     * @var Integer  partner_id
     */
    
    protected $fillable = ['uuid',
                           'balance_employers_contribution',
                           'balance_individual_contribution',
                           'balance_voluntary_contribution',  
                           'partner_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Una fianza pertenece a un préstamo
      * 
      * @return type
      */ 

    public function partner() {

        return $this->belongsTo(Partner::class);
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
