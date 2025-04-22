<?php

namespace App\Services\PosTerminal;

use App\DTO\Admin\Merchant\CreateMerchantGroupDto;
use App\DTO\Admin\PosTerminal\CreatePosTerminalDto;
use App\DTO\BaseDTO;
use App\Enums\Distributor\DistributorConditionPrefixEnum;
use App\Enums\Distributor\DistributorConditionTypeEnum;
use App\Helpers\PosNameGenerator;
use App\Http\Resources\Admin\Distributor\DistributorGroupResource;
use App\Http\Resources\Admin\PosTerminal\ActivePosTerminalResource;
use App\Http\Resources\Admin\PosTerminal\PosTerminalResource;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorGroupServiceInterface;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalServiceInterface;
use App\Models\POSTerminal\PosTerminal;
use App\Repositories\Distributor\DistributorGroupConditionRepository;
use App\Repositories\Distributor\DistributorGroupRepository;
use App\Repositories\Distributor\DistributorPosTerminalRepository;
use App\Repositories\PosTerminal\PosTerminalRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class PosTerminalService extends BaseService implements PosTerminalServiceInterface
{

    public function __construct(private readonly PosTerminalRepository $posTerminalRepository,
                                private readonly DistributorPosTerminalRepository $distributorPosTerminalRepository
    )
    {
    }

    public function index(array $filter): mixed
    {
        $list = $this->posTerminalRepository->index(request('keyword'));
        if (request('isPagination') == "true") {
            return $this->ApiSuccessResponse(PosTerminalResource::collection($list->paginate())->response()->getData());
        }
        return $this->ApiSuccessResponse(PosTerminalResource::collection($list->get()));
    }

    public function show($id): mixed
    {
        $pos_terminal = $this->posTerminalRepository->find($id);

        if (!$pos_terminal) {
            return $this->ApiErrorResponse("invalid POS id");
        }

        return $this->ApiSuccessResponse(new PosTerminalResource($pos_terminal));
    }

    public function store(CreatePosTerminalDto|BaseDTO $data): mixed
    {
        try {
            $data = $data->toArray();

            $data['created_by'] = $this->getCurrentAdmin()->id;
            DB::beginTransaction();
            $pos_terminal = $this->posTerminalRepository->create($data);
            DB::commit();
            $pos_terminal = $this->posTerminalRepository->findWhere(['id' => $pos_terminal->id])->first();
            return $this->ApiSuccessResponse(new PosTerminalResource($pos_terminal));
        }catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function update($id, CreatePosTerminalDto|BaseDTO $data): mixed
    {
        try {
            DB::beginTransaction();

            $data = $data->toArray();
            $pos_terminal = $this->posTerminalRepository->findWhere(['id' => $id])->first();

            if (!$pos_terminal) {
                return $this->ApiErrorResponse("invalid terminal id");
            }

            $data['name'] = $pos_terminal->name; // To prevent updating the POS name
            $pos_terminal = $this->posTerminalRepository->update($data, $pos_terminal->id);

            DB::commit();

            return $this->ApiSuccessResponse(new PosTerminalResource($pos_terminal));

        }catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function delete($id): mixed
    {
        $pos_terminal = $this->posTerminalRepository->find($id);

        if (!$pos_terminal) {
            return $this->ApiErrorResponse("invalid POS");
        }

        $pos_terminal->delete();

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

    public function generateName(): \Illuminate\Http\JsonResponse
    {
        return $this->ApiSuccessResponse(['name' => PosNameGenerator::generate()]);
    }

    public function getAllActiveTerminals($keyword)
    {
        $pos_terminals = $this->distributorPosTerminalRepository->getAll($keyword);
        return $this->ApiSuccessResponse(ActivePosTerminalResource::collection($pos_terminals)->response()->getData());
    }
}
