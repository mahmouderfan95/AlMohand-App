<?php

use App\Http\Controllers\SalesRep\Auth\AuthController;
use App\Http\Controllers\SalesRep\SubSalesRep\SubSalesRepController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesRep\Order\OrderController;
use App\Http\Controllers\SalesRep\BalanceLog\BalanceLogController;

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

Route::group(['middleware' => ['getLang'], 'prefix' => 'salesRep/v1', 'as' => 'api.salesRep.'], function () {
    //////////////////////////// Auth ///////////////////////////////
    Route::group(['prefix' => 'auth'], function (){
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::get('logout', [AuthController::class, 'logout'])->middleware('authGurad:salesRepApi');
    });
});

Route::group(['middleware' => ['getLang', 'authGurad:salesRepApi'], 'prefix' => 'salesRep/v1', 'as' => 'api.salesRep.'], function () {

    #profile route pos
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/',[AuthController::class,'getProfile']);
    });
    Route::group(['prefix' => 'sub-sales'], function () {
        Route::get('/{id}',[SubSalesRepController::class,'show']);
        Route::get('/{id}/merchants',[SubSalesRepController::class,'merchants']);
        Route::get('pos-terminal/{merchant_id}',[SubSalesRepController::class,'posTerminalByMerchantID']);
        Route::get('merchants-with-pos-terminal/getAll',[SubSalesRepController::class,'allMerchantsWithPosTerminalByMerchantID']);
        Route::post('add-transaction/{id}', [SubSalesRepController::class, 'addTransaction'])->name('salesRepUsers.addTransaction');

    });
    Route::get('transactions', [SubSalesRepController::class, 'transactions'])->name('salesRepUsers.transactions');
    //////////////////////////// Home ///////////////////////////////
    // Route::get('', SalesRepHomeController::class)->name('home.main');

    //////////////////////////// Balance Request ///////////////////////////////
    Route::get('all-balance-requests', [SubSalesRepController::class, 'allBalanceRequests']);
    Route::get('pending-balance-request', [SubSalesRepController::class, 'pendingBalanceRequests']);
    Route::post('balance-request/{id}/update', [SubSalesRepController::class, 'UpdateBalanceRequest']);

    /////////////////////////// Report /////////////////////////////////////////
    Route::get('pos-orders/{distributor_pos_terminal_id}', [OrderController::class, 'getPosOrders']);
    Route::get('order/{id}', [OrderController::class, 'show']);

    Route::group(['prefix' => 'balance-log'], function () {
        Route::get('points/{distributor_pos_terminal_id}', [BalanceLogController::class, 'getPointsTransactions']);
        Route::get('commission/{distributor_pos_terminal_id}', [BalanceLogController::class, 'getCommissionTransactions']);
    });

});














