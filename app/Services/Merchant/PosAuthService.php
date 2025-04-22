<?php

namespace App\Services\Merchant;

use App\DTO\Pos\Auth\PosLoginDto;
use App\DTO\Pos\Auth\PosRegisterDto;
use App\Http\Resources\Pos\AuthResources\AuthResource;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosAuthServiceInterface;
use App\Repositories\BalanceLog\BalanceLogRepository;
use App\Repositories\Distributor\DistributorPosTerminalRepository;
use App\Repositories\PosTerminal\BalanceRequestRepository;
use App\Repositories\PosTerminal\PosTerminalLoginsRepository;
use App\Repositories\PosTerminal\PosTerminalRepository;
use App\Repositories\PosTerminal\PosTerminalTransactionRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class PosAuthService extends BaseService implements PosAuthServiceInterface
{

    public function __construct(private readonly DistributorPosTerminalRepository $distributorPosTerminalRepository,
                                private readonly PosTerminalLoginsRepository $posTerminalLoginsRepository,
                                private readonly BalanceRequestRepository $balanceRequestRepository,
                                private readonly PosTerminalTransactionRepository $posTerminalTransactionRepository,
                                private readonly BalanceLogRepository $balanceLogRepository,
    )
    {
    }

    public function login(PosLoginDto $dto): mixed
    {
        try {
            if ($token = Auth::guard('posApi')->attempt([
                'password' => $dto->getPassword(),
                'serial_number' => $dto->getSerialNumber(),
                'is_blocked' => null,
                'is_active' => true,
            ])
            )
            {
                $pos = $this->getCurrentPos();
                if ($pos->activated_at == null) {
                    return $this->ApiErrorResponse(null, 'Invalid credentials');
                }

                // Save Pos Logins data
                $data = $dto->toArray();
                $data['distributor_pos_terminal_id'] = $pos->id;
                $this->posTerminalLoginsRepository->create($data);
                return $this->ApiSuccessResponse(['pos_info' => new AuthResource($pos), 'token' => $token], "Logged in successfully");
            }
            return $this->ApiErrorResponse(null, 'Invalid username or password');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function register(PosRegisterDto $dto): mixed
    {
        try {

            $pos = $this->distributorPosTerminalRepository->checkIfPosNotActivated($dto);
            if (! $pos) {
                return $this->ApiErrorResponse(null, 'Invalid POS');
            }

            // Update POS Password and activate
            $this->distributorPosTerminalRepository->activatePosTerminal($pos, $dto->getPassword());

            if ($token = Auth::guard('posApi')->attempt([
                'otp' => $dto->getOtp(),
                'serial_number' => $dto->getSerialNumber(),
                'is_blocked' => null,
                'is_active' => true,
                'password' => $dto->getPassword(),
            ])
            )
            {
                $pos = $this->getCurrentPos();

                // Save Pos Logins data
                $data = $dto->toArray();
                $data['distributor_pos_terminal_id'] = $pos->id;
                $this->posTerminalLoginsRepository->create($data);
                return $this->ApiSuccessResponse(['pos_info' => new AuthResource($pos), 'token' => $token], "Activated successfully");
            }
            return $this->ApiErrorResponse(null, 'Invalid username or password');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function updateName($name)
    {
        $pos = $this->getCurrentPos();
        return $this->ApiSuccessResponse($this->distributorPosTerminalRepository->update(['receiver_name' => $name], $pos->id));
    }

    public function updatePhone($phone)
    {
        $pos = $this->getCurrentPos();
        return $this->ApiSuccessResponse(new AuthResource($this->distributorPosTerminalRepository->update(['receiver_phone' => $phone], $pos->id)));
    }

    public function updatePassword($old_password, $new_password)
    {
        $pos = $this->getCurrentPos();
        if (!Hash::check($old_password, $pos->password)) {
            return $this->ApiErrorResponse('Invalid Password');
        }

        $pos->update(['password' => Hash::make($new_password)]);

        return $this->ApiSuccessResponse('Password updated successfully');
    }

    public function logout()
    {
        \auth('posApi')->logout();
        return $this->ApiSuccessResponse('Logged out successfully');
    }
}
