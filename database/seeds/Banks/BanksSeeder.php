<?php

use Illuminate\Database\Seeder;

use Webpatser\Uuid\Uuid;

class BanksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['bancos' => [

			['banco'=>'Banco de Venenzuela'],
			['banco'=>'Banco Nacional de Crédito'],
			['banco'=>'Banco Banesco'],
			['banco'=>'Banco Mercantil'],
			['banco'=>'Banco Provincial']
		]];

		// Insertar datos en la BD
	
        foreach($data['bancos'] as $banco) {
         	
         	// Inserta las cuentas en la BD

         	DB::table('banks')->insert(['bank' => $banco['banco'], 'uuid' => Uuid::generate()->string]);
        }
    }
}
