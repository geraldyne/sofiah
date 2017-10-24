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

use App\Entities\Administrative\Association;
use App\Entities\Administrative\Direction;
use App\Entities\Administrative\User;
use App\Entities\Administrative\Bankdetails;

class Employee extends Model {
        
    use Notifiable, UuidScopeTrait;

    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "employees";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var String  codigoEmpleado
     * @var String  nombres
     * @var String  apellidos
     * @var String  email
     * @var array   departamento
     * @var Integer banco_id
     * @var String  numeroCuenta
     * @var Enum    tipoCuenta
     * @var String  cedula
     * @var String  rif
     * @var String  telefono
     * @var Integer asociacion_id
     * @var Integer direccion_id
     * @var Integer usuario_id
     * @var Enum    nacionalidad
     * @var Enum    estatus
     * @var Date    fechaNacimiento
     * @var Date    fechaIngreso
     * @var Date    fechaRetiro
     */

    protected $fillable = [
        'uuid',
        'employee_code',
        'names',
        'lastnames',
        'email',
        'department',
        'rif',
        'id_card',
        'phone',
        'nationality',
        'status',
        'birthdate',
        'date_of_admission',
        'retirement_date',
        'user_id',
        'direction_id',
        'association_id',
        'bankdetails_id'
    ];
            
	/* 
     * RELACIONES 
     */

    /**
     * Un empleado pertenece a una asociacion
     * 
     * @return type
     */

    public function association() {

        return $this->belongsTo(Association::class);
    }

    /**
     * Un empleado tiene una direccion
     * 
     * @return type
     */

    public function direction() {

        return $this->belongsTo(Direction::class);
    }

    /**
     * Un empleado tiene un usuario
     * 
     * @return type
     */

    public function user() {

        return $this->belongsTo(User::class);
    }

    /**
     * Un asociado tiene una o mas cuentas bancarias registradas
     * 
     * @return type
     */

    public function bankdetails() {

        return $this->belongsTo(Bankdetails::class);
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
