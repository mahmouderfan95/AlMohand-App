<?php

use App\Http\Controllers\Pos\Auth\AuthController;
use App\Http\Controllers\Pos\Balance\BalanceRedemptionController;
use App\Http\Controllers\Pos\BalanceRequest\BalanceRequestController;
use App\Http\Controllers\Pos\Brand\BrandController;
use App\Http\Controllers\Pos\Category\CategoryController;
use App\Http\Controllers\Pos\Home\HomeController as PosHomeController;
use App\Http\Controllers\Pos\Order\OrderController;
use App\Http\Controllers\Pos\Print\PrintController;
use App\Http\Controllers\Pos\Report\ReportController;
use App\Http\Controllers\Seller\AuthController as SellerAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pos\Category\CategoryController as MerchantCategoryController;
use App\Http\Controllers\Pos\Product\ProductController as MerchantProductController;
use App\Http\Controllers\Pos\Brand\BrandController as MerchantBrandController;


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

Route::group(['middleware' => ['getLang'], 'prefix' => 'pos/v1', 'as' => 'api.pos.'], function () {
    //////////////////////////// Auth ///////////////////////////////
    Route::group(['prefix' => 'auth'], function (){
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::get('logout', [AuthController::class, 'logout'])->middleware('authGurad:posApi');
    });
});

Route::group(['middleware' => ['getLang', 'authGurad:posApi'],'prefix' => 'pos/v1', 'as' => 'api.pos.'], function () {

    #profile route pos
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/',[AuthController::class,'getProfile']);
        Route::post('update-phone',[AuthController::class,'updatePhone']);
        Route::post('update-name',[AuthController::class,'updateName']);
        Route::post('update-password',[AuthController::class,'updatePassword']);
    });

    //////////////////////////// Home ///////////////////////////////
    Route::get('home', PosHomeController::class)->name('home.main');
    //////////////////////////// Categories ///////////////////////////////Update
    Route::get('categories/main', [CategoryController::class, 'getMainCategories'])->name('categories.main');
    Route::get('categories/subs', [CategoryController::class, 'getSubCategories'])->name('categories.subs');
    Route::get('categories/brands/{id}', [BrandController::class, 'getBrandsByCategoryId'])->name('categories.brands');

    //////////////////////////// brands ///////////////////////////////
    Route::group(['prefix' => 'brands', 'as' => 'brands.'], function () {
        Route::get('category/{category_id}', [MerchantBrandController::class, 'getBrandsByCategoryId'])->name('brands.action.category');
        Route::get('show/{id}', [MerchantBrandController::class, 'show_details'])->name('brands.show_details');
    });

    //////////////////////////// Products ///////////////////////////////
    Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
        Route::get('category/{categoryId}/brand/{brandId}', [MerchantProductController::class, 'productsByCategoryIdAndBrandId'])->name('products.index');
        Route::get('show/{id}', [MerchantProductController::class, 'show'])->name('products.show');
    });

    //////////////////////////// Reports ///////////////////////////////
    Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
        Route::get('order-reports', [ReportController::class, 'orderReports'])->name('reports.order_reports');
        Route::get('balance-reports', [ReportController::class, 'balanceReports'])->name('reports.balanceReports');
        Route::get('commission-reports', [ReportController::class, 'commissionReports'])->name('reports.commissionReports');
        Route::get('point-reports', [ReportController::class, 'pointReports'])->name('reports.pointReports');
        Route::get('main-commission-reports', [ReportController::class, 'mainCommissionReports'])->name('reports.mainCommissionReports');
        Route::get('main-point-reports', [ReportController::class, 'mainPointReports'])->name('reports.mainPointReports');
        Route::get('balance-request-reports', [ReportController::class, 'balanceRequestReports'])->name('reports.balanceRequestReports');
    });

    /////////////////////////// Balance Request ////////////////////////
    Route::post('balance-request', [BalanceRequestController::class, 'create']);
    Route::post('balance-request/callback', [BalanceRequestController::class, 'callback']);

    /////////////////////////// Redeem Points & Commission ////////////////////////
    Route::post('balance/{type}/redeem', [BalanceRedemptionController::class, 'redeem']);
    Route::post('balance/points', [BalanceRedemptionController::class, 'getPointsValue']);

    /////////////////////////// Orders ////////////////////////
    Route::group(['prefix' => 'orders', 'as' => 'orders.'],function(){
        // url: orders
        Route::post('',[OrderController::class,'index'])->name('index');
        // url: orders/store
        Route::post('store',[OrderController::class,'store'])->name('store');
        // url: orders/callback
        Route::post('callback',[OrderController::class,'callback'])->name('callback');
    });

    /////////////////////////// Pos Print ////////////////////////
    Route::group(['prefix' => 'print', 'as' => 'print.'],function(){
        // url: print/decrease-count
        Route::post('decrease-count',[PrintController::class,'decreaseCount']);
    });

    Route::post('/factory-reset', [AuthController::class, 'factoryReset']);
});














