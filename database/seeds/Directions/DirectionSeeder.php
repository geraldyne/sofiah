<?php

use Illuminate\Database\Seeder;

use Webpatser\Uuid\Uuid;

class DirectionSeeder extends Seeder {
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    
    public function run() {
        
        $data = ['paises' => [

					['pais' => 'República Bolivariana de Venezuela', 

						'estados' => [

							['estado' => 'Amazonas', 

								'ciudades' => [

									['codigo' => '0248', 'ciudad' => 'Puerto Ayacucho'],
									['codigo' => '0248', 'ciudad' => 'La Esmeralda'],
									['codigo' => '0248', 'ciudad' => 'San Fernando de Apure'],
									['codigo' => '0248', 'ciudad' => 'San Carlos de Río Negro']
								]
							],

							['estado' => 'Anzoátegui', 

								'ciudades' => [

									['codigo' => '0281', 'ciudad' => 'Barcelona'],
								    ['codigo' => '0281', 'ciudad' => 'Puerto la Cruz'],
								    ['codigo' => '0281', 'ciudad' => 'Puerto Píritu'],
								    ['codigo' => '0281', 'ciudad' => 'Lecherías'],
								    ['codigo' => '0281', 'ciudad' => 'Guanta'],
								    ['codigo' => '0283', 'ciudad' => 'El Tigre'],
								    ['codigo' => '0282', 'ciudad' => 'Anaco'],
								    ['codigo' => '0282', 'ciudad' => 'Cantaura']
								]
							],

							['estado' => 'Apure', // Completo

								'ciudades' => [

									['codigo' => '0247', 'ciudad' => 'Achaguas'],
						        	['codigo' => '0240', 'ciudad' => 'Biruaca'],
						        	['codigo' => '0240', 'ciudad' => 'Bruzual'],
						        	['codigo' => '0278', 'ciudad' => 'El Amparo'],
						        	['codigo' => '0278', 'ciudad' => 'El Nula'],
						        	['codigo' => '0240', 'ciudad' => 'Elorza'],
						        	['codigo' => '0278', 'ciudad' => 'Guasdualito'],
						        	['codigo' => '0247', 'ciudad' => 'Puerto Páez'],
						        	['codigo' => '0247', 'ciudad' => 'San Fernando de Apure'],
						        	['codigo' => '0247', 'ciudad' => 'San Juan de Payara']
								]
							],

							['estado' => 'Aragua', 

								'ciudades' => [

									['codigo' => '0243', 'ciudad' => 'Maracay'],
						        	['codigo' => '0244', 'ciudad' => 'Turmero'],
						        	['codigo' => '0244', 'ciudad' => 'La Victoria'],
						        	['codigo' => '0244', 'ciudad' => 'Villa de Cura'],
						        	['codigo' => '0244', 'ciudad' => 'Santa Rita'],
						        	['codigo' => '0244', 'ciudad' => 'Cagua'],
						        	['codigo' => '0244', 'ciudad' => 'El Limón']
								]
							],

							['estado' => 'Barinas', // Completo

								'ciudades' => [

									['codigo' => '0273', 'ciudad' => 'Barinas'],
						        	['codigo' => '0273', 'ciudad' => 'Barinitas'],
						        	['codigo' => '0273', 'ciudad' => 'Barrancas'],
						        	['codigo' => '0278', 'ciudad' => 'Capitanejo'],
						        	['codigo' => '0273', 'ciudad' => 'Ciudad Bolivia'],
						        	['codigo' => '0278', 'ciudad' => 'El Cantón'],
						        	['codigo' => '0273', 'ciudad' => 'Las Veguitas'],
						        	['codigo' => '0273', 'ciudad' => 'Libertad'],
						        	['codigo' => '0273', 'ciudad' => 'Sabaneta'],
						        	['codigo' => '0278', 'ciudad' => 'Santa Bárbara de Barinas'],
						        	['codigo' => '0273', 'ciudad' => 'Socopó']
								]
							],

							['estado' => 'Bolívar', // Completo

								'ciudades' => [

									['codigo' => '0284', 'ciudad' => 'Caicara del Orinoco'],
						        	['codigo' => '0285', 'ciudad' => 'Ciudad Bolívar'],
						        	['codigo' => '0285', 'ciudad' => 'Ciudad Piar'],
						        	['codigo' => '0288', 'ciudad' => 'El Callao'],
						        	['codigo' => '0288', 'ciudad' => 'El Dorado'],
						        	['codigo' => '0288', 'ciudad' => 'El Manteco'],
						        	['codigo' => '0288', 'ciudad' => 'El Palmar'],
						        	['codigo' => '0288', 'ciudad' => 'Guasipati'],
						        	['codigo' => '0285', 'ciudad' => 'La Paragua'],
						        	['codigo' => '0286', 'ciudad' => 'Puerto Ordaz'],
						        	['codigo' => '0286', 'ciudad' => 'San Félix'],
						        	['codigo' => '0289', 'ciudad' => 'Santa Elena de Uairen'],
						        	['codigo' => '0288', 'ciudad' => 'Tumeremo'],
						        	['codigo' => '0288', 'ciudad' => 'Upata']
								]
							],
							/*
							['estado' => 'Carabobo',

								'ciudades' => []
							],

							['estado' => 'Cojedes',

								'ciudades' => []
							],

							['estado' => 'Delta Amacuro',

								'ciudades' => []
							],

							['estado' => 'Distrito Capital',

								'ciudades' => []
							],

							['estado' => 'Falcón',

								'ciudades' => []
							],

							['estado' => 'Guárico',

								'ciudades' => []
							],

							['estado' => 'Lara',

								'ciudades' => []
							],

							['estado' => 'Mérida',

								'ciudades' => []
							],

							['estado' => 'Miranda',

								'ciudades' => []
							],

							['estado' => 'Monagas',

								'ciudades' => []
							],

							['estado' => 'Nueva Esparta',

								'ciudades' => []
							],

							['estado' => 'Portuguesa',

								'ciudades' => []
							],

							['estado' => 'Sucre',

								'ciudades' => []
							],

							['estado' => 'Táchira',

								'ciudades' => []
							],

							['estado' => 'Trujillo',

								'ciudades' => []
							],

							['estado' => 'Vargas',

								'ciudades' => []
							],

							['estado' => 'Yaracuy',

								'ciudades' => []
							],

							['estado' => 'Zulia',

								'ciudades' => []
							],

							['estado' => 'Dependencias Federales',

								'ciudades' => []
							]
							*/
						]
					],

					// Más paises
				]];

        // Insertar datos en la BD

		$idPais = 1;

        foreach($data['paises'] as $pais) {
         	
         	// Inserta los paises en la BD

         	DB::table('countries')->insert(['country' => $pais['pais'], 'uuid' => Uuid::generate()->string]);

         	$idEstado = 1;

         	foreach ($pais['estados'] as $estado) {
         		
         		// Inserta los estados en la BD

         		DB::table('states')->insert([

		        	'state' => $estado['estado'],
		        	'country_id' => $idPais,
		        	'uuid' => Uuid::generate()->string
		        ]);

         		foreach ($estado['ciudades'] as $ciudad) {

         			// Inserta las ciudades en la BD
         			
         			DB::table('cities')->insert([

        				'city' => $ciudad['ciudad'],
        				'area_code' => $ciudad['codigo'],
        				'state_id' => $idEstado,
        				'uuid' => Uuid::generate()->string
        			]);
         		}

         		$idEstado++;
         	}

         	$idPais++;
        }
    }
}
