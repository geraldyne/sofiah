<?php

$api = app('Dingo\Api\Routing\Router');


$api->version('v1', ['namespace' => 'App\Http\Controllers'], function($api){

    $api->group(['middleware' => 'api'], function($api) {

        $api->get('ping', 'Api\PingController@index');

        $api->group(['middleware' => ['auth:api']], function ($api) {

            # RUTAS DEL MÓDULO ADMINISTRATIVO

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

                        # Rutas para las direcciones
                         
                            $api->resource('directions', 'Api\Administrative\DividendsController', ['except' => ['edit', 'create']]);

                    # RUTAS DE ASOCIACIONES

                        $api->get('associations/edit', 'Api\AssociationsController@edit');
                        $api->get('associations/create', 'Api\AssociationsController@create');
                        $api->post('associations/image', 'Api\AssociationsController@storeImg');
                        $api->resource('associations', 'Api\AssociationsController', ['except' => ['show']]);

                    # RUTAS DE ORGANISMOS

                        $api->get('organisms/edit', 'Api\Administrative\OrganismsController@edit');
                        $api->get('organisms/create', 'Api\Administrative\OrganismsController@create');
                        $api->resource('organisms', 'Api\Administrative\OrganismsController');

                    # RUTAS DE ASOCIADOS

                        $api->get('partners/create', 'Api\Administrative\PartnerController@create');
                        $api->post('partners/updatedatabank', 'Api\Administrative\PartnerController@updateDataBanks');
                        $api->resource('partners', 'Api\Administrative\PartnerController', ['except' => 'destroy']);

                        $api->get('managers/create/{uuid}', 'Api\Administrative\ManagerController@create');
                        $api->resource('managers', 'Api\Administrative\ManagerController', ['except' => 'destroy']);

                        $api->get('charges', 'Api\Administrative\ChargesController@index');  

                    # RUTAS DE EMPLEADOS
                     
                        $api->get('employees/create', 'Api\Administrative\EmployeeController@create');
                        $api->post('employees/updatedatabank', 'Api\Administrative\EmployeeController@updateDataBank');
                        $api->resource('employees', 'Api\Administrative\EmployeeController', ['except' => 'destroy']);

                    # RUTAS DE INTEGRACIÓN CONTABLE

                        $api->resource('accountingintegrations', 'Api\Administrative\AccountingintegrationController', ['except' => ['edit', 'create', 'destroy']]);

                    # (ARREGLAR) RUTAS PARA MOVIMIENTOS DIARIOS
                     
                        $api->get('dailymovement/create', 'Api\Administrative\DailymovementsController@create');
                        $api->resource('dailymovement', 'Api\Administrative\DailymovementsController', ['except' => ['edit']]);

                        # Rutas para las cuentas de integración

                        $api->resource('accountingyear', 'Api\Administrative\AccountingyearController', ['except' => ['edit', 'create']]);

                        # Rutas para los bancos

                        $api->resource('banks', 'Api\Administrative\BanksController', ['except' => ['edit', 'create']]);

                        # Rutas para los flujo de efectivo

                        $api->resource('cashflow', 'Api\Administrative\CashflowController', ['except' => ['edit', 'create']]);

                        # Rutas para los flujo de efectivo

                        

                        # Rutas para las dividendos

                        $api->resource('dividends', 'Api\Administrative\DividendsController', ['except' => ['edit', 'create']]);

                        # Rutas para detalles bancarios

                        $api->group(['prefix' => 'bankdetails'], function ($api) {

                            $api->get('/', 'Api\Administrative\BankdetailsController@index');
                            $api->post('/', 'Api\Administrative\BankdetailsController@store');
                            $api->get('/{uuid}', 'Api\Administrative\BankdetailsController@show');
                            $api->put('/{uuid}', 'Api\Administrative\BankdetailsController@update');
                            $api->patch('/{uuid}', 'Api\Administrative\BankdetailsController@update');
                            $api->delete('/{uuid}', 'Api\Administrative\BankdetailsController@destroy');
                        });                        
                    
                    # RUTAS PARA LAS CUENTAS Y SUS NIVELES
                    
                        $api->resource('accountlvl1', 'Api\Administrative\Accountlvl1Controller', ['except' => ['edit', 'create', 'update', 'store', 'destroy']]);

                        $api->resource('accountlvl2', 'Api\Administrative\Accountlvl2Controller', ['except' => ['edit', 'create', 'update', 'store', 'destroy']]);

                        $api->resource('accountlvl3', 'Api\Administrative\Accountlvl3Controller', ['except' => ['edit', 'create', 'update', 'store', 'destroy']]);

                        $api->resource('accountlvl4', 'Api\Administrative\Accountlvl4Controller', ['except' => ['edit', 'create', 'update', 'store', 'destroy']]);

                        $api->resource('accountlvl5', 'Api\Administrative\Accountlvl5Controller', ['except' => ['edit', 'create', 'update', 'store', 'destroy']]);

                        $api->resource('accountlvl6', 'Api\Administrative\Accountlvl6Controller', ['except' => ['edit', 'create']]);

                        # RUTAS DE REPORTES
                     
                        # Reporte de balance de comprobación
                        
                            $api->post('report/checkingbalance', 'Api\Administrative\Reports\CheckingBalanceController@generate');


                        # Reporte de estado de ganancias y pérdidas
                        
                            $api->post('report/incomestatement', 'Api\Administrative\Reports\IncomeStatementController@generate');

                        # Reporte de balance general
                        
                            $api->post('report/balancesheet', 'Api\Administrative\Reports\BalanceSheetController@generate');

                        # Reporte de libro diario
                            
                            $api->post('report/diarybook', 'Api\Administrative\Reports\DiaryBookController@generate');

                        # Reporte de mayor analítico
                            
                            $api->post('report/analitic', 'Api\Administrative\Reports\AnaliticController@generate');
                });

            # RUTAS DEL MÓDULO OPERATIVO

                $api->group(['prefix' => 'operative'], function($api) {

                    # Index
                    # Store
                    # Show
                    # Update (put, patch)
                    # Destroy

                    # Rutas para los tipos de préstamos

                    $api->resource('loantypes', 'Api\Operative\LoantypesController', ['except' => ['edit', 'create']]);

                    $api->resource('view/loantypes', 'Api\Operative\viewLoantypesController');

                    # Rutas para los tipos de préstamos

                    $api->resource('specialfee', 'Api\Operative\SpecialfeeController', ['except' => ['edit', 'create']]);


                    # Rutas para los grupos de tipo de préstamos

                    $api->resource('loantypegroups', 'Api\Operative\LoantypegroupsController', ['except' => ['edit', 'create']]);

                    $api->resource('loansgroups', 'Api\Operative\LoansgroupsController', ['except' => ['edit', 'create']]);

                    
                    # Rutas para préstamos

                    $api->resource('loan', 'Api\Operative\LoanController', ['except' => ['edit', 'create']]);

                    # Ruta para cuota especial

                    $api->resource('specialfeedetails', 'Api\Operative\SpecialfeedetailsController', ['except' => ['edit', 'create']]);

                    # Ruta para la disponibilidad de un prestamo

                    $api->post('view/loan/disponibility', 'Api\Operative\viewLoanController@disponibility');

                    # Ruta para un prestamo

                    $api->resource('view/loan', 'Api\Operative\viewLoanController', ['except' => ['edit', 'create', 'disponibility']]);

                    $api->resource('loantypecodes', 'Api\Operative\LoantypecodesController', ['except' => ['edit', 'create']]);

                    $api->resource('amortdefloans', 'Api\Operative\AmortdefloansController', ['except' => ['edit', 'create']]);


                    # Ruta para abono de un prestamo

                    $api->post('loanmovements/querypartner', 'Api\Operative\LoanmovementsController@querypartner');

                    $api->resource('loanmovements', 'Api\Operative\LoanmovementsController', ['except' => ['edit', 'create']]);

                    # Ruta para emisiones de prestamos

                    $api->resource('issue', 'Api\Operative\IssueController', ['except' => ['edit', 'create']]);

                    $api->resource('issuedetails', 'Api\Operative\IssuedetailsController', ['except' => ['edit', 'create']]);

                    $api->resource('amortdef', 'Api\Operative\AmortdefController', ['except' => ['edit', 'create']]);

                    # Rutas para amortizacion 

                    $api->resource('amortdef', 'Api\Operative\viewAmortdefController', ['except' => ['edit', 'create']]);

                    # Rutas para la emision

                    $api->resource('amortdefdetails', 'Api\Operative\AmortdefdetailsController', ['except' => ['edit', 'create']]);

                    # Rutas para emision detalles

                    $api->resource('assetstypecodes', 'Api\Operative\AssetstypecodesController', ['except' => ['edit', 'create']]);

                    $api->resource('provider', 'Api\Operative\ProviderController', ['except' => ['edit', 'create']]);

                    $api->resource('bond', 'Api\Operative\BondController', ['except' => ['edit', 'create']]);

                    $api->resource('policie', 'Api\Operative\PolicieController', ['except' => ['edit', 'create']]);


                    # Rutas para Fiadores

                    $api->resource('guarantor', 'Api\Operative\GuarantorController', ['except' => ['edit', 'create']]);

                     # Rutas para movimiento de haberes

                    $api->post('assetsmovements/disponibility', 'Api\Operative\AssetsmovementsController@assetsdisponibility');

                    $api->resource('assetsmovements', 'Api\Operative\AssetsmovementsController', ['except' => ['edit', 'create', 'assetspartner']]);

                    # Rutas para movimiento de haberes detalles

                    $api->resource('assetsmovementsdetails', 'Api\Operative\AssetsmovementsdetailsController', ['except' => ['edit', 'create']]);

                    $api->resource('assetsbalance', 'Api\Operative\AssetsbalanceController', ['except' => ['edit', 'create']]);

                    # Rutas para Movimientos Amortizacion Prestamos

                    $api->resource('loanamortmovements', 'Api\Operative\LoanamortmovementsController', ['except' => ['edit', 'create']]);

                });

            # RUTAS DEL MÓDULO DE USUARIOS

                $api->group(['prefix' => 'users'], function ($api) {

                    # Rutas para los usuarios

                    $api->resource('users', 'Api\Users\UsersController');

                    # Rutas para los roles

                    $api->resource('roles', 'Api\Users\RolesController');

                    # Rutas para los permisos

                    $api->resource('permissions', 'Api\Users\PermissionsController');

                    # Ruta para las preferencias del sistema

                    $api->resource('preferences', 'Api\Users\PreferencesController', ['except' => ['edit', 'create']]);

                    # Ruta para importar/exportar archivos 

                    $api->group(['prefix' => 'files'], function($api) {

                        $api->post('exportPartners', 'Api\FilesController@exportPartners');

                        $api->post('importPartners', 'Api\FilesController@importPartners');
                    });

                    # Ruta para los perfiles de usuario

                    $api->resource('me', 'Api\Users\ProfileController', ['except' => ['updatePassword']]);

                    $api->group(['prefix' => 'assets'], function($api) {

                        $api->post('/', 'Api\Assets\UploadFileController@store');
                    });

                });
            
            # FIN DE LOS GRUPOS DE RUTAS
        });
    });
});
