<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'state' => $this->state,
            'descriptions' => $this->descriptions,
            'is_disable' => $this->is_disable ? 'Yes':'No',
            'price' => $this->price,
            'photo' => isset($this->photo) ? asset($this->photo) : '',
            'product_categories_id' => $this->product_categories_id,
            'category' => $this->productCategory->name ?? '',
            'product_types_id' => $this->product_types_id,
            'types' => $this->productType->name ?? '',
            'product_brands_id' => $this->product_brands_id,
            'brand' => $this->productBrand->name ?? '',
            'product_attribute' => ProductAttributeResource::collection($this->productAttributes()->get()),
            'product_tags' => ProductTagResource::collection($this->productTags()->get())
        ];
    }
}
