<?php

namespace App\Repositories\BalanceLog;

use App\Enums\BalanceLog\BalanceTypeStatusEnum;
use App\Models\BalanceLog\BalanceLog;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Container\Container as Application;


class BalanceLogRepository extends BaseRepository
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    public function getPosTerminalPointsLogs($pos_terminal_id)
    {
        $dateFrom = request()->has('date_from') ? Carbon::parse(request()->input('date_from'))->startOfDay() : null;
        $dateTo = request()->has('date_to') ? Carbon::parse(request()->input('date_to'))->endOfDay() : null;
        return $this->model->query()->where('pos_terminal_id', '=', $pos_terminal_id)
            ->where('balance_type', '=', BalanceTypeStatusEnum::POINTS->value)
            ->when($dateFrom && $dateTo, function ($query) use ($dateTo, $dateFrom) {
                return $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            })
            ->paginate(PAGINATION_COUNT_ADMIN);
    }

    public function getPosTerminalCommissionsLogs($pos_terminal_id)
    {
        $dateFrom = request()->has('date_from') ? Carbon::parse(request()->input('date_from'))->startOfDay() : null;
        $dateTo = request()->has('date_to') ? Carbon::parse(request()->input('date_to'))->endOfDay() : null;
        return $this->model->query()->orderBy('created_at','desc')->where('pos_terminal_id', '=', $pos_terminal_id)
            ->where('balance_type', '=', BalanceTypeStatusEnum::COMMISSION->value)
            ->when($dateFrom && $dateTo, function ($query) use ($dateTo, $dateFrom) {
                return $query->whereBetween('created_at', [$dateFrom, $dateTo]);
            })
            ->paginate(PAGINATION_COUNT_ADMIN);
    }

    /**
     * Currency Model
     *
     * @return string
     */
    public function model(): string
    {
        return BalanceLog::class;
    }
}
