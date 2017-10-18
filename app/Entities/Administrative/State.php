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

use App\Entities\Administrative\Country;
use App\Entities\Administrative\City;

class State extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "states";

    //protected $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var String  estado
     * @var Integer pais_id
     */

    protected $fillable = ['uuid', 'state', 'country_id'];

    /* 
     * RELACIONES 
     */

    /**
     * Un estado pertenece a un país
     * 
     * @return type
     */

    public function country() {

		return $this->belongsTo(Country::class);
	}

	/**
     * Un estado tiene muchas ciudades
     * 
     * @return type
     */

	public function cities() {

		return $this->hasMany(City::class);
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
