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

use App\Entities\Association;
use App\Entities\Administrative\City;
use App\Entities\Administrative\Organism;
use App\Entities\Administrative\Employee;

class Direction extends Model {

    use Notifiable, UuidScopeTrait;
    
    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "directions";

    //protected $timestamps = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var String  direccion
     * @var Integer ciudad_id
     */

    protected $fillable = ['direction', 'uuid', 'city_id'];

    /* 
     * RELACIONES 
     */

    /**
     * Una direccion pertenece a una ciudad
     * 
     * @return type
     */

    public function city() {

		return $this->belongsTo(City::class);
	}

    /**
     * Una dirección tiene una asociacion
     * 
     * @return type
     */

	public function associations() {

		return $this->hasMany(Association::class);
	}

    /**
     * Una dirección pertenece a un organismo
     * 
     * @return type
     */

    public function organisms() {

        return $this->hasMany(Organism::class);
    }

    /**
     * Una dirección pertenece a un empleado
     * 
     * @return type
     */

    public function employees() {

        return $this->hasMany(Employee::class);
    }
/*
    /**
     * Una dirección tiene un proveedor
     * 
     * @return type
     */
/*
    public function proveedor() {

        return $this->hasOne('App\Modelos\Operativo\Proveedor');*/
    //}

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
