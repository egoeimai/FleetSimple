<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$controller_path = 'App\Http\Controllers';

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ClientController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    //Route::resource('users', UserController::class);
    Route::resource('clients', ClientController::class);
});

use Illuminate\Support\Facades\Artisan;

Route::get('/trigger-reminders', function (Illuminate\Http\Request $request) {
    if ($request->input('key') !== config('app.reminder_trigger_key')) {
        abort(403, 'Unauthorized');
    }

    Artisan::call('subscriptions:send-reminders');

    return response()->json(['status' => 'Reminders sent']);
});


// Main Page Route
Route::get('/', $controller_path . '\MainDashboard@index')->name('dashboard');
Route::resource('users', UserController::class);
 //Route::resource('users', UserController::class);

Route::get('/sent-emails/{id}',  $controller_path . '\MainDashboard@show')->name('sent-emails.show');

// layout
Route::get('/layouts/without-menu', $controller_path . '\layouts\WithoutMenu@index')->name('layouts-without-menu');
Route::get('/layouts/without-navbar', $controller_path . '\layouts\WithoutNavbar@index')->name('layouts-without-navbar');
Route::get('/layouts/fluid', $controller_path . '\layouts\Fluid@index')->name('layouts-fluid');
Route::get('/layouts/container', $controller_path . '\layouts\Container@index')->name('layouts-container');
Route::get('/layouts/blank', $controller_path . '\layouts\Blank@index')->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', $controller_path . '\pages\AccountSettingsAccount@index')->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', $controller_path . '\pages\AccountSettingsNotifications@index')->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections/{client}', $controller_path . '\pages\AccountSettingsConnections@index')->name('pages-account-settings-connections');
Route::post('/pages/account-settings-connections-create', $controller_path . '\pages\AccountSettingsConnections@store')->name('addpayment');
Route::delete('/pages/account-settings-connections-delete/{payment}/{client}', $controller_path . '\pages\AccountSettingsConnections@delete')->name('deletepayment');
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', $controller_path . '\pages\MiscUnderMaintenance@index')->name('pages-misc-under-maintenance');
Route::get('/pages/clients', $controller_path . '\ClientsController@index')->name('clients');
Route::get('/pages/athlets-payments', $controller_path . '\AthletsPaymentsController@index')->name('athlets-payments');
Route::post('/pages/athlets-payments-get', $controller_path . '\AthletsPaymentsController@get_payment_earnings')->name('athlets-payments.get');
Route::get('/pages/clients-filter', $controller_path . '\ClientsController@filter')->name('clients-filter');
Route::get('/pages/clients-create', $controller_path . '\ClientsController@create')->name('clients.create');
Route::post('/pages/clients-create', $controller_path . '\ClientsController@store')->name('clients.store');
Route::put('/pages/clients-update', $controller_path . '\ClientsController@update')->name('clients.update');
//Route::delete('/pages/clients-destroy/{client}', $controller_path . '\ClientsController@destroy')->name('clients.destroy');
Route::get('/pages/clients-edit/{client}', $controller_path . '\ClientsController@edit')->name('clients.edit');
Route::get('/pages/coming-user', $controller_path . '\ComingController@index')->name('coming.index');
Route::post('/pages/coming-user-search', $controller_path . '\ComingController@search')->name('coming.search');
Route::get('/pages/entrances/{client}', $controller_path . '\EntranceControler@index')->name('entrances');
Route::post('/pages/entrances-gomonth', $controller_path . '\EntranceControler@gomonth')->name('entrances.gomonth');
Route::post('/pages/entrances-date_entrances', $controller_path . '\EntranceControler@date_entrances')->name('entrances.date_entrances');
Route::get('/pages/financial/', $controller_path . '\Finacials@index')->name('financials');
Route::get('/pages/earnings/', $controller_path . '\Finacials@earnings')->name('earnings');
Route::get('/pages/expenses/', $controller_path . '\Finacials@expenses')->name('expenses');
Route::post('/pages/earnings', $controller_path . '\Finacials@get_earnings')->name('earnings.get');
Route::post('/pages/expenses_get', $controller_path . '\Finacials@get_expenses')->name('earnings.expenses');
Route::post('/pages/expenses-add', $controller_path . '\Finacials@addexpense')->name('addexpense');
Route::delete('/pages/expenses-destroy', $controller_path . '\Finacials@destroy_expenses')->name('destroy_expenses');

// authentication
//Route::resource('/auth/login-basic', $controller_path . '\authentications\LoginBasic@index');
Route::get('/auth/login-basic', $controller_path . '\authentications\LoginBasic@index')->name('auth-login-basic');
Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', $controller_path . '\authentications\ForgotPasswordBasic@index')->name('auth-reset-password-basic');
Route::post('/auth/login-auth', $controller_path . '\authentications\LoginBasic@login')->name('auth-login-auth');
Route::post('/auth/logout', $controller_path . '\authentications\LoginBasic@logout')->name('logout');
Route::post('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@create')->name('create.user');
// cards
Route::get('/cards/basic', $controller_path . '\cards\CardBasic@index')->name('cards-basic');

// User Interface
Route::get('/ui/accordion', $controller_path . '\user_interface\Accordion@index')->name('ui-accordion');
Route::get('/ui/alerts', $controller_path . '\user_interface\Alerts@index')->name('ui-alerts');
Route::get('/ui/badges', $controller_path . '\user_interface\Badges@index')->name('ui-badges');
Route::get('/ui/buttons', $controller_path . '\user_interface\Buttons@index')->name('ui-buttons');
Route::get('/ui/carousel', $controller_path . '\user_interface\Carousel@index')->name('ui-carousel');
Route::get('/ui/collapse', $controller_path . '\user_interface\Collapse@index')->name('ui-collapse');
Route::get('/ui/dropdowns', $controller_path . '\user_interface\Dropdowns@index')->name('ui-dropdowns');
Route::get('/ui/footer', $controller_path . '\user_interface\Footer@index')->name('ui-footer');
Route::get('/ui/list-groups', $controller_path . '\user_interface\ListGroups@index')->name('ui-list-groups');
Route::get('/ui/modals', $controller_path . '\user_interface\Modals@index')->name('ui-modals');
Route::get('/ui/navbar', $controller_path . '\user_interface\Navbar@index')->name('ui-navbar');
Route::get('/ui/offcanvas', $controller_path . '\user_interface\Offcanvas@index')->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', $controller_path . '\user_interface\PaginationBreadcrumbs@index')->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', $controller_path . '\user_interface\Progress@index')->name('ui-progress');
Route::get('/ui/spinners', $controller_path . '\user_interface\Spinners@index')->name('ui-spinners');
Route::get('/ui/tabs-pills', $controller_path . '\user_interface\TabsPills@index')->name('ui-tabs-pills');
Route::get('/ui/toasts', $controller_path . '\user_interface\Toasts@index')->name('ui-toasts');
Route::get('/ui/tooltips-popovers', $controller_path . '\user_interface\TooltipsPopovers@index')->name('ui-tooltips-popovers');
Route::get('/ui/typography', $controller_path . '\user_interface\Typography@index')->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', $controller_path . '\extended_ui\PerfectScrollbar@index')->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', $controller_path . '\extended_ui\TextDivider@index')->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', $controller_path . '\icons\Boxicons@index')->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', $controller_path . '\form_elements\BasicInput@index')->name('forms-basic-inputs');
Route::get('/forms/input-groups', $controller_path . '\form_elements\InputGroups@index')->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', $controller_path . '\form_layouts\VerticalForm@index')->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', $controller_path . '\form_layouts\HorizontalForm@index')->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', $controller_path . '\tables\Basic@index')->name('tables-basic');



//Clients Routes
Route::get('clients', $controller_path . '\ClientsController@index')->name('clients');

Route::get('/pages/clients-filter', $controller_path . '\ClientsController@filter')->name('clients-filter');
Route::get('/pages/clients-create', $controller_path . '\ClientsController@create')->name('clients.create');
Route::post('/pages/clients-create', $controller_path . '\ClientsController@store')->name('clients.store');
Route::put('/pages/clients-update', $controller_path . '\ClientsController@update')->name('clients.update');
Route::delete('/pages/clients-destroy/{client}', $controller_path . '\ClientsController@destroy')->name('clients.destroy');
Route::get('/pages/clients-edit/{client}', $controller_path . '\ClientsController@edit')->name('clients.edit');

Route::get('/pages/clients-edit/{clientId}/revenue/{year?}', $controller_path . '\ClientsController@upcomingRevenueByClient')->name('clients.revenue');

use App\Http\Controllers\SettingController;

Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
// Vehicles Routes
use App\Http\Controllers\VehicleController;

Route::get('/vehicles/create/{client}', [VehicleController::class, 'create'])->name('vehicles.create');
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'edit'])->name('vehicles.edit');
Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])->name('vehicles.update');
Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
Route::delete('/vehicles/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');

// Services Routes

use App\Http\Controllers\ServicesController;

Route::resource('services', ServicesController::class);


use App\Http\Controllers\ImportController;
Route::post('/import-clients-vehicles', [ImportController::class, 'importClients'])->name('import.clients_vehicles');
Route::post('/import-vehicles', [ImportController::class, 'importVehicles'])->name('import.vehicles');

Route::post('/import-subscriptions', [ImportController::class, 'importSubscriptions'])->name('import.subscriptions');



// Subscriptions Routes
use App\Http\Controllers\SubscriptionController;

Route::get('/subscriptions/create/{vehicle}', [SubscriptionController::class, 'create'])->name('subscriptions.create');
Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('subscriptions.store');
Route::get('/subscriptions/{subscription}/edit', [SubscriptionController::class, 'edit'])->name('subscriptions.edit');
Route::put('/subscriptions/{subscription}', [SubscriptionController::class, 'update'])->name('subscriptions.update');
Route::delete('/subscriptions/{subscription}', [SubscriptionController::class, 'destroy'])->name('subscriptions.destroy');
Route::post('/subscriptions/{id}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
