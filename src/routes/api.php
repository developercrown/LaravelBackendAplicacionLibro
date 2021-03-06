<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('libros')->group(function () { // Rutas exclusivas para solicitantes
    Route::get('/', 'LibroController@index');
    Route::get('/{id}', 'LibroController@show');
    Route::post('/', 'LibroController@store');
    Route::put('/{id}', 'LibroController@update');
    Route::delete('/{id}', 'LibroController@destroy');
    Route::get('/image/{id}/{filename}/{key}/{definition}', 'LibroController@getImage');
});

Route::get('/info', function(){
    return phpinfo();
});