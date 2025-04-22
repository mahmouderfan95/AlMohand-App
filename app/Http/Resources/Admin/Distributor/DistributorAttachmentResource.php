<?php

namespace App\Http\Resources\Admin\Distributor;

use App\Http\Resources\Admin\BaseAdminResource;

class DistributorAttachmentResource extends BaseAdminResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            "id" => $this->id,
            "merchant_id" => $this->distributor_id,
            "file_url" => $this->file_url ?? "",
            'type' => $this->type ?? "",
            "extension" => $this->extension ?? "",
            "size" => $this->size ?? "",
            "created_at" => $this->created_at,
            "updated_at" => $this->created_at,
        ];
    }
}
