<?php

namespace App\Http\Controllers\Admin\PosTerminal;

use App\DTO\Admin\PosTerminal\CreatePosTerminalDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PosTerminalRequests\PosTerminalRequest;
use App\Interfaces\ServicesInterfaces\PosTerminal\PosTerminalServiceInterface;
use OpenApi\Annotations as OA;

class PosTerminalController extends Controller
{
    public PosTerminalServiceInterface $posTerminalService;

    /**
     * Attribute  Constructor.
     */
    public function __construct(PosTerminalServiceInterface $posTerminalService)
    {
        $this->posTerminalService = $posTerminalService;
    }

    /**
     * @OA\Get(
     *     path="/pos-terminal",
     *     operationId="getPosTerminals",
     *     tags={"POS Terminal"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return $this->posTerminalService->index([]);
    }

    public function create()
    {

    }

    /**
     * @OA\Get(
     *     path="/pos-terminal/{id}",
     *     operationId="showPOSTerminal",
     *     tags={"POS Terminal"},
     *     summary="Show POS Terminal",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="POS Terminal ID to show",
     *          @OA\Schema(
     *              type="string",
     *              example="edfd82b7-361b-492d-9dd3-e463e6b2566e"
     *          )
     *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        return $this->posTerminalService->show($id);
    }

    /**
     * @OA\Post(
     *     path="/pos-terminal",
     *     operationId="createPosTerminal",
     *     tags={"POS Terminal"},
     *     summary="Create a new POS Terminal",
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "brand", "serial_number", "terminal_id"},
     *             @OA\Property(property="name", type="string", example="POS-0007", description="Name of the POS Terminal"),
     *             @OA\Property(
     *                 property="brand",
     *                 type="string",
     *                 example="NewLand",
     *                 description="Brand of the POS Terminal. Options: [NewLand, NewLeap]"
     *             ),
     *             @OA\Property(
     *                 property="serial_number",
     *                 type="string",
     *                 example="56s465s4s65ty56465465",
     *                 description="Serial number of the POS Terminal"
     *             ),
     *             @OA\Property(
     *                 property="terminal_id",
     *                 type="string",
     *                 example="4545s4555st555",
     *                 description="Terminal ID of the POS Terminal"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="POS Terminal created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="POS Terminal created successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized access.")
     *         )
     *     )
     * )
     */
    public function store(PosTerminalRequest $request)
    {
        return $this->posTerminalService->store(new CreatePosTerminalDto($request));
    }

    public function edit($id)
    {
    }

    /**
     * @OA\Post(
     *     path="/pos-terminal/{id}/update",
     *     operationId="updatePosTerminal",
     *     tags={"POS Terminal"},
     *     summary="Update a POS Terminal",
     *     description="Updates an existing POS Terminal with the provided data.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           description="POS Terminal ID to update",
     *           @OA\Schema(
     *               type="string",
     *               example="edfd82b7-361b-492d-9dd3-e463e6b2566e"
     *           )
     *       ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"brand", "serial_number", "terminal_id"},
     *             @OA\Property(
     *                 property="brand",
     *                 type="string",
     *                 example="NewLeap",
     *                 description="Brand of the POS Terminal. Options: [NewLand, NewLeap]"
     *             ),
     *             @OA\Property(
     *                 property="serial_number",
     *                 type="string",
     *                 example="56465465465465465455",
     *                 description="Serial number of the POS Terminal"
     *             ),
     *             @OA\Property(
     *                 property="terminal_id",
     *                 type="string",
     *                 example="45454555555555",
     *                 description="Terminal ID of the POS Terminal"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="POS Terminal updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="POS Terminal updated successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="code", type="integer", example=422),
     *             @OA\Property(property="message", type="string", example="Validation error."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="field", type="string", example="name", description="The field with the validation error"),
     *                     @OA\Property(property="error", type="string", example="تم استخدام الاسم بالفعل.", description="The error message for the field")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized access.")
     *         )
     *     )
     * )
     */
    public function update(PosTerminalRequest $request, $id)
    {
        return $this->posTerminalService->update($id, new CreatePosTerminalDto($request));
    }

    /**
     * @OA\Delete(
     *     path="/pos-terminal/{id}",
     *     operationId="deletePosTerminal",
     *     tags={"POS Terminal"},
     *     summary="Delete a POS Terminal",
     *     description="Deletes a POS Terminal by its ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the POS Terminal to delete",
     *         @OA\Schema(type="string", format="uuid", example="b3e6251b-9ba3-481e-ae48-c3ccd728f858")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized access.")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        return $this->posTerminalService->delete($id);
    }

    /**
     * @OA\Get(
     *     path="/pos-terminal/generate-name",
     *     operationId="generatePosTerminalName",
     *     tags={"POS Terminal"},
     *     summary="Generate a unique POS Terminal name",
     *     description="Generates and returns a unique name for a POS Terminal.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Name generated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="code", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="name", type="string", example="POS-0003", description="Generated unique name for the POS Terminal")
     *             )
     *         )
     *     ),
     * )
     */
    public function generateTerminalName()
    {
        return $this->posTerminalService->generateName();
    }

    /**
     * @OA\Get(
     *     path="/active-pos-terminal",
     *     operationId="getActivePosTerminals",
     *     tags={"POS Terminal"},
     *     summary="Retrieve active POS terminals",
     *     description="Fetches a list of active POS terminals, optionally filtered by a keyword.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         required=false,
     *         description="A keyword to filter active POS terminals by name or other criteria.",
     *         @OA\Schema(type="string", example="sa")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function getAssignedPosTerminals()
    {
        return $this->posTerminalService->getAllActiveTerminals(request('keyword'));
    }
}
