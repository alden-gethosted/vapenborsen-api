<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoriesResource extends JsonResource
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
            'parents_id' => $this->parents_id,
            'parent' => $this->parent->name ?? null,
            'icon' => isset($this->icon) ? asset($this->icon) : null
        ];
    }
}
