<?php

namespace App\Entities\Assets;

use Illuminate\Notifications\Notifiable;
use App\Support\UuidScopeTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Asset.
 */
class Asset extends Model
{
    use Notifiable, UuidScopeTrait;
    
    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "assets";

    /**
     * @var array
     */
    protected $guarded = ['id'];

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
