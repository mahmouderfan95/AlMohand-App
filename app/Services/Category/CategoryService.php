<?php

namespace App\Services\Category;

use App\DTO\BaseDTO;
use App\Http\Resources\Admin\CategoryResource;
use App\Http\Resources\Pos\CategoryResources\CategoryResource as MerchantCategoryResource;
use App\Interfaces\ServicesInterfaces\Category\CategoryServiceInterface;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Language\LanguageRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryService extends BaseService implements CategoryServiceInterface
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var LanguageRepository
     */
    private $languageRepository;

    /**
     * @param CategoryRepository $categoryRepository
     * @param LanguageRepository $languageRepository
     */
    public function __construct(CategoryRepository $categoryRepository, LanguageRepository $languageRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->languageRepository = $languageRepository;
    }

    /**
     *
     * All  Categories.
     *
     */
    public function getAllCategoriesForm($request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $categories = $this->categoryRepository->getAllCategoriesForm($request);
            if (count($categories) > 0)
                return $this->listResponse(CategoryResource::collection($categories)->resource);
            else
                return $this->listResponse([]);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $category = $this->categoryRepository->show($id);
            if (isset($category))
                return $this->showResponse(new CategoryResource($category));
            else
                return $this->notFoundResponse();
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * Update Category.
     *
     * @param integer $category_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, int $category_id): \Illuminate\Http\JsonResponse
    {
        $data_request = $request->all();

        try {
            $category = $this->categoryRepository->update_status($data_request, $category_id);
            if ($category)
                return $this->showResponse($category);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    /**
     * restore Category.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($id): \Illuminate\Http\JsonResponse
    {
        try {
            $category = $this->categoryRepository->restore($id);
            if (!$category)
                return $this->ApiErrorResponse(null, __('admin.unable_restore'));
            return $this->showResponse($category);

        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    /**
     * @param $filter
     * @return mixed
     */
    public function index($filter): mixed
    {
        try {
            $categories = $this->categoryRepository->getAllCategories($filter);
            if (count($categories) > 0)
                return $this->listResponse(CategoryResource::collection($categories)->resource);
            else
                return $this->listResponse([]);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * @param BaseDTO $data
     * @return mixed
     */
    public function store(BaseDTO $data): mixed
    {
        $data_request = $data->getRequestData();
        if (isset($request->image))
            $data_request['image'] = $this->save_file($request->image, 'categories');

        if (isset($request->brand_ids))
            $data_request['brand_ids'] = explode(',',$data_request['brand_ids']);

        try {
            $category = $this->categoryRepository->store($data_request);
            if ($category)
                return $this->showResponse($category);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * @param $id
     * @param BaseDTO $data
     * @return JsonResponse
     */
    public function update($id, BaseDTO $data): JsonResponse
    {
        $data_request = $data->getRequestData();
        if (isset($request->image))
            $data_request['image'] = $this->save_file($request->image, 'categories');

        if (isset($request->brand_ids))
            $data_request['brand_ids'] = explode(',',$data_request['brand_ids']);
        try {
            $category = $this->categoryRepository->update($data_request, $id);
            if ($category)
                return $this->showResponse($category);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id): mixed
    {
        try {
            $category = $this->categoryRepository->show($id);
            if (!$category)
                return $this->notFoundResponse();
            $deleted = $this->categoryRepository->destroy($id);
            if (!$deleted)
                return $this->ApiErrorResponse(null, __('admin.related_items'));
            return $this->ApiSuccessResponse([], 'Success');
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * @return JsonResponse
     */
    public function getTrashed()
    {
        try {
            $trashes = $this->categoryRepository->trash();
            if (count($trashes) > 0)
                return $this->listResponse($trashes);
            else
                return $this->listResponse([]);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * @param array $ids
     * @return JsonResponse
     */
    public function bulkDelete(array $ids = []): JsonResponse
    {
        try {
            $this->categoryRepository->destroy_selected($ids);
            return $this->ApiSuccessResponse([], 'Success');
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    /**
     * @return JsonResponse
     */
    public function getMainCategories(): JsonResponse
    {
        try {
            DB::beginTransaction();
            // get main categories
            $categories = $this->categoryRepository->getMainCategories();
            if (! $categories)
                return $this->ApiErrorResponse();

            DB::commit();
            return $this->showResponse(MerchantCategoryResource::collection($categories)->resource);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    /**
     * @param $request
     * @return JsonResponse
     */
    public function getSubCategories($request): JsonResponse
    {
        try {
            DB::beginTransaction();
            // get sub categories
            $subCategories = $this->categoryRepository->getSubCategories($request);
            if (! $subCategories)
                return $this->ApiErrorResponse();

            DB::commit();
            return $this->showResponse(MerchantCategoryResource::collection($subCategories)->resource);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }
}
