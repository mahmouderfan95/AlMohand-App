<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CityRequest;
use App\Services\GeoLocation\ZoneService;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ZoneController extends Controller
{
    public $zoneService;

    /**
     * City Constructor.
     */
    public function __construct(ZoneService $zoneService)
    {
        $this->zoneService = $zoneService;
    }


    /**
     * @OA\Get(
     *     path="/zones",
     *     operationId="getZones",
     *     tags={"Zone"},
     *     summary="Retrieve a list of zones",
     *     description="Fetches a list of all available zones.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function index(Request $request)
    {
        return $this->zoneService->index([]);
    }
}
