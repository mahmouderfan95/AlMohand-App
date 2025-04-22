<?php
namespace App\Repositories\Seller;

use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Traits\ApiResponseAble;

class SupportTicketRepository{
    use ApiResponseAble;
    public function create($data){
        return auth('sellerApi')->user()->SupportTickets()->create($data);
    }
    public function uploadAttachments($data,$ticketId)
    {
        // Find the ticket by its ID
        $ticket = SupportTicket::findOrFail($ticketId);
        if($data && is_array($data['attachments'])){
            foreach($data['attachments'] as $file){
                // Handle the file upload
                // Define the file path where you want to store the attachments
                $path = $file->store('support_ticket_attachments', 'public');

                // Save the file path in the ticket_attachments table
                $attachment = new SupportTicketAttachment();
                $attachment->support_ticket_id = $ticket->id;
                $attachment->file_url = $path;
                $attachment->extension = $file->getClientOriginalExtension();
                $attachment->size = $file->getSize();
                $attachment->save();
            }
        }

    }
    private function getModel(): String
    {
        return SupportTicket::class;
    }
}
