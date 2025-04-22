<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';



    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    protected $vendorNamespace = 'App\\Http\\Controllers\\Vendor';
    protected $sellerNamespace = 'App\\Http\\Controllers\\Seller';
    protected $merchantNamespace = 'App\\Http\\Controllers\\Pos';
    protected $salesRepNamespace = 'App\\Http\\Controllers\\SalesRep';
    protected $adminNamespace = 'App\\Http\\Controllers\\Admin\\';


    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware(['api'])
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::prefix('api')
                ->middleware(['api'])
                ->namespace($this->sellerNamespace)
                ->group(base_path('routes/seller.php'));

            if (app()->environment('local')) {
                Route::prefix('api')
                    ->middleware('api')
                    ->namespace($this->merchantNamespace)
                    ->group(base_path('routes/pos.php'));
            } else {
                Route::prefix('api')
                    ->middleware(['api', 'decrypt.request', 'encrypt.response'])
                    ->namespace($this->merchantNamespace)
                    ->group(base_path('routes/pos.php'));
            }


            Route::prefix('api')
                ->middleware(['api'])
                ->namespace($this->salesRepNamespace)
                ->group(base_path('routes/sales_rep.php'));

            Route::prefix('api')
                ->middleware(['api'])
                ->namespace($this->adminNamespace)
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(6000)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
