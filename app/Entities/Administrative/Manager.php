<?php

/**
 *  @package        SOFIAH.App.Entities.Administrative
 *  
 *  @author         Idepixel. <idepixel@gmail.com>.
 *  @copyright      Todos los derechos reservados. SOFIAH. 2017.
 *  
 *  @since          Versión 1.0, revisión 17-07-2017.
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

use App\Entities\Administrative\Partner;
use App\Entities\Administrative\Charge;

class Manager extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "managers";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var Integer cargo_id
     * @var Integer asociado_id
     * @var Boolean estatus
     */

    protected $fillable = [
        'uuid',
        'status',
        'partner_id',
        'charge_id'
    ];

    /* 
     * RELACIONES 
     */

    /**
     * Un asociado tiene un cargo de directivo (Muchos por si repite el cargo en otro período)
     * 
     * @return type
     */

    public function partner() {

        return $this->belongsTo(Partner::class);
    }

    /**
     * Cargo que posee el asociado
     * 
     * @return type
     */

    public function charge() {

        return $this->belongsTo(Charge::class);
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
