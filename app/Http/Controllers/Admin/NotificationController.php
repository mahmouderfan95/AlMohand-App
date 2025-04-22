<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        // private NotificationService $notificationService,
    )
    {}

    public function index(): JsonResponse
    {
        // return $this->notificationService->index();
    }

    public function count(): JsonResponse
    {
        // return $this->notificationService->count();
    }

    public function read($notificationId): JsonResponse
    {
        // return $this->notificationService->read($notificationId);
    }


}
