<?php

use App\Http\Controllers\Admin\AttributeController as DashAttributeController;
use App\Http\Controllers\Admin\CategoryController as DashCategoryController;
use App\Http\Controllers\Admin\CertificateController as DashCertificateController;
use App\Http\Controllers\Admin\CityController as DashCityController;
use App\Http\Controllers\Admin\CountryController as DashCountryController;
use App\Http\Controllers\Admin\CurrencyController as DashCurrencyController;
use App\Http\Controllers\Admin\LanguageController as DashLanguageController;
use App\Http\Controllers\Admin\RegionController as DashRegionController;
use App\Http\Controllers\Admin\TypeController as DashTypeController;
use App\Http\Controllers\Admin\UnitController as DashUnitController;
use App\Http\Controllers\Admin\QualityController as DashQualityController;
use App\Http\Controllers\Admin\BannerController as DashBannerController;
use App\Http\Controllers\Admin\BankController as DashBankController;
use App\Http\Controllers\Admin\VendorController as DashVendorController;
use App\Http\Controllers\Admin\ProductController as DashProductController;
use App\Http\Controllers\Admin\ReviewController as DashReviewController;
use App\Http\Controllers\Admin\ClientController as DashClientController;
use App\Http\Controllers\Admin\FagController as DashFagController;
use App\Http\Controllers\Admin\PrivacyPolicyController as DashPrivacyPolicyController;
use App\Http\Controllers\Admin\ContactController as DashContactController;
use App\Http\Controllers\Admin\SizeController as DashSizeController;
use App\Http\Controllers\Admin\OrderController as DashOrderController;
use App\Http\Controllers\Admin\PackageController as DashPackageController;
use App\Http\Controllers\Admin\AttributeGroupController as DashAttributeGroupController;
use App\Http\Controllers\RoleController as DashRoleController;
use App\Http\Controllers\PermissionController as DashPermissionController;
use App\Http\Controllers\Admin\TransactionController as DashTransactionController;
use App\Http\Controllers\Admin\ShippingMethodController as DashShippingMethodController;
use App\Http\Controllers\Admin\AboutUsController as DashAboutUsController;
use App\Http\Controllers\Admin\SubVendorController as DashSubVendorController;
use App\Http\Controllers\Admin\SettingController as DashSettingController;
use App\Http\Controllers\Admin\TermsAndConditionController as DashTermsAndConditionController;
use App\Http\Controllers\Admin\VendorAgreementController;
use App\Http\Controllers\Admin\VendorWalletController;
use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();
//Route::get('/', [App\Http\Controllers\CategoryController::class, 'root'])->name('root');

Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');


Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.loginForm');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {
    //Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});
