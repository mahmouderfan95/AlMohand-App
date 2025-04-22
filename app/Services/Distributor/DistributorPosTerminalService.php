<?php

namespace App\Services\Distributor;

use App\DTO\Admin\Merchant\AddDistributorPosDTO;
use App\DTO\Admin\Merchant\GetDistributorPosDto;
use App\DTO\BaseDTO;
use App\Helpers\UniqueOtpGeneratorHelper;
use App\Http\Resources\Admin\Distributor\DistributorPosTerminalDetailsResource;
use App\Http\Resources\Admin\Distributor\DistributorPosTerminalListResource;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorPosTerminalServiceInterface;
use App\Models\Distributor\DistributorPosTerminal;
use App\Repositories\BalanceLog\BalanceLogRepository;
use App\Repositories\Distributor\DistributorPosTerminalRepository;
use App\Repositories\PosTerminal\BalanceRequestRepository;
use App\Repositories\PosTerminal\PosTerminalRepository;
use App\Repositories\PosTerminal\PosTerminalTransactionRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;

class DistributorPosTerminalService extends BaseService implements DistributorPosTerminalServiceInterface
{

    public function __construct(private DistributorPosTerminalRepository $distributorPosTerminalRepository,
                                private readonly PosTerminalRepository $posTerminalRepository,
                                private readonly PosTerminalTransactionRepository $posTerminalTransactionRepository,
                                private readonly BalanceRequestRepository $balanceRequestRepository,
                                private readonly BalanceLogRepository $balanceLogRepository,
    )
    {

    }

    public function index(array $filter): mixed
    {
        // TODO: Implement index() method.
    }

    public function show($id): mixed
    {
        $pos = $this->distributorPosTerminalRepository->findWithRelations($id, ['distributor', 'distributor.translations']);

        if (!$pos) {
            return $this->ApiErrorResponse("invalid pos id");
        }

        return $this->ApiSuccessResponse(new DistributorPosTerminalDetailsResource($pos));
    }

    public function store(BaseDTO|AddDistributorPosDTO $data): mixed
    {
        try {
            $input = $data->toArray();
            $input['distributor_id'] = $data->getMerchantId();
            $input['otp'] = UniqueOtpGeneratorHelper::generate(6, DistributorPosTerminal::class, 'otp');
            $input['password'] = "";

            $pos_terminal = $this->posTerminalRepository->find($data->getPosTerminalId());

            if (!$pos_terminal) {
                return $this->ApiErrorResponse("invalid pos terminal id");
            }

            if ($this->distributorPosTerminalRepository->checkIfPosTerminalAssigned($pos_terminal->id)) {
                return $this->ApiErrorResponse("pos terminal id assigned to another merchant");
            }

            // Check if pos assigned
            if ($this->distributorPosTerminalRepository)

            DB::beginTransaction();

            $input['serial_number'] = $pos_terminal->serial_number;
            $input['is_active'] = true;
            $distributor = $this->distributorPosTerminalRepository->create($input);

            DB::commit();

            return $this->ApiSuccessResponse(new DistributorPosTerminalListResource($distributor));
        }catch (Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function update($id, BaseDTO $data): mixed
    {
        // TODO: Implement update() method.
    }

    public function delete($id): mixed
    {
        $distributor_pos_terminal = $this->distributorPosTerminalRepository->find($id);

        if (!$distributor_pos_terminal) {
            return $this->ApiErrorResponse("invalid pos terminal id");
        }

        $this->distributorPosTerminalRepository->delete($id);

        return $this->ApiSuccessResponse();
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

    public function getPosTerminalsList(GetDistributorPosDto $dto)
    {
        $filters = [
            "branch_name" => $dto->getBranchName(),
            "receiver_phone" => $dto->getReceiverPhone(),
            "receiver_name" => $dto->getReceiverName(),
            "pos_terminal_id" => $dto->getPosTerminalId(),
        ];
        $pos_terminals = $this->distributorPosTerminalRepository->getMerchantPosTerminals($dto->getMerchantId(), $filters);
        return $this->ApiSuccessResponse(DistributorPosTerminalListResource::collection($pos_terminals)->response()->getData());
    }

    public function updateStatus($id, $is_active)
    {
        try {
            DB::beginTransaction();

            $distributor = $this->distributorPosTerminalRepository->find($id);

            if (!$distributor) {
                return $this->ApiErrorResponse("invalid pos terminal id");
            }

            $updated = $this->distributorPosTerminalRepository->update(['is_active' => $is_active], $id);

            DB::commit();

            return $this->ApiSuccessResponse(new DistributorPosTerminalDetailsResource($updated));
        }catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function factoryReset($pos_terminal_id, $otp)
    {
        try {

            DB::beginTransaction();

            $distributor_pos_terminal = $this->distributorPosTerminalRepository->findWhere(['pos_terminal_id' => $pos_terminal_id, 'otp' => $otp])->first();
            if (!$distributor_pos_terminal) {
                return $this->ApiErrorResponse('Invalid OTP');
            }

            // Update Distributor POS Terminal
            $this->distributorPosTerminalRepository->update(['reset_at' => now()], $distributor_pos_terminal->id);
            // Soft Delete Distributor POS Terminal
            $this->distributorPosTerminalRepository->deleteByPosTerminalID($pos_terminal_id);
            // Soft delete pos terminal transactions
            $this->posTerminalTransactionRepository->deleteWhere(['pos_terminal_id'=> $pos_terminal_id]);
            // Delete Balance Requests
            $this->balanceRequestRepository->deleteWhere(['pos_terminal_id'=> $pos_terminal_id]);
            // Delete Balance Logs
            $this->balanceLogRepository->deleteWhere(['pos_terminal_id'=> $pos_terminal_id]);

            DB::commit();

            return $this->ApiSuccessResponse('Pos Reset Finished Successfully');

        } catch (Exception $exception) {
            DB::rollBack();
            return $this->ApiErrorResponse("Error on Factory Reset", $exception->getMessage());
        }
    }
}
