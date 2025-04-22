<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesRepLevelResource extends JsonResource
{
    /**
     * Transform the SalesRepLevel resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Get the model's attributes
        $attributes = $this->resource->toArray();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status,
            'code' => $this->code,
            'parent_id' => $this->parent_id,
            'permissions' => $this->permissions,
            'parent' => new SalesRepLevelResource($this->whenLoaded('parent')),
            'children' => SalesRepLevelResource::collection($this->whenLoaded('children')),
            'translations' => $this->translations, // Nested translations
            'sales_reps_count' => $this->sales_reps()->count(), // Add the count of sales reps
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
