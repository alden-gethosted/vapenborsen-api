<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'email'       => $this->email,
            'website'       => $this->website,
            'status'       => $this->status ? 'Active':'Inactive',
            'address'       => $this->address,
            'description'       => $this->description,
            'photo'       => isset($this->photo) ? asset($this->photo) : ''
        ];
    }
}
