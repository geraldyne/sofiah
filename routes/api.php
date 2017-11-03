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

                # RUTA PRINCIPAL

                    $api->get('/', 'Api\Administrative\AdministrativeController@index');

                # RUTAS DE DIRECCION

                    # Rutas para los paises

                        $api->resource('countries', 'Api\Administrative\CountriesController');

                    # Rutas para los estados

                        $api->resource('states', 'Api\Administrative\StatesController');

                    # Rutas para las ciudades

                        $api->resource('cities', 'Api\Administrative\CitiesController');

                # RUTAS DE ASOCIACIONES

                    $api->get('associations/edit', 'Api\AssociationsController@edit');
                    $api->get('associations/create', 'Api\AssociationsController@create');
                    $api->resource('associations', 'Api\AssociationsController', ['except' => ['show']]);

                # RUTAS DE ORGANISMOS

                    $api->get('organisms/edit', 'Api\Administrative\OrganismsController@edit');
                    $api->get('organisms/create', 'Api\Administrative\OrganismsController@create');
                    $api->resource('organisms', 'Api\Administrative\OrganismsController');

                # RUTAS DE ASOCIADOS

                    $api->get('partner/create', 'Api\Administrative\PartnerController@create');
                    $api->resource('partner', 'Api\Administrative\PartnerController', ['except' => 'edit']);

                # RUTAS DE EMPLEADOS
                 
                    $api->get('employee/create', 'Api\Administrative\EmployeeController@create');
                    $api->resource('employee', 'Api\Administrative\EmployeeController', ['except' => 'edit']);

                # RUTAS DE INTEGRACIÓN CONTABLE

                    $api->resource('accountingintegration', 'Api\Administrative\AccountingintegrationController', ['except' => ['edit', 'create']]);

                # RUTAS PARA MOVIMIENTOS DIARIOS
                 
                    $api->get('dailymovement/create', 'Api\Administrative\DailymovementsController@create');
                    $api->resource('dailymovement', 'Api\Administrative\DailymovementsController', ['except' => ['create']]);


                # Rutas para las cuentas de integración

                $api->resource('accountingyear', 'Api\Administrative\AccountingyearController', ['except' => ['edit', 'create']]);

                # Rutas para los bancos

                $api->resource('banks', 'Api\Administrative\BanksController', ['except' => ['edit', 'create']]);

                # Rutas para los flujo de efectivo

                $api->resource('cashflow', 'Api\Administrative\CashflowController', ['except' => ['edit', 'create']]);

                # Rutas para los flujo de efectivo

                $api->resource('charges', 'Api\Administrative\ChargesController', ['except' => ['edit', 'create']]);

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

                $api->resource('accountlvl1', 'Api\Administrative\Accountlvl1Controller', ['except' => ['edit', 'create', 'update', 'store', 'destroy']]);

                # Rutas para las cuentas nivel 2

                $api->resource('accountlvl2', 'Api\Administrative\Accountlvl2Controller', ['except' => ['edit', 'create', 'update', 'store', 'destroy']]);

                # Rutas para las cuentas nivel 3

                $api->resource('accountlvl3', 'Api\Administrative\Accountlvl3Controller', ['except' => ['edit', 'create', 'update', 'store', 'destroy']]);

                # Rutas para las cuentas nivel 4

                $api->resource('accountlvl4', 'Api\Administrative\Accountlvl4Controller', ['except' => ['edit', 'create', 'update', 'store', 'destroy']]);

                # Rutas para las cuentas nivel 5

                $api->resource('accountlvl5', 'Api\Administrative\Accountlvl5Controller', ['except' => ['edit', 'create', 'update', 'store', 'destroy']]);

                # Rutas para las cuentas nivel 6

                $api->resource('accountlvl6', 'Api\Administrative\Accountlvl6Controller', ['except' => ['edit', 'create']]);
            });

            # Rutas del módulo operativo

            $api->group(['prefix' => 'operative'], function($api){

                # Rutas para los tipos de préstamos

                $api->group(['prefix' => 'loantypes'], function($api){

                    $api->get('/', 'Api\Operative\LoantypesController@index');
                    $api->post('/', 'Api\Operative\LoantypesController@store');
                    $api->get('/{uuid}', 'Api\Operative\LoantypesController@show');
                    $api->put('/{uuid}', 'Api\Operative\LoantypesController@update');
                    $api->patch('/{uuid}', 'Api\Operative\LoantypesController@update');
                    $api->delete('/{uuid}', 'Api\Operative\LoantypesController@destroy');
                });


                # Rutas para los tipos de préstamos

                $api->group(['prefix' => 'view/loantypes'], function($api){

                    $api->get('/', 'Api\Operative\viewLoantypesController@index');
                    $api->get('/create', 'Api\Operative\viewLoantypesController@create');
                    $api->post('/', 'Api\Operative\viewLoantypesController@store');
                    $api->get('/{uuid}', 'Api\Operative\viewLoantypesController@show');
                    $api->put('/{uuid}', 'Api\Operative\viewLoantypesController@update');
                    $api->patch('/{uuid}', 'Api\Operative\viewLoantypesController@update');
                    $api->delete('/{uuid}', 'Api\Operative\viewLoantypesController@destroy');
                });

                # Rutas para los grupos de tipo de préstamos

                $api->group(['prefix' => 'loantypegroups'], function($api){

                    $api->get('/', 'Api\Operative\LoantypegroupsController@index');
                    $api->post('/', 'Api\Operative\LoantypegroupsController@store');
                    $api->get('/{uuid}', 'Api\Operative\LoantypegroupsController@show');
                    $api->put('/{uuid}', 'Api\Operative\LoantypegroupsController@update');
                    $api->patch('/{uuid}', 'Api\Operative\LoantypegroupsController@update');
                    $api->delete('/{uuid}', 'Api\Operative\LoantypegroupsController@destroy');
                });

                # Rutas para los grupos de préstamos

                $api->group(['prefix' => 'loansgroups'], function($api){

                    $api->get('/', 'Api\Operative\LoansgroupsController@index');
                    $api->post('/', 'Api\Operative\LoansgroupsController@store');
                    $api->get('/{uuid}', 'Api\Operative\LoansgroupsController@show');
                    $api->put('/{uuid}', 'Api\Operative\LoansgroupsController@update');
                    $api->patch('/{uuid}', 'Api\Operative\LoansgroupsController@update');
                });


                # Rutas para cuota especial

                $api->group(['prefix' => 'specialfee'], function($api){

                    $api->get('/', 'Api\Operative\SpecialfeeController@index');
                    $api->post('/', 'Api\Operative\SpecialfeeController@store');
                    $api->get('/{uuid}', 'Api\Operative\SpecialfeeController@show');
                    $api->put('/{uuid}', 'Api\Operative\SpecialfeeController@update');
                    $api->patch('/{uuid}', 'Api\Operative\SpecialfeeController@update');
                });


                # Rutas para cuota especial detalles

                $api->group(['prefix' => 'specialfeedetails'], function($api){

                    $api->get('/', 'Api\Operative\SpecialfeedetailsController@index');
                    $api->post('/', 'Api\Operative\SpecialfeedetailsController@store');
                    $api->get('/{uuid}', 'Api\Operative\SpecialfeedetailsController@show');
                    $api->put('/{uuid}', 'Api\Operative\SpecialfeedetailsController@update');
                    $api->patch('/{uuid}', 'Api\Operative\SpecialfeedetailsController@update');
                });

                # Rutas para prestamos

                $api->group(['prefix' => 'loan'], function($api){

                    $api->get('/', 'Api\Operative\LoanController@index');
                    $api->post('/', 'Api\Operative\LoanController@store');
                    $api->get('/{uuid}', 'Api\Operative\LoanController@show');
                    $api->put('/{uuid}', 'Api\Operative\LoanController@update');
                    $api->patch('/{uuid}', 'Api\Operative\LoanController@update');
                });


                # Rutas para prestamos

                $api->group(['prefix' => 'view/loan'], function($api){

                    $api->get('/', 'Api\Operative\viewLoanController@index');
                    $api->post('/create', 'Api\Operative\viewLoanController@create');
                    $api->post('/', 'Api\Operative\viewLoanController@store');
                    $api->get('/{uuid}', 'Api\Operative\viewLoanController@show');
                    $api->put('/{uuid}', 'Api\Operative\viewLoanController@update');
                    $api->patch('/{uuid}', 'Api\Operative\viewLoanController@update');
                });


                # Rutas para codigos de tipo de prestamos

                $api->group(['prefix' => 'loantypecodes'], function($api){

                    $api->get('/', 'Api\Operative\LoantypecodesController@index');
                    $api->post('/', 'Api\Operative\LoantypecodesController@store');
                    $api->get('/{uuid}', 'Api\Operative\LoantypecodesController@show');
                    $api->put('/{uuid}', 'Api\Operative\LoantypecodesController@update');
                    $api->patch('/{uuid}', 'Api\Operative\LoantypecodesController@update');
                });


                # Rutas para amortizacion prestamos

                $api->group(['prefix' => 'amortdefloans'], function($api){

                    $api->get('/', 'Api\Operative\AmortdefloansController@index');
                    $api->post('/', 'Api\Operative\AmortdefloansController@store');
                    $api->get('/{uuid}', 'Api\Operative\AmortdefloansController@show');
                    $api->put('/{uuid}', 'Api\Operative\AmortdefloansController@update');
                    $api->patch('/{uuid}', 'Api\Operative\AmortdefloansController@update');
                    $api->delete('/{uuid}', 'Api\Operative\AmortdefloansController@destroy');
                });

                # Rutas para amortizacion detalles

                $api->group(['prefix' => 'loanmovements'], function($api){

                    $api->get('/', 'Api\Operative\LoanmovementsController@index');
                    $api->post('/', 'Api\Operative\LoanmovementsController@store');
                    $api->get('/{uuid}', 'Api\Operative\LoanmovementsController@show');
                    $api->put('/{uuid}', 'Api\Operative\LoanmovementsController@update');
                    $api->patch('/{uuid}', 'Api\Operative\LoanmovementsController@update');
                });

                # Rutas para la emision

                $api->group(['prefix' => 'issue'], function($api){

                    $api->get('/', 'Api\Operative\IssueController@index');
                    $api->post('/', 'Api\Operative\IssueController@store');
                    $api->get('/{uuid}', 'Api\Operative\IssueController@show');
                    $api->put('/{uuid}', 'Api\Operative\IssueController@update');
                    $api->patch('/{uuid}', 'Api\Operative\IssueController@update');
                });


                # Rutas para emision detalles

                $api->group(['prefix' => 'issuedetails'], function($api){

                    $api->get('/', 'Api\Operative\IssuedetailsController@index');
                    $api->post('/', 'Api\Operative\IssuedetailsController@store');
                    $api->get('/{uuid}', 'Api\Operative\IssuedetailsController@show');
                    $api->put('/{uuid}', 'Api\Operative\IssuedetailsController@update');
                    $api->patch('/{uuid}', 'Api\Operative\IssuedetailsController@update');
                });


                # Rutas para amortizacion

                $api->group(['prefix' => 'amortdef'], function($api){

                    $api->get('/', 'Api\Operative\AmortdefController@index');
                    $api->post('/', 'Api\Operative\AmortdefController@store');
                    $api->get('/{uuid}', 'Api\Operative\AmortdefController@show');
                    $api->put('/{uuid}', 'Api\Operative\AmortdefController@update');
                    $api->patch('/{uuid}', 'Api\Operative\AmortdefController@update');
                });


                # Rutas para amortizacion detalles

                $api->group(['prefix' => 'amortdefdetails'], function($api){

                    $api->get('/', 'Api\Operative\AmortdefdetailsController@index');
                    $api->post('/', 'Api\Operative\AmortdefdetailsController@store');
                    $api->get('/{uuid}', 'Api\Operative\AmortdefdetailsController@show');
                    $api->put('/{uuid}', 'Api\Operative\AmortdefdetailsController@update');
                    $api->patch('/{uuid}', 'Api\Operative\AmortdefdetailsController@update');
                });

                # Rutas para codigo tipo haberes

                $api->group(['prefix' => 'assetstypecodes'], function($api){

                    $api->get('/', 'Api\Operative\AssetstypecodesController@index');
                    $api->post('/', 'Api\Operative\AssetstypecodesController@store');
                    $api->get('/{uuid}', 'Api\Operative\AssetstypecodesController@show');
                    $api->put('/{uuid}', 'Api\Operative\AssetstypecodesController@update');
                    $api->patch('/{uuid}', 'Api\Operative\AssetstypecodesController@update');
                    $api->delete('/{uuid}', 'Api\Operative\AssetstypecodesController@destroy');
                });


                # Rutas para proveedores

                $api->group(['prefix' => 'provider'], function($api){

                    $api->get('/', 'Api\Operative\ProviderController@index');
                    $api->post('/', 'Api\Operative\ProviderController@store');
                    $api->get('/{uuid}', 'Api\Operative\ProviderController@show');
                    $api->put('/{uuid}', 'Api\Operative\ProviderController@update');
                    $api->patch('/{uuid}', 'Api\Operative\ProviderController@update');
                    $api->delete('/{uuid}', 'Api\Operative\ProviderController@destroy');
                });


                # Rutas para fianzas

                $api->group(['prefix' => 'bond'], function($api){

                    $api->get('/', 'Api\Operative\BondController@index');
                    $api->post('/', 'Api\Operative\BondController@store');
                    $api->get('/{uuid}', 'Api\Operative\BondController@show');
                    $api->put('/{uuid}', 'Api\Operative\BondController@update');
                    $api->patch('/{uuid}', 'Api\Operative\BondController@update');
                    $api->delete('/{uuid}', 'Api\Operative\BondController@destroy');
                });

                # Rutas para polizas

                $api->group(['prefix' => 'policie'], function($api){

                    $api->get('/', 'Api\Operative\PolicieController@index');
                    $api->post('/', 'Api\Operative\PolicieController@store');
                    $api->get('/{uuid}', 'Api\Operative\PolicieController@show');
                    $api->put('/{uuid}', 'Api\Operative\PolicieController@update');
                    $api->patch('/{uuid}', 'Api\Operative\PolicieController@update');
                    $api->delete('/{uuid}', 'Api\Operative\PolicieController@destroy');
                });


                # Rutas para polizas

                $api->group(['prefix' => 'guarantor'], function($api){

                    $api->get('/', 'Api\Operative\GuarantorController@index');
                    $api->post('/', 'Api\Operative\GuarantorController@store');
                    $api->get('/{uuid}', 'Api\Operative\GuarantorController@show');
                    $api->put('/{uuid}', 'Api\Operative\GuarantorController@update');
                    $api->patch('/{uuid}', 'Api\Operative\GuarantorController@update');
                    $api->delete('/{uuid}', 'Api\Operative\GuarantorController@destroy');
                });


                # Rutas para movimientos haberes

                $api->group(['prefix' => 'assetsmovements'], function($api){

                    $api->get('/', 'Api\Operative\AssetsmovementsController@index');
                    $api->post('/', 'Api\Operative\AssetsmovementsController@store');
                    $api->get('/{uuid}', 'Api\Operative\AssetsmovementsController@show');
                    $api->put('/{uuid}', 'Api\Operative\AssetsmovementsController@update');
                    $api->patch('/{uuid}', 'Api\Operative\AssetsmovementsController@update');
                    $api->delete('/{uuid}', 'Api\Operative\AssetsmovementsController@destroy');
                });


                # Rutas para movimientos haberes detalles

                $api->group(['prefix' => 'assetsmovementsdetails'], function($api){

                    $api->get('/', 'Api\Operative\AssetsmovementsdetailsController@index');
                    $api->post('/', 'Api\Operative\AssetsmovementsdetailsController@store');
                    $api->get('/{uuid}', 'Api\Operative\AssetsmovementsdetailsController@show');
                    $api->put('/{uuid}', 'Api\Operative\AssetsmovementsdetailsController@update');
                    $api->patch('/{uuid}', 'Api\Operative\AssetsmovementsdetailsController@update');
                    $api->delete('/{uuid}', 'Api\Operative\AssetsmovementsdetailsController@destroy');
                });

                # Rutas para saldo haberes

                $api->group(['prefix' => 'Assetsbalance'], function($api){

                    $api->get('/', 'Api\Operative\AssetsbalanceController@index');
                    $api->post('/', 'Api\Operative\AssetsbalanceController@store');
                    $api->get('/{uuid}', 'Api\Operative\AssetsbalanceController@show');
                    $api->put('/{uuid}', 'Api\Operative\AssetsbalanceController@update');
                    $api->patch('/{uuid}', 'Api\Operative\AssetsbalanceController@update');
                });

                # Rutas para Movimientos Amortizacion Prestamos

                $api->group(['prefix' => 'loanamortmovements'], function($api){

                    $api->get('/', 'Api\Operative\LoanamortmovementsController@index');
                    $api->post('/', 'Api\Operative\LoanamortmovementsController@store');
                    $api->get('/{uuid}', 'Api\Operative\LoanamortmovementsController@show');
                    $api->put('/{uuid}', 'Api\Operative\LoanamortmovementsController@update');
                    $api->patch('/{uuid}', 'Api\Operative\LoanamortmovementsController@update');
                });

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

            });
        });
    });
});
