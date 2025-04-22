<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\Distributor\DistributorController;
use App\Http\Controllers\Admin\Distributor\DistributorGroupController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\PosTerminal\PosTerminalController;
use App\Http\Controllers\Admin\PosTerminal\PosTerminalTransactionsController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\SalesRep\BalanceLog\BalanceLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Distributor\DistributorPosTerminalController;
use App\Http\Controllers\Admin\BalanceRequest\BalanceRequestController;
use App\Http\Controllers\Admin\Distributor\DistributorTransactionsController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\NotificationTokenController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\RoleAndPermissionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\NotificationSettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\ValueAddedTaxController;
use App\Http\Controllers\Admin\SubAdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SalesRepLevelController;
use App\Http\Controllers\Admin\SalesRepUserController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\VendorProductController;
use App\Http\Controllers\Admin\DirectPurchaseController;
use App\Http\Controllers\Admin\AttributeGroupController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\ProductSerialController;
use App\Http\Controllers\Admin\SellerGroupController;
use App\Http\Controllers\Admin\SellerGroupLevelController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\IntegrationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\Report\SalesReportController;

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
Route::post('admin/v1/loginAdmin', [AuthController::class, 'loginAdmin']);
Route::post('admin/v1/logout', [AuthController::class, 'logout']);

Route::group(['middleware' => ['authGurad:adminApi', 'getLang'],'prefix' => 'admin/v1', 'as' => 'api.admin.'], function () {

    //////////////////////////// Home ///////////////////////////////
    Route::get('home', [HomeController::class, 'index'])->name('home')->middleware(['can:view-home']);

    ////////////////////////// Tokens like ( Firebase ) ///////////////////////////////
    Route::group(['prefix' => 'notificationTokens', 'as' => 'notificationTokens.'], function () {
        Route::post('firebase/store', [NotificationTokenController::class, 'firebaseStore'])->name('firebaseStore');
    });

    //////////////////////////// Notifications ///////////////////////////////
    Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
        Route::get('', [NotificationController::class, 'index'])->name('index');
        Route::get('count', [NotificationController::class, 'count'])->name('count');
        Route::get('read/{notificationId}', [NotificationController::class, 'read'])->name('read');
    });

    //////////////////////////// Merchants ///////////////////////////////
    Route::group(['as' => 'merchant.'], function () {
        Route::resource('merchant', DistributorController::class);
        Route::group(['prefix' => 'merchant'], function () {
            Route::post('{id}/update', [DistributorController::class, 'update']);
            Route::post('attachment/{id}/delete', [DistributorController::class, 'deleteAttachment']);
            Route::get('form/fill', [DistributorController::class, 'fillRequiredData']);
            // Get Merchant POS Terminal List
            // url: merchant/pos-terminal
            Route::post('pos-terminal', [DistributorPosTerminalController::class, 'getPosTerminalsList']);
            Route::group(['prefix' => '{id}'], function () {
                // url: merchant/{id}/transactions/balance
                Route::post('transactions/balance', [DistributorTransactionsController::class, 'getBalanceTransactionsList']);
                // url: merchant/{id}/transactions/commission
                Route::post('transactions/commission', [DistributorTransactionsController::class, 'getCommissionTransactionsList']);
                // url: merchant/{id}/update-status
                Route::post('update-status', [DistributorController::class, 'updateStatus']);
                // url: merchant/{id}/update-balance
                Route::post('update-balance', [DistributorTransactionsController::class, 'updateBalance']);
            });
            Route::group(['prefix' => 'pos-terminal'], function () {
                // url: merchant/pos-terminal/create
                Route::post('create', [DistributorPosTerminalController::class, 'create']);
                Route::group(['prefix' => '{id}'], function () {
                    // url: merchant/pos-terminal/{id}
                    Route::get('/', [DistributorPosTerminalController::class, 'show']);
                    // url: merchant/pos-terminal/{id}/transactions/balance
                    Route::post('transactions/balance', [PosTerminalTransactionsController::class, 'getBalanceTransactionsList']);
                    // url: merchant/pos-terminal/{id}/transactions/sales
                    Route::post('transactions/sales', [PosTerminalTransactionsController::class, 'getSalesTransactionsList']);
                    // url: merchant/pos-terminal/{id}/transactions/commission
                    Route::post('transactions/commission', [PosTerminalTransactionsController::class, 'getCommissionTransactionsList']);
                    // url: merchant/pos-terminal/{id}/transactions/points
                    Route::get('transactions/points', [DistributorPosTerminalController::class, 'getPointsTransactionsList']);
                    // url: merchant/pos-terminal/{id}/update-balance
                    Route::post('update-balance', [DistributorPosTerminalController::class, 'updateBalance']);
                    // url: merchant/pos-terminal/{id}/update-status
                    Route::post('update-status', [DistributorPosTerminalController::class, 'updateStatus']);
                    // url: merchant/pos-terminal/{id}/delete
                    Route::delete('/', [DistributorPosTerminalController::class, 'delete']);
                });
            });
        });

        Route::group(['as' => 'pos-terminal.', 'prefix' => 'merchant/pos-terminal'] ,function () {

        });
    });

    //////////////////////////// Pos Groups ///////////////////////////////
    Route::group(['as' => 'merchant-group.'], function () {
        Route::get('merchant-group/form/fill', [DistributorGroupController::class, 'fill']);
        Route::resource('merchant-group', DistributorGroupController::class);
    });

    //////////////////////////// POS Terminal ///////////////////////////////
    Route::group(['as' => 'pos-terminal.'], function () {
        Route::get('pos-terminal/generate-name', [PosTerminalController::class, 'generateTerminalName']);
        Route::resource('pos-terminal', PosTerminalController::class);
        Route::post('pos-terminal/{id}/update', [PosTerminalController::class, 'update']);
        Route::get('active-pos-terminal', [PosTerminalController::class, 'getAssignedPosTerminals']);
        Route::get('pos-terminal/update-status', [PosTerminalController::class, 'getAssignedPosTerminals']);
    });

    //////////////////////////// Balance Request ///////////////////////////////
    Route::group(['as' => 'balance-request.'], function () {
        Route::post('balance-request', [BalanceRequestController::class, 'index']);
        Route::post('balance-request/{id}/update', [BalanceRequestController::class, 'update']);
    });

    //////////////////////////// Language ///////////////////////////////
    Route::group(['as' => 'languages.'], function () {
        Route::resource('languages', LanguageController::class);
        Route::post('languages/{id}/update', [LanguageController::class, 'update']);
    });

    Route::group(['as' => 'countries.'], function () {
        Route::group(['prefix' => 'countries'], function () {
            Route::get('/', [CountryController::class, 'index'])->middleware(['can:view-countries']);
            Route::post('/', [CountryController::class, 'store'])->middleware(['can:create-countries']);
            Route::get('/{id}', [CountryController::class, 'show'])->middleware(['can:view-countries']);
            Route::post('/{id}', [CountryController::class, 'update'])->middleware(['can:update-countries']);
            Route::delete('/{id}', [CountryController::class, 'destroy'])->middleware(['can:delete-countries']);
        });
        Route::get('getAllCountriesForm', [CountryController::class, 'getAllCountriesForm'])->middleware(['can:view-all-countries-form']);
    });

    Route::group(['as' => 'cities.'], function () {
        Route::group(['prefix' => 'cities'], function () {
            Route::get('/', [CityController::class, 'index'])->middleware(['can:view-cities']);
            Route::post('', [CityController::class, 'store'])->middleware(['can:create-cities']);
            Route::get('/{id}', [CityController::class, 'show'])->middleware(['can:view-cities']);
            Route::delete('/{id}', [CityController::class, 'destroy'])->middleware(['can:delete-cities']);
            Route::post('/{id}', [CityController::class, 'update'])->middleware(['can:update-cities']);
        });
        Route::get('getAllCitiesForm', [CityController::class, 'getAllCitiesForm'])->middleware(['can:view-all-cities-form']);
    });

    Route::group(['as' => 'regions.'], function () {
        Route::group(['prefix' => 'regions'], function () {
            Route::get('/', [RegionController::class, 'index'])->middleware(['can:view-regions']);
            Route::post('/', [RegionController::class, 'store'])->middleware(['can:create-regions']);
            Route::get('/{id}', [RegionController::class, 'show'])->middleware(['can:view-regions']);
            Route::delete('/{id}', [RegionController::class, 'destroy'])->middleware(['can:delete-regions']);
            Route::post('/{id}', [RegionController::class, 'update'])->middleware(['can:update-regions']);
        });
        Route::get('getAllRegionsForm', [RegionController::class, 'getAllRegionsForm'])->middleware(['can:view-all-regions-form']);
    });

    Route::group(['as' => 'zones.'], function () {
        Route::resource('zones', ZoneController::class);
        Route::post('zones/{id}/update', [ZoneController::class, 'update']);
    });


    //////////////////////////// brands ///////////////////////////////
    Route::get('brands', [BrandController::class, 'index'])->middleware(['can:view-brands']);
    Route::post('brands', [BrandController::class, 'store'])->middleware(['can:create-brands']);
    Route::get('brands/{id}', [BrandController::class, 'show'])->middleware(['can:view-brands']);
    Route::delete('brands/{id}', [BrandController::class, 'destroy'])->middleware(['can:delete-brands']);
    Route::post('brands/{id}', [BrandController::class, 'update'])->middleware(['can:update-brands']);
    Route::post('brand/changeStatus/{id}', [BrandController::class, 'changeStatus'])->middleware(['can:change-brand-status']);
    Route::post('brand/destroy_selected', [BrandController::class, 'destroy_selected'])->middleware(['can:destroy-selected-brands']);

    //////////////////////////// vendors ///////////////////////////////
    Route::get('vendors', [VendorController::class, 'index'])->middleware(['can:view-vendors']);
    Route::post('vendors', [VendorController::class, 'store'])->middleware(['can:create-vendors']);
    Route::get('vendors/{id}', [VendorController::class, 'show'])->middleware(['can:view-vendors']);
    Route::delete('vendors/{id}', [VendorController::class, 'destroy'])->middleware(['can:delete-vendors']);
    Route::post('vendors/{id}', [VendorController::class, 'update'])->middleware(['can:update-vendors']);
    Route::post('vendor/destroy_selected', [VendorController::class, 'destroy_selected'])->middleware(['can:destroy-selected-vendors']);
    Route::get('vendor/trashes', [VendorController::class, 'trash'])->middleware(['can:view-vendor-trashes']);
    Route::get('vendor/restore/{id}', [VendorController::class, 'restore'])->middleware(['can:restore-vendor']);
    Route::post('vendor/update-status/{id}', [VendorController::class, 'update_status'])->middleware(['can:update-vendor-status']);

    //////////////////////////// attributeGroups ///////////////////////////////
    Route::get('attributeGroups', [AttributeGroupController::class, 'index'])->middleware(['can:view-attribute-groups']);
    Route::post('attributeGroups', [AttributeGroupController::class, 'store'])->middleware(['can:create-attribute-groups']);
    Route::get('attributeGroups/{id}', [AttributeGroupController::class, 'show'])->middleware(['can:view-attribute-groups']);
    Route::delete('attributeGroups/{id}', [AttributeGroupController::class, 'destroy'])->middleware(['can:delete-attribute-groups']);
    Route::post('attributeGroups/{id}', [AttributeGroupController::class, 'update'])->middleware(['can:update-attribute-groups']);
    Route::post('attributeGroup/destroy_selected', [AttributeGroupController::class, 'destroy_selected'])->middleware(['can:destroy-selected-attribute-groups']);

    //////////////////////////// attributes ///////////////////////////////
    Route::get('attributes', [AttributeController::class, 'index'])->middleware(['can:view-attributes']);
    Route::post('attributes', [AttributeController::class, 'store'])->middleware(['can:create-attributes']);
    Route::get('attributes/{id}', [AttributeController::class, 'show'])->middleware(['can:view-attributes']);
    Route::delete('attributes/{id}', [AttributeController::class, 'destroy'])->middleware(['can:delete-attributes']);
    Route::post('attributes/{id}', [AttributeController::class, 'update'])->middleware(['can:update-attributes']);
    Route::post('attribute/destroy_selected', [AttributeController::class, 'destroy_selected'])->middleware(['can:destroy-selected-attributes']);

    //////////////////////////// categories ///////////////////////////////
    Route::get('categories', [CategoryController::class, 'index'])->middleware(['can:view-categories']);
    Route::post('categories', [CategoryController::class, 'store'])->middleware(['can:create-categories']);
    Route::get('categories/{id}', [CategoryController::class, 'show'])->middleware(['can:view-categories']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->middleware(['can:delete-categories']);
    Route::post('categories/{id}', [CategoryController::class, 'update'])->middleware(['can:update-categories']);
    Route::get('category/trashes', [CategoryController::class, 'trash'])->name('categories.trashes')->middleware(['can:view-categories-trashes']);
    Route::get('category/restore/{id}', [CategoryController::class, 'restore'])->name('category.restore')->middleware(['can:restore-categories']);
    Route::get('getAllCategoriesForm', [CategoryController::class, 'getAllCategoriesForm'])->middleware(['can:view-all-categories-form']);
    Route::post('category/destroy_selected', [CategoryController::class, 'destroy_selected'])->middleware(['can:destroy-selected-categories']);
    Route::post('category/update-status/{id}', [CategoryController::class, 'update_status'])->middleware(['can:change-category-status']);

// Refactor salesRepLevels routes
    Route::resource('salesRepLevels', SalesRepLevelController::class)
        ->only(['index', 'store', 'show', 'destroy', 'update'])
        ->names([
            'index' => 'salesRepLevels.index',
            'store' => 'salesRepLevels.store',
            'show' => 'salesRepLevels.show',
            'destroy' => 'salesRepLevels.destroy',
            'update' => 'salesRepLevels.update'
        ]);

    Route::post('salesRepLevels/{id}', [SalesRepLevelController::class, 'update'])->middleware(['can:update-categories']);
    Route::post('salesRepLevel/destroy_selected', [SalesRepLevelController::class, 'destroy_selected'])->name('salesRepLevels.destroy_selected');
    Route::post('salesRepLevel/update-status/{id}', [SalesRepLevelController::class, 'update_status'])->name('salesRepLevels.update_status');

    Route::resource('salesRepUsers', SalesRepUserController::class)
        ->only(['index', 'store', 'show', 'destroy'])
        ->names([
            'index' => 'salesRepUsers.index',
            'store' => 'salesRepUsers.store',
            'show' => 'salesRepUsers.show',
            'destroy' => 'salesRepUsers.destroy',
        ]);

    Route::post('salesRepUsers/{id}', [SalesRepUserController::class, 'update'])->name('salesRepUsers.update');
    Route::post('salesRepUser/destroy_selected', [SalesRepUserController::class, 'destroy_selected'])->name('salesRepUsers.destroy_selected');
    Route::post('salesRepUser/update-status/{id}', [SalesRepUserController::class, 'update_status'])->name('salesRepUsers.update_status');
    Route::post('salesRepUser/add-transaction/{id}', [SalesRepUserController::class, 'addTransaction'])->name('salesRepUsers.addTransaction');
    Route::get('salesRepUser/permissions', [SalesRepUserController::class, 'permissions'])->name('salesRepUsers.permissions');


    //////////////////////////// products ///////////////////////////////
    Route::get('products', [ProductController::class, 'index'])->middleware(['can:view-products']);
    Route::post('products', [ProductController::class, 'store'])->middleware(['can:create-products']);
    Route::get('products/{id}', [ProductController::class, 'show'])->middleware(['can:view-products']);
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->middleware(['can:delete-products']);
    Route::post('products/{id}', [ProductController::class, 'update'])->middleware(['can:update-products']);
    Route::post('product/changeStatus/{id}', [ProductController::class, 'changeStatus'])->name('products.changeStatus')->middleware(['can:change-product-status']);
    Route::get('product/trashes', [ProductController::class, 'trash'])->name('products.trashes')->middleware(['can:view-products-trashes']);
    Route::get('product/restore/{id}', [ProductController::class, 'restore'])->name('products.restore')->middleware(['can:restore-products']);
    Route::post('product/multiDelete', [ProductController::class, 'multiDelete'])->name('products.multiDelete')->middleware(['can:destroy-selected-products']);

    Route::post('product/serials', [ProductController::class, 'serials'])->name('products.serials')->middleware(['can:view-products-serials']);
    Route::post('product/applyPriceAll', [ProductController::class, 'applyPriceAll'])->name('products.applyPriceAll')->middleware(['can:apply-products-price-all']);
    Route::post('product/applyPriceAllGroups', [ProductController::class, 'applyPriceAllGroups'])->name('products.applyPriceAllGroups')->middleware(['can:apply-products-price-all-groups']);
    Route::post('product/prices', [ProductController::class, 'prices'])->name('products.prices')->middleware(['can:view-products-prices']);
    Route::get('product/get-brand-products/{brand_id}', [ProductController::class, 'get_brand_products'])->name('products.get_brand_products')->middleware(['can:view-products-by-brands']);
    Route::post('product/delete_image_product/{id}', [ProductController::class, 'delete_image_product'])->name('products.delete_image_product')->middleware(['can:delete-products-images']);

    //////////////////////////// Filling Serials ///////////////////////////////
    Route::group(['prefix' => 'productSerials', 'as' => 'productSerials.'], function () {
        Route::post('manualFilling', [ProductSerialController::class, 'manualFilling'])->name('manualFilling')->middleware(['can:manual-filling-products']);
        Route::get('stock-logs', [ProductSerialController::class, 'stock_logs'])->name('stockLogs')->middleware(['can:stock-logs-products-serials']);
        Route::get('stock-logs-by-invoice/{invoice_id}', [ProductSerialController::class, 'stock_logs_invoice'])->name('stock_logs_invoice')->middleware(['can:stock-logs-products-serials-invoice']);
        Route::post('stock-logs/{id}', [ProductSerialController::class, 'update_stock_logs'])->name('stockLogs.show')->middleware(['can:update-stock-logs-products-serials']);

        //Route::post('vendorIntegrate', [ProductSerialController::class, 'vendorIntegrate'])->name('vendorIntegrate');
        Route::post('autoFilling', [ProductSerialController::class, 'autoFilling'])->name('autoFilling')->middleware(['can:auto-filling-products']);
        Route::post('changeStatus', [ProductSerialController::class, 'statusInvoiceSerials'])->name('changeStatus')->middleware(['can:change-status-products-serials']);
    });

    //////////////////////////// sellers ///////////////////////////////
    Route::group(['prefix' => 'sellers', 'as' => 'sellers.'], function () {
        Route::get('', [SellerController::class, 'index'])->name('index')->middleware(['can:view-sellers']);
        Route::get('notApproved', [SellerController::class, 'notApproved'])->name('notApproved')->middleware(['can:view-not-approved-sellers']);
        Route::get('{id}', [SellerController::class, 'show'])->name('show')->middleware(['can:view-sellers']);
        Route::post('store', [SellerController::class, 'store'])->name('store')->middleware(['can:create-sellers']);
        Route::post('update/{id}', [SellerController::class, 'update'])->name('update')->middleware(['can:update-sellers']);
        Route::post('add-balance/{id}', [SellerController::class, 'add_balance'])->name('add_balance')->middleware(['can:add-balance-sellers']);
        Route::post('changeStatus/{id}', [SellerController::class, 'changeStatus'])->name('changeStatus.update')->middleware(['can:change-status-sellers']);
        Route::post('changeApprovalStatus/{id}', [SellerController::class, 'changeApprovalStatus'])->name('changeApprovalStatus')->middleware(['can:change-approval-status-sellers']);
        Route::delete('delete/{id}', [SellerController::class, 'destroy'])->name('delete')->middleware(['can:delete-sellers']);
        Route::delete('attachments/delete/{id}', [SellerController::class, 'deleteAttachments'])->name('attachments.delete')->middleware(['can:delete-attachments-sellers']);
        Route::get('trashes', [SellerController::class, 'trash'])->name('trashes')->middleware(['can:view-trashes-sellers']);
        Route::get('restore/{id}', [SellerController::class, 'restore'])->name('restore')->middleware(['can:restore-sellers']);
    });

    //////////////////////////// sellerGroups ///////////////////////////////
    Route::get('sellerGroups', [SellerGroupController::class, 'index'])->middleware(['can:view-sellerGroups']);
    Route::post('sellerGroups', [SellerGroupController::class, 'store'])->middleware(['can:create-sellerGroups']);
    Route::get('sellerGroups/{id}', [SellerGroupController::class, 'show'])->middleware(['can:view-sellerGroups']);
    Route::delete('sellerGroups/{id}', [SellerGroupController::class, 'destroy'])->middleware(['can:delete-sellerGroups']);
    Route::post('sellerGroups/{id}', [SellerGroupController::class, 'update'])->middleware(['can:update-sellerGroups']);
    Route::get('sellerGroup/trashes', [SellerGroupController::class, 'trash'])->name('sellerGroup.trashes')->middleware(['can:view-trashes-sellerGroups']);
    Route::get('sellerGroup/restore/{id}', [SellerGroupController::class, 'restore'])->name('sellerGroup.restore')->middleware(['can:restore-sellerGroups']);
    Route::post('sellerGroup/update-status/{id}', [SellerGroupController::class, 'update_status'])->middleware(['can:view-sellerGroups']);
    Route::post('sellerGroup/auto-assign/{id}', [SellerGroupController::class, 'auto_assign'])->middleware(['can:auto-assign-sellerGroups']);
    Route::post('category/destroy_selected', [SellerGroupController::class, 'destroy_selected'])->middleware(['can:destroy-selected-sellerGroups']);


    //////////////////////////// sellerGroupLevels ///////////////////////////////
    Route::get('sellerGroupLevels', [SellerGroupLevelController::class, 'index'])->middleware(['can:view-sellerGroupLevels']);
    Route::post('sellerGroupLevels', [SellerGroupLevelController::class, 'store'])->middleware(['can:create-sellerGroupLevels']);
    Route::get('sellerGroupLevels/{id}', [SellerGroupLevelController::class, 'show'])->middleware(['can:view-sellerGroupLevels']);
    Route::delete('sellerGroupLevels/{id}', [SellerGroupLevelController::class, 'destroy'])->middleware(['can:delete-sellerGroupLevels']);
    Route::post('sellerGroupLevels/{id}', [SellerGroupLevelController::class, 'update'])->middleware(['can:update-sellerGroupLevels']);
    Route::get('sellerGroupLevel/trashes', [SellerGroupLevelController::class, 'trash'])->name('sellerGroupLevel.trashes')->middleware(['can:view-trashes-sellerGroupLevels']);
    Route::get('sellerGroupLevel/restore/{id}', [SellerGroupLevelController::class, 'restore'])->name('sellerGroupLevel.restore')->middleware(['can:restore-sellerGroupLevels']);
    Route::post('sellerGroupLevel/update-status/{id}', [SellerGroupLevelController::class, 'update_status'])->middleware(['can:change-status-sellerGroupLevels']);

    //////////////////////////// Settings ///////////////////////////////
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/{group}', [SettingController::class, 'getByGroup']);
        Route::post('/{group}', [SettingController::class, 'updateCommissionSetting']);
        Route::get('main', [SettingController::class, 'mainSettings'])->name('main')->middleware(['can:view-main-settings']);
        Route::post('updateMain', [SettingController::class, 'updateMainSettings'])->name('update.main')->middleware(['can:update-main-settings']);
        //// Static pages
        Route::group(['prefix' => 'staticPages', 'as' => 'staticPages.'], function () {
            Route::get('', [StaticPageController::class, 'index'])->name('index')->middleware(['can:view-static-pages']);
            Route::get('{id}', [StaticPageController::class, 'show'])->name('show')->middleware(['can:view-static-pages']);
            Route::post('store', [StaticPageController::class, 'store'])->name('store')->middleware(['can:create-static-pages']);
            Route::post('update/{id}', [StaticPageController::class, 'update'])->name('update')->middleware(['can:update-static-pages']);
            Route::post('changeStatus/{id}', [StaticPageController::class, 'changeStatus'])->name('staticPages.changeStatus')->middleware(['can:change-status-static-pages']);
            Route::delete('delete/{id}', [StaticPageController::class, 'delete'])->name('delete')->middleware(['can:delete-static-pages']);
        });
        //// Notifications
        Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
            Route::get('', [NotificationSettingController::class, 'getNotificationSettings'])->name('getNotificationSettings')->middleware(['can:view-notifications']);
            Route::post('update/{id}', [NotificationSettingController::class, 'updateNotificationSettings'])->name('updateNotificationSettings')->middleware(['can:update-notifications']);
        });
        //// Store Appearance
        Route::group(['prefix' => 'storeAppearance', 'as' => 'storeAppearance.'], function () {
            Route::get('sliders', [SliderController::class, 'index'])->name('sliders.index')->middleware(['can:view-sliders']);
            Route::get('sliders/{id}', [SliderController::class, 'show'])->name('sliders.show')->middleware(['can:view-sliders']);
            Route::post('sliders/store', [SliderController::class, 'store'])->name('sliders.store')->middleware(['can:create-sliders']);
            Route::post('sliders/update/{id}', [SliderController::class, 'update'])->name('sliders.update')->middleware(['can:update-sliders']);
            Route::post('sliders/changeStatus/{id}', [SliderController::class, 'changeStatus'])->name('sliders.changeStatus')->middleware(['can:change-status-sliders']);
            Route::post('sliders/move', [SliderController::class, 'changeOrder'])->name('sliders.changeOrder')->middleware(['can:move-sliders']);
            Route::delete('sliders/delete/{id}', [SliderController::class, 'delete'])->name('sliders.delete')->middleware(['can:delete-sliders']);
            /////////
            Route::get('homeSections', [HomeSectionController::class, 'index'])->name('homeSections.index')->middleware(['can:view-home-sections']);
            Route::get('homeSections/{id}', [HomeSectionController::class, 'show'])->name('homeSections.show')->middleware(['can:view-home-sections']);
            Route::post('homeSections/store', [HomeSectionController::class, 'store'])->name('homeSections.store')->middleware(['can:create-home-sections']);
            Route::post('homeSections/update/{id}', [HomeSectionController::class, 'update'])->name('homeSections.update')->middleware(['can:update-home-sections']);
            Route::post('homeSections/changeStatus/{id}', [HomeSectionController::class, 'changeStatus'])->name('homeSections.changeStatus')->middleware(['can:change-status-home-sections']);
            Route::post('homeSections/move', [HomeSectionController::class, 'changeOrder'])->name('homeSections.changeOrder')->middleware(['can:move-home-sections']);
            Route::delete('homeSections/delete/{id}', [HomeSectionController::class, 'delete'])->name('homeSections.delete')->middleware(['can:delete-home-sections']);
        });
    });

    //////////////////////////// Taxes ///////////////////////////////
    Route::group(['prefix' => 'taxes', 'as' => 'taxes.'], function () {
        Route::get('', [ValueAddedTaxController::class, 'index'])->name('index')->middleware(['can:view-taxes']);
        Route::post('store', [ValueAddedTaxController::class, 'store'])->name('store')->middleware(['can:create-taxes']);
        Route::post('update/{id}', [ValueAddedTaxController::class, 'update'])->name('update')->middleware(['can:update-taxes']);
        Route::post('changeStatus/{id}', [ValueAddedTaxController::class, 'changeStatus'])->name('taxes.changeStatus')->middleware(['can:change-status-taxes']);
        Route::delete('delete/{id}', [ValueAddedTaxController::class, 'delete'])->name('delete')->middleware(['can:delete-taxes']);
        ////
        Route::post('updatePricesDisplay', [ValueAddedTaxController::class, 'updatePricesDisplay'])->name('updatePricesDisplay')->middleware(['can:update-prices-display']);
        Route::post('me', [ValueAddedTaxController::class, 'updateTaxNumber'])->name('updateTaxNumber')->middleware(['can:update-tax-number']);
    });

    //////////////////////////// roles and permissions ///////////////////////////////
    Route::get('roles/permissions', [RoleAndPermissionController::class, 'getPermissions'])->name('roles.permissions')->middleware(['can:view-roles-permissions']);
    Route::get('roles', [RoleAndPermissionController::class, 'getRoles'])->name('roles')->middleware(['can:view-roles-permissions']);
    Route::get('roles/all', [RoleAndPermissionController::class, 'getAllRoles'])->name('roles.all')->middleware(['can:view-roles-permissions']);
    Route::get('roles/{id}', [RoleAndPermissionController::class, 'getOneRole'])->name('roles.getOneRole')->middleware(['can:view-roles-permissions']);
    Route::post('roles/store', [RoleAndPermissionController::class, 'storeRole'])->name('roles.store')->middleware(['can:create-roles']);
    Route::post('roles/update/{id}', [RoleAndPermissionController::class, 'updateRole'])->name('roles.update')->middleware(['can:update-roles']);
    Route::post('roles/changeStatus/{id}', [RoleAndPermissionController::class, 'changeStatus'])->name('roles.changeStatus')->middleware(['can:change-status-roles']);
    Route::delete('roles/delete/{id}', [RoleAndPermissionController::class, 'deleteRole'])->name('roles.delete')->middleware(['can:delete-roles']);

    //////////////////////////// sub admins ///////////////////////////////
    Route::get('subAdmins', [SubAdminController::class, 'getAdmins'])->name('subAdmins.index')->middleware(['can:view-subAdmins']);
    Route::get('subAdmins/{id}', [SubAdminController::class, 'getOneAdmin'])->name('subAdmins.getOneAdmin')->middleware(['can:view-subAdmins']);
    Route::post('subAdmins/store', [SubAdminController::class, 'storeAdmin'])->name('subAdmins.store')->middleware(['can:create-subAdmins']);
    Route::post('subAdmins/update/{id}', [SubAdminController::class, 'updateAdmin'])->name('subAdmins.update')->middleware(['can:update-subAdmins']);
    Route::post('subAdmins/changeStatus/{id}', [SubAdminController::class, 'changeStatus'])->name('subAdmins.changeStatus')->middleware(['can:change-status-subAdmins']);
    Route::delete('subAdmins/delete/{id}', [SubAdminController::class, 'deleteAdmin'])->name('subAdmins.delete')->middleware(['can:delete-subAdmins']);

    //////////////////////////// integrations ///////////////////////////////
    Route::group(['prefix' => 'integrations', 'as' => 'integrations.'], function () {
        Route::get('', [IntegrationController::class, 'index'])->name('index')->middleware(['can:view-integrations']);
        Route::post('update/{id}', [IntegrationController::class, 'updateIntegration'])->name('update')->middleware(['can:update-integrations']);
        Route::post('changeStatus/{id}', [IntegrationController::class, 'changeStatus'])->name('integrations.changeStatus')->middleware(['can:change-status-integrations']);
    });

    //////////////////////////// Vendor Products ///////////////////////////////
    Route::group(['prefix' => 'vendorProducts', 'as' => 'vendorProducts.'], function () {
        Route::get('', [VendorProductController::class, 'index'])->name('index')->middleware(['can:view-vendorProducts']);
        Route::post('store', [VendorProductController::class, 'store'])->name('store')->middleware(['can:create-vendorProducts']);
        Route::post('update/{id}', [VendorProductController::class, 'update'])->name('update')->middleware(['can:update-vendorProducts']);
        Route::delete('delete/{id}', [VendorProductController::class, 'delete'])->name('delete')->middleware(['can:delete-vendorProducts']);
    });

    //////////////////////////// Direct Purchase Priorities ///////////////////////////////
    Route::group(['prefix' => 'purchasePriorities', 'as' => 'purchasePriorities.'], function () {
        Route::get('', [DirectPurchaseController::class, 'index'])->name('index')->middleware(['can:view-purchasePriorities']);
        Route::post('store', [DirectPurchaseController::class, 'store'])->name('store')->middleware(['can:create-purchasePriorities']);
        Route::post('changeStatus/{id}', [DirectPurchaseController::class, 'changeStatus'])->name('purchasePriorities.changeStatus')->middleware(['can:change-status-purchasePriorities']);
        Route::post('delete-vendor', [DirectPurchaseController::class, 'deleteVendor'])->name('deleteVendor');
    });

    //////////////////////////// Orders ///////////////////////////////
    Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
        Route::get('', [OrderController::class, 'index'])->name('index')->middleware(['can:view-orders']);
        Route::get('{id}', [OrderController::class, 'show'])->name('show')->middleware(['can:view-customers']);
    });


    Route::group(['prefix' => 'report'], function () {
        Route::group(['prefix' => 'sales'], function () {
            Route::get('daily', [SalesReportController::class, 'getDailyReport']);
            Route::get('total-profit', [SalesReportController::class, 'getTotalProfitReport']);
            Route::get('detailed-profit', [SalesReportController::class, 'getDetailedProfitReport']);
        });
    });

});














