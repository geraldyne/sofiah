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

use App\Entities\Administrative\Accountlvl6;
use App\Entities\Administrative\Dailymovement;

class Dailymovementdetails extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "daily_movements_details";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
    	'uuid',
    	'description',
		'debit',
		'asset',
		'dailymovement_id',
		'accountlvl6_id'
	];

	/* RELACIONES */

	/**
	 * El detalle de movimiento pertenece a un movimiento diario
	 * 
	 * @return type
	 */

	public function dailymovement() {

		return $this->belongsTo(Dailymovement::class);
	}

	/**
	 * El detalle de movimiento afecta a una cuenta del plan de cuentas
	 * 
	 * @return type
	 */

	public function accountlvl6() {

        return $this->belongsTo(Accountlvl6::class);
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
