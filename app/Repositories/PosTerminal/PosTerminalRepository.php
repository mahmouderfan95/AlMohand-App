<?php

namespace App\Repositories\PosTerminal;

use App\Models\Distributor\Distributor;
use App\Models\POSTerminal\PosTerminal;
use App\Repositories\BaseRepository;
use Illuminate\Container\Container as Application;


class PosTerminalRepository extends BaseRepository
{

    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Currency Model
     *
     * @return string
     */
    public function model(): string
    {
        return PosTerminal::class;
    }

    public function store($data = array())
    {
        return $this->model->query()->create($data);
    }

    public function index($keyword = null)
    {
        $query = $this->model->query()->whereDoesntHave('distributorPosTerminal');
        if (!empty($keyword)) {
            $query = $query->where('name', 'LIKE', "%$keyword%");
        }
        return $query;
    }


}
