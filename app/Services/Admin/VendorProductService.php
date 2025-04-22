<?php

namespace App\Services\Admin;

use App\Repositories\Vendor\VendorProductRepository;
use App\Repositories\DirectPurchase\DirectPurchasePriorityRepository;
use App\Repositories\DirectPurchase\DirectPurchaseRepository;
use App\Services\BaseService;
use App\Traits\ApiResponseAble;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class VendorProductService
{
    use ApiResponseAble;


    public function __construct(
        private VendorProductRepository                 $vendorProductRepository,
        private DirectPurchaseRepository                $directPurchaseRepository,
        private DirectPurchasePriorityRepository        $directPurchasePriorityRepository,
    )
    {}


    public function index($request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $vendorProducts = $this->vendorProductRepository->index($request);
            if (! $vendorProducts)
                return $this->ApiErrorResponse();

            DB::commit();
            return $this->showResponse($vendorProducts);
        } catch (Exception $e) {
            DB::rollBack();
            //return $this->ApiErrorResponse(null, $e);
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function storeProduct($request): JsonResponse
    {
        try {
            DB::beginTransaction();
            // store new vendor product
            $vendorProduct = $this->vendorProductRepository->storeProduct($request);
            if (! $vendorProduct)
                return $this->ApiErrorResponse(null, 'Exist before');

            DB::commit();
            return $this->ApiSuccessResponse(null, 'Created Successfully...!');
        } catch (Exception $e) {
            DB::rollBack();
            if ($e instanceof QueryException)
                return $this->ApiErrorResponse(null, 'Exist before');
            else
                return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function updateProduct($request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $vendorProductUpdated = $this->vendorProductRepository->updateProduct($request, $id);
            if (! $vendorProductUpdated)
                return $this->ApiErrorResponse(null, __('admin.data_exist_before'));

            DB::commit();
            return $this->ApiSuccessResponse(null, "Updated Successfully");
        } catch (Exception $e) {
            DB::rollBack();
            if ($e instanceof QueryException)
                return $this->ApiErrorResponse(null, 'Exist before');
            else
                return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function deleteProduct($id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $vendorProduct = $this->vendorProductRepository->show($id);
            if (! $vendorProduct){
                return $this->ApiErrorResponse(null, 'This id invalid.');
            }

            $directPurchase = $this->directPurchaseRepository->showByProductId($vendorProduct->product_id);
            if (! $directPurchase){
                return $this->ApiErrorResponse(null, __('admin.general_error'));
            }

            $directPurchasePriority = $this->directPurchasePriorityRepository->showPriorityByVendor($directPurchase->id, $vendorProduct->vendor_id);
            if(! $directPurchasePriority){
                return $this->ApiErrorResponse(null, __('admin.general_error'));
            }

            $this->directPurchasePriorityRepository->deleteVendor($directPurchase->id,$directPurchasePriority);

            $vendorProduct->delete();

            DB::commit();
            return $this->ApiSuccessResponse(null, "Deleted Successfully");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }



}
