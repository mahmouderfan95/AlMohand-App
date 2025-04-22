<?php

namespace App\Jobs\NotificationJobs;

use App\Enums\FirebaseTopicEnum;
use App\Jobs\EmailJobs\SendEmailJob;
use App\Models\Customer;
use App\Models\User;
use App\Services\General\NotificationServices\FirebaseService;
use App\Traits\LoggingTrait;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LoggingTrait;

    /**
     * Create a new job instance.
     */
    public function __construct(private array $data, private $customer = null)
    {
        // $this->onQueue('notification-messaging');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // check if notification for admins or customers
            if ($this->data['to'] == User::class &&  $this->data['notification_permission_name']) {
                // get all admins that have permissions to send notifications
                $admins = User::where(function ($query) {
                    $query->whereHas('roles', function ($roleQuery) {
                        $roleQuery->where('name', 'Super Admin');
                    })
                    ->orWhereHas('permissions', function ($permissionQuery){
                        $permissionQuery->where('name', $this->data['notification_permission_name']);
                    });
                })->get();
                // send notification for every admin that has this permission
                if ($admins->isNotEmpty()) {
                    // get a batch of jobs
                    $jobs = $admins->map(function ($admin) {
                        return [
                            new SendDatabaseNotificationJob($admin, $this->data),
                            new SendFirebaseNotificationJob(FirebaseTopicEnum::getAdminTopic() . $admin->id, $this->data),
                        ];
                    })->flatten();
                    // use Bus tech.
                    Bus::batch($jobs)->dispatch();
                }
            }
            elseif ($this->data['to'] == Customer::class &&  $this->customer) {
                Bus::batch([
                        new SendDatabaseNotificationJob($this->customer, $this->data),
                        new SendFirebaseNotificationJob(FirebaseTopicEnum::getCustomerTopic() . $this->customer->id, $this->data),
                    ])->dispatch();
            }

        } catch (Exception $e) {
            Log::error('NotificationMessageJob failed: ' . $e->getMessage());
            $this->logException($e);
        }

    }

}
