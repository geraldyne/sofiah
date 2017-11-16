<?php

$api = app('Dingo\Api\Routing\Router');


$api->version('v1', ['namespace' => 'App\Http\Controllers'], function($api){

    $api->group(['middleware' => 'api'], function($api) {

        $api->get('ping', 'Api\PingController@index');

        $api->group(['middleware' => ['auth:api']], function ($api) {

            # Rutas del módulo administrativo
            
            $api->group(['prefix' => 'administrative'], function ($api) {

                # Index
                # Store
                # Show
                # Update (put, patch)
                # Destroy

                $api->get('/', 'Api\Administrative\AdministrativeController@index');

                # Rutas para las cuentas de integración

                $api->resource('accountingintegration', 'Api\Administrative\AccountingintegrationController', ['except' => ['edit', 'create']]);

                # Rutas para las cuentas de integración

                $api->resource('accountingyear', 'Api\Administrative\AccountingyearController', ['except' => ['edit', 'create']]);

                # Rutas para las asociaciones

                $api->get('associations/create', 'Api\AssociationsController@create');
                $api->resource('associations', 'Api\AssociationsController', ['except' => ['edit', 'show', 'create']]);

                # Rutas para los paises
                
                $api->resource('countries', 'Api\Administrative\CountriesController', ['except' => ['edit', 'create']]);

                # Rutas para los estados
                
                $api->resource('states', 'Api\Administrative\StatesController', ['except' => ['edit', 'create']]);

                # Rutas para las ciudades
                
                $api->resource('cities', 'Api\Administrative\CitiesController', ['except' => ['edit', 'create']]);

                # Rutas para los organismos

                $api->resource('organisms', 'Api\Administrative\OrganismsController', ['except' => ['edit', 'create']]);

                # Rutas para los bancos
                
                $api->resource('banks', 'Api\Administrative\BanksController', ['except' => ['edit', 'create']]);

                # Rutas para los flujo de efectivo
                
                $api->resource('cashflow', 'Api\Administrative\CashflowController', ['except' => ['edit', 'create']]);

                # Rutas para los flujo de efectivo
                
                $api->resource('charges', 'Api\Administrative\ChargesController', ['except' => ['edit', 'create']]);

                # Rutas para los asociados
                
                $api->get('partner/create', 'Api\Administrative\PartnerController@create');
                $api->resource('partner', 'Api\Administrative\PartnerController', ['except' => 'edit']);

                # Rutas para las dividendos
                
                $api->resource('dividends', 'Api\Administrative\DividendsController', ['except' => ['edit', 'create']]);

                # Rutas para las direcciones
                
                $api->group(['prefix' => 'direction'], function ($api) {

                    $api->get('/', 'Api\Administrative\DirectionController@index');
                    $api->post('/', 'Api\Administrative\DirectionController@store');
                    $api->get('/{uuid}', 'Api\Administrative\DirectionController@show');
                    $api->put('/{uuid}', 'Api\Administrative\DirectionController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\DirectionController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\DirectionController@destroy');
                });

                # Rutas para detalles bancarios
                
                $api->group(['prefix' => 'bankdetails'], function ($api) {

                    $api->get('/', 'Api\Administrative\BankdetailsController@index');
                    $api->post('/', 'Api\Administrative\BankdetailsController@store');
                    $api->get('/{uuid}', 'Api\Administrative\BankdetailsController@show');
                    $api->put('/{uuid}', 'Api\Administrative\BankdetailsController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\BankdetailsController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\BankdetailsController@destroy');
                });

                # Rutas para los empleado
                
                $api->group(['prefix' => 'employee'], function ($api) {

                    $api->get('/', 'Api\Administrative\EmployeeController@index');
                    $api->post('/', 'Api\Administrative\EmployeeController@store');
                    $api->get('/{uuid}', 'Api\Administrative\EmployeeController@show');
                    $api->put('/{uuid}', 'Api\Administrative\EmployeeController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\EmployeeController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\EmployeeController@destroy');
                });

                # Rutas para los directivos
                
                $api->group(['prefix' => 'managers'], function ($api) {

                    $api->get('/', 'Api\Administrative\ManagerController@index');
                    $api->post('/', 'Api\Administrative\ManagerController@store');
                    $api->get('/{uuid}', 'Api\Administrative\ManagerController@show');
                    $api->put('/{uuid}', 'Api\Administrative\ManagerController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\ManagerController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\ManagerController@destroy');
                });

                # Rutas para las cuentas nivel 1
                
                $api->group(['prefix' => 'accountlvl1'], function ($api) {

                    $api->get('/', 'Api\Administrative\Accountlvl1Controller@index');
                    $api->post('/', 'Api\Administrative\Accountlvl1Controller@store');
                    $api->get('/{uuid}', 'Api\Administrative\Accountlvl1Controller@show');
                    $api->put('/{uuid}', 'Api\Administrative\Accountlvl1Controller@update');
                    $api->patch('/{uuid}', 'Api\Administrative\Accountlvl1Controller@update');
                    $api->delete('/{uuid}', 'Api\Administrative\Accountlvl1Controller@destroy');
                });

                # Rutas para las cuentas nivel 2
                
                $api->group(['prefix' => 'accountlvl2'], function ($api) {

                    $api->get('/', 'Api\Administrative\Accountlvl2Controller@index');
                    $api->post('/', 'Api\Administrative\Accountlvl2Controller@store');
                    $api->get('/{uuid}', 'Api\Administrative\Accountlvl2Controller@show');
                    $api->put('/{uuid}', 'Api\Administrative\Accountlvl2Controller@update');
                    $api->patch('/{uuid}', 'Api\Administrative\Accountlvl2Controller@update');
                    $api->delete('/{uuid}', 'Api\Administrative\Accountlvl2Controller@destroy');
                });

                # Rutas para las cuentas nivel 3
                
                $api->group(['prefix' => 'accountlvl3'], function ($api) {

                    $api->get('/', 'Api\Administrative\Accountlvl3Controller@index');
                    $api->post('/', 'Api\Administrative\Accountlvl3Controller@store');
                    $api->get('/{uuid}', 'Api\Administrative\Accountlvl3Controller@show');
                    $api->put('/{uuid}', 'Api\Administrative\Accountlvl3Controller@update');
                    $api->patch('/{uuid}', 'Api\Administrative\Accountlvl3Controller@update');
                    $api->delete('/{uuid}', 'Api\Administrative\Accountlvl3Controller@destroy');
                });

                # Rutas para las cuentas nivel 4
                
                $api->group(['prefix' => 'accountlvl4'], function ($api) {

                    $api->get('/', 'Api\Administrative\Accountlvl4Controller@index');
                    $api->post('/', 'Api\Administrative\Accountlvl4Controller@store');
                    $api->get('/{uuid}', 'Api\Administrative\Accountlvl4Controller@show');
                    $api->put('/{uuid}', 'Api\Administrative\Accountlvl4Controller@update');
                    $api->patch('/{uuid}', 'Api\Administrative\Accountlvl4Controller@update');
                    $api->delete('/{uuid}', 'Api\Administrative\Accountlvl4Controller@destroy');
                });

                # Rutas para las cuentas nivel 5

                $api->resource('accountlvl5', 'Api\Administrative\Accountlvl5Controller', ['except' => ['edit', 'create']]);

                # Rutas para las cuentas nivel 6
                
                $api->group(['prefix' => 'accountlvl6'], function ($api) {

                    $api->get('/', 'Api\Administrative\Accountlvl6Controller@index');
                    $api->post('/', 'Api\Administrative\Accountlvl6Controller@store');
                    $api->get('/{uuid}', 'Api\Administrative\Accountlvl6Controller@show');
                    $api->put('/{uuid}', 'Api\Administrative\Accountlvl6Controller@update');
                    $api->patch('/{uuid}', 'Api\Administrative\Accountlvl6Controller@update');
                    $api->delete('/{uuid}', 'Api\Administrative\Accountlvl6Controller@destroy');
                });
            });

            # Rutas del módulo operativo

            $api->group(['prefix' => 'operative'], function($api){

                # Index
                # Store
                # Show
                # Update (put, patch)
                # Destroy

                # Rutas para los tipos de préstamos

                $api->resource('loantypes', 'Api\Operative\LoantypesController', ['except' => ['edit', 'create']]);


                # Rutas para los tipos de préstamos

                $api->resource('view/loantypes', 'Api\Operative\viewLoantypesController');


                # Rutas para los grupos de tipo de préstamos

                $api->resource('loantypegroups', 'Api\Operative\LoantypegroupsController', ['except' => ['edit', 'create']]);


                # Rutas para los grupos de préstamos

                $api->resource('loansgroups', 'Api\Operative\LoansgroupsController', ['except' => ['edit', 'create']]);


                # Rutas para cuota especial

                $api->resource('specialfee', 'Api\Operative\SpecialfeeController', ['except' => ['edit', 'create']]);


                # Rutas para cuota especial detalles

                $api->resource('specialfeedetails', 'Api\Operative\SpecialfeedetailsController', ['except' => ['edit', 'create']]);


                # Rutas para prestamos

                $api->resource('loan', 'Api\Operative\LoanController', ['except' => ['edit', 'create']]);


                # Rutas para prestamos

                $api->post('view/loan/disponibility', 'Api\Operative\viewLoanController@disponibility');

                $api->resource('view/loan', 'Api\Operative\viewLoanController', ['except' => ['edit', 'create', 'disponibility']]);


                # Rutas para codigos de tipo de prestamos

                $api->resource('loantypecodes', 'Api\Operative\LoantypecodesController', ['except' => ['edit', 'create']]);


                # Rutas para amortizacion prestamos

                $api->resource('amortdefloans', 'Api\Operative\AmortdefloansController', ['except' => ['edit', 'create']]);

                # Rutas para amortizacion detalles

                $api->resource('loanmovements', 'Api\Operative\LoanmovementsController', ['except' => ['edit', 'create']]);

                # Rutas para la emision 

                $api->resource('issue', 'Api\Operative\IssueController', ['except' => ['edit', 'create']]);


                # Rutas para emision detalles

                $api->resource('issuedetails', 'Api\Operative\IssuedetailsController', ['except' => ['edit', 'create']]);


                # Rutas para amortizacion 

                $api->resource('amortdef', 'Api\Operative\AmortdefController', ['except' => ['edit', 'create']]);

                # Rutas para amortizacion 

                $api->resource('amortdef', 'Api\Operative\viewAmortdefController', ['except' => ['edit', 'create']]);


                # Rutas para amortizacion detalles

                $api->resource('amortdefdetails', 'Api\Operative\AmortdefdetailsController', ['except' => ['edit', 'create']]);

                # Rutas para codigo tipo haberes

                $api->resource('assetstypecodes', 'Api\Operative\AssetstypecodesController', ['except' => ['edit', 'create']]);


                # Rutas para proveedores

                $api->resource('provider', 'Api\Operative\ProviderController', ['except' => ['edit', 'create']]);


                # Rutas para fianzas

                $api->resource('bond', 'Api\Operative\BondController', ['except' => ['edit', 'create']]);


                # Rutas para polizas

                $api->resource('policie', 'Api\Operative\PolicieController', ['except' => ['edit', 'create']]);


                # Rutas para Fiadores

                $api->resource('guarantor', 'Api\Operative\GuarantorController', ['except' => ['edit', 'create']]);


                # Rutas para movimientos haberes 

                $api->resource('assetsmovements', 'Api\Operative\AssetsmovementsController', ['except' => ['edit', 'create']]);


                # Rutas para movimientos haberes detalles

                $api->resource('assetsmovementsdetails', 'Api\Operative\AssetsmovementsdetailsController', ['except' => ['edit', 'create']]);

                # Rutas para saldo haberes 

                $api->resource('assetsbalance', 'Api\Operative\AssetsbalanceController', ['except' => ['edit', 'create']]);

                # Rutas para Movimientos Amortizacion Prestamos

                $api->resource('loanamortmovements', 'Api\Operative\LoanamortmovementsController', ['except' => ['edit', 'create']]);

            });
            
            # Rutas del módulo de usuarios
             
            $api->group(['prefix' => 'users'], function ($api) {

                # Rutas para los usuarios
                
                $api->group(['prefix' => 'users'], function ($api) {

                    $api->get('/', 'Api\Users\UsersController@index');
                    $api->post('/', 'Api\Users\UsersController@store');
                    $api->get('/{uuid}', 'Api\Users\UsersController@show');
                    $api->put('/{uuid}', 'Api\Users\UsersController@update');
                    $api->patch('/{uuid}', 'Api\Users\UsersController@update');
                    $api->delete('/{uuid}', 'Api\Users\UsersController@destroy');
                });
                
                $api->group(['prefix' => 'roles'], function ($api) {

                    $api->get('/', 'Api\Users\RolesController@index');
                    $api->post('/', 'Api\Users\RolesController@store');
                    $api->get('/{uuid}', 'Api\Users\RolesController@show');
                    $api->put('/{uuid}', 'Api\Users\RolesController@update');
                    $api->patch('/{uuid}', 'Api\Users\RolesController@update');
                    $api->delete('/{uuid}', 'Api\Users\RolesController@destroy');
                });

                $api->group(['prefix' => 'permissions'], function ($api) {

                    $api->get('/', 'Api\Users\PermissionsController@index');
                    $api->post('/', 'Api\Users\PermissionsController@store');
                    $api->get('/{uuid}', 'Api\Users\PermissionsController@show');
                });

                $api->group(['prefix' => 'me'], function($api) {

                    $api->get('/', 'Api\Users\ProfileController@index');
                    $api->put('/', 'Api\Users\ProfileController@update');
                    $api->patch('/', 'Api\Users\ProfileController@update');
                    $api->put('/password', 'Api\Users\ProfileController@updatePassword');
                });

                $api->group(['prefix' => 'assets'], function($api) {

                    $api->post('/', 'Api\Assets\UploadFileController@store');
                });

                # Ruta para importar/exportar archivos 

                $api->group(['prefix' => 'files'], function($api) {

                    $api->post('exportPartners', 'Api\FilesController@exportPartners');

                    $api->post('importPartners', 'Api\FilesController@importPartners');
                });

            });
        });
    });
});