<?php

namespace App\Services\Admin;

use App\Http\Requests\Admin\SettingRequests\PointsCommissionSettingRequest;
use App\Http\Resources\Admin\MainSettingResource;
use App\Http\Resources\LanguageResource;
use App\Repositories\Admin\SettingRepository;
use App\Repositories\Language\LanguageRepository;
use App\Settings\CommissionPointsSettings;
use App\Traits\ApiResponseAble;
use Exception;
use Illuminate\Support\Facades\DB;

class SettingService
{
    use ApiResponseAble;

    public function __construct(
        private SettingRepository $settingRepository,
        private LanguageRepository $languageRepository
    )
    {}

    public function mainSettings()
    {
        try {
            DB::beginTransaction();
            $settings = $this->settingRepository->mainSettings();
            $languages = $this->languageRepository->get();
            $data = [
                'settings' => MainSettingResource::collection($settings),
                'languages' => LanguageResource::collection($languages),
            ];
            DB::commit();
            return $this->ApiSuccessResponse($data, 'Main Settings...!');
        } catch (Exception $e) {
            DB::rollBack();
            //return $this->ApiErrorResponse(null, $e);
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function updateMainSettings($request)
    {
        try {
            DB::beginTransaction();
            $mainSettings = $this->settingRepository->updateMainSettings($request);
            if (! $mainSettings)
                return $this->ApiErrorResponse();

            DB::commit();
            return $this->ApiSuccessResponse(null, 'Updated Successfully...!');
        } catch (Exception $e) {
            DB::rollBack();
            //return $this->ApiErrorResponse(null, $e);
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function getByGroup($group)
    {
        return match ($group) {
            'points_commission' => $this->ApiSuccessResponse(app(CommissionPointsSettings::class)->toArray()),
            default => null,
        };
    }

    public function updatePointCommissionSetting(PointsCommissionSettingRequest $request, $group)
    {
        $settings = match ($group) {
            'points_commission' => app(\App\Settings\CommissionPointsSettings::class),
            'core'              => app(\App\Settings\CoreSettings::class),
            default             => throw new \InvalidArgumentException("Invalid settings group: $group"),
        };

        foreach ($request->all() as $key => $value) {
            $settings->$key = $value;
        }

        $settings->save();

        return $this->ApiSuccessResponse('Settings updated successfully');
    }

}
