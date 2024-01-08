<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});


Route::get('dashboard', [ 'as' => 'dashboard', 'uses' => 'App\Http\Controllers\MainController@dashboard'])->middleware(['auth']);

Route::post('dashboard', [ 'as' => 'dashboard', 'uses' => 'App\Http\Controllers\MainController@dashboard'])->middleware(['auth']);


Route::post('recensione/{id?}', [ 'as' => 'recensione', 'uses' => 'App\Http\Controllers\MainController@recensione'])->middleware(['auth']);

Route::get('recensione/{id?}', [ 'as' => 'recensione', 'uses' => 'App\Http\Controllers\MainController@recensione'])->middleware(['auth']);


Route::post('elenco_pns', [ 'as' => 'elenco_pns', 'uses' => 'App\Http\Controllers\MainController@elenco_pns'])->middleware(['auth']);
Route::get('elenco_pns', [ 'as' => 'elenco_pns', 'uses' => 'App\Http\Controllers\MainController@elenco_pns'])->middleware(['auth']);


Route::get('gspr', [ 'as' => 'gspr', 'uses' => 'App\Http\Controllers\ControllerArchivi@gspr'])->middleware(['auth']);

Route::post('gspr', [ 'as' => 'gspr', 'uses' => 'App\Http\Controllers\ControllerArchivi@gspr'])->middleware(['auth']);

Route::get('risk', [ 'as' => 'risk', 'uses' => 'App\Http\Controllers\ControllerArchivi@risk'])->middleware(['auth']);

Route::post('risk', [ 'as' => 'risk', 'uses' => 'App\Http\Controllers\ControllerArchivi@risk'])->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
