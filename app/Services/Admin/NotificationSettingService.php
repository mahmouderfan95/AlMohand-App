<?php

namespace App\Services\Admin;

use App\Repositories\Admin\NotificationSettingRepository;
use App\Traits\ApiResponseAble;
use Exception;
use Illuminate\Support\Facades\DB;

class NotificationSettingService
{
    use ApiResponseAble;

    public function __construct(
        private NotificationSettingRepository $notificationSettingRepository,
    )
    {}

    public function getNotificationSettings()
    {
        try {
            DB::beginTransaction();
            $settings = $this->notificationSettingRepository->index();
            DB::commit();
            return $this->ApiSuccessResponse($settings, 'Notification Settings...!');
        } catch (Exception $e) {
            DB::rollBack();
            //return $this->ApiErrorResponse(null, $e);
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }

    public function updateNotificationSettings($id, $request)
    {
        try {
            DB::beginTransaction();
            $settings = $this->notificationSettingRepository->updateNotificationSettings($id, $request);
            if (! $settings)
                return $this->ApiErrorResponse();
            DB::commit();
            return $this->ApiSuccessResponse(null, 'Updated Successfully...!');
        } catch (Exception $e) {
            DB::rollBack();
            //return $this->ApiErrorResponse(null, $e);
            return $this->ApiErrorResponse($e->getMessage(), __('admin.general_error'));
        }
    }



}
