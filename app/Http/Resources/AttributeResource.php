<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            'is_filterable' => $this->is_filterable,
            'attribute_sets_id' => $this->attribute_sets_id,
            'attribute_set' => $this->attributeSet->name ?? null,
            'link_with' => AttributeLinkResource::collection($this->attributeLinks()->get()),
            'attr_value' => AttributeValueResource::collection($this->attributeValues()->get())
        ];
    }
}
