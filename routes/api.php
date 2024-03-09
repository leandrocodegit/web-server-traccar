<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\ImagemController;
use App\Http\Controllers\CategoriaTagController;
use App\Http\Controllers\ExportImportController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\RegraController;
use App\Http\Controllers\TipoRegraController;

use App\Http\Controllers\RotinaController;
use App\Http\Controllers\Traccar\ContaController;
use App\Http\Controllers\Traccar\DeviceController;
use App\Http\Controllers\Traccar\DriverController;
use App\Http\Controllers\Traccar\PermissionsController;
use App\Http\Controllers\Traccar\PositionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route:: Account active and passwords
Route::post('/forgot', [SecurityController::class, 'forgot']);
Route::post('/forgot/resend', [SecurityController::class, 'resend']);
Route::put('/forgot/reset/password', [SecurityController::class, 'reset']);

//Route:: Auth
Route::group([
//    'middleware' => 'cors',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);

});

//Route:: Users
Route::post('/user', [UserController::class, 'store']);

//Route:: Users
Route::group([
//    'middleware' => 'JWT:ROOT,ADMIN,USER',
    'prefix' => 'user',
    'roles' => ['ROOT', 'ADMIN', 'USER']

], function ($router) {

    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/filter/list', [UserController::class, 'search']);

    Route::put('/reset/password', [UserController::class, 'editPassword']);
    Route::patch('/', [UserController::class, 'update']);
     Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::group([
    //    'middleware' => 'JWT:ROOT,ADMIN,USER',
        'prefix' => 'conta',
        'roles' => ['ROOT', 'ADMIN', 'USER']

    ], function ($router) {

        Route::get('/notificacoes', [ContaController::class, 'notificacoes']);
        Route::get('/shares/{contaid}', [ContaController::class, 'compartilhados']);
        Route::delete('/share/{conta}/{user}/{device}', [ContaController::class, 'removerCompartilhado']);
        Route::put('/share', [ContaController::class, 'editarShare']);
        Route::post('/share', [ContaController::class, 'salvarShare']);
        Route::get('/{contaid}', [ContaController::class, 'find']);
        Route::get('/devices/{contaid}', [ContaController::class, 'devices']);
        Route::get('/geofences/{contaid}', [ContaController::class, 'geofences']);
        Route::get('/rotinas/{contaid}', [ContaController::class, 'rotinas']);
        Route::get('/users/{contaid}/{readonly}', [ContaController::class, 'users']);
        Route::get('/drivers/{contaid}', [ContaController::class, 'drivers']);



    });

Route::group([
    'prefix' => 'device',
    'roles' => ['ROOT', 'ADMIN', 'USER']

], function ($router) {
    Route::get('/', [DeviceController::class, 'listDevicesUser']);
    Route::post('/', [DeviceController::class, 'store']);
    Route::put('/', [DeviceController::class, 'update']);
    Route::delete('/{id}', [DeviceController::class, 'delete']);


    Route::get('/positions/{deviceId}', [PositionController::class, 'findDevice']);
    Route::get('/{id}', [DeviceController::class, 'find']);
    Route::get('/user/{deviceId}', [DeviceController::class, 'listUsersDevice']);
    Route::get('/device/{userId}', [DeviceController::class, 'listDevicesUser']);
    Route::patch('/driver/associar', [DeviceController::class, 'associarDriver']);
    Route::patch('/geofence/associar', [DeviceController::class, 'associarGeofence']);
    Route::patch('/notificacao/associar', [DeviceController::class, 'associarNotificacao']);


});

Route::group([
    'prefix' => 'permissions',
    'roles' => ['ROOT', 'ADMIN', 'USER']

], function ($router) {
    Route::post('/associar', [PermissionsController::class, 'associar']);
    Route::post('/desassociar', [PermissionsController::class, 'desassociar']);


});


Route::group([
    'prefix' => 'driver',
    'roles' => ['ROOT', 'ADMIN', 'USER']

], function ($router) {
    Route::post('/', [DriverController::class, 'store']);
    Route::delete('/{driverId}', [DriverController::class, 'remover']);

});

Route::group([
    'prefix' => 'position',
    'roles' => ['ROOT', 'ADMIN', 'USER']

], function ($router) {
    Route::get('/{id}', [\App\Http\Controllers\Traccar\PositionController::class, 'find']);
    Route::get('/device/{deviceId}', [\App\Http\Controllers\Traccar\PositionController::class, 'findDevice']);
});

Route::group([
    'prefix' => 'geofence',
    'roles' => ['ROOT', 'ADMIN', 'USER']

], function ($router) {
    Route::post('/', [\App\Http\Controllers\Traccar\GeofenceController::class, 'store']);
    Route::get('/{id}', [\App\Http\Controllers\Traccar\GeofenceController::class, 'find']);
    Route::get('/devices/{deviceId}', [\App\Http\Controllers\Traccar\GeofenceController::class, 'listGeofencesDevice']);
    Route::get('/users/{userId}', [\App\Http\Controllers\Traccar\GeofenceController::class, 'listGeofencesUsers']);
    Route::put('/area', [\App\Http\Controllers\Traccar\GeofenceController::class, 'alterarArea']);

});

Route::group([
    'prefix' => 'event',
    'roles' => ['ROOT', 'ADMIN', 'USER']

], function ($router) {
    Route::get('/{id}', [\App\Http\Controllers\Traccar\EventController::class, 'find']);
    Route::get('/list/{deviceId}', [\App\Http\Controllers\Traccar\EventController::class, 'list']);
});

Route::group([
    'prefix' => 'rotina',
    'roles' => ['ROOT', 'ADMIN', 'USER']

], function ($router) {
    Route::get('/{rotinaId}', [\App\Http\Controllers\Traccar\RotinaController::class, 'find']);
    Route::get('/{userId}/{order}', [\App\Http\Controllers\Traccar\RotinaController::class, 'listRotinasUsers']);
    Route::delete('/{rotinaId}', [\App\Http\Controllers\Traccar\RotinaController::class, 'remover']);
    Route::post('/', [\App\Http\Controllers\Traccar\RotinaController::class, 'store']);
});









