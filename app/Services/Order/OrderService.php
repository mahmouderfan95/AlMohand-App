<?php

namespace App\Services\Order;

use App\Builders\Order\AbstractOrderProductBuilder;
use App\DTO\BaseDTO;
use App\DTO\Pos\Order\AllOrdersDto;
use App\DTO\Pos\Order\CallbackOrderDto;
use App\DTO\Pos\Order\StoreOrderDto;
use App\Enums\BalanceLog\BalanceTypeStatusEnum;
use App\Enums\GeneralStatusEnum;
use App\Enums\Integration\MintrouteIntegrationType;
use App\Enums\Order\OrderPaymentMethod;
use App\Enums\Order\OrderProductType;
use App\Enums\Order\OrderSource;
use App\Enums\Order\OrderStatus;
use App\Enums\PosTerminalTransaction\TransactionStatusEnum;
use App\Enums\PosTerminalTransaction\TransactionTypeEnum;
use App\Enums\ProductSerialType;
use App\Enums\VendorStatus;
use App\Helpers\SettingsHelper;
use App\Helpers\UniqueCodeGeneratorHelper;
use App\Http\Resources\Admin\Report\SalesReport\DailySalesReportResource;
use App\Http\Resources\Pos\Order\StoredOrderProductSerialResource;
use App\Http\Resources\SalesRep\Order\OrderDetailsResource;
use App\Interfaces\ServicesInterfaces\Order\OrderServiceInterface;
use App\Mail\AdminOrderCreatedEmail;
use App\Models\Distributor\DistributorPosTerminal;
use App\Models\Order\Order;
use App\Models\POSTerminal\PosTerminalTransaction;
use App\Models\Product\Product;
use App\Models\User;
use App\Notifications\CustomNotification;
use App\Repositories\BalanceLog\BalanceLogRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\DirectPurchase\DirectPurchaseRepository;
use App\Repositories\Integration\IntegrationRepository;
use App\Repositories\Order\OrderPaymentTransactionRepository;
use App\Repositories\Order\OrderProductRepository;
use App\Repositories\Order\OrderProductSerialRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderUserRepository;
use App\Repositories\PosTerminal\PosTerminalTransactionRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Vendor\VendorProductRepository;
use App\Services\BaseService;
use App\Services\General\OnlineShoppingIntegration\IntegrationServiceFactory;
use App\Traits\ApiResponseAble;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderService extends BaseService implements OrderServiceInterface
{
    use ApiResponseAble;


    public function __construct(
        private readonly OrderRepository                   $orderRepository,
        private readonly OrderProductRepository            $orderProductRepository,
        private readonly OrderProductSerialRepository      $orderProductSerialRepository,
        private readonly ProductRepository                 $productRepository,
        private readonly CategoryRepository                $categoryRepository,
        private readonly DirectPurchaseRepository          $directPurchaseRepository,
        private readonly VendorProductRepository           $vendorProductRepository,
        private readonly IntegrationRepository             $integrationRepository,
        private readonly OrderPaymentTransactionRepository $orderPaymentTransactionRepository,
        private readonly PosTerminalTransactionRepository  $posTerminalTransactionRepository,
        private readonly BalanceLogRepository              $balanceLogRepository,
        private readonly AbstractOrderProductBuilder       $orderProductBuilder,
        private readonly OrderUserRepository               $orderUserRepository
    )
    {

    }

    public function getPosOrders(AllOrdersDto $data, $distributor_pos_terminal_id = null): mixed
    {
        DB::beginTransaction();
        try{
            $data = $data->toArray();
            $orders = $this->orderProductSerialRepository->getPosOrdersReport($data, $distributor_pos_terminal_id);

            DB::commit();
            return $this->ApiSuccessResponse($orders);
        }catch(Exception $e){
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(),trans('admin.general_error'));
        }
    }

    public function store(StoreOrderDto|BaseDTO $data): mixed
    {
        $data = $data->toArray();
        if ($data['payment_method'] == OrderPaymentMethod::getBalance()){
            // order with balance
            return $this->storeUsingBalance($data);
        }else {
            // order with mada
            return $this->storeUsingMada($data);
        }
    }

    public function storeUsingBalance(array $data): mixed
    {
        $authPos = Auth::guard('posApi')->user();
        DB::beginTransaction();
        try {
            $orderTotal = 0;
            $orderProfit = 0;
            //get product details
            $product = $this->productRepository->find($data['product_id']);
            if (!$product) {
                return $this->ApiErrorResponse(null, 'this product doesnt exist');
            }
            // check balance and product validate
            $validatedOrderFees = $this->validateOrderFees($product, $data['quantity'], $authPos);
            if ($validatedOrderFees['error']) {
                return $this->ApiErrorResponse($validatedOrderFees['data'], $validatedOrderFees['message']);
            }
            $data['payment_method'] = OrderPaymentMethod::getBalance();
            $data['real_amount'] = $product->wholesale_price * $data['quantity'];     // amount from wholesale_price for balance
            $order = $this->prepareOrder($data, $authPos);
            if (!$order) {
                return $this->ApiErrorResponse(null, __('admin.general_error'));
            }

            // check if exist in stock after check priorities
            if ($validatedOrderFees['is_stock']){
                // Call the stored procedure (executes immediately)
                DB::statement('CALL update_product_serials(?, ?, ?)', [$order->id, $product->id, $data['quantity']]);
            }

            // make process for order product
            $orderProduct = $this->orderProductBuilder
                ->set($order, $product, $data['quantity'])
                ->selectOrderWithType()
                ->updateOrderProductPrices()
                ->storeFailedOrderReasons()
                ->getOrderProduct();
            $orderTotal += $orderProduct->total;
            $orderProfit += $orderProduct->profit;

            // make order as complete after payment done
            $order->sub_total = $orderTotal;
            $order->total = $orderTotal;
            $order->status = OrderStatus::COMPLETED;
            $order->save();
            // handle logic to decrease balance
            $this->decreaseBalance($authPos, $order);

            DB::commit();
            // execute dependencies after order created
            // $this->executeDependencies($order);

            return $this->ApiSuccessResponse(StoredOrderProductSerialResource::collection($orderProduct->orderProductSerials), 'Order Created Success');
        } catch (Exception $e) {
            DB::rollBack();
            // return $this->ApiErrorResponse(null, $e);
            Log::info($authPos);
            Log::info($e);
            Log::info('something went wrong.n_261');
            return $this->ApiErrorResponse($e->getMessage(), trans('admin.general_error'));
        }
    }

    public function storeUsingMada(array $data): mixed
    {
        $authPos = Auth::guard('posApi')->user();
        DB::beginTransaction();
        try {
            //get product details
            $product = $this->productRepository->find($data['product_id']);
            if (!$product) {
                return $this->ApiErrorResponse(null, 'this product doesnt exist');
            }

            $data['payment_method'] = OrderPaymentMethod::getMada();
            $data['real_amount'] = $product->price * $data['quantity'];     // amount from price for mada
            $availableData = $this->checkVendorProductAvailability($product, $data['quantity']);
            if (! $availableData['success']) {
                return $this->ApiErrorResponse(['product_id' => $product->id], 'Quantity not enough, or not available..!');
            }
            $order = $this->prepareOrder($data, $authPos);
            if (!$order) {
                return $this->ApiErrorResponse(null, __('admin.general_error'));
            }
            // check if exist in stock after check priorities
            if ($availableData['is_stock']){
                // Call the stored procedure (executes immediately)
                DB::statement('CALL update_product_serials(?, ?, ?)', [$order->id, $product->id, $data['quantity']]);
            }

            // make process for order product
            $this->orderProductBuilder
                ->set($order, $product, $data['quantity'])
                ->getOrderProduct();

            DB::commit();
            return $this->ApiSuccessResponse(['order_id' => $order->id , 'amount' => $order->real_amount], 'Order Prepare Successfully');

        } catch (Exception $e) {
            DB::rollBack();
            // return $this->ApiErrorResponse(null, $e);
            Log::info($authPos);
            Log::info($e);
            Log::info('something went wrong.n_261');
            return $this->ApiErrorResponse($e->getMessage(), trans('admin.general_error'));
        }
    }

    public function callback(CallbackOrderDto $data): mixed
    {
        $authPos = Auth::guard('posApi')->user();
        $data = $data->toArray();
        // get order details
        $order = $this->orderRepository->getPendingOrderById($data['order_id']);
        if (!$order) {
            return $this->ApiErrorResponse(null, __('admin.general_error'));
        }
        // check for mada response if success
        Log::info($data['mada_response']);
        if (! $data['mada_response'] || $data['mada_response']['StatusCode'] != '00'){
            return $this->ApiErrorResponse(null, __('admin.general_error'));
        }
        // store OrderPaymentTransaction
        $this->orderPaymentTransactionRepository->store($data);
        DB::beginTransaction();
        try {
            $orderTotal = 0;
            $orderProfit = 0;
            // get order product details
            $orderProduct = $order->orderProduct;

            // make process for order product
            $orderProduct = $this->orderProductBuilder
                ->reset($orderProduct)
                ->selectOrderWithType()
                ->updateOrderProductPrices()
                ->storeFailedOrderReasons()
                ->getOrderProduct();

            $orderTotal += $orderProduct->total;
            $orderProfit += $orderProduct->profit;

            // make order as complete after payment done
            $order->sub_total = $orderTotal;
            $order->total = $orderTotal;
            $order->status = OrderStatus::COMPLETED;
            $order->save();
            // handle logic to decrease balance
            $this->increaseCommission($authPos, $order, $orderProfit);
            $this->increasePoint($authPos, $order, $orderProfit);

            DB::commit();
            // execute dependencies after order created
            // $this->executeDependencies($order);

            return $this->ApiSuccessResponse(StoredOrderProductSerialResource::collection($orderProduct->orderProductSerials), 'Order Created Success');
        } catch (Exception $e) {
            DB::rollBack();
            // return $this->ApiErrorResponse(null, $e);
            Log::info($authPos);
            Log::info($e);
            Log::info('something went wrong.n_261');
            return $this->ApiErrorResponse($e->getMessage(), trans('admin.general_error'));
        }
    }




    private function prepareOrder(array $data, Authenticatable $authPos): Order
    {
        $data['status'] = OrderStatus::PENDING;
        $data['owner_type'] = DistributorPosTerminal::class;
        $data['owner_id'] = $authPos->id;
        $data['order_source'] = OrderSource::getSourceMobile();
        $order = $this->orderRepository->store($data);
        return $order;
    }

    private function validateOrderFees(Product $product, int $quantity, $authPos): array
    {
        // check availability for vendors
        $availableData = $this->checkVendorProductAvailability($product, $quantity);
        if (! $availableData['success']) {
            return ['data' => ['product_id' => $product->id], 'error' => true, 'message' => 'Quantity not enough, or not available..!'];
        }
        // depend on pos balance
        if ($authPos->balance < ($product->wholesale_price * $quantity)) {
            return ['data' => null, 'error' => true, 'message' => 'Not enough balance.'];
        }
        return ['data' => null, 'error' => false, 'message' => '', 'is_stock' => $availableData['is_stock']];
    }

    private function checkVendorProductAvailability(Product $product, int $quantity = 1): array
    {
        $data = ['success' => false, 'is_stock' => 1];
        // get product from direct purchase priorities
        $directPurchase = $this->directPurchaseRepository->showByProductId($product->id);
        // that mean we make this based on priority of live integrations
        if ($directPurchase && $directPurchase->status == GeneralStatusEnum::getStatusActive() && $directPurchase->directPurchasePriorities) {
            foreach ($directPurchase->directPurchasePriorities as $directPurchasePriority) {
                // get first vendor available for this product with id in this vendor integration
                $vendorProduct = $this->vendorProductRepository->showByVendorIdAndProductId($directPurchase->product_id, $directPurchasePriority->vendor_id);
                if (!$vendorProduct || $vendorProduct->vendor->integration_id == null || $vendorProduct->vendor->status != VendorStatus::getTypeApproved()){
                    continue;
                }
                $vendorIntegrate = $this->integrationRepository->showById($vendorProduct->vendor->integration_id);
                $vendorIntegrate->name = MintrouteIntegrationType::resolve($vendorIntegrate->name, OrderProductType::getTypeSerial());
                // Initiate Integration Factory
                $service = IntegrationServiceFactory::create($vendorIntegrate);
                if (! $service){
                    continue;
                }
                // Check Vendor Balance
                $result = $service->checkBalance();
                if (!$result || $result['balance'] <= 0){
                    continue;
                }
                // Check product stock
                $available = $service->checkStock($vendorProduct->vendor_product_id, $quantity);
                if (!$available){
                    continue;
                }
                $data['success'] = true;
                $data['is_stock'] = 0;
                break;
            }
            // after end of all priorities check if stock is available
            if (!$data['success'] && $product->quantity >= $quantity &&
                $product->productSerials()->where('status', ProductSerialType::getTypeFree())->count() >= $quantity
            ){
                $data['success'] = true;
                $data['is_stock'] = 1;
            }
            return $data;
        }
        elseif ($product->quantity >= $quantity &&
            $product->productSerials()->where('status', ProductSerialType::getTypeFree())->count() >= $quantity
        ){
            $data['success'] = true;
            $data['is_stock'] = 1;
            return $data;
        }
        else{
            return $data;
        }

    }

    private function decreaseBalance(Authenticatable $distributorPosTerminal, Order $order): void
    {

        // Get the current balance of pos
        $balanceBefore = $distributorPosTerminal->balance;
        // get balance after decreasing
        $balanceAfter = $balanceBefore - $order->total;

        // Update the distributorPosTerminal's balance
        $distributorPosTerminal->balance = $balanceAfter;
        $distributorPosTerminal->save();
        // Create a balance Transaction
        $transactionData = [
            'transaction_id' => Str::uuid(),
            'transaction_code' => UniqueCodeGeneratorHelper::generateDigits(12, PosTerminalTransaction::class, "transaction_code"),
            'status' => TransactionStatusEnum::SUCCESS,
            'transaction_date' => now(),
            'track_id' => UniqueCodeGeneratorHelper::generateTrackingID(),
            'distributor_id' => $distributorPosTerminal->distributor_id,
            'pos_terminal_id' => $distributorPosTerminal->pos_terminal_id,
            'balance_before' => (float) $balanceBefore,
            'balance_after' => $balanceAfter,
            'amount' => $order->total,
            'order_id' => $order->id,
        ];

        // store balance Transaction
        $this->posTerminalTransactionRepository->create($transactionData);
    }

    private function increaseCommission(Authenticatable $distributorPosTerminal, Order $order, float $orderProfit): void
    {
        // decrease mada commission from profit
        $commission = $orderProfit - 0.1;       // replace 0.1 mada commission
        if ($commission <= 0){
            Log::error('commission out');
            return;
        }
        $balanceBefore = $distributorPosTerminal->commission;
        // Update the distributorPosTerminal's commission
        $distributorPosTerminal->commission += $commission;
        $distributorPosTerminal->save();


        // create transactiosn
        $transactionData = [
            'transaction_id' => Str::uuid(),
            'transaction_code' => UniqueCodeGeneratorHelper::generateDigits(12, PosTerminalTransaction::class, "transaction_code"),
            'status' => TransactionStatusEnum::SUCCESS,
            'transaction_date' => now(),
            'track_id' => UniqueCodeGeneratorHelper::generateTrackingID(),
            'distributor_id' => $distributorPosTerminal->distributor_id,
            'pos_terminal_id' => $distributorPosTerminal->pos_terminal_id,
            'balance_before' => (float) $distributorPosTerminal->balance,
            'balance_after' => $balanceBefore - $order->total,
            'amount' => $order->total,
            'order_id' => $order->id,
        ];

        // store balance Transaction
        $transaction = $this->posTerminalTransactionRepository->create($transactionData);

        // store commission log
        $balanceLogData = [
            'transaction_id' => $transaction->id,
            'distributor_id' => $distributorPosTerminal->distributor_id,
            'pos_terminal_id' => $distributorPosTerminal->pos_terminal_id,
            'amount' => $commission,
            'balance_type' => BalanceTypeStatusEnum::COMMISSION->value,
            'balance_before' => $balanceBefore,
            'balance_after' => $distributorPosTerminal->commission,
            'transaction_type' => TransactionTypeEnum::CREDIT->value
        ];
        $this->balanceLogRepository->create($balanceLogData);
    }

    private function increasePoint(Authenticatable $distributorPosTerminal, Order $order, float $orderProfit): void
    {
        // create transactiosn
        $transactionData = [
            'transaction_id' => Str::uuid(),
            'transaction_code' => UniqueCodeGeneratorHelper::generateDigits(12, PosTerminalTransaction::class, "transaction_code"),
            'status' => TransactionStatusEnum::SUCCESS,
            'transaction_date' => now(),
            'track_id' => UniqueCodeGeneratorHelper::generateTrackingID(),
            'distributor_id' => $distributorPosTerminal->distributor_id,
            'pos_terminal_id' => $distributorPosTerminal->pos_terminal_id,
            'balance_before' => (float) $distributorPosTerminal->balance,
            'balance_after' => $distributorPosTerminal->balance - $order->total,
            'amount' => $order->total,
            'order_id' => $order->id,
        ];

        // store balance Transaction
        $transaction = $this->posTerminalTransactionRepository->create($transactionData);

        $amount_per_points_redeem = SettingsHelper::getPointsCommissionSetting('amount_per_points_redeem');
        $points_per_amount_redeem = SettingsHelper::getPointsCommissionSetting('points_per_amount_redeem');
        $apply_on_recharging_by_mada = SettingsHelper::getPointsCommissionSetting('apply_on_recharging_by_mada');
        $points = 0;
        // set balance log depend on setting
        if ($apply_on_recharging_by_mada) {

            if ($order->total >= $points_per_amount_redeem) {
                // Calculate how many times the amount meets the redeem rule
                $times = floor($order->total/ $points_per_amount_redeem);

                // Total points based on the setting
                $points = $times * $amount_per_points_redeem;

                // Log points in the balance log
                $this->balanceLogRepository->create([
                    'transaction_id' => $transaction->id,
                    'distributor_id' => $distributorPosTerminal->distributor_id,
                    'pos_terminal_id' => $distributorPosTerminal->pos_terminal_id,
                    'amount' => $points, // Log the calculated points instead of amount
                    'balance_type' => 'points',
                    'balance_before' => $distributorPosTerminal->points,
                    'balance_after' => $distributorPosTerminal->points + $points,
                    'transaction_type' => TransactionTypeEnum::CREDIT->value
                ]);
                $distributorPosTerminal->points += $points;
                $distributorPosTerminal->save();
            }
        }
    }

    private function executeDependencies($order): void
    {
        // prepare data for messaging to admin
        $adminRequestData = [
            'to'  => User::class,
            'notification_permission_name' => 'notifications-new-customers',
            'notificationClass' => CustomNotification::class,
            'notification_translations' => 'admin_order_created',
            'type' => 'order',
            'type_id' => $order->id,
            //////////////
            'emailClass' => AdminOrderCreatedEmail::class,
            'emailData' => ['order' => $order],
        ];
        // send emails
        // EmailMessageJob::dispatch($adminRequestData);             Stopped until now
        // send notifications
        // NotificationMessageJob::dispatch($adminRequestData);

    }


    public function index(array $filter): mixed
    {
        // TODO: Implement index() method.
    }

    public function show($id): mixed
    {
        try {
            $order_details = $this->orderRepository->show($id);
            if (! $order_details)
                return $this->notFoundResponse();

            $order = $this->orderRepository->formatOrderProductsWithHashedAll($order_details);

            return $this->showResponse(new OrderDetailsResource($order));

        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function update($id, BaseDTO $data): mixed
    {
        // TODO: Implement update() method.
    }

    public function delete($id): mixed
    {
        // TODO: Implement delete() method.
    }

    public function getTrashed()
    {
        // TODO: Implement getTrashed() method.
    }

    public function restore($id)
    {
        // TODO: Implement restore() method.
    }

    public function bulkDelete(array $ids = [])
    {
        // TODO: Implement bulkDelete() method.
    }

    public function getSalesRepPosOrders(AllOrdersDto $data, $distributor_pos_terminal_id)
    {
        try {
            DB::beginTransaction();

            $data = $data->toArray();

            $orders = $this->orderProductSerialRepository->getDailySalesReport($data);

            DB::commit();

            return $this->ApiSuccessResponse(StoredOrderProductSerialResource::collection($orders)->response()->getData() );

        } catch(Exception $e){
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), trans('admin.general_error'));
        }
    }
}
