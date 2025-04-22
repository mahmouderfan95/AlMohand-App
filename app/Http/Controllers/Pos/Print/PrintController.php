<?php
namespace App\Http\Controllers\Pos\Print;

use App\DTO\Pos\Print\DecreaseCountDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\Print\DecreaseCountRequest;
use App\Interfaces\ServicesInterfaces\Print\PrintServiceInterface;

class PrintController extends Controller
{

    public function __construct(private PrintServiceInterface $printServiceInterface)
    {
    }

    /**
     * @OA\Post(
     *     path="/print/decrease-count",
     *     operationId="printDecreaseCount",
     *     tags={"Orders"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              type="object",
     *              required={"order_product_serial_ids"},
     *              @OA\Property(property="order_product_serial_ids", type="array", @OA\Items(type="integer"), example={1,2}, description="order product serial id")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Count decreased successfully.",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="boolean", example=true),
     *              @OA\Property(property="message", type="string", example="Count decreased successfully."),
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
    public function decreaseCount(DecreaseCountRequest $request)
    {
        return $this->printServiceInterface->decreaseCount(new DecreaseCountDto($request));
    }

}
