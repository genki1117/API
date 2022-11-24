<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Samples\CheckCsrfTokenController;

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


/**
 * sample Routes
 */

/** @var Router $router */
$router->get('/check-csrf-token', [CheckCsrfTokenController::class, 'index'])->name('checkCsrfToken');
$router->post('/check-csrf-token', [CheckCsrfTokenController::class, 'update'])->name('checkCsrfToken.update');
