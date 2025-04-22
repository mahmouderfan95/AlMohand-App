<?php
namespace App\Http\Controllers\Pos\Order;

use App\DTO\Pos\Order\AllOrdersDto;
use App\DTO\Pos\Order\CallbackOrderDto;
use App\DTO\Pos\Order\StoreOrderDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\Order\AllOrderRequest;
use App\Http\Requests\Pos\Order\CallbackOrderRequest;
use App\Http\Requests\Pos\Order\StoreOrderRequest;
use App\Interfaces\ServicesInterfaces\Order\OrderServiceInterface;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class OrderController extends Controller
{

    public function __construct(private OrderServiceInterface $orderServiceInterface)
    {
    }

    /**
     * @OA\Post(
     *     path="/orders",
     *     operationId="showOrders",
     *     tags={"Orders"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="date_from", type="string", format="date", example="2025-01-01", description="Start date for filtering (YYYY-MM-DD)", nullable=true),
     *              @OA\Property(property="date_to", type="string", format="date", example="2025-01-31", description="End date for filtering (YYYY-MM-DD)", nullable=true),
     *              @OA\Property(property="search", type="string", example="product123", description="Search keyword for products", nullable=true),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Order created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Order created successfully."),
     *              @OA\Property(property="data", type="object")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Validation error.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Unauthorized access.")
     *          )
     *      )
     * )
     */
    public function index(AllOrderRequest $request)
    {
        return $this->orderServiceInterface->getPosOrders(new AllOrdersDto($request));
    }

    /**
     * @OA\Post(
     *     path="/orders/store",
     *     operationId="storeOrder",
     *     tags={"Orders"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"product_id", "quantity", "payment_method"},
     *              @OA\Property(property="product_id", type="integer", example=1, description="Product id"),
     *              @OA\Property(property="quantity", type="integer", example=1, description="Product quantity"),
     *              @OA\Property(property="payment_method", type="string", example="balance", description="balance or mada"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Order created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Order created successfully."),
     *              @OA\Property(property="data", type="object")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Validation error.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Unauthorized access.")
     *          )
     *      )
     * )
     */
    public function store(StoreOrderRequest $request)
    {
        return $this->orderServiceInterface->store(new StoreOrderDto($request));
    }

    /**
     * @OA\Post(
     *     path="/orders/callback",
     *     operationId="callbackOrder",
     *     tags={"Orders"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"order_id", "mada_response"},
     *              @OA\Property(property="order_id", type="integer", example=1, description="order id"),
     *              @OA\Property(property="mada_response", type="json", example="{}", description="mada response"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Order created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Order created successfully."),
     *              @OA\Property(property="data", type="object")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Validation error.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=false),
     *              @OA\Property(property="message", type="string", example="Unauthorized access.")
     *          )
     *      )
     * )
     */
    public function callback(CallbackOrderRequest $request)
    {
        return $this->orderServiceInterface->callback(new CallbackOrderDto($request));
    }
}
