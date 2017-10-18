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

use App\Entities\Administrative\Partner;
use App\Entities\Administrative\Accountingyear;

class Dividend extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "dividends";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var Date    fechaRegistro
     * @var Double  haberesMes
     * @var Double  dividendosMes
     * @var Double  haberesMesAsociacion
     * @var Double  factor
     * @var Boolean estatus
     * @var Integer ejercicio_id
     * @var Integer asociado_id
     */

    protected $fillable = [ 'uuid',
                            'registration_date',
                            'assets_associated',
                            'dividends',
                            'assets_association',
                            'factor',
                            'status',
                            'partner_id',
                            'accounting_id'];

	/* 
     * RELACIONES 
     */

    /**
     * Un dividendo pertenece a un asociado
     * 
     * @return type
     */

    public function partner() {

        return $this->belongsTo(Partner::class);
    }

    /**
     * Un dividendo pertenece a un ejercicio contable
     * 
     * @return type
     */

    public function accountingyear() {

        return $this->belongsTo(Accountingyear::class);
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