<?php

namespace App\Services\Brand;

use App\DTO\BaseDTO;
use App\Http\Resources\Pos\BrandResources\BrandWithCategoryProductsResource;
use App\Interfaces\ServicesInterfaces\Brand\BrandServiceInterface;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Category\CategoryRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Pos\BrandResources\BrandResource;

class BrandService extends BaseService implements BrandServiceInterface
{

    public function __construct(private readonly BrandRepository $brandRepository,
                                private readonly CategoryRepository $categoryRepository)
    {
    }

    public function index(array $filter): mixed
    {
        try {
            $brands = $this->brandRepository->getAllBrands($filter);
            if (count($brands) > 0) {
                return $this->listResponse(BrandResource::collection($brands)->resource);
            } else {
                return $this->listResponse([]);
            }
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function show($id): mixed
    {
        try {
            $brand = $this->brandRepository->show($id);
            if (isset($brand))
                return $this->showResponse(new BrandResource($brand));

            return $this->notFoundResponse();

        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function show_details($request,$id): mixed
    {
        try {
            $brand = $this->brandRepository->show_details($id);
            if (isset($brand))
                return $this->showResponse(new BrandWithCategoryProductsResource($brand));

            return $this->notFoundResponse();

        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function store(BaseDTO $data): mixed
    {
        try {
            DB::beginTransaction();
            $brand = $this->brandRepository->store($data->getRequestData());
            if (! $brand)
                return $this->ApiErrorResponse();

            DB::commit();
            return $this->showResponse($brand);
        } catch (Exception $e) {
            DB::rollBack();
            //return $this->ApiErrorResponse(null, $e);
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function update($id, BaseDTO $data): mixed
    {
        try {
            DB::beginTransaction();
            $brand = $this->brandRepository->updateBrand($data->getRequestData(), $id);
            if (! $brand)
                return $this->ApiErrorResponse();

            DB::commit();
            return $this->showResponse($brand);
        } catch (Exception $e) {
            DB::rollBack();
            //return $this->ApiErrorResponse(null, $e);
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function delete($id): mixed
    {
        try {
            $brand = $this->brandRepository->show($id);
            if (!$brand)
                return $this->notFoundResponse();
            $deleted = $this->brandRepository->destroy($id);
            if (!$deleted)
                return $this->ApiErrorResponse(null, __('admin.related_items'));
            // $this->remove_file('brands', $brand->name);

            return $this->ApiSuccessResponse([], 'Brand deleted successfully.');
        }
        catch (QueryException $e) {
            if ($e->getCode() === '23000')
                return $this->ApiErrorResponse(null, __('admin.related_items'));
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
        catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
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
        try {
            $this->brandRepository->destroy_selected($ids);

            return $this->ApiSuccessResponse([], 'Success');
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function getCategoryBrands($request, $category_id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = [];
            $data['category'] = $this->categoryRepository->getCategoryDetails($category_id);
            $data['brands'] = BrandResource::collection($this->brandRepository->getBrandsByCategoryId($request, $data['category']))->resource;
            if (! $data['brands'])
                return $this->ApiErrorResponse();

            DB::commit();
            return $this->ApiSuccessResponse($data);
        } catch (Exception $e) {
            DB::rollBack();
            //return $this->ApiErrorResponse(null, $e);
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function changeStatus($request, $id): JsonResponse
    {
        try {
            $brand = $this->brandRepository->changeStatus($request, $id);
            if (! $brand)
                return $this->notFoundResponse();

            return $this->ApiSuccessResponse(null, "Status Changed Successfully");
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }
}
