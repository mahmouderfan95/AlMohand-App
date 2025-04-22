<?php

namespace App\Services\Distributor;

use App\DTO\Admin\Merchant\CreateMerchantGroupDto;
use App\DTO\BaseDTO;
use App\Enums\Distributor\DistributorConditionPrefixEnum;
use App\Enums\Distributor\DistributorConditionTypeEnum;
use App\Http\Resources\Admin\Distributor\DistributorGroupResource;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorGroupServiceInterface;
use App\Repositories\Distributor\DistributorGroupConditionRepository;
use App\Repositories\Distributor\DistributorGroupRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class DistributorGroupService extends BaseService implements DistributorGroupServiceInterface
{

    public function __construct(private readonly DistributorGroupRepository $distributorGroupRepository)
    {

    }

    public function index(array $filter): mixed
    {
        return $this->ApiSuccessResponse(DistributorGroupResource::collection($this->distributorGroupRepository->with(['conditions', 'translations'])->paginate()));
    }

    public function show($id): mixed
    {
        $distributor_group = $this->distributorGroupRepository->find($id);

        if (!$distributor_group) {
            return $this->ApiErrorResponse("invalid group id");
        }

        return $this->ApiSuccessResponse(new DistributorGroupResource($distributor_group));
    }

    public function store(CreateMerchantGroupDto|BaseDTO $data): mixed
    {
        try {
            $data = $data->toArray();
            DB::beginTransaction();
            if ($group = $this->distributorGroupRepository->saveWithTranslations($data)) {
                foreach ($data['conditions'] as $condition) {
                    $group->conditions()->create($condition);
                }
            }
            DB::commit();
            return $this->ApiSuccessResponse(new DistributorGroupResource($group));
        }catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function update($id, CreateMerchantGroupDto|BaseDTO $data): mixed
    {
        try {
            DB::beginTransaction();

            $data = $data->toArray();
            $distributor_group = $this->distributorGroupRepository->find($id);

            if (!$distributor_group) {
                return $this->ApiErrorResponse("invalid group id");
            }

            $this->distributorGroupRepository->saveWithTranslations($data, $distributor_group);

            if (!empty($data['conditions'])) {
                $distributor_group->conditions()->delete();
                foreach ($data['conditions'] as $condition) {
                    $distributor_group->conditions()->create($condition);
                }
            }

            DB::commit();

            return $this->ApiSuccessResponse(new DistributorGroupResource($distributor_group));

        }catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function delete($id): mixed
    {
        $distributor_group = $this->distributorGroupRepository->find($id);

        if (!$distributor_group) {
            return $this->ApiErrorResponse("invalid group id");
        }

        $distributor_group->delete();

        return $this->ApiSuccessResponse("", "Deleted Successfully");
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

    public function fill()
    {
        $data = [];
        $data['conditions'] = DistributorConditionTypeEnum::getTranslatedList();
        $data['prefix'] = DistributorConditionPrefixEnum::getTranslatedList();
        return $this->ApiSuccessResponse($data);
    }
}
