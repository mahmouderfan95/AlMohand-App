<?php

namespace App\Services\Admin;

use App\Helpers\FileUpload;
use App\Http\Resources\Admin\Order\OrderDetailsResource;
use App\Http\Resources\Admin\Order\OrdersResource;
use App\Repositories\Admin\SellerRepository;
use App\Repositories\Admin\ValueAddedTaxRepository;
use App\Repositories\DirectPurchase\DirectPurchaseRepository;
use App\Repositories\GeoLocation\CountryRepository;
use App\Repositories\Integration\IntegrationRepository;
use App\Repositories\Invoice\InvoiceRepository;
use App\Repositories\Order\FailedOrderReasonRepository;
use App\Repositories\Order\OrderHistoryRepository;
use App\Repositories\Order\OrderProductRepository;
use App\Repositories\Order\OrderProductSerialRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderUserRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Product\ProductSerialRepository;
use App\Repositories\Vendor\VendorProductRepository;
use App\Services\General\NotificationServices\EmailsAndNotificationService;
use App\Traits\ApiResponseAble;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderService
{

    use FileUpload, ApiResponseAble;

    public function __construct(
        private OrderRepository                 $orderRepository,
        private SellerRepository                $sellerRepository,
        private OrderProductRepository          $orderProductRepository,
        private CountryRepository               $countryRepository,
        private OrderUserRepository             $orderUserRepository,
        private ProductRepository               $productRepository,
        private VendorProductRepository         $vendorProductRepository,
        private IntegrationRepository           $integrationRepository,
        private ValueAddedTaxRepository         $valueAddedTaxRepository,
        private InvoiceRepository               $invoiceRepository,
        private ProductSerialRepository         $productSerialRepository,
        private OrderProductSerialRepository    $orderProductSerialRepository,
        private DirectPurchaseRepository        $directPurchaseRepository,
        private FailedOrderReasonRepository     $failedOrderReasonRepository,
        private OrderHistoryRepository          $orderHistoryRepository,
        private EmailsAndNotificationService    $emailsAndNotificationService,
    )
    {}

    public function getAllOrders($request)
    {
        try {
            $orders = $this->orderRepository->getAllOrders($request);
            if (count($orders) > 0)
                return $this->listResponse(OrdersResource::collection($orders)->response()->getData());
            else
                return $this->listResponse([]);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $authAdmin = Auth::guard('adminApi')->user();
            $order = $this->orderRepository->show($id);
            if (! $order)
                return $this->notFoundResponse();

            $orderUser = $this->orderUserRepository->checkByOrderIdAndUserId($order->id, $authAdmin->id);
            if ($orderUser)
                $order = $this->orderRepository->formatOrderProductsWithoutHashedTopUp($order);
            else
                $order = $this->orderRepository->formatOrderProductsWithHashedAll($order);

            return $this->showResponse(new OrderDetailsResource($order));

        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function update_status(Request $request, int $order_id): \Illuminate\Http\JsonResponse
    {
        $data_request = $request->all();

        try {
            $order = $this->orderRepository->update_status($data_request, $order_id);
            if ($order)
                return $this->showResponse($order);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function save_notes(Request $request): \Illuminate\Http\JsonResponse
    {
        $data_request = $request->all();

        try {
            $order = $this->orderRepository->save_notes($data_request);
            if ($order)
                return $this->showResponse($order);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function get_status(Request $request, $status): \Illuminate\Http\JsonResponse
    {
        $data_request = $request->all();

        try {
            $order = $this->orderRepository->get_status($data_request, $status);
            if ($order)
                return $this->showResponse($order);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function get_customer_orders($customer_id): \Illuminate\Http\JsonResponse
    {

        try {
            $order = $this->orderRepository->get_customer_orders($customer_id);
            if ($order)
                return $this->showResponse($order);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }





}
