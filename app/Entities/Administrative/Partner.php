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

use App\Entities\User;
use App\Entities\Administrative\Bankdetails;
use App\Entities\Administrative\Organism;
use App\Entities\Administrative\Manager;
use App\Entities\Administrative\Dividend;

use App\Entities\Operative\Loans;
use App\Entities\Operative\Guarantors;
use App\Entities\Operative\Assetsbalance;
use App\Entities\Operative\Assetsmovements;


class Partner extends Model {
    
    use Notifiable, UuidScopeTrait;
    
    # Nombre de la tabla a la que pertenece el modelo

    protected $table = "partners";

    //protected $timestamps = true;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var String  codigoEmpleado
     * @var String  nombres
     * @var String  apellidos
     * @var String  email
     * @var String  titulo
     * @var Integer banco_id
     * @var String  numeroCuenta
     * @var String  telefonoLocal
     * @var Integer organismo_id
     * @var Date    fechaRetiro
     * @var Date    fechaRetiroUltimo
     * @var String  nacionalidad
     * @var String  estatus
     * @var String  codigoCuenta
     * @var String  password
     * @var String  cedula
     * @var String  telefonoMovil
     * @var String  tipoCuenta
     */

    protected $fillable = [

        'uuid',
        'employee_code',
        'names',
        'lastnames',
        'email',
        'title',
        'local_phone',
        'retirement_date',
        'retirement_last_date',
        'nationality',
        'status',
        'account_code',
        'id_card',
        'phone',
        'bankdetails_id',
        'user_id',
        'organism_id'
    ];
            

	/* 
     * RELACIONES 
     */

    /**
     * Un asociado pertenece a un organismo
     * 
     * @return type
     */

    public function organism() {

        return $this->belongsTo(Organism::class);
    }

    /**
     * Un asociado tiene un usuario
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
     * Un asociado tiene un cargo de directivo (Muchos por si repite el cargo en otro período)
     * 
     * @return type
     */

    public function managers() {

        return $this->hasMany(Manager::class);
    }

    /**
     * Un asociado posee dividendos
     * 
     * @return type
     */

    public function dividends() {

        return $this->hasMany(Dividend::class);
    }

    /**
     * Un asociado posee muchos fiadores
     * 
     * @return type
     */

    public function guarantors() {

        return $this->hasMany(Guarantor::class);
    }


    /**
     * Un asociado posee muchos prestamos
     * 
     * @return type
     */

    public function loans() {

        return $this->hasMany(Loan::class);
    }

    /**
     * Un asociado posee muchos prestamos
     * 
     * @return type
     */

    public function assetsmovements() {

        return $this->hasMany(Assetsmovements::class);
    }

    /**
     * Un asociado posee muchos prestamos
     * 
     * @return type
     */

    public function assetsbalance() {

        return $this->hasOne(Assetsbalance::class);
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
