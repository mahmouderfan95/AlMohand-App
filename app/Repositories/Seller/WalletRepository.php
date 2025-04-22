<?php
namespace App\Repositories\Seller;

use App\Helpers\FileUpload;
use App\Http\Resources\Seller\RechargeBalanceResource;
use App\Models\Wallet;
use App\Traits\ApiResponseAble;
use Illuminate\Support\Facades\Log;

class WalletRepository
{
    use FileUpload,ApiResponseAble;
    public function balanceRecharge($request)
    {
        try{
            if($request['recharge_balance_type'] == 'cash')
            {
                $data = $this->addBalance($request);
                #add data in log
                Log::info('add balance in wallet',$data);
                return $this->ApiSuccessResponse(RechargeBalanceResource::make($data),'success message');
            }
            #visa integrate
            return $this->ApiSuccessResponse([],'visa integration');
        }catch(\Exception $e){
            return $this->ApiErrorResponse($e->getMessage(),trans('admin.general_error'));
        }
    }
    private function addBalance($data)
    {
        $balanceData = [
            'recharge_balance_type' => $data['recharge_balance_type'],
            'bank_name' => $data['bank_name'],
            'transferring_name' => $data['transferring_name'],
             'notes' => $data['notes'],
            'amount' => $data['amount'],
            'seller_id' => auth('sellerApi')->user()->id
        ];
        // Check if the 'receipt_image' is provided
        if (isset($data['receipt_image'])) {
            // Use the save_file function to store the image in the 'receipts' directory
            $imagePath = $this->save_file($data['receipt_image'], 'receipts');
            $balanceData['receipt_image'] = $imagePath;  // Store the file path in the database
        }

        return $this->getModel()::create($balanceData);
    }
    public function getBalanceList()
    {
        try{
            $data = $this->getModel()::query()->orderByDesc('id')->get();
            if($data->count() > 0)
                return $this->ApiSuccessResponse(RechargeBalanceResource::collection($data),'success message');
            return $this->ApiErrorResponse([],'data not found');
        }catch (\Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(),trans('admin.general_error'));
        }
    }
    private function getModelById($id)
    {
        return $this->getModel()::find($id);
    }
    private function getModel()
    {
        return Wallet::class;
    }
}
