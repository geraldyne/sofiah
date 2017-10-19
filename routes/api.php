<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api){

    $api->group(['middleware' => 'api', 'namespace' => 'App\Http\Controllers'], function($api) {

        $api->get('ping', 'Api\PingController@index');

        $api->group(['middleware' => ['auth:api']], function ($api) {

            # Rutas del módulo administrativo
            
            $api->group(['prefix' => 'administrative'], function ($api) {

                # Rutas para las cuentas de integración

                $api->group(['prefix' => 'accountingintegration'], function ($api) {

                    $api->get('/', 'Api\Administrative\AccountingintegrationController@index');
                    $api->post('/', 'Api\Administrative\AccountingintegrationController@store');
                    $api->get('/{uuid}', 'Api\Administrative\AccountingintegrationController@show');
                    $api->put('/{uuid}', 'Api\Administrative\AccountingintegrationController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\AccountingintegrationController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\AccountingintegrationController@destroy');
                });

                # Rutas para las cuentas de integración

                $api->group(['prefix' => 'accountingyear'], function ($api) {

                    $api->get('/', 'Api\Administrative\AccountingyearController@index');
                    $api->post('/', 'Api\Administrative\AccountingyearController@store');
                    $api->get('/{uuid}', 'Api\Administrative\AccountingyearController@show');
                    $api->put('/{uuid}', 'Api\Administrative\AccountingyearController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\AccountingyearController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\AccountingyearController@destroy');
                });

                # Rutas para las asociaciones

                $api->group(['prefix' => 'associations'], function ($api) {

                    $api->get('/', 'Api\AssociationsController@index');
                    $api->post('/', 'Api\AssociationsController@store');
                    $api->get('/{uuid}', 'Api\AssociationsController@show');
                    $api->put('/{uuid}', 'Api\AssociationsController@update');
                    $api->patch('/{uuid}', 'Api\AssociationsController@update');
                    $api->delete('/{uuid}', 'Api\AssociationsController@destroy');
                });

                # Rutas para los paises
                
                $api->group(['prefix' => 'countries'], function ($api) {

                    $api->get('/', 'Api\Administrative\CountriesController@index');
                    $api->post('/', 'Api\Administrative\CountriesController@store');
                    $api->get('/{uuid}', 'Api\Administrative\CountriesController@show');
                    $api->put('/{uuid}', 'Api\Administrative\CountriesController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\CountriesController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\CountriesController@destroy');
                });

                # Rutas para los estados
                
                $api->group(['prefix' => 'states'], function ($api) {

                    $api->get('/', 'Api\Administrative\StatesController@index');
                    $api->post('/', 'Api\Administrative\StatesController@store');
                    $api->get('/{uuid}', 'Api\Administrative\StatesController@show');
                    $api->put('/{uuid}', 'Api\Administrative\StatesController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\StatesController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\StatesController@destroy');
                });

                # Rutas para las ciudades
                
                $api->group(['prefix' => 'cities'], function ($api) {

                    $api->get('/', 'Api\Administrative\CitiesController@index');
                    $api->post('/', 'Api\Administrative\CitiesController@store');
                    $api->get('/{uuid}', 'Api\Administrative\CitiesController@show');
                    $api->put('/{uuid}', 'Api\Administrative\CitiesController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\CitiesController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\CitiesController@destroy');
                });

                # Rutas para los organismos

                $api->group(['prefix' => 'organisms'], function ($api) {

                    $api->get('/', 'Api\Administrative\OrganismsController@index');
                    $api->post('/', 'Api\Administrative\OrganismsController@store');
                    $api->get('/{uuid}', 'Api\Administrative\OrganismsController@show');
                    $api->put('/{uuid}', 'Api\Administrative\OrganismsController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\OrganismsController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\OrganismsController@destroy');
                });

                # Rutas para los bancos
                
                $api->group(['prefix' => 'banks'], function ($api) {

                    $api->get('/', 'Api\Administrative\BanksController@index');
                    $api->post('/', 'Api\Administrative\BanksController@store');
                    $api->get('/{uuid}', 'Api\Administrative\BanksController@show');
                    $api->put('/{uuid}', 'Api\Administrative\BanksController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\BanksController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\BanksController@destroy');
                });

                # Rutas para los flujo de efectivo
                
                $api->group(['prefix' => 'cashflow'], function ($api) {

                    $api->get('/', 'Api\Administrative\CashflowController@index');
                    $api->post('/', 'Api\Administrative\CashflowController@store');
                    $api->get('/{uuid}', 'Api\Administrative\CashflowController@show');
                    $api->put('/{uuid}', 'Api\Administrative\CashflowController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\CashflowController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\CashflowController@destroy');
                });

                # Rutas para los flujo de efectivo
                
                $api->group(['prefix' => 'charges'], function ($api) {

                    $api->get('/', 'Api\Administrative\ChargesController@index');
                    $api->post('/', 'Api\Administrative\ChargesController@store');
                    $api->get('/{uuid}', 'Api\Administrative\ChargesController@show');
                    $api->put('/{uuid}', 'Api\Administrative\ChargesController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\ChargesController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\ChargesController@destroy');
                });

                # Rutas para los asociados
                
                $api->group(['prefix' => 'partner'], function ($api) {

                    $api->get('/', 'Api\Administrative\PartnerController@index');
                    $api->post('/', 'Api\Administrative\PartnerController@store');
                    $api->get('/{uuid}', 'Api\Administrative\PartnerController@show');
                    $api->put('/{uuid}', 'Api\Administrative\PartnerController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\PartnerController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\PartnerController@destroy');
                });

                # Rutas para las dividendos
                
                $api->group(['prefix' => 'dividends'], function ($api) {

                    $api->get('/', 'Api\Administrative\DividendsController@index');
                    $api->post('/', 'Api\Administrative\DividendsController@store');
                    $api->get('/{uuid}', 'Api\Administrative\DividendsController@show');
                    $api->put('/{uuid}', 'Api\Administrative\DividendsController@update');
                    $api->patch('/{uuid}', 'Api\Administrative\DividendsController@update');
                    $api->delete('/{uuid}', 'Api\Administrative\DividendsController@destroy');
                });

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
                
                $api->group(['prefix' => 'accountlvl5'], function ($api) {

                    $api->get('/', 'Api\Administrative\Accountlvl5Controller@index');
                    $api->post('/', 'Api\Administrative\Accountlvl5Controller@store');
                    $api->get('/{uuid}', 'Api\Administrative\Accountlvl5Controller@show');
                    $api->put('/{uuid}', 'Api\Administrative\Accountlvl5Controller@update');
                    $api->patch('/{uuid}', 'Api\Administrative\Accountlvl5Controller@update');
                    $api->delete('/{uuid}', 'Api\Administrative\Accountlvl5Controller@destroy');
                });

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

                # Rutas para los tipos de préstamos

                $api->group(['prefix' => 'loantypes'], function($api){

                    $api->get('/', 'Api\Operative\LoantypesController@index');
                    $api->post('/', 'Api\Operative\LoantypesController@store');
                    $api->get('/{uuid}', 'Api\Operative\LoantypesController@show');
                    $api->put('/{uuid}', 'Api\Operative\LoantypesController@update');
                    $api->patch('/{uuid}', 'Api\Operative\LoantypesController@update');
                    $api->delete('/{uuid}', 'Api\Operative\LoantypesController@destroy');
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