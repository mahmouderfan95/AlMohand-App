<?php

namespace App\Services\Distributor;

use App\DTO\Admin\Merchant\CreateMerchantDto;
use App\DTO\Admin\Merchant\GetDistributorPosDto;
use App\DTO\BaseDTO;
use App\Http\Resources\Admin\CityResource;
use App\Http\Resources\Admin\Distributor\DistributorDetailsResource;
use App\Http\Resources\Admin\Distributor\DistributorPosTerminalListResource;
use App\Http\Resources\Admin\Distributor\DistributorResource;
use App\Http\Resources\Admin\RegionResource;
use App\Http\Resources\Admin\ZoneResource;
use App\Interfaces\ServicesInterfaces\Distributor\DistributorServiceInterface;
use App\Models\Distributor\DistributorAttachments;
use App\Repositories\Distributor\DistributorAttachmentsRepository;
use App\Repositories\Distributor\DistributorPosTerminalRepository;
use App\Repositories\Distributor\DistributorRepository;
use App\Repositories\GeoLocation\CityRepository;
use App\Repositories\GeoLocation\RegionRepository;
use App\Repositories\GeoLocation\ZoneRepository;
use App\Repositories\PosTerminal\PosTerminalRepository;
use App\Services\BaseService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DistributorService extends BaseService implements DistributorServiceInterface
{

    public function __construct(private readonly DistributorRepository $distributorRepository,
                                private readonly DistributorAttachmentsRepository $distributorAttachmentsRepository,
                                private readonly CityRepository $cityRepository,
                                private readonly ZoneRepository $zoneRepository,
                                private readonly DistributorPosTerminalRepository $distributorPosTerminalRepository,
                                private readonly RegionRepository $regionRepository,
    )
    {
    }

    public function index(array $filter): mixed
    {
        return $this->ApiSuccessResponse(DistributorResource::collection($this->distributorRepository->with(['translations'])->paginate())->response()->getData());
    }

    public function show($id): mixed
    {
        $distributor = $this->distributorRepository->find($id);

        if (!$distributor) {
            return $this->ApiErrorResponse("invalid distributor id");
        }

        return $this->ApiSuccessResponse(new DistributorDetailsResource($distributor));
    }

    public function store(BaseDTO|CreateMerchantDto $data): mixed
    {
        try {
            $input = $data->toArray();
            DB::beginTransaction();
            $request = $data->getRequest();
            $input['code'] = rand(1000, 9999);

            if (isset($request->logo))
                $input['logo'] = $this->save_file($request->logo, 'distributors');

            $distributor = $this->distributorRepository->saveWithTranslations($input);
            if ($distributor) {
                $this->distributorAttachmentsRepository->store($request, $distributor->id);
            }

            DB::commit();
            return $this->ApiSuccessResponse(new DistributorResource($distributor));
        }catch (Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    /**
     * @throws Exception
     */
    public function update($id, BaseDTO|CreateMerchantDto $data): mixed
    {
        try {
            DB::beginTransaction();

            $input = $data->getRequestData();
            $distributor = $this->distributorRepository->find($id);

            if (!$distributor) {
                return $this->ApiErrorResponse("invalid distributor id");
            }

            $this->distributorRepository->saveWithTranslations($input, $distributor);
            $request = $data->getRequest();
            $this->distributorAttachmentsRepository->store($request, $distributor->id);

            DB::commit();

            return $this->ApiSuccessResponse(new DistributorResource($distributor));
        }catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }

    public function delete($id): mixed
    {
        $distributor = $this->distributorRepository->find($id);

        if (!$distributor) {
            return $this->ApiErrorResponse("invalid group id");
        }

        $distributor->delete();

        return $this->ApiSuccessResponse("", "Deleted Successfully");
    }

    public function getTrashed()
    {
        // TODO: Implement getTrashed() method.
    }

    public function restore($id)
    {
        // TODO: Implement restore() method.
    }

    public function bulkDelete(array $ids = [])
    {
        // TODO: Implement bulkDelete() method.
    }

    public function uploadDistributorAttachments(Request $request, $id)
    {
        $attachmentTypes = ['commercial_register','identity','tax_card','more'];
        foreach ($attachmentTypes as $type) {
            $attachmentData = $this->uploadAttachments($type,"uploads/attachments");
            if ($attachmentData) {
                // Update or create attachment
                DistributorAttachments::updateOrCreate(
                    ['distributor_id' => $id, 'type' => $type],
                    $attachmentData
                );
            }
        }
    }

    public function deleteAttachment($id)
    {
        $attachment = $this->distributorAttachmentsRepository->find($id);

        if (!$attachment) {
            return $this->ApiErrorResponse("invalid attachment id");
        }

        $attachment->delete();

        return $this->ApiSuccessResponse("", "Deleted Successfully");
    }

    public function fillRequiredData()
    {
        // Get cities
        $cities = $this->cityRepository->getAllCities();
        // Get Zones
        $zones = $this->zoneRepository->get();
        // Get Regions
        $regions = $this->regionRepository->getAllRegions();
        // Get sales reps
        return $this->ApiSuccessResponse([
            'cities' => CityResource::collection($cities),
            'zones' => ZoneResource::collection($zones),
            'regions' => RegionResource::collection($regions),
            'sales_reps' => [
                [
                    'id' => '234528b4-b9ce-4b81-8d03-7a0f11296d5c',
                    'name' => 'علي محمد علي'
                ]
            ]
        ]);
    }

    public function updateStatus($id, $is_active)
    {
        try {
            DB::beginTransaction();

            $distributor = $this->distributorRepository->find($id);

            if (!$distributor) {
                return $this->ApiErrorResponse("invalid distributor id");
            }

            $updated = $this->distributorRepository->update(['is_active' => $is_active], $id);
            DB::commit();

            return $this->ApiSuccessResponse(new DistributorResource($updated));
        }catch (\Exception $exception) {
            return $this->ApiErrorResponse($exception->getMessage(), __('admin.general_error'));
        }
    }
}
