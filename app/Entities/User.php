<?php

namespace App\Entities;

use App\Support\HasRolesUuid;
use App\Support\UuidScopeTrait;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Entities\Administrative\Dailymovement;
use App\Entities\Administrative\Partner;
use App\Entities\Administrative\Employee;
use App\Entities\Administrative\Preference;

/**
 * Class User.
 */
class User extends Authenticatable
{
    use Notifiable, UuidScopeTrait, HasApiTokens, HasRoles, SoftDeletes, HasRolesUuid {
        HasRolesUuid::getStoredRole insteadof HasRoles;
    }

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
        'name',
        'uuid',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* 
     * RELACIONES 
     */
    
    /**
     * Un usuario origina un movimiento diario
     * 
     * @return type
     */
    
    public function movement_origin() {

        return $this->belongsToMany(
            Dailymovement::class, 
            'daily_movements_origin', 
            'user_id',
            'dailymovement_id'
        );
    }

    /**
     * Un usuario aplica un movimiento diario
     * 
     * @return type
     */

    public function movement_apply() {

        return $this->belongsToMany(
            Dailymovement::class, 
            'daily_movements_apply', 
            'user_id',
            'dailymovement_id'
        );
    }

    /**
     * Un usuario pertenece a un asociado
     * 
     * @return type
     */
    
    public function partner() {

        return $this->hasOne(Partner::class);
    }

    /**
     * Un usuario pertenece a un empleado
     * 
     * @return type
     */
    
    public function employee() {

        return $this->hasOne(Employee::class);
    }

    /**
     * Una preferencia pertenece a un usuario
     * 
     * @return type
     */
    
    public function preference() {

        return $this->hasOne(Preference::class);
    }

    /**
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function create(array $attributes = [])
    {
        if (array_key_exists('password', $attributes)) {
            $attributes['password'] = bcrypt($attributes['password']);
        }

        $model = static::query()->create($attributes);

        return $model;
    }
}
