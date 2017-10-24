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

use App\Entities\Operative\Loan;
use App\Entities\Operative\Loanamortmovements;

/**
 *  Modelo de movimiento de prestamo
 */

class Loanmovements extends Model {

    use Notifiable, UuidScopeTrait;

	// Nombre de la tabla a la que pertenece el modelo

    protected $table = "loan_movements";

    /**
     * The attributes that are mass assignable.
     *
     * @var Date    date_issue
     * @var Double  amount
     * @var Enum    type
     * @var Enum    status
     * @var Integer loan_id
     */

    protected $fillable = ['uuid',
                           'date_issue',
              					   'amount',
              					   'type',
              					   'status',
              					   'loan_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Un movimiento de préstamo pertenece a un préstamo
      * 
      * @return type
      */ 

    public function loan() {

        return $this->belongsTo(Loan::class);
    }

    /**
      * Un movimiento de préstamo pertenece a una emision detalles
      * 
      * @return type
      */ 

    public function loanamortmovements() {

        return $this->hasMany(Loanamortmovements::class);
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
