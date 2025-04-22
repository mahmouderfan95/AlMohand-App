<?php

namespace App\Services\SalesRepUser;

use App\DTO\BaseDTO;
use App\Enums\BalanceRequest\BalanceRequestStatusEnum;
use App\Http\Resources\Admin\BalanceRequest\BalanceRequestResource;
use App\Http\Resources\Admin\Distributor\DistributorPosTerminalListResource;
use App\Http\Resources\Admin\SalesRepUserResource;
use App\Http\Resources\SalesRep\AuthResources\AuthResource;
use App\Interfaces\ServicesInterfaces\SalesRepUser\SalesRepUserServiceInterface;
use App\Repositories\Distributor\DistributorRepository;
use App\Repositories\PosTerminal\PosTerminalRepository;
use App\Repositories\SalesRepUser\SalesRepUserRepository;
use App\Repositories\Language\LanguageRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesRepUserService extends BaseService implements SalesRepUserServiceInterface
{
    /**
     * @var SalesRepUserRepository
     */
    private $salesRepUserRepository;
    private $distributorRepository;
    private $posTerminalRepository;
    /**
     * @var LanguageRepository
     */

    /**
     * @param SalesRepUserRepository $salesRepUserRepository
     * @param DistributorRepository $distributorRepository
     */
    public function __construct(SalesRepUserRepository $salesRepUserRepository, DistributorRepository $distributorRepository, PosTerminalRepository $posTerminalRepository)
    {
        $this->salesRepUserRepository = $salesRepUserRepository;
        $this->distributorRepository = $distributorRepository;
        $this->posTerminalRepository = $posTerminalRepository;
    }

    /**
     *
     * All  SalesRepUsers.
     *
     */
    public function index($request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $salesRepUsers = $this->salesRepUserRepository->getAllSalesRepUsersForm($request);
            if (count($salesRepUsers) > 0)
                return $this->listResponse(SalesRepUserResource::collection($salesRepUsers)->resource);
            else
                return $this->listResponse([]);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    /**
     *
     * All  transactions.
     *
     * @param Request $request
     */
    public function transactions(Request $request)
    {
        try {
            $transactions= $this->salesRepUserRepository->transactions($request);
            if (count($transactions) > 0)

                return $this->listResponse($transactions);
            else
                return $this->listResponse([]);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    /**
     *
     * All  transactions.
     *
     * @param Request $request
     */
    public function allBalanceRequests(Request $request)
    {
        try {
            $requests = $this->salesRepUserRepository->allBalanceRequests($request);
            return $this->ApiSuccessResponse(BalanceRequestResource::collection($requests)->resource);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    /**
     *
     * All  transactions.
     *
     * @param Request $request
     */
    public function pendingBalanceRequests(Request $request)
    {
        try {
            $requests = $this->salesRepUserRepository->pendingBalanceRequests($request);
            return $this->ApiSuccessResponse(BalanceRequestResource::collection($requests)->resource);
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     *
     * All  SalesRepUsers.
     *
     */
    public function permissions($request): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        try {
            $salesRepUsersPermissions = $this->salesRepUserRepository->permissions($request);
            if (count($salesRepUsersPermissions) > 0)
                return $this->listResponse($salesRepUsersPermissions);
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
            $salesRepUser = $this->salesRepUserRepository->store($data->getRequestData());
            if ($salesRepUser)
                return $this->showResponse(new SalesRepUserResource($salesRepUser->load('parent')));
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
            $salesRepUser = $this->salesRepUserRepository->update($data->getRequestData(), $id);
            if ($salesRepUser)
                return $this->showResponse($salesRepUser);
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
            $salesRepUser = $this->salesRepUserRepository->show($id);
            if (isset($salesRepUser))
                return $this->showResponse(new SalesRepUserResource($salesRepUser->load('roles')));
            else
                return $this->notFoundResponse();
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function merchants($id): \Illuminate\Http\JsonResponse
    {
        try {
            $salesRepUser = $this->salesRepUserRepository->show($id);
            if (isset($salesRepUser))
                return $this->showResponse(new SalesRepUserResource($salesRepUser->load('distributors')));
            else
                return $this->notFoundResponse();
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function allMerchantsWithPosTerminalByMerchantID(): \Illuminate\Http\JsonResponse
    {
        try {
            $salesRep = Auth::guard('salesRepApi')->user();
            if (isset($salesRep))
                return $this->showResponse(new AuthResource($salesRep));
            else
                return $this->notFoundResponse();
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function posTerminalByMerchantID($distributor_id): \Illuminate\Http\JsonResponse
    {
        try {
            $pos_terminals = $this->salesRepUserRepository->posTerminalByMerchantID($distributor_id);
            if (isset($pos_terminals))
                return $this->ApiSuccessResponse(DistributorPosTerminalListResource::collection($pos_terminals)->response()->getData());
            else
                return $this->notFoundResponse();
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * Update SalesRepUser.
     *
     * @param integer $salesRepUser_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_status(Request $request, int $salesRepUser_id): \Illuminate\Http\JsonResponse
    {
        $status = $request->status;

        try {
            $salesRepUser = $this->salesRepUserRepository->update_status($status, $salesRepUser_id);
            if ($salesRepUser)
                return $this->showResponse(new SalesRepUserResource($this->salesRepUserRepository->show($salesRepUser_id)));
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
            $salesRepUser = $this->salesRepUserRepository->show($id);
            if (!$salesRepUser)
                return $this->notFoundResponse();
            $deleted = $this->salesRepUserRepository->destroy($id);
            if (!$deleted)
                return $this->ApiErrorResponse(null, __('admin.related_items'));
            return $this->ApiSuccessResponse([], 'Success');
        } catch (Exception $e) {
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function addTransaction($request, $id)
    {
        $data_request = $request->only('transaction_type', 'amount', 'type', 'balance_type');
        $user = auth('salesRepApi')->user();

        // Find the corresponding entity (SalesRep, Merchant, or POS)
        $entity = match ($data_request['type']) {
            'sales_rep' => $this->salesRepUserRepository->find($id),
            'merchant' => $this->distributorRepository->find($id),
            'pos' => $this->posTerminalRepository->find($id),
            default => null,
        };

        if (!$entity) {
            return $this->ApiErrorResponse(null, "Entity not found.");
        }

        $amount = (float)$data_request['amount'];

        if ($data_request['transaction_type'] == 'debit' && $entity->balance < $amount) {
            return $this->ApiErrorResponse(__('admin.insufficient_balance'), 'Insufficient balance.');
        }

        if ($data_request['transaction_type'] == 'credit' && $user->balance < $amount) {
            return $this->ApiErrorResponse(__('admin.insufficient_balance'), 'Insufficient balance for credit transaction.');
        }

        try {
            // Process transaction and update balances
            $transaction = $this->salesRepUserRepository->addTransaction($data_request, $id);

            if ($transaction) {
                return $this->showResponse($transaction);
            }

            return $this->ApiErrorResponse(null, "Failed to add balance, please try again.");
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
            $this->salesRepUserRepository->destroy_selected($ids);
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
