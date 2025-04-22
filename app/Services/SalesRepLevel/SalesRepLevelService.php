<?php

namespace App\Services\SalesRepLevel;

use App\DTO\BaseDTO;
use App\Http\Resources\Admin\SalesRepLevelResource;
use App\Interfaces\ServicesInterfaces\SalesRepLevel\SalesRepLevelServiceInterface;
use App\Repositories\SalesRepLevel\SalesRepLevelRepository;
use App\Repositories\Language\LanguageRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesRepLevelService extends BaseService implements SalesRepLevelServiceInterface
{
    /**
     * @var SalesRepLevelRepository
     */
    private $salesRepLevelRepository;
    /**
     * @var LanguageRepository
     */

    /**
     * @param SalesRepLevelRepository $salesRepLevelRepository
     */
    public function __construct(SalesRepLevelRepository $salesRepLevelRepository)
    {
        $this->salesRepLevelRepository = $salesRepLevelRepository;
    }

    /**
     *
     * All  SalesRepLevels.
     *
     */
    public function index($request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $salesRepLevels = $this->salesRepLevelRepository->getAllSalesRepLevelsForm($request);
            if (count($salesRepLevels) > 0)
                return $this->listResponse(SalesRepLevelResource::collection($salesRepLevels)->resource);
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
    public function store(BaseDto $data): mixed
    {
        try {
            $salesRepLevel = $this->salesRepLevelRepository->store($data->getRequestData());
            if ($salesRepLevel)
                return $this->showResponse(new SalesRepLevelResource($salesRepLevel->load('parent')));
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    /**
     * @param $id
     * @param BaseDTO $data
     * @return JsonResponse
     */
    public function update($id, BaseDto $data): JsonResponse
    {
        try {
            $salesRepLevel = $this->salesRepLevelRepository->update($data->getRequestData(), $id);
            if ($salesRepLevel)
                return $this->showResponse($salesRepLevel);
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
            $salesRepLevel = $this->salesRepLevelRepository->show($id);
            if (isset($salesRepLevel))
                return $this->showResponse(new SalesRepLevelResource($salesRepLevel));
            else
                return $this->notFoundResponse();
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * Update SalesRepLevel.
     *
     * @param integer $salesRepLevel_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_status(Request $request, int $salesRepLevel_id): \Illuminate\Http\JsonResponse
    {
        $status = $request->status;

        try {
            $salesRepLevel = $this->salesRepLevelRepository->update_status($status, $salesRepLevel_id);
            if ($salesRepLevel)
                return $this->showResponse(new SalesRepLevelResource($this->salesRepLevelRepository->show($salesRepLevel_id)));
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
            $salesRepLevel = $this->salesRepLevelRepository->show($id);
            if (!$salesRepLevel)
                return $this->notFoundResponse();
            $deleted = $this->salesRepLevelRepository->destroy($id);
            if (!$deleted)
                return $this->ApiErrorResponse(null, __('admin.related_items'));
            return $this->ApiSuccessResponse([], 'Success');
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
            $this->salesRepLevelRepository->destroy_selected($ids);
            return $this->ApiSuccessResponse([], 'Success');
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    public function trash()
    {
        // TODO: Implement trash() method.

    }

    public function getTrashed()
    {
        // TODO: Implement getTrashed() method.
    }

    public function restore($id)
    {
        // TODO: Implement restore() method.
    }
}
