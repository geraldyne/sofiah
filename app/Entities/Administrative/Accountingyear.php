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

use App\Entities\Administrative\Dividends;
use App\Entities\Administrative\Dailymovement;

class Accountingyear extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "accounting_year";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var Date    fechaInicio
     * @var Date    fechaCierre
     * @var Boolean estatus
     * @var Integer movimientoDiario_id
     */

    protected $fillable = [ 
        'uuid',
        'start_date',
        'deadline',            
        'status',
        'dailymovement_id'
    ];

	/* 
     * RELACIONES 
     */

    /**
     * Un ejercicio posee varios dividendos
     * 
     * @return type
     */

    public function dividends() {

        return $this->hasMany(Dividends::class);
    }

    /**
     * Un ejercicio posee un movimiento diario
     * 
     * @return type
     */

    public function dailymovement() {

        return $this->belongsTo(Dailymovement::class);
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
