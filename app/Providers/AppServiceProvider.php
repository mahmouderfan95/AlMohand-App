<?php

namespace App\Providers;

use App\Builders\Order\AbstractOrderProductBuilder;
use App\Builders\Order\OrderProductBuilder;
use App\Builders\Order\OrderProductBuilderInterface;
use App\Interfaces\ServicesInterfaces\BalanceLog\BalanceLogServiceInterface;
use App\Interfaces\ServicesInterfaces\BalanceRequest\BalanceRequestServiceInterface;
use App\Interfaces\ServicesInterfaces\Brand\BrandServiceInterface;
use App\Interfaces\ServicesInterfaces\Category\CategoryServiceInterface;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorGroupConditionServiceInterface;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorGroupServiceInterface;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorPosTerminalServiceInterface;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorServiceInterface;
use App\Interfaces\ServicesInterfaces\GeoLocation\ZoneServiceInterface;
use App\Interfaces\ServicesInterfaces\Merchant\MerchantServiceInterface;
use App\Interfaces\ServicesInterfaces\Order\OrderServiceInterface;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosAuthServiceInterface;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalServiceInterface;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalTransactionServiceInterface;
use App\Interfaces\ServicesInterfaces\Print\PrintServiceInterface;
use App\Interfaces\ServicesInterfaces\Product\ProductServiceInterface;
use App\Interfaces\ServicesInterfaces\Report\ReportServiceInterface;
use App\Interfaces\ServicesInterfaces\Report\SalesReportServiceInterface;
use App\Interfaces\ServicesInterfaces\SalesRep\SalesRepAuthServiceInterface;
use App\Interfaces\ServicesInterfaces\SalesRepLevel\SalesRepLevelServiceInterface;
use App\Interfaces\ServicesInterfaces\SalesRepUser\SalesRepUserServiceInterface;
use App\Services\BalanceLog\BalanceLogService;
use App\Services\BalanceRequest\BalanceRequestService;
use App\Services\Brand\BrandService;
use App\Services\Category\CategoryService;
use App\Services\Distributor\DistributorAttachmentService;
use App\Services\Distributor\DistributorGroupConditionService;
use App\Services\Distributor\DistributorGroupService;
use App\Services\Distributor\DistributorPosTerminalService;
use App\Services\Distributor\DistributorService;
use App\Services\GeoLocation\ZoneService;
use App\Services\Merchant\PosAuthService;
use App\Services\Merchant\MerchantService;
use App\Services\Order\OrderService;
use App\Services\PosTerminal\PosTerminalService;
use App\Services\PosTerminal\PosTerminalTransactionService;
use App\Services\Print\PrintService;
use App\Services\Product\ProductService;
use App\Services\Report\ReportService;
use App\Services\Report\SalesReportService;
use App\Services\SalesRep\SalesRepAuthService;
use App\Services\SalesRepLevel\SalesRepLevelService;
use App\Services\SalesRepUser\SalesRepUserService;
use App\Settings\CoreSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelSettings\SettingsContainer;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(MerchantServiceInterface::class, MerchantService::class);
        $this->app->bind(PosAuthServiceInterface::class, PosAuthService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(BrandServiceInterface::class, BrandService::class);
        $this->app->bind(DistributorServiceInterface::class, DistributorService::class);
        $this->app->bind(DistributorGroupServiceInterface::class, DistributorGroupService::class);
        $this->app->bind(DistributorGroupConditionServiceInterface::class, DistributorGroupConditionService::class);
        $this->app->bind(DistributorAttachmentService::class, DistributorAttachmentService::class);
        $this->app->bind(ZoneServiceInterface::class, ZoneService::class);
        $this->app->bind(PosTerminalServiceInterface::class, PosTerminalService::class);
        $this->app->bind(DistributorPosTerminalServiceInterface::class, DistributorPosTerminalService::class);
        $this->app->bind(PosTerminalTransactionServiceInterface::class, PosTerminalTransactionService::class);
        $this->app->bind(BalanceRequestServiceInterface::class, BalanceRequestService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(AbstractOrderProductBuilder::class, OrderProductBuilder::class);
        $this->app->bind(BalanceLogServiceInterface::class, BalanceLogService::class);
        $this->app->bind(PrintServiceInterface::class, PrintService::class);
        $this->app->bind(SalesRepLevelServiceInterface::class, SalesRepLevelService::class);
        $this->app->bind(SalesRepUserServiceInterface::class, SalesRepUserService::class);
        $this->app->bind(SalesRepAuthServiceInterface::class, SalesRepAuthService::class);
        $this->app->bind(ReportServiceInterface::class, ReportService::class);
        $this->app->bind(SalesReportServiceInterface::class, SalesReportService::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
