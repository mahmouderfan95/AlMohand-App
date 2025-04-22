<?php
namespace App\Repositories\Seller;
use App\Enums\GeneralStatusEnum;
use App\Helpers\FileUpload;
use App\Http\Resources\Seller\AuthResource;
use App\Models\Seller\SellerAttachment;
use App\Traits\ApiResponseAble;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthRepository{
    use FileUpload,ApiResponseAble;
    public function register($request)
    {
        try{
            $createSeller = $this->createSeller($request);
            // Generate a JWT token for the user
            $token = JWTAuth::fromUser($createSeller);
            return $this->ApiSuccessResponseAndToken(AuthResource::make($createSeller),'success message',$token);
        }catch(\Exception $ex){
            return $this->ApiErrorResponse($ex->getMessage(),__('admin.general_error'));
        }
    }
    public function createSeller($data)
    {
        return $this->getModel()::create([
            'name' => $data['name'],
            'owner_name' => $data['owner_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'status' => GeneralStatusEnum::ACTIVE,
            'approval_status' => 'pending'
        ]);
    }
    public function updateSeller($seller,$data)
    {
        $seller->name = $data['name'];
        $seller->owner_name = $data['owner_name'];
        // Handle logo upload
        if (isset($data['logo'])) {
            $imagePath = $this->save_file($data['logo'],'sellers');
            $seller->logo = $imagePath;  // Store the file path in the database
        }
        $seller->save();

        #Update seller address
        $this->updateSellerAddress($seller, $data);
        if($seller->approval_status == 'pending')
            #update seller attachments
            $this->updateSellerAttachments($seller,$data);
    }
    private function updateSellerAddress($seller,$data)
    {
        $addressData = [
            'country_id' => $data['country_id'] ?? null,
            'city_id'    => $data['city_id'] ?? null,
            'region_id'  => $data['region_id'] ?? null,
            'street'    => $data['street'] ?? null,
        ];
        if($seller->sellerAddress)
            $seller->sellerAddress->update($addressData);
        $seller->sellerAddress()->create($addressData);
    }
    private function updateSellerAttachments($seller,$data)
    {
        $attachmentTypes = ['commercial_register','identity','tax_card','more'];
        foreach ($attachmentTypes as $type) {
            $attachmentData = $this->uploadAttachments($type,'uploads/attachments');
            if ($attachmentData) {
                // Update or create attachment
                SellerAttachment::updateOrCreate(
                    ['seller_id' => $seller->id, 'type' => $type],
                    $attachmentData
                );
            }
        }
    }
    private function getModel()
    {
        return '\App\Models\Seller\Seller';
    }
    private function getModelById($id)
    {
        return $this->getModel()::find($id);
    }
}
