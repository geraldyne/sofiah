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
namespace App\Entities\Administrative;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Support\UuidScopeTrait;
use Webpatser\Uuid\Uuid;

use App\Entities\Operative\Loantypes;
use App\Entities\Operative\Loan;
use App\Entities\Administrative\Organism;

/**
 *  Modelo de codigos de tipo de prestamos
 */

class Loantypecodes extends Model {
    
    // Nombre de la tabla a la que pertenece el modelo

    protected $table = "loan_type_codes";

    /**
     * The attributes that are mass assignable.
     *
     * @var Integer  montoMaximo
     */

    protected $fillable = ['uuid',
                           'loan_code',
                           'loantype_id',
                           'organism_id'];

    /* 
     * RELACIONES 
     */

    /**
      * El código del tipo de préstamo es asignado por un tipo de prestamo
      * 
      * @return type
      */ 

    public function loantypes() {

        return $this->belongsTo(Loantypes::class);
    }


    /**
      * El código del tipo de préstamo es asignado por un prestamo
      * 
      * @return type
      */ 
    public function loan() 
    {
        return $this->belongsTo(Loan::class);
    }

    /**
      * El código del tipo de préstamo es asignado por un organismo
      * 
      * @return type
      */ 

    public function organism() {

        return $this->belongsTo(Organism::class);
    }
}
