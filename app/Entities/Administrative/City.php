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

use App\Entities\Administrative\State;
use App\Entities\Administrative\Direction;

class City extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "cities";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var String  ciudad
     * @var Integer codigoArea
     * @var Integer estado_id
     */

    protected $fillable = [
        'city', 
        'uuid', 
        'area_code', 
        'state_id'
    ];

    /* 
     * RELACIONES 
     */

    /**
     * Una ciudad pertenece a un estado
     * 
     * @return type
     */

    public function state() {

		return $this->belongsTo(State::class);
	}

    /**
     * Una ciudad tiene muchas direcciones
     * 
     * @return type
     */

	public function directions() {

        return $this->hasMany(Direction::class);
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
