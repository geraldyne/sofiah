<?php

/**
 *  @package        SOFIAH.App.Entities
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

namespace App\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Support\UuidScopeTrait;
use Webpatser\Uuid\Uuid;

use App\Entities\Administrative\Direction;
use App\Entities\Administrative\Organism;
use App\Entities\Administrative\Employee;

class Association extends Model {

    use Notifiable, UuidScopeTrait;
    
    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "associations";

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'name',
        'alias',
        'sudeca',
        'web_site',
        'email',
        'logo',
        'phone',
        'rif',
        'direction_id',
        'lock_date',
        'time_to_reincorporate',
        'loan_time',
        'percent_legal_reserve',
        'employers_contribution_account_id',
        'deferred_employer_contribution_account_id',
        'individual_contribution_account_id',
        'deferred_individual_contribution_account_id',
        'voluntary_contribution_account_id',
        'deferred_voluntary_contribution_account_id',
        'legal_reserve_account_id'
    ];

    /*
        Relaciones
     */
    
    // Una asociacion tiene una direccion
    // 
    public function direction() {

        return $this->belongsTo(Direction::class);
    }
    
    // Una asociacion tiene muchos organismos

    public function organisms() {

        return $this->hasMany(Organism::class);
    }

    // Una asociacion tiene muchos empleados

    public function employees() {

        return $this->hasMany(Employee::class);
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
