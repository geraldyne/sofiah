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

use App\Entities\Operative\Loanmovements;
use App\Entities\Operative\Amortdefdetails;

/**
 *  Modelo de Movimientos Amortizacion Prestamos 
 */

class Loanamortmovements extends Model {

    use Notifiable, UuidScopeTrait;

	// Nombre de la tabla a la que pertenece el modelo

    protected $table = "loan_amort_movements";

    /**
     * The attributes that are mass assignable.
     *
     * @var Date    date_issue
     * @var Double  amount
     * @var Enum    status
     */

    protected $fillable = [
                          'uuid',
                          'loanmovement_id',
                          'amortdefdetails_id'];

    /* 
     * RELACIONES 
     */

    /**
      * Un movimiento amortizacion de prestamo tiene muchos movimientos de prestamos
      * 
      * @return type
      */ 

    public function loanmovements() {

        return $this->belongsTo(Loanmovements::class);
    }

    /**
      * Un movimiento amortizacion de prestamo posee una amortizacion detalle
      * 
      * @return type
      */ 

    public function amortdefdetails() {

        return $this->belongsTo(Amortdefdetails::class);
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
