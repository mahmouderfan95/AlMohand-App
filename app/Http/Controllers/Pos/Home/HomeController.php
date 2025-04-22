<?php

namespace App\Http\Controllers\Pos\Home;

use App\Http\Controllers\Controller;
use App\Services\Pos\HomeService;

class HomeController extends Controller
{

    private $homeService;
    public function __construct(HomeService $homeService )
    {
        $this->homeService = $homeService;
    }

    /**
     * @OA\Get(
     *     path="/home",
     *     tags={"POS Home"},
     *     summary="Retrieve authenticated user info, languages, and currencies",
     *     description="This endpoint returns the authenticated user's information, available languages, and currencies.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function __invoke()
    {
        return $this->homeService->home();
    }
}
