<?php

use Illuminate\Support\Facades\Route;
use LaraDev\Http\Controllers\PropertyController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('imoveis', [PropertyController::class, 'index']);

Route::get('imoveis/novo', [PropertyController::class, 'create']);
Route::post('imoveis/store', [PropertyController::class, 'store']);

Route::get('imoveis/{name}', [PropertyController::class, 'show']);

Route::get('imoveis/editar/{name}', [PropertyController::class, 'edit']);
Route::put('imoveis/update/{name}', [PropertyController::class, 'update']);
Route::get('imoveis/remover/{name}', [PropertyController::class, 'destroy']);
