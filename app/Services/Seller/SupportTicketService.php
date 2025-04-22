<?php
namespace App\Services\Seller;

use App\Repositories\Seller\SupportTicketRepository;
use App\Traits\ApiResponseAble;
use Illuminate\Support\Facades\DB;

class SupportTicketService
{
    use ApiResponseAble; 
    public function __construct(public SupportTicketRepository $supportTicketRepository){}
    public function index()
    {

    }
    public function store($request)
    {
        try{
            DB::beginTransaction();
            $ticket = $this->supportTicketRepository->create($request);
            #upload attachment
            // Upload multiple attachments if they exist
            if (isset($request['attachments'])) {
                $this->supportTicketRepository->uploadAttachments($request,$ticket->id);
            }
            DB::commit();
            return $this->ApiSuccessResponse($ticket);
        }catch(\Exception $e){
            DB::rollBack();
            return $this->ApiErrorResponse($e->getMessage(),trans('admin.general_error'));
        }
    }
}
