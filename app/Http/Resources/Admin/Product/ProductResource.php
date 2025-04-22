<?php

namespace App\Http\Resources\Admin\Product;

use App\Http\Resources\Admin\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        $attributes = $this->resource->toArray();
        return [
            "id" => $this->id,
            "brand_id" => $this->brand_id,
            "name" => $this->name,
            "receipt_content" => $this?->receipt_content,
            "image" => $this->image,
            "quantity" => $this->quantity,
            "status" => $this->status,
            "cost_price" => $this->cost_price,
            "price" => $this->price,
            "wholesale_price" => $this->wholesale_price,
            "type" => $this->type,
            "notify" => $this->notify,
            "tax_id" => $this->tax_id,
            "tax_type" => $this->tax_type,
            "tax_amount" => $this->tax_amount,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "web" => $this->web,
            "mobile" => $this->mobile,
            "is_available" => $this->is_available,
            "profit_rate" => $this->profit_rate,
            "desc" => $this->desc,
            "meta_title" => $this->meta_title,
            "meta_keyword" => $this->meta_keyword,
            "meta_description" => $this->meta_description,
            "long_desc" => $this->long_desc,
            "content" => $this->content,
            "minimum_quantity" => $this->minimum_quantity,
            "max_quantity" => $this->max_quantity,
            "vendor" => $this->when( array_key_exists('vendor', $attributes), function () use($attributes){
                if (is_null($attributes['vendor']))
                    return null;
                return ["id" => $this->vendor->id, "name" => $this->vendor->name];
            } ),
            "brand" => $this->when( array_key_exists('brand', $attributes), function () use($attributes){
                if (is_null($attributes['brand']))
                    return null;
                return ["id" => $this->brand->id, "name" => $this->brand->name];
            } ),
            "translations" => $this->when( $this->relationLoaded('translations'), $this->whenLoaded('translations') ),
            "product_images" => $this->when( $this->relationLoaded('product_images'), ProductImageResource::collection($this->whenLoaded('product_images')) ),
            "categories" => $this->when( array_key_exists('categories', $attributes), ProductCategoryResource::collection($this->whenLoaded('categories')) ),
            "product_price_merchant_groups" => $this->whenLoaded('productPriceDistributorGroups', ProductPriceDistributorGroupResource::collection($this->productPriceDistributorGroups)),
        ];
    }
}
