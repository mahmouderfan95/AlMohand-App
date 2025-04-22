<?php

namespace App\Repositories\Distributor;

use App\DTO\Pos\Auth\PosRegisterDto;
use App\Models\Distributor\DistributorPosTerminal;
use App\Repositories\BaseRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Facades\Hash;


class DistributorPosTerminalRepository extends BaseRepository
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function activatePosTerminal($pos, $password): void
    {
        $pos->password = Hash::make($password);
        $pos->activated_at = now();
        $pos->save();
    }

    public function checkIfPosNotActivated(PosRegisterDto $dto)
    {
        $posQuery = $this->model->query();
        if (app()->environment('production')) {
            $posQuery
                ->whereNull('activated_at');
                // ->whereNull('password')
        }

        return $posQuery
            ->where('otp' ,$dto->getOtp())
            ->where('serial_number', $dto->getSerialNumber())
            ->whereNull('is_blocked')
            ->where('is_active', true)
            ->first();
    }

    public function getMerchantPosTerminals($distributor_id, $filters = array())
    {
        $query = $this->model->query()->where('distributor_id', '=', $distributor_id);

        if (!empty($filters)) {
            if (!empty($filters['branch_name'])) {
                $query->where('branch_name', 'LIKE', '%' . $filters['branch_name'] . '%');
            }

            if (!empty($filters['address'])) {
                $query->where('address', 'LIKE', '%' . $filters['address'] . '%');
            }

            if (!empty($filters['receiver_phone'])) {
                $query->where('receiver_phone', '=', $filters['receiver_phone']);
            }

            if (!empty($filters['receiver_name'])) {
                $query->where('receiver_name', 'LIKE', '%' . $filters['receiver_name'] . '%');
            }

            if (!empty($filters['pos_terminal_id'])) {
                $query->where('pos_terminal_id', '=', $filters['pos_terminal_id']);
            }
        }

        if (!request('isPaginate')) {
            $query = $query->get();
        } else {
            $query = $query->paginate();
        }

        return $query;
    }

    public function getAll(string $keyword = null)
    {
        return $this->model->query()
            ->orderByDesc('created_at')
            ->where(function ($query) use ($keyword) {
                $query->whereHas('distributor.translations', function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%$keyword%");
                })->orWhereHas('posTerminal', function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', "%$keyword%");
                });
            })
            ->distinct()
            ->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function checkIfPosTerminalAssigned($pos_terminal_id)
    {
        return $this->model->query()->where('pos_terminal_id', '=', $pos_terminal_id)->first();
    }

    public function getByPosTerminalID($pos_terminal_id)
    {
        return $this->model->query()->where('pos_terminal_id', '=', $pos_terminal_id)->first();
    }

    public function deleteByPosTerminalID($pos_terminal_id)
    {
        return $this->model->query()->where('pos_terminal_id', '=', $pos_terminal_id)->delete();
    }

    /**
     * Currency Model
     *
     * @return string
     */
    public function model(): string
    {
        return DistributorPosTerminal::class;
    }
}
