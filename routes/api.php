<?php

use App\Http\Controllers\Admin\CurrencyController as DashCurrencyController;
use App\Http\Controllers\Admin\SellerController as DashSellerController;


////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////
use App\Http\Controllers\Seller\AttachmentController;
use App\Http\Controllers\Seller\AuthController as SellerAuthController;
use App\Http\Controllers\Seller\FavController;
use App\Http\Controllers\Seller\ForgotPasswordController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\Store\BrandController;
use App\Http\Controllers\Seller\Store\CartController;
use App\Http\Controllers\Seller\Store\CategoryController;
use App\Http\Controllers\Seller\Store\OrderController;
use App\Http\Controllers\Seller\SupportTicketController;
use App\Http\Controllers\Seller\WalletController;
use Illuminate\Support\Facades\Route;


///////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// End Customer Guard Name ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
Route::group(['middleware' => [/*'authGurad:sssss',*/ 'getLang'],'prefix' => 'pos/v1', 'as' => 'api.pos.'], function () {

});



///////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// Dashboard ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////

////////////////////////////// login admin /////////////////////////

Route::group(['middleware' => ['authGurad:adminApi', 'getLang'],'prefix' => 'admin/v1', 'as' => 'api.admin.'], function () {

    //////////////////////////// currencies ///////////////////////////////
    Route::get('currencies', [DashCurrencyController::class, 'index'])->middleware(['can:view-currencies']);
    Route::post('currencies', [DashCurrencyController::class, 'store'])->middleware(['can:create-currencies']);
    Route::get('currencies/{id}', [DashCurrencyController::class, 'show'])->middleware(['can:view-currencies']);
    Route::delete('currencies/{id}', [DashCurrencyController::class, 'destroy'])->middleware(['can:delete-currencies']);
    Route::post('currencies/{id}', [DashCurrencyController::class, 'update'])->middleware(['can:update-currencies']);


});


Route::group(['middleware' => ['authGurad:sellerApi', 'getLang'],'prefix' => 'seller', 'as' => 'api.seller.'], function () {

    //////////////////////////// Auth ///////////////////////////////
    #profile route seller
    Route::get('profile',[SellerAuthController::class,'profile']);
    #logout route seller
    Route::post('logout',[SellerAuthController::class,'logout']);
    Route::group(['prefix' => 'attachment'],function(){
        #show attachment
        Route::get('show/{id}',[AttachmentController::class,'show']);
        #delete attachment
        Route::delete('delete/{id}',[AttachmentController::class,'destroy']);
    });
    #generateG2FAuth
    Route::get('generateG2FAuth', [SellerAuthController::class, 'generateG2FAuth']);
    #update profile data
    Route::post('update/profile-data',[SellerAuthController::class,'updateProfile']);
    #Balance-recharge
    Route::post('balance-recharge',[WalletController::class,'balanceRecharge']);
    #get all transaction for balance recharge
    Route::get('wallet-transactions',[WalletController::class,'getBalanceList']);
    #products
    Route::get('products',[ProductController::class,'index']);
    #search in products
    Route::get('search-products',[ProductController::class,'search']);
    #store apis
    Route::group(['prefix' => 'store'],function(){
        #get categories api
        Route::get('categories',[CategoryController::class,'index']);
        #get brands api
        Route::get('brands',[BrandController::class,'index']);
        #get categories with brand_id
        Route::get('categories/{brand_id}',[BrandController::class,'getCategories']);
        #get products with category_id
        Route::get('products/{category_id}',[BrandController::class,'getProducts']);
        Route::group(['prefix' => 'favorites'],function(){
            #add-product-to-fav
            Route::post('add-product-to-fav',[FavController::class,'store']);
            #get Fav Products
            Route::get('get/products',[FavController::class,'getProducts']);
        });
        Route::group(['prefix' => 'cart'],function(){
            #add product to cart
            Route::post('add-product-to-cart',[CartController::class,'store']);
            #get cart
            Route::get('get',[CartController::class,'index']);
        });
        #create Order api
        Route::post('create-order',[OrderController::class,'store']);
        #get Orders api
        Route::get('orders',[OrderController::class,'index']);
    });
    Route::group(['prefix' => 'support-tickets'],function(){
        #get Support Tickets
        Route::get('/',[SupportTicketController::class,'index']);
        #add support ticket
        Route::post('store',[SupportTicketController::class,'store']);
    });
    // Route::post('verifyOtp', [FrontSmsVerificationController::class, 'verifyOtp'])->name('auth.smsVerification.verifyOtp');
    // Route::post('resendOtp', [FrontSmsVerificationController::class, 'resendOtp'])->name('auth.smsVerification.resendOtp');
    // Route::post('forgetPassword/send', [FrontForgetPasswordController::class, 'sendForgetPassword'])->name('auth.forgetPassword.send');
    // Route::post('forgetPassword/verify', [FrontForgetPasswordController::class, 'verifyForgetPasswordOtp'])->name('auth.forgetPassword.verify');
    // Route::post('forgetPassword/update', [FrontForgetPasswordController::class, 'updatePassword'])->name('auth.forgetPassword.update');

    //////////////////////////// sellers ///////////////////////////////
    Route::group(['prefix' => 'auth', 'as' => 'sellers.'], function () {
        Route::get('', [DashSellerController::class, 'index'])->name('index');
        Route::get('notApproved', [DashSellerController::class, 'notApproved'])->name('notApproved');
        Route::get('{id}', [DashSellerController::class, 'show'])->name('show');
    });

});
Route::group(['middleware' => ['getLang'],'prefix' => 'seller', 'as' => 'api.seller.'], function () {

    //////////////////////////// Auth ///////////////////////////////
    Route::post('register', [SellerAuthController::class, 'register']);
    Route::post('login', [SellerAuthController::class, 'login']);
    Route::post('password/forgot', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('password/reset', [ForgotPasswordController::class, 'resetPassword']);
});

Route::match(['GET', 'POST'], "enc", [\App\Http\Controllers\OpenSslController::class, 'encrypt']);
Route::match(['GET', 'POST'], "dec", [\App\Http\Controllers\OpenSslController::class, 'decrypt']);












