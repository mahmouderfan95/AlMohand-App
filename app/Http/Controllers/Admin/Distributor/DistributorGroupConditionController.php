<?php

namespace App\Http\Controllers\Admin\Distributor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AttributeRequest;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorGroupConditionServiceInterface;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorGroupServiceInterface;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorServiceInterface;
use App\Models\Attribute\AttributeTranslation;
use App\Models\Language\Language;
use App\Services\Admin\AttributeService;
use Illuminate\Http\Request;

class DistributorGroupConditionController extends Controller
{
    public $distributorGroupConditionService;

    /**
     * Attribute  Constructor.
     */
    public function __construct(DistributorGroupConditionServiceInterface $distributorGroupConditionService)
    {
        $this->distributorGroupConditionService = $distributorGroupConditionService;
    }

    public function index()
    {

    }

    public function create()
    {

    }

    public function store(Request $request)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function delete($id)
    {

    }


}
