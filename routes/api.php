<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ViewUser;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * To access the api - Users will need to authenticate first
 * Authentication will be handled by Laravel Passport which uses OAuth2
 * Token will last 1 year
 */
Route::post('/login', 'AuthController@apiLogin')->name('user.login');

#### User Routes
Route::middleware(['auth:api', 'user.access'])->group(static function () {
    Route::get('/users', 'UserApiController@index')->name('users.get');
    Route::get('/user/{id}', 'UserApiController@show')->name('user.get');
    Route::post('/user/{id}', 'UserApiController@update')->name('user.update');
});

#### Entry Routes
// Laravel already has CRUD endpoints
Route::apiResource('/entry', 'EntryApiController')->middleware(['auth:api', 'entry.access']);


/**
 * We could do simple responses here since Laravel has the core authentication, model & response methods
 * But that would defeat the purpose of the test
 * IE:
 * Route::apiResource('/user', 'UserApiController')->middleware('auth:api');
 *
 * Or we could do quick simple responses
 * Route::get('/user/{id}', function ($id) {
 * 	   return App\User::findOrFail($id)->middleware('auth:api'); // Failed requests will return 404 response
 * });
 */