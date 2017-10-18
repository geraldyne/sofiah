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

use App\Entities\User;

use App\Entities\Administrative\Accountingyear;
use App\Entities\Administrative\Dailymovement_details;

class Dailymovement extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "daily_movements";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var DataTime    fecha
     * @var Text        descripcion
     * @var Enum        estatus
     * @var Float       totalDebe
     * @var Float       totalHaber
     * @var Enum        origen
     * @var Enum        tipo
     */

    protected $fillable = [
        'uuid',
        'date',
		'description',
		'status',
		'debit',
		'asset',
        'number',
		'origin',
		'type'
    ];

	/* 
     * RELACIONES 
     */

    /**
     * Un movimiento es originado por un usuario
     * 
     * @return type
     */

    public function user_origin() {

        return $this->belongsToMany(
        	User::class, 
        	'daily_movements_origin', 
        	'daily_movement_id',
        	'user_id' 
        );
    }

    /**
     * Un movimiento es aplicado por un usuario
     * 
     * @return type
     */

    public function user_apply() {

        return $this->belongsToMany(
        	User::class, 
        	'daily_movements_apply', 
            'daily_movement_id',
            'user_id' 
        );
    }

    /**
     * Un movimiento tiene muchos detalles
     * 
     * @return type
     */

    public function details() {

        return $this->hasMany(Dailymovement_details::class);
    }

    /**
     * Un movimiento pertenece a un ejercicio contable
     * 
     * @return type
     */

    public function accounting_year() {

        return $this->hasOne(Accountingyear::class);
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
