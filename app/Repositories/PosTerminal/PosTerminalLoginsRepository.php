<?php

namespace App\Repositories\PosTerminal;

use App\Models\Distributor\Distributor;
use App\Models\POSTerminal\PosTerminal;
use App\Models\POSTerminal\PosTerminalLogins;
use App\Repositories\BaseRepository;
use Illuminate\Container\Container as Application;


class PosTerminalLoginsRepository extends BaseRepository
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
        return PosTerminalLogins::class;
    }

    public function store($data = array())
    {
        return $this->model->query()->create($data);
    }
}
