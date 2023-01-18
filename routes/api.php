<?php

use Illuminate\Routing\Router;
use App\Http\Controllers\Samples\Api\UseSampleController;
use App\Http\Controllers\Document\DocumentDetailController;
use App\Http\Controllers\Document\DocumentGetListController;
use App\Http\Controllers\Document\DocumentBulkCreateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| 【補足:権限チェックの共通処理について】
| グローバルミドルウェア（Kernel.phpの$middlewareのAuthorizationToken）として設定しているため、
| 全てのルートに対して呼び出される（本ファイルに記載していない404エラーとなるルートに対しても）。
| よって、本ファイルにて個別にルートやグループに対しての指定は不要である。
|
|    <ミドルウェアの実行順序について>
|    １．グローバルミドルウェア
|    ２．Routerによるルートのチェック（本ファイルに該当ルートが存在しているか）
|    ３．Routerのグループで指定したミドルウェア
|    ４．Routerの上記以外で指定したミドルウェア
|    ５．ルートに紐づく各コントローラの処理
|
|    ※２で該当ルートなし(404）となった場合は、3、4で指定したミドルウェアは動作しない。
|
*/

/** @var Router $router */
$router->get('/sample-get', [UseSampleController::class, 'getSample']);
$router->post('/sample-login', [UseSampleController::class, 'login']);

$router->post('/document/detail', [DocumentDetailController::class, 'getDetail']);
$router->post('/document/list', [DocumentGetListController::class, 'getList']);

$router->post('/document/dl-tempcsv', [DocumentBulkCreateController::class, 'dlTmpCsv']);



