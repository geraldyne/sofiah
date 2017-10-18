<?php

use Illuminate\Database\Seeder;

use Webpatser\Uuid\Uuid;

class ChargesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['cargos' => [

			['cargo'=>'Presidente del consejo de administración'],
			['cargo'=>'Presidente suplente del consejo de administración'],
			['cargo'=>'Tesorero'],
			['cargo'=>'Tesorero suplente'],
			['cargo'=>'Secretario del consejo de administración'],
			['cargo'=>'Secretario suplente del consejo de administración'],

			['cargo'=>'Presidente del consejo de vigilancia'],
			['cargo'=>'Presidente suplente del consejo de vigilancia'],
			['cargo'=>'Vice-Presidente del consejo de vigilancia'],
			['cargo'=>'Vice-Presidente suplente del consejo de vigilancia'],
			['cargo'=>'Secretario del consejo de vigilancia'],
			['cargo'=>'Secretario suplente del consejo de vigilancia'],

			['cargo'=>'Delegado'],

		]];

		// Insertar datos en la BD
	
        foreach($data['cargos'] as $cargo) {
         	
         	// Inserta las cuentas en la BD

         	DB::table('charges')->insert(['charge' => $cargo['cargo'], 'uuid' => Uuid::generate()->string]);
        }
    }
}
