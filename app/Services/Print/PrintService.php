<?php

namespace App\Services\Print;

use App\DTO\BaseDTO;

use App\DTO\Pos\Print\DecreaseCountDto;
use App\Http\Resources\Pos\Order\StoredOrderProductSerialResource;
use App\Interfaces\ServicesInterfaces\Print\PrintServiceInterface;
use App\Repositories\Order\OrderProductSerialRepository;
use App\Services\BaseService;
use App\Traits\ApiResponseAble;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PrintService extends BaseService implements PrintServiceInterface
{
    use ApiResponseAble;


    public function __construct(
        private OrderProductSerialRepository        $orderProductSerialRepository,
    )
    {}


    public function decreaseCount(DecreaseCountDto $data): mixed
    {
        $data = $data->toArray();
        $authPos = Auth::guard('posApi')->user();
        DB::beginTransaction();
        try {
            $orderProductSerials = $this->orderProductSerialRepository->showForAuthByIds($data['order_product_serial_ids'], $authPos);
            if (!$orderProductSerials || $orderProductSerials->isEmpty()) {
                return $this->ApiErrorResponse(null, 'order product serial id not found');
            }
            foreach ($orderProductSerials as $orderProductSerial) {
                $orderProductSerial->update([
                    'print_count' => $orderProductSerial->print_count > 0
                        ? $orderProductSerial->print_count - 1
                        : 0,
                ]);
            }
            // if all serials for order wanted
            if ($data['all_serials']){
                $firstOrderProductSerial = $orderProductSerials->first();
                $orderProduct = $firstOrderProductSerial->orderProduct;
                $orderProductSerials = $orderProduct->orderProductSerials;
            }

            DB::commit();
            return $this->ApiSuccessResponse(StoredOrderProductSerialResource::collection($orderProductSerials), 'Count decreased successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), trans('admin.general_error'));
        }
    }
}
