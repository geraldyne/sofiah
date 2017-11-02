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

use App\Entities\Administrative\Bank;
use App\Entities\Administrative\Partner;
use App\Entities\Administrative\Employee;

class Bankdetails extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "bank_details";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var String Banco
     */

    protected $fillable = [
        'uuid',
        'account_number',
        'account_type',
        'bank_id'
    ];

    /* 
     * RELACIONES 
     */

    /**
     * Un cuenta bancaria existe en un banco
     * 
     * @return type
     */

    public function bank() {

        return $this->belongsTo(Bank::class);
    }

    /**
     * Un asociado tiene una cuenta de banco registrada
     * 
     * @return type
     */

    public function partner() {

        return $this->hasOne(Partner::class);
    }

    /**
     * Un empleado tiene una cuenta de banco registrada
     * 
     * @return type
     */

    
    public function employee() {

        return $this->hasOne(Employee::class);
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
