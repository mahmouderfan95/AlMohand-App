<?php

namespace App\Http\Controllers\Admin;

use App\DTO\BaseDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SalesRepUserRequests\AddTransactionRequest;
use App\Http\Requests\Admin\SalesRepUserRequests\SalesRepUserStatusRequest;
use App\Interfaces\ServicesInterfaces\SalesRepUser\SalesRepUserServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\SalesRepUserRequests\SalesRepUserRequest;

class SalesRepUserController extends Controller
{
    public $salesRepUserService;

    /**
     * SalesRepUser  Constructor.
     */
    public function __construct(SalesRepUserServiceInterface $salesRepUserService)
    {
        $this->salesRepUserService = $salesRepUserService;
    }


    /**
     * @OA\Get(
     *     path="/salesRepUsers",
     *     operationId="getSalesRepUsers",
     *     tags={"SalesRepUser"},
     *     summary="Retrieve a list of SalesRepUsers",
     *     description="Fetches a list of all available SalesRepUsers, with optional sorting, filtering, and pagination.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Search term to filter salesRepUsers by name, username, or email",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="region_id",
     *         in="query",
     *         required=false,
     *         description="Filter SalesRepUsers by region ID",
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of salesRepUsers",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Electronics"),
     *                 @OA\Property(property="code", type="string", example="SRL001"),
     *                 @OA\Property(property="status", type="string", example="active"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function index(Request $request)
    {
        return $this->salesRepUserService->index($request);
    }

    /**
     * @OA\Get(
     *     path="/salesRepUser/permissions",
     *     operationId="getSalesRepUsersPermissions",
     *     tags={"SalesRepUser"},
     *     summary="Retrieve a list of salesRepUsers permissions",
     *     description="Fetches a list of all available salesRepUsers, with optional sorting, filtering, and pagination.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A paginated list of salesRepUsers permissions",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Electronics"),
     *                 @OA\Property(property="code", type="string", example="code Description"),
     *                 @OA\Property(property="status", type="string", example="status Description"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */
    public function permissions(Request $request)
    {
        return $this->salesRepUserService->permissions($request);
    }


    /**
     * @OA\Post(
     *     path="/salesRepUsers",
     *     operationId="storeSalesRepUser",
     *     tags={"SalesRepUser"},
     *     summary="Create a new salesRepUser",
     *     description="Creates a new salesRepUser with the provided details.",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="SalesRepUser data that needs to be stored",
     *         @OA\JsonContent(
     *             required={"name", "username", "code", "sales_rep_level_id", "locations"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="John Doe",
     *                 description="The name of the salesRepUser"
     *             ),
     *             @OA\Property(
     *                 property="username",
     *                 type="string",
     *                 example="john_doe",
     *                 description="Unique username for the salesRepUser"
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="string",
     *                 example="SR001",
     *                 description="Unique code for the salesRepUser"
     *             ),
     *             @OA\Property(
     *                 property="phone",
     *                 type="string",
     *                 nullable=true,
     *                 example="+123456789",
     *                 description="Phone number of the salesRepUser"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 nullable=true,
     *                 example="john.doe@example.com",
     *                 description="Email address of the salesRepUser"
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"active", "inactive"},
     *                 example="active",
     *                 description="Status of the salesRepUser"
     *             ),
     *             @OA\Property(
     *                 property="parent_id",
     *                 type="integer",
     *                 nullable=true,
     *                 example=1,
     *                 description="ID of the parent salesRepUser, if any"
     *             ),
     *             @OA\Property(
     *                 property="sales_rep_level_id",
     *                 type="integer",
     *                 example=2,
     *                 description="ID of the sales rep level"
     *             ),
     *             @OA\Property(
     *                 property="locations",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="city_id", type="integer", example=1, description="City ID"),
     *                     @OA\Property(property="region_id", type="integer", example=2, description="Region ID")
     *                 ),
     *                 description="List of locations for the salesRepUser"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="SalesRepUser created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="username", type="string", example="john_doe"),
     *             @OA\Property(property="code", type="string", example="SR001"),
     *             @OA\Property(property="phone", type="string", example="+123456789"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="sales_rep_level_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation Error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 example={"name": {"The name field is required."}}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */



    public function store(SalesRepUserRequest $request)
    {
        return $this->salesRepUserService->store(new BaseDTO($request));
    }


    /**
     * @OA\Get(
     *     path="/salesRepUsers/{id}",
     *     operationId="getSalesRepUserById",
     *     tags={"SalesRepUser"},
     *     summary="Retrieve a single salesRepUser",
     *     description="Fetches details of a specific salesRepUser by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepUser ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             ref="#/components/responses/Unauthenticated"
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        return $this->salesRepUserService->show($id);
    }



    /**
     * @OA\Put (
     *     path="/salesRepUsers/{id}",
     *     operationId="updateSalesRepUser",
     *     tags={"SalesRepUser"},
     *     summary="Update an existing SalesRepUser",
     *     description="Updates details of a specific SalesRepUser by ID.",
     *     security={{"BearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepUser ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="SalesRepUser data that needs to be updated",
     *         @OA\JsonContent(
     *             required={"name", "status", "code", "sales_rep_level_id"},
     *             @OA\Property(
     *                 property="name",
     *                 type="object",
     *                 description="The name of the SalesRepUser in multiple languages",
     *                 @OA\Property(property="en", type="string", example="Updated Electronics"),
     *                 @OA\Property(property="ar", type="string", example="إلكترونيات محدثة")
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 enum={"active", "inactive"},
     *                 example="inactive",
     *                 description="Updated status of the SalesRepUser"
     *             ),
     *             @OA\Property(
     *                 property="code",
     *                 type="string",
     *                 example="SRL002",
     *                 description="Unique code for the SalesRepUser"
     *             ),
     *             @OA\Property(
     *                 property="sales_rep_level_id",
     *                 type="integer",
     *                 example=2,
     *                 description="Updated ID of the SalesRepUser level"
     *             ),
     *             @OA\Property(
     *                 property="parent_id",
     *                 type="integer",
     *                 nullable=true,
     *                 example=1,
     *                 description="Updated ID of the parent SalesRepUser, if any"
     *             ),
     *             @OA\Property(
     *                 property="locations",
     *                 type="array",
     *                 description="List of locations associated with the SalesRepUser",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="city_id",
     *                         type="integer",
     *                         example=101,
     *                         description="ID of the city"
     *                     ),
     *                     @OA\Property(
     *                         property="region_id",
     *                         type="integer",
     *                         example=202,
     *                         description="ID of the region"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="SalesRepUser updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(
     *                 property="name",
     *                 type="object",
     *                 @OA\Property(property="en", type="string", example="Updated Electronics"),
     *                 @OA\Property(property="ar", type="string", example="إلكترونيات محدثة")
     *             ),
     *             @OA\Property(property="status", type="string", example="inactive"),
     *             @OA\Property(property="code", type="string", example="SRL002"),
     *             @OA\Property(property="sales_rep_level_id", type="integer", example=2),
     *             @OA\Property(property="parent_id", type="integer", nullable=true, example=1),
     *             @OA\Property(
     *                 property="locations",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="city_id", type="integer", example=101),
     *                     @OA\Property(property="region_id", type="integer", example=202)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             ref="#/components/responses/Unauthenticated"
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="SalesRepUser not found")
     *         )
     *     )
     * )
     */
    public function update(SalesRepUserRequest $request, int $id)
    {
        return $this->salesRepUserService->update($id , new BaseDTO($request));
    }





    /**
     * @OA\Post(
     *     path="/salesRepUser/update-status/{id}",
     *     operationId="updateSalesRepUserStatus",
     *     tags={"SalesRepUser"},
     *     summary="Update the status of a salesRepUser",
     *     description="Updates the status of a specific salesRepUser by ID.",
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepUser ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated status",
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"active", "inactive"}, example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Status updated successfully",
     *         @OA\JsonContent(type="object", @OA\Property(property="message", type="string", example="Status updated successfully"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function update_status(SalesRepUserStatusRequest $request, int $id)
    {
        return $this->salesRepUserService->update_status($request, $id);
    }






    /**
     * @OA\Post(
     *     path="/salesRepUser/add-transaction/{id}",
     *     operationId="addTransactionSalesRepUser",
     *     tags={"SalesRepUser"},
     *     summary="Add a transaction for a Sales Rep User",
     *     description="Adds a transaction (credit or debit) for a specific Sales Rep User by ID.",
     *     security={{"BearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepUser ID",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Transaction details",
     *         @OA\JsonContent(
     *             required={"transaction_type", "amount"},
     *             @OA\Property(
     *                 property="transaction_type",
     *                 type="string",
     *                 enum={"credit", "debit"},
     *                 example="credit"
     *             ),
     *             @OA\Property(
     *                 property="amount",
     *                 type="integer",
     *                 example=100
     *             ),
     *             @OA\Property(
     *                 property="sales_rep_id",
     *                 type="integer",
     *                 nullable=true,
     *                 example=123
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Transaction added successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Transaction added successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         ref="#/components/responses/Unauthenticated"
     *     )
     * )
     */

    public function addTransaction(AddTransactionRequest $request, int $id)
    {
        return $this->salesRepUserService->addTransaction($request, $id);
    }

    /**
     * @OA\Delete(
     *     path="/salesRepUsers/{id}",
     *     operationId="deleteSalesRepUser",
     *     tags={"SalesRepUser"},
     *     summary="Delete a salesRepUser",
     *     description="Deletes a salesRepUser by its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="SalesRepUser ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */


    public function destroy(int $id)
    {
        return $this->salesRepUserService->delete($id);

    }

    /**
     * @OA\post(
     *     path="/salesRepUser/destroy_selected",
     *     operationId="destroySelectedSalesRepUsers",
     *     tags={"SalesRepUser"},
     *     summary="Delete selected salesRepUsers",
     *     description="Deletes multiple salesRepUsers by their IDs.",
     *     @OA\Parameter(
     *         name="ids",
     *         in="query",
     *         required=true,
     *         description="Comma-separated list of SalesRepUser IDs to be deleted.",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Success"),
     *             @OA\Property(property="message", type="string", example="Success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="Error"),
     *             @OA\Property(property="message", type="string", example="general_error")
     *         )
     *     )
     * )
     */
    public function destroy_selected(Request $request)
    {;
        return $this->salesRepUserService->bulkDelete(array ($request->ids));
    }


    /**
     *
     * trash SalesRepUser
     *
     */
    public function trash()
    {
        return $this->salesRepUserService->trash();

    }

    /**
     *
     * trash SalesRepUser
     *
     */
    public function restore(int $id)
    {
        return $this->salesRepUserService->restore($id);

    }

}
