<?php

use App\Http\Controllers\MalfunctioningSensorController;
use App\Http\Controllers\SidesTemperatureController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::controller(SidesTemperatureController::class)->group(function () {
    Route::group(['prefix' => 'tower','as' => 'tower.'],function (){
        Route::get('/temperature','index')->name('index');
    });
});
Route::controller(MalfunctioningSensorController::class)->group(function () {
    Route::group(['prefix' => 'tower','as' => 'tower.'],function (){
        Route::get('/malfunctioning','index')->name('index');
    });
});


