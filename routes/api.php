<?php

use Illuminate\Routing\Router;
use App\Http\Controllers\Samples\Api\UseSampleController;
use App\Http\Controllers\Document\DocumentListController;
use App\Http\Controllers\Document\DocumentSaveController;
use App\Http\Controllers\Document\DocumentDetailController;
use App\Http\Controllers\Document\DocumentGetListController;

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

/** @var Router $router */
$router->get('/sample-get', [UseSampleController::class, 'getSample']);
$router->post('/sample-login', [UseSampleController::class, 'login']);
$router->get('/test', [UseSampleController::class, 'test']);

$router->get('/token-test', function ($request) {
    return 'success';
})->middleware('auth.token');

$router->post('/document/detail', [DocumentListController::class, 'getDetail']);

$router->post('/document/save', [DocumentSaveController::class, 'saveDocument']);
$router->post('/document/detail', [DocumentDetailController::class, 'getDetail']);
$router->post('/document/list', [DocumentGetListController::class, 'getList']);
