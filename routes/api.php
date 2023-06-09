<?php

use App\Infrastructure\Controllers\BalanceWalletController;
use App\Infrastructure\Controllers\GetUserController;
use App\Infrastructure\Controllers\GetWalletCryptocurrenciesController;
use App\Infrastructure\Controllers\IsEarlyAdopterUserController;
use App\Infrastructure\Controllers\GetStatusController;
use App\Infrastructure\Controllers\OpenWalletController;
use App\Infrastructure\Controllers\SellCoinController;
use App\Infrastructure\Controllers\BuyCoinController;
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


Route::get('/status', GetStatusController::class);
Route::get('/wallet/{wallet_id}', GetWalletCryptocurrenciesController::class);
Route::get('/wallet/{wallet_id}/balance', BalanceWalletController::class);
Route::post('/wallet/open/', [OpenWalletController::class, '__invoke']);
Route::post('/coin/sell', [SellCoinController::class, '__invoke']);
Route::post('/coin/buy', [BuyCoinController::class, '__invoke']);
