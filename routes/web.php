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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', [App\Http\Controllers\AdminController::class, 'index'])->name('home');
Route::get('/home/list/{applicationName}', [App\Http\Controllers\AdminController::class, 'appData']);

/*
 Password & Unauthorize Routes
*/
Route::post('/request-password-change', [App\Http\Controllers\AdminController::class, 'changePassword']);
Route::post('/request-logout-everywhere', [App\Http\Controllers\AdminController::class, 'logoutEverywhere']);

/*
 Application Subscription And Management
*/

Route::post('/add-subscription', [App\Http\Controllers\AdminController::class, 'addSubscription']);
Route::post('/add-secondary-user', [App\Http\Controllers\AdminController::class, 'addSecondaryUser']);
Route::delete('/remove-secondary-user/{id}/{encodedRequestCode}', [App\Http\Controllers\AdminController::class, 'removeSecondaryUser']);