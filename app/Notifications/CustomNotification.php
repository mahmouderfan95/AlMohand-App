<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CustomNotification extends Notification
{
    use Queueable;

    private $data;
    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'variables' => [
                'type' => $this->data['type'] ?? null,
                'type_id' => (string)$this->data['type_id'] ?? null
            ],
            'details' => [
                'en' => [
                    'title' => trans('notifications.'.$this->data['notification_translations'].'.title', [], 'en'),
                    'body' => trans('notifications.'.$this->data['notification_translations'].'.body', [], 'en'),
                ],
                'ar' => [
                    'title' => trans('notifications.'.$this->data['notification_translations'].'.title', [], 'ar'),
                    'body' => trans('notifications.'.$this->data['notification_translations'].'.body', [], 'ar'),
                ],
            ]

        ];
    }
}
