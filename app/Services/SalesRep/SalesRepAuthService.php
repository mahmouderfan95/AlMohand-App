<?php

namespace App\Services\SalesRep;

use App\DTO\BaseDTO;
use App\Http\Resources\SalesRep\AuthResources\AuthResource;
use App\Interfaces\ServicesInterfaces\SalesRep\SalesRepAuthServiceInterface;
use App\Repositories\SalesRepUser\SalesRepUserRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class SalesRepAuthService extends BaseService implements SalesRepAuthServiceInterface
{

    public function __construct(private readonly SalesRepUserRepository $salesRepUserRepository
    )
    {
    }

    public function login(BaseDto $data): mixed
    {
        try {
            $data = $data->getRequestData();

            if ($token = Auth::guard('salesRepApi')->attempt([
                'password' => $data['password'],
                'username' => $data['username'],
            ])
            ) {
                $salesRep = $this->salesRepUserRepository->checkIfSalesRepActivated($data['username']);
                if (!$salesRep) {
                    return $this->ApiErrorResponse(null, 'User not activated');
                }
                $salesRep = Auth::guard('salesRepApi')->user();
                return $this->ApiSuccessResponse(['sales_rep_info' => new AuthResource($salesRep), 'token' => $token], "Activated successfully");
            }
            return $this->ApiErrorResponse(null, 'Invalid username or password');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function register(BaseDto $data): mixed
    {
        try {
            $data = $data->getRequestData();
            $salesRep = $this->salesRepUserRepository->checkIfSalesRepActivated($data['username']);
            if (!$salesRep) {
                return $this->ApiErrorResponse(null, 'User not activated');
            }
            $salesRep = $this->salesRepUserRepository->checkIfSalesRepAccount($data['username']);
            if ($salesRep) {
                return $this->ApiErrorResponse(null, 'User already registered');
            }

            // Update POS Password and activate
            $this->salesRepUserRepository->register($data);

            if ($token = Auth::guard('salesRepApi')->attempt([
                'username' => $data['username'],
                'password' => $data['password']
            ])
            ) {
                $salesRep = Auth::guard('salesRepApi')->user();
                return $this->ApiSuccessResponse(['sales_rep_info' => new AuthResource($salesRep), 'token' => $token], "Activated successfully");
            }
            return $this->ApiErrorResponse(null, 'Invalid username or password');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }


    public function logout()
    {
        \auth('posApi')->logout();
        return $this->ApiSuccessResponse('Logged out successfully');
    }
}
